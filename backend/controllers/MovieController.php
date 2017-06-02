<?php
namespace backend\controllers;

use Yii;
use backend\components\Helper;
use yii\helpers\Url;
use yii\db\Query;


class MovieController extends BaseController
{
	public $enableCsrfValidation = false;
	public $layout = "myloyout";
	
	
	/**
	 * 電影配置首頁
	 * @return string
	 */
	public function actionIndex()
	{
		$sql = "SELECT count(*) count FROM `y_movie` ";
		$count = Yii::$app->db->createCommand($sql)->queryOne();
		
		$sql = "SELECT * FROM `y_movie` ORDER BY movie_id DESC LIMIT 10";
		$data = Yii::$app->db->createCommand($sql)->queryAll();
		
		
		return $this->render('index',[
				'data'=>$data,
				'count'=>$count['count'],
        		'pag'=>1,
        ]);
	}
	
	
	/**
	 * 添加電影基本信息
	 * @return string
	 */
	public function actionAdd()
	{
		if($_POST){
			
			$movie_name = isset($_POST['movie_name'])?trim($_POST['movie_name']):'';
			$on_time = isset($_POST['on_time'])?trim($_POST['on_time']):'';
			$style = isset($_POST['style'])?trim($_POST['style']):'';
			$area = isset($_POST['area'])?trim($_POST['area']):'';
			$duration = isset($_POST['duration'])?trim($_POST['duration']):'';
			$director = isset($_POST['director'])?trim($_POST['director']):'';
			$charactor = isset($_POST['charactor'])?trim($_POST['charactor']):'';
			$score = isset($_POST['score'])?trim($_POST['score']):'';
			$status = isset($_POST['status'])?trim($_POST['status']):1;
			$content = isset($_POST['content'])?trim($_POST['content']):'';
			
			
			$allow_size = 3;
			if($_FILES['image']['error']!=4){
				$result=Helper::upload_file('image',"./".Yii::$app->params['img_url_prefix'].date('Ymd',time()), 'image', $allow_size);
				$photo=date('Ymd',time()).'/'.$result['filename'];
			}
			if(!isset($photo)){
				$photo="";
			}
			
			
			$result = Yii::$app->db->createCommand()
			->insert('y_movie',[
					'movie_name'=>$movie_name,
					'on_time'=>$on_time,
					'style'=>$style,
					'duration'=>$duration,
					'area'=>$area,
					'director'=>$director,
					'charactor'=>$charactor,
					'score'=>$score,
					'status'=>$status,
					'img_url'=>$photo,
					'content'=>$content,
			])->execute();
			
			if($result) {
				Helper::show_message('保存成功', Url::toRoute(['index']));
			} else {
				Helper::show_message('保存失败','#');
			}
		}
		
		return $this->render('add');
	}
	
	
	/**
	 * 編輯電影信息
	 * @return string
	 */
	public function actionEdit()
	{
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		
		if($_POST){
			$movie_name = isset($_POST['movie_name'])?trim($_POST['movie_name']):'';
			$on_time = isset($_POST['on_time'])?trim($_POST['on_time']):'';
			$style = isset($_POST['style'])?trim($_POST['style']):'';
			$duration = isset($_POST['duration'])?trim($_POST['duration']):'';
			$area = isset($_POST['area'])?trim($_POST['area']):'';
			$director = isset($_POST['director'])?trim($_POST['director']):'';
			$charactor = isset($_POST['charactor'])?trim($_POST['charactor']):'';
			$score = isset($_POST['score'])?trim($_POST['score']):'';
			$status = isset($_POST['status'])?trim($_POST['status']):1;
			$content = isset($_POST['content'])?trim($_POST['content']):'';
		
		
			$allow_size = 3;
			if($_FILES['image']['error']!=4){
				$result=Helper::upload_file('image',"./".Yii::$app->params['img_url_prefix'].date('Ymd',time()), 'image', $allow_size);
				$photo=date('Ymd',time()).'/'.$result['filename'];
			}
		
		
			if(!isset($photo)){
				$query=new Query();
				$photo= $query->select(['img_url'])
				->from('y_movie')
				->where("movie_id=$id")
				->one()['img_url'];
			}
		
			$result = Yii::$app->db->createCommand()
			->update('y_movie',[
					'movie_name'=>$movie_name,
					'on_time'=>$on_time,
					'style'=>$style,
					'duration'=>$duration,
					'area'=>$area,
					'director'=>$director,
					'charactor'=>$charactor,
					'score'=>$score,
					'status'=>$status,
					'img_url'=>$photo,
					'content'=>$content,],"movie_id=$id")
			->execute();
		
			if($result) {
				Helper::show_message('保存成功', Url::toRoute(['index']));
			} else {
				Helper::show_message('保存失败','#');
			}
		
		}
		
		$query = new Query();
		$movie = $query->select(['movie_name','on_time','style','duration','area','director','charactor','score','status','img_url','content'])
		->from('y_movie')
		->where(['movie_id'=>$id])
		->one();
		
		
		return $this->render('edit',['movie'=>$movie]);
	}
	
	
	/**
	 *  刪除電影
	 */
	public function actionDelete()
	{
		//单项删除
		if(isset($_GET['id'])) {
			$id = isset($_GET['id']) ? $_GET['id'] : '' ;
	
			$sql = " DELETE FROM `y_movie` WHERE `movie_id`= $id ";
			$count = Yii::$app->db->createCommand($sql)->execute();
	
			if($count > 0) {
				Helper::show_message('删除成功', Url::toRoute(['index']));
			}else{
				Helper::show_message('删除失败');
			}
		}
		
		//多项删除
		if(isset($_POST['ids'])) {
	
			$ids = implode('\',\'', $_POST['ids']);
	
			$sql = "DELETE FROM `y_movie` WHERE movie_id in ('$ids')";
			$count = Yii::$app->db->createCommand($sql)->execute();
	
			if($count>0){
				Helper::show_message('删除成功', Url::toRoute(['index']));
			}else{
				Helper::show_message('删除失败 ');
			}
		}
	}
	
	
	/**
	 * ajax獲取電影分頁 
	 */
	public function actionGetmoviepage()
	{
		$pag = isset($_GET['pag']) ? $_GET['pag'] == 1 ? 0 : ($_GET['pag'] - 1) * 10 : 0;
	
		$query = new Query();
		$result = $query->select(['*'])
		->from('y_movie')
		->offset($pag)
		->orderby('movie_id desc')
		->limit(10)
		->all();
		if($result) {
			echo json_encode($result);
		} else {
			echo 0;
		}
	}
	
	
}