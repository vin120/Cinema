<?php

namespace frontend\modules\api\controllers;


use Yii;
use frontend\modules\api\controllers\BaseController;
use frontend\models\MovieOnlineOrder;
use frontend\models\MovieShow;
use frontend\models\Cinema;
use frontend\models\Room;
use frontend\models\Movie;
use frontend\models\User;
use frontend\models\MovieSeat;
use frontend\components\Helper;


class OrderController extends BaseController
{
	
	/** 
	 * 選座位
	 * @return number[]|string[]
	 */
	public function actionPickseat()
	{
	
		$uid = Yii::$app->request->post('uid');
		$appsecret = Yii::$app->request->post('appsecret');
		
		$movie_id = Yii::$app->request->post('movie_id');
		$seats = Yii::$app->request->post('seats');
		
		if(empty($uid) || empty($appsecret)){
			$response = ['code' => 2,'msg' => 'uid和appsecret 不能爲空'];
			return $response;
			Yii::$app->end();
		}
	
		$user = User::find()->where('user_id = :user_id',[':user_id'=>$uid])->one();
		
		if(is_null($user)){
			$response = ['code' => 3,'msg' => '獲取不到用戶信息'];
			return $response;
			Yii::$app->end();
		}
		
		
		if($appsecret != $user['appsecret']){
			$response = ['code' => 4,'msg' => '沒有權限，請先登錄'];
			return $response;
			Yii::$app->end();
		}
		
		
		if(empty($movie_id)){
			$response = ['code' => 5,'msg' => 'movie_id不能爲空'];
			return $response;
			Yii::$app->end();
		}
		
		if(empty($seats)){
			$response = ['code' => 6,'msg' => 'seats不能爲空'];
			return $response;
			Yii::$app->end();
		}
		
		
		$data =[];
		$seatArray = explode(",", $seats);
		$movie_show = MovieShow::find()->where('id = :id',[':id'=>$movie_id])->one();
		
		$online_order = new MovieOnlineOrder();
		
		$seat_ids = "";
		$seat_names = "";
		
		$transaction = Yii::$app->db->beginTransaction();
		try{
				
			foreach($seatArray as $row){
		
				$movie_seat = MovieSeat::find()->where('seat_id = :seat_id',[':seat_id'=>$row])->one();
		
				if(!is_null($movie_seat)){
					$response = ['code'=> 7,'msg' => "該位置已被預訂"];
					return $response;
					Yii::$app->end();
				}
		
				$seat = new MovieSeat();
				$seat->show_id = $movie_id;
				$seat->seat_id = $row;
				$seat->seat_name = explode("_", $row)[0]."排".explode("_", $row)[1]."座";
				$seat->cur_time = date("Y-m-d H:i:s",time());
				$seat->status = 1;
					
				$seat_ids .= $row.",";
				$seat_names .= explode("_", $row)[0]."排".explode("_", $row)[1]."座".",";
					
				$seat->save();
			}
				
				
			$online_order->movie_show_id = $movie_id;
			$online_order->phone = $user->user_phone;
			$online_order->seat_ids = rtrim($seat_ids,",");
			$online_order->seat_names = rtrim($seat_names,",");
			$online_order->order_time = date("Y-m-d H:i:s",time());
			$online_order->price = $movie_show->price;
			$online_order->count = count($seatArray);
			$online_order->total_money = (int)$movie_show->price * (int)count($seatArray);
			$online_order->order_code = date('Ymd',time()).Helper::rand_number().Helper::rand_number();//Helper::get_order_sn();
			$online_order->order_number = Helper::createOrderno();
			$online_order->status = 0;
				
			$online_order->save();
				
			$transaction->commit();
				
			$data['ssid'] = $online_order->order_code;
			
			
			$response = ['code'=> 1,'msg'=>'下單成功','data'=>$data];
				
			
		} catch (Exception $e){
				
			$transaction->rollBack();
			$response = ['code'=> 8,'msg' => "出現了未知錯誤"];
		}
		
		
		return $response;
		
	}
	
	
	/**
	 *  支付信息
	 * @return number[]|string[]|number[]|string[]|\frontend\models\unknown[][]
	 */
	public function actionPayinfo()
	{
		$ssid = Yii::$app->request->post('ssid');
		$data = [];
		if(empty($ssid)){
			$response = ['code' => 2,'msg' => 'ssid不能爲空'];
			return $response;
			Yii::$app->end();
		}
		
		$online_order = MovieOnlineOrder::find()->where('order_code = :ssid',[':ssid'=>$ssid])->one();
		
		if(is_null($online_order)){
			$response = ['code' => 3,'msg' => '找不到相關訂單信息'];
			return $response;
			Yii::$app->end();
		}
		
		
		$data['order'] = MovieOnlineOrder::findApiOrderDetail($online_order);
		
		//超過15分鐘，訂單超時
		if(strtotime($online_order->order_time) < time()-900){
			$response =  ['code' => 4,'msg' => '訂單失效，請重新下單','data'=>$data];
			return $response;
			Yii::$app->end();
		}
		$response = ['code' => 1,'msg' => '獲取成功','data'=>$data];
		
		return $response;
		
	}
	
	
	
	/**
	 * 支付時，記錄支付記錄
	 * @return number[]|string[]
	 */
	public function actionPaylog()
	{
		$ssid = Yii::$app->request->post('ssid');
		$channel = Yii::$app->request->post("channel");
		$charge_id = Yii::$app->request->post("charge_id");
		
		$data = [];
		if(empty($ssid)){
			$response = ['code' => 2,'msg' => 'ssid不能爲空'];
			return $response;
			Yii::$app->end();
		}
		
		if(empty($channel)){
			$response = ['code' => 3,'msg' => '支付渠道channel不能爲空'];
			return $response;
			Yii::$app->end();
		}
		
		if(empty($charge_id)){
			$response = ['code' => 4,'msg' => 'P++的charge_id不能爲空'];
			return $response;
			Yii::$app->end();
		}
		
		$online_order = MovieOnlineOrder::find()->where('order_code = :ssid',[':ssid'=>$ssid])->one();
		
		if(is_null($online_order)){
			$response = ['code' => 5,'msg' => '找不到相關訂單信息'];
			return $response;
			Yii::$app->end();
		}
		
		$data = MovieOnlineOrder::findApiOrderDetail($online_order);
			
		//寫入支付方式
		MovieOnlineOrder::updateAll(['payment'=>$channel,'charge_id'=>$charge_id,'pay_time'=>date("Y-m-d H:i:s",time())],'order_number = :order_number',[':order_number'=>$data['order_number']]);
		
		$response = ['code' => 1,'msg' => '記錄成功'];
		return $response;
		
	}
	
	
	/**
	 * App用的「alipay」 接口
	 */
	public function actionPay()
	{
		$ssid = Yii::$app->request->post("ssid");
		$channel = Yii::$app->request->post("channel");
		
		if(empty($ssid)) {
			$response = ['code' => 2,'msg' => 'ssid不能爲空'];
			return $response;
			Yii::$app->end();
		}
		
		
		if(empty($channel)){
			$response = ['code' => 3,'msg' => '支付渠道channel不能爲空'];
			return $response;
			Yii::$app->end();
		}
		
		
		if($channel != "alipay" && $channel != "wx"){
			$response = ['code' => 4,'msg' => '支付渠道channel只能填alipay 或者 wx'];
			return $response;
			Yii::$app->end();
		}
		
		
		
		$online_order = MovieOnlineOrder::find()->where('order_code = :ssid',[':ssid'=>$ssid])->one();
		
		if(is_null($online_order)){
			$response = ['code' => 5,'msg' => 'ssid不正确'];
			return $response;
			Yii::$app->end();
		}
		
		$data = MovieOnlineOrder::findOrderDetail($online_order);
	
		
		//汇率
		$mop = Helper::rate();
		$money = $data['total_money'] *100 * $mop;
		
		
		//調用支付接口
		$ch = Helper::AppPay($money, $data['order_number'], $channel);
		
		//返回的消息
		$response = json_decode($ch,true);
		$id = $response['id'];
		
		//寫入支付方式
		MovieOnlineOrder::updateAll(['payment'=>$channel,'charge_id'=>$id,'pay_time'=>date("Y-m-d H:i:s",time())],'order_number = :order_number',[':order_number'=>$data['order_number']]);
		echo $ch;
		
		
	}
	
	
	
}