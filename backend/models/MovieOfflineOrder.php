<?php
namespace backend\models;

use yii\db\ActiveRecord;

use Yii;
use backend\models\MovieShow;
use backend\models\MovieType;

class MovieOfflineOrder extends ActiveRecord
{
	public static function tableName()
	{
		return "{{%y_movie_offline_order}}";
	}
	
	
	public static function findOrderDetail($offline_order)
	{
		$movie_show = MovieShow::find()->where('id = :id',['id'=>$offline_order->movie_show_id])->one();
		$cinema = Cinema::find()->where('cinema_id = :cinema_id',[':cinema_id'=>$movie_show->cinema_id])->one();
		$room = Room::find()->where('room_id = :room_id',[':room_id'=>$movie_show->room_id])->one();
		$movie = Movie::find()->where('movie_id = :movie_id',[':movie_id'=>$movie_show->movie_id])->one();
		$movie_type = MovieType::find()->where('type_id = :type_id',[':type_id'=>$movie_show->type_id])->one();
		
		$admin_name = User::find()->where('admin_id = :admin_id',[':admin_id'=>$offline_order['admin_id']])->one()['admin_nickname'];
		//拼接時間
		$date = explode(" ", $movie_show->time_begin)[0] ;
		$time = explode(":", explode(" ", $movie_show->time_begin)[1])[0].":".explode(":", explode(" ", $movie_show->time_begin)[1])[1] ;
		
		
		$data['order_time'] = $offline_order->order_time;
		$data['cinema_name'] = $cinema->cinema_name;
		$data['room_name'] = $room->room_name;
		$data['seats'] = $offline_order->seat_names;
		$data['total_money'] = $offline_order->total_money;
		$data['date'] = $date;
		$data['time'] = $time;
		$data['movie_name'] = $movie->movie_name;
		$data['count'] = $offline_order->count;
		$data['price'] = $offline_order->price;
		$data['admin_name'] = $admin_name;
		return $data;
	}
	
	
}