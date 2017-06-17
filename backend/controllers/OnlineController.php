<?php
namespace backend\controllers;

use Yii;
use backend\components\Helper;
use yii\helpers\Url;
use backend\models\MovieOfflineOrder;
use backend\models\User;
use backend\models\Movie;
use backend\models\Room;
use backend\models\MovieOnlineOrder;
use backend\models\MovieShow;
use backend\components\OnlineExcel;

class OnlineController extends BaseController
{
	public $enableCsrfValidation = false;
	public $layout = "myloyout";


	
	public function actionIndex()
	{
	
		$movie = Movie::find()->asArray()->all();
		$room = Room::find()->asArray()->all();
		$movie_show = MovieShow::find()->asArray()->all();
		
		
		$search = Yii::$app->request->post('search');
		
		$sql =  MovieOnlineOrder::find()->where('status = 1')->asArray();
		
		$start_time = "";
		$end_time = "";
		$movie_id = 0;
		$room_id = 0;
		
		//按條件篩選
		if(!empty($search)){
			$start_time = Yii::$app->request->post('start_time');
			$end_time = Yii::$app->request->post('end_time');
			$movie_id = Yii::$app->request->post('movie_id');
			$room_id = Yii::$app->request->post('room_id');
			
			if(!empty($start_time)){
				$s_time = $start_time." 00:00:00";
				$sql->andWhere('order_time > :start_time',[':start_time'=>$s_time]);
			}
				
			if(!empty($end_time)){
				$e_time = $end_time." 23:59:59";
				$sql->andWhere('order_time < :end_time',[':end_time'=>$e_time]);
			}
			
			if($movie_id != 0){
				$movie_select = MovieShow::find()->where('movie_id = :movie_id',[':movie_id'=>$movie_id])->asArray()->all();
				$movie_str = "";
				foreach ($movie_select as $row){
					$movie_str .= $row['id'].",";
				}
				$movie_str = rtrim($movie_str,',');
				$sql->andWhere("movie_show_id in ($movie_str)");
			}
			
			if($room_id != 0){
				$movie_select = MovieShow::find()->where('room_id = :room_id',[':room_id'=>$room_id])->asArray()->all();
				$movie_str = "";
				foreach ($movie_select as $row){
					$movie_str .= $row['id'].",";
				}
				$movie_str = rtrim($movie_str,',');
				$sql->andWhere("movie_show_id in ($movie_str)");
			}
		}
		
		$online_order = $sql->orderBy('id desc')->all();
		
		
		$total_money = 0;
		
		foreach ($online_order as $row){
			$total_money += $row['total_money'];
		}
		

		foreach ($online_order as $key => $row){
			foreach ($movie_show as $s_row){
				if($row['movie_show_id'] == $s_row['id']){
					$online_order[$key]['movie_id'] = $s_row['movie_id'];
					$online_order[$key]['room_id'] = $s_row['room_id'];
				}
			}
		}
		

		//獲取電影名
		foreach ($online_order as $key => $row){
			foreach ($movie as $m_row){
				if($row['movie_id'] == $m_row['movie_id']){
					$online_order[$key]['movie_name'] = $m_row['movie_name'];
				}
			}
		}
		
		//獲取大廳名
		foreach ($online_order as $key => $row){
			foreach ($room as $r_row){
				if($row['room_id'] == $r_row['room_id']){
					$online_order[$key]['room_name'] = $r_row['room_name'];
				}
			}
		}
		
		
		
		return $this->render('index',[
			'online_order'=>$online_order,
			'movie'=>$movie,
			'room'=>$room,
			'total_money'=>$total_money,
			'start_time'=>$start_time,
			'end_time'=>$end_time,
			'movie_id'=>$movie_id,
			'room_id'=>$room_id,
		]);
	}
	
	
	
	/**
	 * 導出excel表格
	 */

	public function actionExport()
	{
		
		$start_time = Yii::$app->request->get('start_time');
		$end_time = Yii::$app->request->get('end_time');
		$movie_id = Yii::$app->request->get('movie_id');
		$room_id = Yii::$app->request->get('room_id');
		
		
		$movie = Movie::find()->asArray()->all();
		$room = Room::find()->asArray()->all();
		$movie_show = MovieShow::find()->asArray()->all();
		
		
		$sql =  MovieOnlineOrder::find()->where('status = 1')->asArray();
		//按條件篩選
		
		if(!empty($start_time)){
			$s_time = $start_time." 00:00:00";
			$sql->andWhere('order_time > :start_time',[':start_time'=>$s_time]);
		}
	
		if(!empty($end_time)){
			$e_time = $end_time." 23:59:59";
			$sql->andWhere('order_time < :end_time',[':end_time'=>$e_time]);
		}
			
		if($movie_id != 0){
			$movie_select = MovieShow::find()->where('movie_id = :movie_id',[':movie_id'=>$movie_id])->asArray()->all();
			$movie_str = "";
			foreach ($movie_select as $row){
				$movie_str .= $row['id'].",";
			}
			$movie_str = rtrim($movie_str,',');
			$sql->andWhere("movie_show_id in ($movie_str)");
		}
			
		if($room_id != 0){
			$movie_select = MovieShow::find()->where('room_id = :room_id',[':room_id'=>$room_id])->asArray()->all();
			$movie_str = "";
			foreach ($movie_select as $row){
				$movie_str .= $row['id'].",";
			}
			$movie_str = rtrim($movie_str,',');
			$sql->andWhere("movie_show_id in ($movie_str)");
		}
		
		
		$online_order = $sql->orderBy('id desc')->all();
		
		$total_money = 0;
		
		foreach ($online_order as $row){
			$total_money += $row['total_money'];
		}
		
		
		foreach ($online_order as $key => $row){
			foreach ($movie_show as $s_row){
				if($row['movie_show_id'] == $s_row['id']){
					$online_order[$key]['movie_id'] = $s_row['movie_id'];
					$online_order[$key]['room_id'] = $s_row['room_id'];
				}
			}
		}
		
		
		//獲取電影名
		foreach ($online_order as $key => $row){
			foreach ($movie as $m_row){
				if($row['movie_id'] == $m_row['movie_id']){
					$online_order[$key]['movie_name'] = $m_row['movie_name'];
				}
			}
		}
		
		//獲取大廳名
		foreach ($online_order as $key => $row){
			foreach ($room as $r_row){
				if($row['room_id'] == $r_row['room_id']){
					$online_order[$key]['room_name'] = $r_row['room_name'];
				}
			}
		}
		
		$data = OnlineExcel::CreateExcelReportingList($online_order,$total_money);
		return $data;
	}
	
}