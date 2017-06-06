<?php
namespace backend\controllers;

use Yii;
use backend\components\Helper;
use yii\helpers\Url;
use yii\db\Query;
use backend\models\MovieShow;
use backend\models\CinemaRoom;
use backend\models\Movie;
use backend\models\Room;
use backend\models\Cinema;
use backend\models\MovieSeat;


class CinemaController extends BaseController
{
	public $enableCsrfValidation = false;
	public $layout = "myloyout";
		
	public function actionIndex()
	{
		//電影院個數
		$sql = " SELECT count(*) count FROM `y_cinema`";
		$count = Yii::$app->db->createCommand($sql)->queryOne();
		
		//電影院信息
		$sql = "SELECT * FROM `y_cinema` ORDER BY cinema_id";
		$data = Yii::$app->db->createCommand($sql)->queryAll();
		
		return $this->render('index',[
			'data'=>$data,
			'count'=>$count['count'],
        	'pag'=>1,
				
        ]);
	}
	
	
	
	public function actionAdd()
	{
		$db = Yii::$app->db;
		if($_POST) {
			$cinema_name = isset($_POST['cinema_name']) ? $_POST['cinema_name'] : '';
			$cinema_phone = isset($_POST['cinema_phone']) ? $_POST['cinema_phone'] : '';
			$cinema_address = isset($_POST['cinema_address']) ? $_POST['cinema_address'] : '';
			$low_price = isset($_POST['low_price']) ? $_POST['low_price'] : '';
			$cinema_work_time = isset($_POST['cinema_work_time']) ? $_POST['cinema_work_time'] :'';
			$status = isset($_POST['status']) ? $_POST['status'] : 1;
			
			$sql = "INSERT INTO `y_cinema` (cinema_name,cinema_phone,low_price,cinema_address,cinema_work_time,status) VALUES ('{$cinema_name}','{$cinema_phone}','{$low_price}','{$cinema_address}','{$cinema_work_time}','{$status}')";
			
			$commen = $db->beginTransaction();
			try{
				$db->createCommand($sql)->execute();
				$in_id = $db->getLastInsertId();
				$commen->commit();
				Helper::show_message('保存成功', Url::toRoute(['edit','id'=>$in_id]));
			}catch(Exception $e){
				$commen->rollBack();
				Helper::show_message('保存失敗','#');
			}
			
		}
		
		return $this->render('add');
	}
	
	
	public function actionEdit()
	{
		$id = isset($_GET['id'])?trim($_GET['id']):'';
		$table = isset($_GET['table'])?trim($_GET['table']):1;
		$db = Yii::$app->db;
		
		// if($table == 1)
		$sql = "SELECT * FROM `y_cinema` WHERE cinema_id='{$id}'AND status=1 ";
		$cinema_basic = $db->createCommand($sql)->queryOne();
		
		
		
		// if($table == 2)
		$sql = "SELECT * FROM `y_room` WHERE status=1";
		$room = $db->createCommand($sql)->queryAll();
		
		
		$sql = "SELECT * FROM `y_cinema_room` WHERE `cinema_id`={$id} ORDER BY id DESC ";
		$cinema_room = $db->createCommand($sql)->queryAll();
		
		
		
		// if($table == 3)
		$sql = "SELECT * FROM `y_movie_show` WHERE cinema_id={$id} ORDER BY id DESC LIMIT 10";
		$movie_show = $db->createCommand($sql)->queryAll();
		
		
		$sql = "SELECT count(*) count FROM `y_movie_show` WHERE cinema_id={$id} ";
		$movie_count = $db->createCommand($sql)->queryOne();
		
		$sql = "SELECT a.* FROM `y_room` a LEFT JOIN `y_cinema_room`  b ON a.room_id = b.room_id WHERE b.cinema_id={$id} ";
		$room_select = $db->createCommand($sql)->queryAll();
		
		$sql = "SELECT * FROM `y_movie_type` WHERE status = 1";
		$movie_type = $db->createCommand($sql)->queryAll();
		
		$sql = "SELECT * FROM `y_movie` WHERE status=1";
		$movie = $db->createCommand($sql)->queryAll();
		

		return $this->render('edit',[
			'cinema_basic'=>$cinema_basic,
			'room'=>$room,
			'cinema_room'=>$cinema_room,
			'movie_show'=>$movie_show,
			'room_select'=>$room_select,
			'movie'=>$movie,
			'table'=>$table,
			'movie_type'=>$movie_type,
			'movie_count' => $movie_count['count'],
		]);
	}
	
	
	/**
	 * ajax 获取上映电影 分页 
	 */
	public function actionGetmoviepage()
	{
		
		$pag = isset($_GET['pag']) ? $_GET['pag'] == 1 ? 0 : ($_GET['pag'] - 1) * 10 : 0;
		$id = isset($_GET['id'])?trim($_GET['id']):'';
		
		
		$query = new Query();
		$result = $query->select(['*'])
		->from('y_movie_show')
		->where('cinema_id = :id',[':id'=>$id])
		->offset($pag)
		->orderby('id desc')
		->limit(10)
		->all();
		
		
		if($result) {
			echo json_encode($result);
		} else {
			echo 0;
		}
	}
	
	
	
	public function actionCinemabasicedit()
	{
		$db = Yii::$app->db;
		
		if($_POST) {
			$cinema_id = isset($_POST['cinema_id']) ? $_POST['cinema_id'] : '';
			$cinema_name = isset($_POST['cinema_name']) ? $_POST['cinema_name'] : '';
			$cinema_phone = isset($_POST['cinema_phone']) ? $_POST['cinema_phone'] : '';
			$low_price = isset($_POST['low_price']) ? $_POST['low_price'] : '';
			$cinema_address = isset($_POST['cinema_address']) ? $_POST['cinema_address'] : '';
			$cinema_work_time = isset($_POST['cinema_work_time']) ? $_POST['cinema_work_time'] :'';
			$status = isset($_POST['status']) ? $_POST['status'] : 1;
						
			$sql = "UPDATE `y_cinema` 
			       SET cinema_name='{$cinema_name}',cinema_phone='{$cinema_phone}',low_price='{$low_price}',cinema_address='{$cinema_address}',cinema_work_time='{$cinema_work_time}',status='{$status}' 
			       WHERE cinema_id='{$cinema_id}' ";
			
			$commen = $db->beginTransaction();
			try{
				$db->createCommand($sql)->execute();
				$commen->commit();
				Helper::show_message('保存成功', Url::toRoute(['edit','id'=>$cinema_id]));
			}catch(Exception $e){
				$commen->rollBack();
				Helper::show_message('保存失敗','#');
			}
		}
	}
	

	
	/**
	 * 保存大厅基本信息
	 */
	public function actionRoombasicsave()
	{
		$db = Yii::$app->db;
		if($_POST) {
			
			$room_number = isset($_POST['room_number'])?trim(trim($_POST['room_number']),','):'';
			$cinema_id  = isset($_POST['cinema_id'])?trim($_POST['cinema_id']):'';
			$room_name  = isset($_POST['room_name'])?$_POST['room_name']:array();
			$room_attr  = isset($_POST['room_attr'])?$_POST['room_attr']:array();	//区分新增or编辑
			$r_status   = isset($_POST['r_status'])?$_POST['r_status']:array();
			$total_seat = isset($_POST['total_seat']) ? $_POST['total_seat'] :array();
			$sale_seat  = isset($_POST['sale_seat']) ? $_POST['sale_seat'] :array();
			$room_type  = isset($_POST['room_type']) ? $_POST['room_type'] :array();
			
	
			
			$in_sql = "INSERT INTO `y_cinema_room` (cinema_id,room_id,room_type,total_seat,sale_seat,status) VALUES ";
			$in_str = "";
			$commen = $db->beginTransaction();
			try{
				foreach ($room_attr as $k=>$value) {
					if($value !=''){
						$up_sql = "UPDATE `y_cinema_room` SET cinema_id='{$cinema_id}',room_id='{$room_name[$k]}',room_type='{$room_type[$k]}',total_seat='{$total_seat[$k]}',sale_seat='{$sale_seat[$k]}',status='{$r_status[$k]}' WHERE id='{$value}' ";
						$db->createCommand($up_sql)->execute();
					} else{
						$in_str .= "('{$cinema_id}','{$room_name[$k]}','{$room_type[$k]}','{$total_seat[$k]}','{$sale_seat[$k]}','{$r_status[$k]}'),";
					}
					
				}
				if($in_str!=''){
					$in_str = trim($in_str,',');
					$in_sql = $in_sql . $in_str;
					$db->createCommand($in_sql)->execute();
				}

				$commen->commit();
				Helper::show_message('保存成功', Url::toRoute(['edit','id'=>$cinema_id,'table'=>'2']));
			}catch(Exception $e){
				$commen->rollBack();
				Helper::show_message('保存失敗','#');
			}
				
		}
	}
	
	
	
	/**
	 * 删除大厅配置信息
	 */
	public function actionDeleteroombaisc()
	{
		$db = Yii::$app->db;
		$flag = 0;
		$id = isset($_POST['id'])?trim($_POST['id']):'';
		$sql = "DELETE FROM `y_cinema_room` WHERE id='{$id}' ";
	
		$commen = $db->beginTransaction();
		try{
			$db->createCommand($sql)->execute();
			$commen->commit();
			$flag = 1;
		}catch(Exception $e){
			$commen->rollBack();
			$flag = 0;
		}
	
		echo $flag;
	}
	
	
	
	
	/**
	 * 保存上映電影信息
	 */
	public function actionMoviesave()
	{
		$db = Yii::$app->db;
		if($_POST){

			$cinema_id = isset($_POST['cinema_id'])?trim($_POST['cinema_id']):'';
			$movie_show_attr = isset($_POST['movie_show_attr'])?$_POST['movie_show_attr']:array();
			$room_name = isset($_POST['room_name'])?$_POST['room_name']:array();
			$movie_name = isset($_POST['movie_name'])?$_POST['movie_name']:array();
			$movie_type = isset($_POST['movie_type']) ? $_POST['movie_type'] : array();
			$price = isset($_POST['price'])?$_POST['price']:array();
			$real_price = isset($_POST['real_price'])?$_POST['real_price']:array();
			$s_time = isset($_POST['s_time'])?$_POST['s_time']:array();
			$e_time = isset($_POST['e_time'])?$_POST['e_time']:array();
			$m_status = isset($_POST['m_status'])?$_POST['m_status']:array();
			
			
			$in_sql = "INSERT INTO `y_movie_show` (cinema_id,room_id,movie_id,type_id,price,real_price,time_begin,time_end,status) VALUES ";
			$in_str = '';
			$commen = $db->beginTransaction();
			try{

				foreach($movie_show_attr as $k=>$value){
					if($value!=''){	//修改
						$sql = "UPDATE `y_movie_show` SET cinema_id='{$cinema_id}',room_id='{$room_name[$k]}',movie_id='{$movie_name[$k]}',type_id='{$movie_type[$k]}',price='{$price[$k]}',real_price='{$real_price[$k]}',time_begin='{$s_time[$k]}',time_end='{$e_time[$k]}',status='{$m_status[$k]}' WHERE id='{$value}' ";
						$db->createCommand($sql)->execute();
					}else{	//新增
						$in_str .= " ('{$cinema_id}','{$room_name[$k]}','{$movie_name[$k]}','{$movie_type[$k]}','{$price[$k]}','{$real_price[$k]}','{$s_time[$k]}','{$e_time[$k]}','{$m_status[$k]}'),";
					}
				}

				if($in_str!=''){
					$in_str = trim($in_str,',');
					$in_sql = $in_sql . $in_str;
					$db->createCommand($in_sql)->execute();
				}

				$commen->commit();
				Helper::show_message('保存成功', Url::toRoute(['edit','id'=>$cinema_id,'table'=>'3']));
			}catch(Exception $e){
				$commen->rollBack();
				Helper::show_message('保存失败','#');
			}

		}
		
	}
	
	
	/**
	 * 删除上映電影信息
	 */
	public function actionDeletemovieattr() 
	{
		$db = Yii::$app->db;
		$flag = 0;
		$id = isset($_POST['id'])?trim($_POST['id']):'';
		$sql = "DELETE FROM `y_movie_show` WHERE id='{$id}' ";
		
		$commen = $db->beginTransaction();
		try{
			$db->createCommand($sql)->execute();
			$commen->commit();
			$flag = 1;
		}catch(Exception $e){
			$commen->rollBack();
			$flag = 0;
		}
		
		echo $flag;
	}
	
	
	public function actionSeat()
	{
		if(Yii::$app->request->isGet){
			$show_id = Yii::$app->request->get('showid');
			
			$movie_show = MovieShow::find()->where('id = :id',[':id'=>$show_id])->one();
			
			if(is_null($movie_show)){
				return $this->redirect(Url::to('/error/index'));
				Yii::$app->end();
			}
			
			
			$cinema_room = CinemaRoom::find()->where('room_id = :room_id',[':room_id'=>$movie_show['room_id']])->one();
			
			if(is_null($cinema_room)){
				return $this->redirect(Url::to('/error/index'));
				Yii::$app->end();
			}
			
			$movie_name = Movie::find()->where('movie_id = :movie_id',[':movie_id'=>$movie_show['movie_id']])->one();
			
			$room_name = Room::find()->where('room_id = :room_id',[':room_id'=>$movie_show['room_id']])->one();
				
			$cinema_name = Cinema::find()->where('cinema_id = :cinema_id',[':cinema_id'=>$movie_show['cinema_id']])->one();
			
			$data['time_begin'] = explode(":", explode(" ", $movie_show->time_begin)[1])[0].":".explode(":", explode(" ", $movie_show->time_begin)[1])[1] ;
			$data['time_end'] = explode(":", explode(" ", $movie_show->time_end)[1])[0].":".explode(":", explode(" ", $movie_show->time_end)[1])[1] ;
			$data['movie_name'] = $movie_name->movie_name;
			$data['room_name'] = $room_name->room_name;
			$data['cinema_name'] = $cinema_name->cinema_name;
			$data['date'] = Helper::getTimeFormat($movie_show->time_begin);
			$data['movie_id'] = $movie_show->id;
	

			//查找已經「出售」或者 「預訂」 的座位
			$seats_str = MovieSeat::GetSellSeats($movie_show->id);
			
			//查找已經「被禁止 的座位
			$seats_forbidden = MovieSeat::GetForbiddenSeats($movie_show->id);
			
			
			if($cinema_room->room_type == 1){
				
				return $this->render('seat1',['data'=>$data,'seats_str'=>$seats_str,'seats_forbidden'=>$seats_forbidden]);
			}
			
			if($cinema_room->room_type == 2){
				
				return $this->render('seat2',['data'=>$data,'seats_str'=>$seats_str,'seats_forbidden'=>$seats_forbidden]);
			}

		}else{
			return $this->redirect(Url::to('/error/index'));
		}
	}
	
	
	public function actionOpenseat()
	{
		
		$response = [];
		
		if(Yii::$app->request->isPost){
			$post = Yii::$app->request->post();
			$movie_id = $post['movie_id'];
			
			if(!empty($post['seatArray'])){
				
				$seatArray = $post['seatArray'];
				
				foreach ($seatArray as $row) {
					MovieSeat::deleteAll('show_id = :show_id and seat_id = :seat_id and status = 3',[':show_id'=>$movie_id,':seat_id'=>$row]);
				}
				
				$response = ['code'=> 0,'msg'=>''];
				
			}else {
				$response = ['code'=> 3,'msg' => "請選擇座位"];
			}
			
		} else {
			$response = ['code'=> 2,'msg' => "出現了錯誤"];
		}
		
		echo json_encode($response);
	}
	
	
	
	public function actionCloseseat()
	{
		$response = [];
	
		
		
		if(Yii::$app->request->isPost){
			$post = Yii::$app->request->post();
			$movie_id = $post['movie_id'];
			if(!empty($post['seatArray'])){
				
				$seatArray = $post['seatArray'];
				
				foreach ($seatArray as $row) {
					$seat_select = MovieSeat::find()->where('show_id = :show_id and seat_id = :seat_id',[':show_id'=>$movie_id,':seat_id'=>$row])->one();
					
					if(is_null($seat_select)){
						$seat = new MovieSeat();
						$seat->show_id = $movie_id;
						$seat->seat_id = $row;
						$seat->seat_name = explode("_", $row)[0] . "行".explode("_", $row)[1]."座";
						$seat->cur_time = date("Y-m-d H:i:s",time());
						$seat->status = 3;
						$seat->save();
					}
					
				}
				
				$response = ['code'=> 0,'msg'=>''];
				
			}else {
				$response = ['code'=> 3,'msg' => "請選擇座位"];
			}
			
		} else {
			$response = ['code'=> 2,'msg' => "出現了錯誤"];
		}
		
		echo json_encode($response);
	}
	
	
	
	
	
}