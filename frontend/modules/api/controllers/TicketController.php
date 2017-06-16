<?php

namespace frontend\modules\api\controllers;


use Yii;
use frontend\modules\api\controllers\BaseController;
use frontend\models\MovieOnlineOrder;
use frontend\models\MovieShow;
use frontend\models\Cinema;
use frontend\models\Room;
use frontend\models\Movie;
use frontend\models\Admin;
use frontend\models\MovieOfflineOrder;
use frontend\models\MovieSeat;
use frontend\components\Helper;



class TicketController extends BaseController
{
	
	/**
	 * 取票機 「打印電影票」 接口
	 * @return number[]|string[]
	 */
	public function actionCheck()
	{
		$response = [];
		$data = [];
		$datas = [];
		
		if(Yii::$app->request->isPost){
			$ssid = Yii::$app->request->post('ssid');
			
			if(!empty($ssid)) {
				$order = MovieOnlineOrder::find()->where('order_code = :order_code',[':order_code'=>$ssid])->one();
				
				if(!is_null($order)){
					
					if($order->status == 1){
						//已支付
						
						//返回電影票信息
						$seats = explode(",", $order->seat_names);
						
						$movie_show = MovieShow::find()->where('id = :id',['id'=>$order->movie_show_id])->one();
						
						$cinema = Cinema::find()->where('cinema_id = :cinema_id',[':cinema_id'=>$movie_show->cinema_id])->one();
						$room = Room::find()->where('room_id = :room_id',[':room_id'=>$movie_show->room_id])->one();
						$movie = Movie::find()->where('movie_id = :movie_id',[':movie_id'=>$movie_show->movie_id])->one();
						
						//拼接時間
						$date = explode("-", explode(" ", $movie_show->time_begin)[0])[1]."-".explode("-", explode(" ", $movie_show->time_begin)[0])[2] ;
						$time = explode(":", explode(" ", $movie_show->time_begin)[1])[0].":".explode(":", explode(" ", $movie_show->time_begin)[1])[1] ;
						
						foreach($seats as $row) {

							$data['cinema'] = $cinema->cinema_name;
							$data['hall'] = $room->room_name;
							$data['seat'] = $row;
							$data['date'] = $date;
							$data['time'] = $time;
							$data['price'] = $order->price + $cinema->service_price;
							$data['movie'] = $movie->movie_name;
							$data['ticket_type'] = "網絡票";
							$data['service_charge'] = $cinema->service_price;
							$data['ssid'] = $ssid;
							
							$datas[] = $data;
						}
						
	
						$response = ['code' => 1,'msg' => '獲取成功','data'=>$datas];
						
					} else if ($order->status == 3){
						//電影票已經被提取過
						$response = ['code'=> 4,'msg'=>'電影票已經被提取'];
					} else {
						//未完成的訂單(找不到正確的訂單信息)
						$response = ['code'=> 3,'msg'=>'輸入的ssid驗證碼錯誤'];
					}
				} else {
					//找不到正確的訂單信息
					$response = ['code'=> 3,'msg'=>'請輸入正確的ssid驗證碼'];
				}
				
			} else {
				//沒有把訂單號傳過來
				$response = ['code'=> 2,'msg'=>'ssid驗證碼不能爲空'];
			}
			
		} else {
			$response = ['code'=> 2,'msg'=>'ssid驗證碼不能爲空'];
		}
		
		return $response;
	}
	
	
	
	/**
	 * 取票機  打印完電影票後的 「回調」 接口
	 * @return number[]|string[]
	 */
	public function actionCallback()
	{
		if(Yii::$app->request->isPost){
			$ssid = Yii::$app->request->post('ssid');
			$status = Yii::$app->request->post('status');
			
			if(!empty($ssid) && !empty($status)){
				
				$order = MovieOnlineOrder::find()->where('order_code = :order_code',[':order_code'=>$ssid])->one();
				if(!is_null($order)){
					if($order->status == 1){
						
						if($status == 1){
							//更改狀態
							MovieOnlineOrder::updateAll(['status' => 3], 'order_code = :order_code',[':order_code'=>$ssid]);
							$response = ['code' => 1,'msg' => '取票成功'];
						} else {
							$response = ['code' => 5,'msg' => '请重新打印'];
						}
					} else if ($order->status == 3){
						//電影票已經被提取過
						$response = ['code'=> 4,'msg'=>'電影票已經被提取'];
					} else {
						//未完成的訂單(找不到正確的訂單信息)
						$response = ['code'=> 3,'msg'=>'請輸入正確的ssid驗證碼'];
					}
					
				} else {
					//找不到正確的訂單信息
					$response = ['code'=> 3,'msg'=>'請輸入正確的ssid驗證碼'];
				}
				
			} else {
				//沒有把訂單號傳過來
				$response = ['code'=> 2,'msg'=>'ssid驗證碼和status狀態不能爲空'];
			}
		}
		
		return $response;
	}
	
	
	/**
	 * 影院售票系統登錄
	 * @return number[]|string[]|number[]|string[]|\yii\db\ActiveRecord[]|NULL[]
	 */
	public function actionLogin()
	{
		$username = Yii::$app->request->post('username');
		$password = Yii::$app->request->post('password');
		
		if(empty($username) || empty($password)){
			$response = ['code'=> 2,'msg'=>'帳號名和密碼不能爲空'];
			return $response;
			Yii::$app->end();
		}
		
		
		$admin = Admin::find()->select('admin_id,admin_nickname')->where('admin_user=:admin_user and admin_pwd=:admin_pwd',[':admin_user'=>$username,':admin_pwd'=>md5($password)])->one();
		
		
		if(is_null($admin)){
			$response = ['code'=> 3,'msg'=>'帳號名或密碼錯誤'];
			return $response;
			Yii::$app->end();
		}
		
		
		
		$data = $admin;
		
		$response = ['code'=> 1,'msg'=>'登錄成功','data'=>$data];
		return $response;
		
		
	}
	
	
	
	/**
	 * 售票員售票接口
	 * @return number[]|string[]
	 */
	public function actionSellticket()
	{
		$admin_id = Yii::$app->request->post('admin_id');
		
		$movie_id = Yii::$app->request->post('movie_id');
		$seats = Yii::$app->request->post('seats');
		
		
		if(empty($movie_id)){
			$response = ['code' => 2,'msg' => 'movie_id不能爲空'];
			return $response;
			Yii::$app->end();
		}
		
		if(empty($seats)){
			$response = ['code' => 3,'msg' => 'seats不能爲空'];
			return $response;
			Yii::$app->end();
		}
		
		
		if(empty($admin_id)){
			$response = ['code' => 4,'msg' => 'admin_id不能爲空'];
			return $response;
			Yii::$app->end();
		}
		
		
		$seatArray = explode(",", $seats);
		$movie_show = MovieShow::find()->where('id = :id',[':id'=>$movie_id])->one();
		
		
		$offline_order = new MovieOfflineOrder();
		$seat_ids = "";
		$seat_names = "";
		
		
		$transaction = Yii::$app->db->beginTransaction();
		try{
		
			foreach($seatArray as $row){
		
				$movie_seat = MovieSeat::find()->where('seat_id = :seat_id',[':seat_id'=>$row])->one();
		
				if(!is_null($movie_seat)){
					$response = ['code'=> 5,'msg' => "該位置已被預訂"];
					return $response;
					Yii::$app->end();
				}
		
				$seat = new MovieSeat();
				$seat->show_id = $movie_id;
				$seat->seat_id = $row;
				$seat->seat_name = explode("_", $row)[0]."排".explode("_", $row)[1]."座";
				$seat->cur_time = date("Y-m-d H:i:s",time());
				$seat->status = 2;
					
				$seat_ids .= $row.",";
				$seat_names .= explode("_", $row)[0]."排".explode("_", $row)[1]."座".",";
					
				$seat->save();
			}
		
		
			$offline_order->movie_show_id = $movie_id;
			$offline_order->seat_ids = rtrim($seat_ids,",");
			$offline_order->seat_names = rtrim($seat_names,",");
			$offline_order->order_time = date("Y-m-d H:i:s",time());
			$offline_order->price = $movie_show->price;
			$offline_order->count = count($seatArray);
			$offline_order->total_money = (int)$movie_show->price * (int)count($seatArray);
			$offline_order->admin_id = $admin_id;
			
			$offline_order->status = 1;
		
			$offline_order->save();
		
			$transaction->commit();
				
			$response = ['code'=> 1,'msg'=>'售票成功'];
		
				
		} catch (Exception $e){
		
			$transaction->rollBack();
			$response = ['code'=> 6,'msg' => "出現了未知錯誤"];
		}
		
		
		return $response;
		
	}
	
	
	
	
	/**
	 * 自助售票機沒紙的時候「短信」通知管理員
	 * @return number[]|string[]
	 */
	public function actionNotify()
	{
		$phone = "65430594";
		$phones = "853".$phone;
		$ch = Helper::notifyNoPaper($phones);
		$response = ['code'=> 1,'msg' => "提示管理員補充紙張"];
		return $response;
	}
	

	
	
}