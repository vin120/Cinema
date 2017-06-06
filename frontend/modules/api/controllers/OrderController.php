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
	
	
	public function actionPay()
	{
		
	}
	
	
	
	
	
	
	
}