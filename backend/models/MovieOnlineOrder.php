<?php
namespace backend\models;

use yii\db\ActiveRecord;

use Yii;
use backend\models\MovieShow;
use backend\models\MovieType;

class MovieOnlineOrder extends ActiveRecord
{
	public static function tableName()
	{
		return "{{%y_movie_online_order}}";
	}
	
	
	
	
	public static function findOrderDetail($online_order)
	{

		$movie_show = MovieShow::find()->where('id = :id',['id'=>$online_order->movie_show_id])->one();
		$cinema = Cinema::find()->where('cinema_id = :cinema_id',[':cinema_id'=>$movie_show->cinema_id])->one();
		$room = Room::find()->where('room_id = :room_id',[':room_id'=>$movie_show->room_id])->one();
		$movie = Movie::find()->where('movie_id = :movie_id',[':movie_id'=>$movie_show->movie_id])->one();
		$movie_type = MovieType::find()->where('type_id = :type_id',[':type_id'=>$movie_show->type_id])->one();
		
		//拼接時間
		$date = explode(" ", $movie_show->time_begin)[0] ;
		$time = explode(":", explode(" ", $movie_show->time_begin)[1])[0].":".explode(":", explode(" ", $movie_show->time_begin)[1])[1] ;
		
		
		$data['order_number'] = $online_order->order_number;
		$data['pay_time'] = $online_order->pay_time;
		$data['order_time'] = $online_order->order_time;
		$data['order_code'] = $online_order->order_code;
		$data['cinema_name'] = $cinema->cinema_name;
		$data['room_name'] = $room->room_name;
		$data['seats'] = $online_order->seat_names;
		$data['total_money'] = $online_order->total_money;
		$data['date'] = $date;
		$data['time'] = $time;
		$data['movie_name'] = $movie->movie_name;
		$data['phone'] = $online_order->phone;
		$data['count'] = $online_order->count;
		$data['price'] = $online_order->price;
		
		return $data;
	}
	
	
}