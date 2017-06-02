<?php

namespace frontend\modules\api\controllers;


use Yii;
use frontend\modules\api\controllers\BaseController;
use frontend\models\MovieOnlineOrder;
use frontend\models\MovieShow;
use frontend\models\Cinema;
use frontend\models\Room;
use frontend\models\Movie;

class OrderController extends BaseController
{
	
	
	
	/**
	 * 取票機 「打印電影票」 接口
	 * @return number[]|string[]
	 */
	public function actionCheckticket()
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
						
						//更改數據狀態，返回電影票信息
						MovieOnlineOrder::updateAll(['status' => 3], 'order_code = :order_code',[':order_code'=>$ssid]);
						
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
						
	
						$response = ['code' => 1,'msg' => '取票成功','data'=>$datas];
						
					} else if ($order->status == 3){
						//電影票已經被提取過
						$response = ['code'=> 4,'msg'=>'電影票已經被提取','data'=>''];
					} else {
						//未完成的訂單(找不到正確的訂單信息)
						$response = ['code'=> 3,'msg'=>'請輸入正確的驗證碼','data'=>''];
					}
				} else {
					//找不到正確的訂單信息
					$response = ['code'=> 3,'msg'=>'請輸入正確的驗證碼','data'=>''];
				}
				
			} else {
				//沒有把訂單號傳過來
				$response = ['code'=> 2,'msg'=>'請填寫驗證碼','data'=>''];
			}
			
		} else {
			$response = ['code'=> 2,'msg'=>'請填寫驗證碼','data'=>''];
		}
		
		return $response;
	}
	
	
	
	
	
	
}