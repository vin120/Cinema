<?php
namespace backend\models;

use yii\db\ActiveRecord;

use Yii;

class MovieSeat extends ActiveRecord
{
	public static function tableName()
	{
		return "{{%y_movie_seat}}";
	}
	
	
	
	/**
	 * 獲取已售的位置
	 * @param unknown $id
	 * @return string
	 */
	public static function GetSellSeats($id)
	{
		
		//刪除 已經過期的電影 的位置
		$movie_unshow = MovieShow::find()->where('time_begin < :now ',[':now'=>date("Y-m-d H:i:s",time())])->all();
		foreach ($movie_unshow as $row) {
			self::deleteAll('show_id = :show_id',[':show_id'=>$row->id]);
		}
		
		
		//刪除 「狀態」爲「待付款」的並且時間超過15分鐘的位置
		self::deleteAll('show_id = :show_id and cur_time < :time and status = 1',[':show_id'=>$id,':time'=>date("Y-m-d H:i:s",strtotime('-15 minute'))]);
		
		
		$seats = self::find()->where('show_id = :show_id and (status = 1 or status = 2)',[':show_id'=>$id])->all();
		
		
		
		$seats_str = "";
		foreach ($seats as $row){
			$seats_str .= $row['seat_id']."\",\"";
		}
		
		$seats_str = rtrim($seats_str,',""');
		
		return $seats_str;
	}
	
	
	/**
	 * 獲取被禁止的位置
	 * @param unknown $id
	 */
	public static function GetForbiddenSeats($id)
	{
		$seats = self::find()->where('show_id = :show_id and status = 3',[':show_id'=>$id])->all();
		
		$seats_str = "";
		foreach ($seats as $row){
			$seats_str .= $row['seat_id']."\",\"";
		}
		
		$seats_str = rtrim($seats_str,',""');
		
		return $seats_str;
	}
	
}