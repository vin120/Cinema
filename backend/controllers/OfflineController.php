<?php
namespace backend\controllers;

use Yii;
use backend\components\Helper;
use yii\helpers\Url;
use backend\models\MovieOfflineOrder;
use backend\models\User;
use backend\models\Movie;
use backend\models\Room;
use backend\models\MovieShow;
use backend\components\OfflineExcel;


class OfflineController extends BaseController
{
	public $enableCsrfValidation = false;
	public $layout = "myloyout";


	
	/**
	 * 	線下流水頁面
	 * @return string
	 */
	public function actionIndex()
	{
		
		$admin = User::find()->where('admin_grade = 0')->asArray()->all();
		$movie = Movie::find()->where('status = 1')->asArray()->all();
		$room = Room::find()->asArray()->all();
		$movie_show = MovieShow::find()->asArray()->all();
		
		
		$search = Yii::$app->request->post('search');
		$sql = MovieOfflineOrder::find()->where('status = 1')->asArray();
		
		$start_time = "";
		$end_time = "";
		$admin_id = 0;
		$movie_id = 0;
		$room_id = 0;
		
		//按條件篩選
		if(!empty($search)){
			$start_time = Yii::$app->request->post('start_time');
			$end_time = Yii::$app->request->post('end_time');
			$admin_id = Yii::$app->request->post('admin_id');
			$movie_id = Yii::$app->request->post('movie_id');
			$room_id = Yii::$app->request->post('room_id');
			
			if(!empty($start_time)){
				$s_time = $start_time." 00:00:00";
				$sql->andWhere('order_time > :start_time',[':start_time'=>$s_time]);
			}
			
			if(!empty($end_time)){
				$e_time = $end_time." 59:59:59";
				$sql->andWhere('order_time < :end_time',[':end_time'=>$e_time]);
			}
			
			if($admin_id != 0){
				$sql->andWhere('admin_id = :admin_id',[':admin_id'=>$admin_id]);
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
		
		$offline_order = $sql->all();
		
		$total_money = 0;
		
		foreach ($offline_order as $row){
			$total_money += $row['total_money'];
		}
		
		//獲取售票員名
		foreach ($offline_order as $key => $row){
			foreach ($admin as $a_row){
				if($row['admin_id'] == $a_row['admin_id']){
					$offline_order[$key]['admin_name'] = $a_row['admin_nickname'];
				}
			}
		}
		
		
		foreach ($offline_order as $key => $row){
			foreach ($movie_show as $s_row){
				if($row['movie_show_id'] == $s_row['id']){
					$offline_order[$key]['movie_id'] = $s_row['movie_id'];
					$offline_order[$key]['room_id'] = $s_row['room_id'];
				}
			}
		}
		
		//獲取電影名
		foreach ($offline_order as $key => $row){
			foreach ($movie as $m_row){
				if($row['movie_id'] == $m_row['movie_id']){
					$offline_order[$key]['movie_name'] = $m_row['movie_name'];
				}
			}
		}
		
		//獲取大廳名
		foreach ($offline_order as $key => $row){
			foreach ($room as $r_row){
				if($row['room_id'] == $r_row['room_id']){
					$offline_order[$key]['room_name'] = $r_row['room_name'];
				}
			}
		}
		
		return $this->render('index',[
				'admin'=>$admin,
				'movie'=>$movie,
				'room'=>$room,
				'offline_order'=>$offline_order,
				'total_money'=>$total_money,
				'start_time'=>$start_time,
				'end_time' =>$end_time,
				'admin_id'=>$admin_id,
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
		$admin_id = Yii::$app->request->get('admin_id');
		$movie_id = Yii::$app->request->get('movie_id');
		$room_id = Yii::$app->request->get('room_id');
		
		
		$admin = User::find()->where('admin_grade = 0')->asArray()->all();
		$movie = Movie::find()->where('status = 1')->asArray()->all();
		$room = Room::find()->asArray()->all();
		$movie_show = MovieShow::find()->asArray()->all();
		
		$sql = MovieOfflineOrder::find()->where('status = 1')->asArray();
		
		
		
		if(!empty($start_time)){
			$s_time = $start_time." 00:00:00";
			$sql->andWhere('order_time > :start_time',[':start_time'=>$s_time]);
		}
			
		if(!empty($end_time)){
			$e_time = $end_time." 59:59:59";
			$sql->andWhere('order_time < :end_time',[':end_time'=>$e_time]);
		}
			
		if($admin_id != 0){
			$sql->andWhere('admin_id = :admin_id',[':admin_id'=>$admin_id]);
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
		
		
		
		$offline_order = $sql->all();
		
		$total_money = 0;
		
		foreach ($offline_order as $row){
			$total_money += $row['total_money'];
		}
		
		//獲取售票員名
		foreach ($offline_order as $key => $row){
			foreach ($admin as $a_row){
				if($row['admin_id'] == $a_row['admin_id']){
					$offline_order[$key]['admin_name'] = $a_row['admin_nickname'];
				}
			}
		}
		
		
		foreach ($offline_order as $key => $row){
			foreach ($movie_show as $s_row){
				if($row['movie_show_id'] == $s_row['id']){
					$offline_order[$key]['movie_id'] = $s_row['movie_id'];
					$offline_order[$key]['room_id'] = $s_row['room_id'];
				}
			}
		}
		
		//獲取電影名
		foreach ($offline_order as $key => $row){
			foreach ($movie as $m_row){
				if($row['movie_id'] == $m_row['movie_id']){
					$offline_order[$key]['movie_name'] = $m_row['movie_name'];
				}
			}
		}
		
		//獲取大廳名
		foreach ($offline_order as $key => $row){
			foreach ($room as $r_row){
				if($row['room_id'] == $r_row['room_id']){
					$offline_order[$key]['room_name'] = $r_row['room_name'];
				}
			}
		}
		
		
		$data = OfflineExcel::CreateExcelReportingList($offline_order,$total_money);
		return $data;
	}
	
	
}