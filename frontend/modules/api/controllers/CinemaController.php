<?php

namespace frontend\modules\api\controllers;


use Yii;
use frontend\modules\api\controllers\BaseController;
use frontend\models\MovieOnlineOrder;
use frontend\models\MovieShow;
use frontend\models\Cinema;
use frontend\models\Room;
use frontend\models\Movie;
use frontend\components\Helper;
use frontend\models\MovieType;
use frontend\models\CinemaRoom;
use frontend\models\MovieSeat;


class CinemaController extends BaseController
{
	/**
	 * 获取首页电影院列表
	 * @return number[]|string[]|\yii\db\ActiveRecord[][]
	 */
	public function actionCinemas()
	{
		$cinema = Cinema::find()->select('cinema_id,cinema_name,cinema_address,low_price')->where('status = 1')->orderBy('cinema_id desc')->all();

		$data = [];
		$data['cinema'] = $cinema;
		$response = ['code' => 1,'msg' => '獲取成功','data'=>$data];
		
		return $response;
	}
	
	
	/**
	 * 获取电影院详细信息
	 */
	public function actionCinemadetail()
	{
		$id = Yii::$app->request->post('id');
		
		if(empty($id)){
			$response = ['code' => 2,'msg' => '请输入id'];
			return $response;
			Yii::$app->end();
		}
		
		
		$movie = Movie::find()->select('movie_id,movie_name,img_url,style,duration,charactor,score')->where('status = 1')->orderBy('movie_id desc')->all();
		$cinema = Cinema::find()->select('cinema_id,cinema_phone,cinema_name,cinema_address')->where('cinema_id = :cinema_id',[':cinema_id'=>$id])->one();

		
		if(!empty($movie)){
			foreach ($movie as $row){
				unset($row['movie_id']);
			}
		}
		$data =[];
		$data['cinema'] = $cinema;
		$data['movie'] = $movie;
		
		$response = ['code' => 1,'msg' => '获取成功','data'=>$data];
		
		return $response;
		
	}
	
	
	/**
	 * 獲取上映電影場次
	 * @return number[]|string[]|number[]|string[]|unknown[]
	 */
	public function actionMovieshow()
	{
		$id = Yii::$app->request->post("id");
		$cinema_id = Yii::$app->request->post("cinema_id");
		
		if(empty($cinema_id)){
			$response = ['code' => 2,'msg' => 'cinema_id 不能爲空'];
			return $response;
			Yii::$app->end();
		}
		
		if(empty($id)){
			$id = 0;
		}
		
		
		$movie = Movie::find()->where('status = 1')->orderBy('movie_id desc')->all();
		
		$now = date("Y-m-d H:i:s",time());
		$movie_show = MovieShow::find()->where('time_begin > :now and  status = 1 and movie_id = :movie_id and cinema_id = :cinema_id ',['now'=>$now,':movie_id'=>$movie[$id]['movie_id'],':cinema_id'=>$cinema_id ])->orderBy('time_begin asc')->all();
			
		$movie_time = [];
			
		foreach ($movie_show as $key => $value) {
			$movie_time[$key] = Helper::getToday($value['time_begin']).Helper::getTimeFormat($value['time_begin']);
		}
		
		//去除重复的数组内容，
		$movie_time = array_unique($movie_time);
		//以键名排序
		ksort($movie_time);
		//重新索引数组，使键名连续
		$movie_time = array_values($movie_time);
		
		
		//封装json里面list的内容
		$tmp = [];
		$tmp_detail = [];
		foreach ($movie_time as $key => $value){
			$tmp[$key]['time'] = $value;
			$tmp[$key]['detail'] = [];
		
			foreach ($movie_show as $k => $v){
				if($value == Helper::getToday($v['time_begin']).Helper::getTimeFormat($v['time_begin'])){
		
					$type = MovieType::find()->where('type_id = :type_id',[':type_id'=>$v['type_id']])->one();
					$cinema_room = Room::find()->where('room_id = :room_id',[':room_id'=>$v['room_id']])->one();
					$hall_type = CinemaRoom::find()->where('room_id = :room_id',[':room_id'=>$v['room_id']])->one()['room_type'];
					
					$tmp_detail[$k]['show_id'] = $v->id;
					$tmp_detail[$k]['start'] = explode(":", explode(" ", $v['time_begin'])[1])[0].":".explode(":", explode(" ", $v['time_begin'])[1])[1];
					$tmp_detail[$k]['end'] = explode(":", explode(" ", $v['time_end'])[1])[0].":".explode(":", explode(" ", $v['time_end'])[1])[1];
					$tmp_detail[$k]['language'] = $type->type_name;
					$tmp_detail[$k]['hall_type'] = $hall_type;
					$tmp_detail[$k]['hall'] = $cinema_room->room_name;
					$tmp_detail[$k]['price'] = $v->price;
					$tmp_detail[$k]['o_price'] = $v->real_price;
					
					
					$tmp[$key]['detail'][] = $tmp_detail[$k];
				}
			}
		}
			
			
		$data['movie_name'] = $movie[$id]['movie_name'];
		$data['lists'] = $tmp;
		
		$response = ['code' => 1,'msg' => '获取成功','data'=>$data];
		
		return $response;
	}
	
	
	
	/**
	 *  獲取已售座位
	 * @return number[]|string[]
	 */
	public function actionSeats()
	{
		$show_id = Yii::$app->request->post('show_id');
		
		if(empty($show_id)){
			$response = ['code' => 2,'msg' => 'show_id 不能爲空'];
			return $response;
			Yii::$app->end();
		}
		
		$movie_show = MovieShow::find()->where('id = :show_id',[':show_id'=>$show_id])->one();
			
		$room_type = CinemaRoom::find()->where('room_id = :room_id',[':room_id'=>$movie_show['room_id']])->one()['room_type'];
		
		
		$movie_name = Movie::find()->where('movie_id = :movie_id',[':movie_id'=>$movie_show['movie_id']])->one();
		
		$room_name = Room::find()->where('room_id = :room_id',[':room_id'=>$movie_show['room_id']])->one();
			
		$cinema_name = Cinema::find()->where('cinema_id = :cinema_id',[':cinema_id'=>$movie_show['cinema_id']])->one();

		//查找已經「出售」或者 「預訂」 的座位
		$seats_str = MovieSeat::GetSellSeats($movie_show->id,true);
			
		$data['time_begin'] = explode(":", explode(" ", $movie_show->time_begin)[1])[0].":".explode(":", explode(" ", $movie_show->time_begin)[1])[1] ;
		$data['time_end'] = explode(":", explode(" ", $movie_show->time_end)[1])[0].":".explode(":", explode(" ", $movie_show->time_end)[1])[1] ;
		$data['cinema_name'] = $cinema_name->cinema_name;
		$data['hall_name'] = $room_name->room_name;
		$data['movie_name'] = $movie_name->movie_name;
		$data['date'] = Helper::getTimeFormat($movie_show->time_begin);
		$data['price'] = $movie_show->price;
		$data['movie_id'] = $movie_show->id;
		$data['seats'] = $seats_str;
		
		
		$response = ['code' => 1,'msg' => '获取成功','data'=>$data];
		
		return $response;
	}
	
	
	
	
}