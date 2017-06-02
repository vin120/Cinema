<?php
namespace backend\controllers;

use Yii;
use backend\components\Helper;
use yii\helpers\Url;
use yii\db\Query;


class MovietypeController extends BaseController
{
	public $enableCsrfValidation = false;
	public $layout = "myloyout";
	

	
	public function actionIndex() 
	{
		$sql = "SELECT count(*) count FROM `y_movie_type` ";
		$count = Yii::$app->db->createCommand($sql)->queryOne();
		
		$sql = "SELECT * FROM `y_movie_type` ORDER BY type_id DESC LIMIT 10";
		$data = Yii::$app->db->createCommand($sql)->queryAll();
		
		
		return $this->render('index',[
				'data'=>$data,
				'count'=>$count['count'],
				'pag'=>1,
		]);
	}
	


	/**
	 * 添加
	 * @return string
	 */
	public function actionAdd()
	{
		if($_POST){
			$name = isset($_POST['name']) ? $_POST['name'] : '';
			$status = isset($_POST['status']) ? $_POST['status'] : 1;
	
			$result = Yii::$app->db->createCommand()
			->insert('y_movie_type',['type_name'=>$name,'status'=>$status,])
			->execute();
	
			if($result){
				Helper::show_message('保存成功', Url::toRoute(['index']));
			} else {
				Helper::show_message('保存失败','#');
			}
		}
	
		return $this->render('add');
	}
	
	
	
	/**
	 * 編輯
	 * @return string
	 */
	public function actionEdit()
	{
		$id = $_GET['id'];
	
		if($_POST){
			$name = isset($_POST['name']) ? $_POST['name'] : '';
			$status = isset($_POST['status']) ? $_POST['status'] : 1;
	
			$result = Yii::$app->db->createCommand()
			->update('y_movie_type',['type_name'=>$name,'status'=>$status],"type_id=$id")
			->execute();
	
			if($result){
				Helper::show_message('保存成功', Url::toRoute(['index']));
			} else {
				Helper::show_message('保存失败','#');
			}
		}
	
		$query = new Query();
		$type = $query->select(['*'])
		->from('y_movie_type')
		->where(['type_id'=>$id])
		->one();
	
		return $this->render('edit',['type' => $type]);
	}
	
	
	
	/**
	 *  刪除
	 */
	public function actionDelete()
	{
		//单项删除
		if(isset($_GET['id'])) {
			$id = isset($_GET['id']) ? $_GET['id'] : '' ;
	
			$sql = " DELETE FROM `y_movie_type` WHERE `type_id`= $id ";
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
	
			$sql = "DELETE FROM `y_movie_type` WHERE type_id in ('$ids')";
			$count = Yii::$app->db->createCommand($sql)->execute();
	
			if($count>0){
				Helper::show_message('删除成功', Url::toRoute(['index']));
			}else{
				Helper::show_message('删除失败 ');
			}
		}
	}
	
	
	/**
	 * ajax获取分页
	 */
	public function actionGetmovietypepage()
	{
		$pag = isset($_GET['pag']) ? $_GET['pag'] == 1 ? 0 : ($_GET['pag'] - 1) * 10 : 0;
	
		$query = new Query();
		$result = $query->select(['*'])
		->from('y_movie_type')
		->offset($pag)
		->orderby('type_id desc')
		->limit(10)
		->all();
		if($result) {
			echo json_encode($result);
		} else {
			echo 0;
		}
	}
	
	
	
	
	
}