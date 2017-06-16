<?php
namespace frontend\controllers;


use yii\web\Controller;
use frontend\components\Helper;
use Yii;
use frontend\models\Cinema;
use frontend\models\Movie;
use frontend\models\MovieShow;
use frontend\models\MovieType;
use frontend\models\Room;
use frontend\models\CinemaRoom;
use frontend\models\MovieSeat;
use frontend\components\MyUrl;


class CinemaController extends Controller
{
	public $layout = "mylayout";
	
	public function actionIndex()
	{
		
		if(Yii::$app->request->isGet){
			$id = Yii::$app->request->get('id');
			
			$movie = Movie::find()->where('status = 1')->orderBy('movie_id desc')->all();
			$cinema = Cinema::find()->where('cinema_id = :cinema_id',[':cinema_id'=>$id])->one();
			$movie_time = [];
			$data = [];
			
			
			$now = date("Y-m-d H:i:s",time());
			$movie_show = MovieShow::find()->where('time_begin > :now and  status = 1 and movie_id = :movie_id and cinema_id = :cinema_id ',['now'=>$now,':movie_id'=>$movie[0]['movie_id'],':cinema_id'=>$id ])->orderBy('time_begin asc')->all();
			
			
			foreach ($movie_show as $key => $value) {
				$movie_time[$key] = Helper::getToday($value['time_begin']).Helper::getTimeFormat($value['time_begin']);
			}
			
			//去除重复的数组内容，
			$movie_time = array_unique($movie_time);
			//以键名排序
			ksort($movie_time);
			//重新索引数组，使键名连续
			$movie_time = array_values($movie_time);
			
			//拼接时间，到一天的最后1秒，获取这一天上映的电影
			if(isset($movie_show[0]['time_begin'])) {
				$last_day = explode(" ", $movie_show[0]['time_begin'])[0];
				$last_day .= " 59:59:59";
			}else {
				$last_day = date('Y-m-d',time());
			}
			
		} else {
			$movie = new Movie();
			$movie_time = new MovieShow();
			$cinema = new Cinema();
		}
		
		return $this->render('index',['movie'=>$movie,'movie_time'=>$movie_time,'cinema'=>$cinema,'cinema_id'=>$id]);
	}
	
	
	
	
	/**
	 *  ajax 获取电影场次， 用json数据返回
	 */
	public function actionJsonurl()
	{
		$data = [];
		
		if(Yii::$app->request->isGet){
			$id = Yii::$app->request->get("id");
			$cinema_id = Yii::$app->request->get("cinema_id");

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
						
						$tmp_detail[$k]['show_id'] = $v->id;
						$tmp_detail[$k]['start'] = explode(":", explode(" ", $v['time_begin'])[1])[0].":".explode(":", explode(" ", $v['time_begin'])[1])[1];
						$tmp_detail[$k]['end'] = explode(":", explode(" ", $v['time_end'])[1])[0].":".explode(":", explode(" ", $v['time_end'])[1])[1];
						$tmp_detail[$k]['language'] = $type->type_name;
						$tmp_detail[$k]['hall'] = $cinema_room->room_name;
						$tmp_detail[$k]['price'] = $v->price;
						$tmp_detail[$k]['o_price'] = $v->real_price;
						
						$tmp[$key]['detail'][] = $tmp_detail[$k];
					}
				}
			}
			
			
			$data['movie_name'] = $movie[$id]['movie_name'];
			$data['score'] = $movie[$id]['score'];
			$data['info'] = $movie[$id]['duration']." | ".$movie[$id]['style']." | ". $movie[$id]['charactor'];
			$data['lists'] = $tmp;
			
			$data = json_encode($data);
		
		}
		
		echo $data;
		
	}
	
	public function actionSeat()
	{
		if(Yii::$app->request->isGet){
			$show_id = Yii::$app->request->get("show_id");
			
			$movie_show = MovieShow::find()->where('id = :show_id',[':show_id'=>$show_id])->one();
			
			$room_type = CinemaRoom::find()->where('room_id = :room_id',[':room_id'=>$movie_show['room_id']])->one()['room_type'];
			
			$data = [];
			
			if(!empty($room_type)){

				$movie_name = Movie::find()->where('movie_id = :movie_id',[':movie_id'=>$movie_show['movie_id']])->one();

				$room_name = Room::find()->where('room_id = :room_id',[':room_id'=>$movie_show['room_id']])->one();
					
				$cinema_name = Cinema::find()->where('cinema_id = :cinema_id',[':cinema_id'=>$movie_show['cinema_id']])->one();
					
					
				$data['time_begin'] = explode(":", explode(" ", $movie_show->time_begin)[1])[0].":".explode(":", explode(" ", $movie_show->time_begin)[1])[1] ;
				$data['time_end'] = explode(":", explode(" ", $movie_show->time_end)[1])[0].":".explode(":", explode(" ", $movie_show->time_end)[1])[1] ;
				$data['movie_name'] = $movie_name->movie_name;
				$data['room_name'] = $room_name->room_name;
				$data['cinema_name'] = $cinema_name->cinema_name;
				$data['date'] = Helper::getTimeFormat($movie_show->time_begin);
				$data['price'] = $movie_show->price;
				$data['movie_id'] = $movie_show->id;
				
				
				//查找已經「出售」或者 「預訂」 的座位
				$seats_str = MovieSeat::GetSellSeats($movie_show->id);
				
				//記錄當前url,在登錄之後會跳轉回來
				$seat_url = Yii::$app->request->getHostInfo().Yii::$app->request->url;
				MyUrl::SetUrlCookie($seat_url);
				
				if($room_type == 1){
					return $this->render('seat1',['data'=>$data,'seats_str'=>$seats_str]);
				} else if($room_type == 2){
					return $this->render('seat2',['data'=>$data,'seats_str'=>$seats_str]);
				}
			}else{
				//找不到大厅类型，报错
				return $this->redirect(['error/index']);
			}
		} else{
			//找不到大厅类型，报错
			return $this->redirect(['error/index']);
		}
	}
	
	
	
	
	
	
}