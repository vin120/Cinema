<?php
namespace frontend\models;

use yii\db\ActiveRecord;

use Yii;

class MovieOnlineOrder extends ActiveRecord
{
	public static function tableName()
	{
		return "{{%y_movie_online_order}}";
	}
	
	
	/**
	 * 獲取用戶訂單
	 * @param unknown $page
	 * @param unknown $phone
	 * @return string[]|number[]|NULL[]
	 */
	public static function getUserOrder($page,$phone)
	{
		$data = [];
		$datas = [];
		
		
		//更改「已過期」 並且 「未支付」的訂單 「狀態」 	
		MovieOnlineOrder::updateAll(['status'=>2],'status = 0 and order_time < :time',[':time'=>date("Y-m-d H:i:s",strtotime('-10 minutes'))]);
		
		$online_order = MovieOnlineOrder::find()->where('phone = :phone and status != 2',[':phone'=>$phone])->offset(($page-1)*10)->limit(10)->orderBy('id desc')->all();
	
		foreach ($online_order as $row){
				
			$movie_show = MovieShow::find()->where('id = :id',['id'=>$row->movie_show_id])->one();
			$cinema = Cinema::find()->where('cinema_id = :cinema_id',[':cinema_id'=>$movie_show->cinema_id])->one();
			$room = Room::find()->where('room_id = :room_id',[':room_id'=>$movie_show->room_id])->one();
			$movie = Movie::find()->where('movie_id = :movie_id',[':movie_id'=>$movie_show->movie_id])->one();
			//拼接時間
			$date = explode("-", explode(" ", $movie_show->time_begin)[0])[1]."-".explode("-", explode(" ", $movie_show->time_begin)[0])[2] ;
			$time = explode(":", explode(" ", $movie_show->time_begin)[1])[0].":".explode(":", explode(" ", $movie_show->time_begin)[1])[1] ;
				
			$data['cinema_name'] = $cinema->cinema_name;
			$data['movie_name'] = $movie->movie_name;
			$data['counts'] = $row->count."張";
			$data['pic'] = $movie->img_url;
			$data['cinema_id'] = $cinema->cinema_id;
			$data['hall'] = $room->room_name;
			$data['seats'] = $row->seat_names;
			$data['date'] = $date." ".$time;
			$data['price'] = $row->total_money + $row->count * $cinema->service_price;
			$data['status'] = $row->status;
			$data['ssid'] = $row->order_code;
				
			$datas['lists'][] = $data;
		}
		
		return $datas;
	}
	
	
	public static function findOrderDetail($online_order)
	{

		$movie_show = MovieShow::find()->where('id = :id',['id'=>$online_order->movie_show_id])->one();
		$cinema = Cinema::find()->where('cinema_id = :cinema_id',[':cinema_id'=>$movie_show->cinema_id])->one();
		$room = Room::find()->where('room_id = :room_id',[':room_id'=>$movie_show->room_id])->one();
		$movie = Movie::find()->where('movie_id = :movie_id',[':movie_id'=>$movie_show->movie_id])->one();
		$movie_type = MovieType::find()->where('type_id = :type_id',[':type_id'=>$movie_show->type_id])->one();
		
		//拼接時間
		$date = explode("-", explode(" ", $movie_show->time_begin)[0])[1]."-".explode("-", explode(" ", $movie_show->time_begin)[0])[2] ;
		$time = explode(":", explode(" ", $movie_show->time_begin)[1])[0].":".explode(":", explode(" ", $movie_show->time_begin)[1])[1] ;
		
		$data['seats'] = $online_order->seat_names;
		$data['phone'] = $online_order->phone;
		$data['movie_name'] = $movie->movie_name;
		$data['date'] = $date." ".$time;
		$data['movie_type'] = $movie_type->type_name;
		$data['cinema_name'] = $cinema->cinema_name;
		$data['room_name'] = $room->room_name;
		$data['service_price'] = $cinema->service_price;
		$data['price'] = $online_order->price + $cinema->service_price;
		$data['total_money'] = $online_order->total_money + $online_order->count * $cinema->service_price;
		$data['order_time'] = date("Y-m-d H:i:s",strtotime($online_order->order_time)+600) ;
		$data['address'] = $cinema->cinema_address;
		$data['order_code'] = $online_order->order_code;
		$data['order_number'] = $online_order->order_number;
		$data['cinema_phone'] = $cinema->cinema_phone;
		$data['cinema_work_time'] = $cinema->cinema_work_time;
		$data['status'] = $online_order->status;
		
		return $data;
	}
	
	
	
	/**
	 * API 查找訂單信息
	 * @param unknown $online_order
	 * @return unknown
	 */
	public static function findApiOrderDetail($online_order)
	{
		$movie_show = MovieShow::find()->where('id = :id',['id'=>$online_order->movie_show_id])->one();
		$cinema = Cinema::find()->where('cinema_id = :cinema_id',[':cinema_id'=>$movie_show->cinema_id])->one();
		$room = Room::find()->where('room_id = :room_id',[':room_id'=>$movie_show->room_id])->one();
		$movie = Movie::find()->where('movie_id = :movie_id',[':movie_id'=>$movie_show->movie_id])->one();
		$movie_type = MovieType::find()->where('type_id = :type_id',[':type_id'=>$movie_show->type_id])->one();
		
		//拼接時間
		$date = explode("-", explode(" ", $movie_show->time_begin)[0])[1]."-".explode("-", explode(" ", $movie_show->time_begin)[0])[2] ;
		$time = explode(":", explode(" ", $movie_show->time_begin)[1])[0].":".explode(":", explode(" ", $movie_show->time_begin)[1])[1] ;
		
		$data['seats'] = $online_order->seat_names;
		$data['phone'] = $online_order->phone;
		$data['movie_name'] = $movie->movie_name;
		$data['date'] = $date." ".$time;
		$data['movie_type'] = $movie_type->type_name;
		$data['cinema_name'] = $cinema->cinema_name;
		$data['hall_name'] = $room->room_name;
		$data['service_price'] = $cinema->service_price;
		$data['price'] = $online_order->price + $cinema->service_price;
		$data['total_money'] = $online_order->total_money + $online_order->count * $cinema->service_price;
		$data['remaining_time'] = date("Y-m-d H:i:s",strtotime($online_order->order_time)+600) ;
		$data['order_number'] = $online_order->order_number;
		$data['ssid'] = $online_order->order_code;
	
		return $data;
	}
	
	
}