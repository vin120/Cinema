<?php
namespace backend\controllers;

use Yii;
use backend\components\Helper;
use yii\helpers\Url;
use backend\models\User;


class AdminController extends BaseController
{
	public $enableCsrfValidation = false;
	public $layout = "myloyout";

	
	public function actionIndex()
	{
		$admin = User::find()->where('admin_grade = 0')->all();
		$count = User::find()->where('admin_grade = 0')->count();
		
		return $this->render('index',['admin'=>$admin,'count'=>$count]);
	}
	
	
	
	/**
	 * 添加管理員
	 * @return string
	 */
	public function actionAdd()
	{
		
		if(Yii::$app->request->isPost){
			$admin_user = Yii::$app->request->post('admin_user');
			$admin_nickname = Yii::$app->request->post('admin_nickname');
			$admin_pwd = Yii::$app->request->post('admin_pwd');
			$status = Yii::$app->request->post('status');
			
			$admin = new User;
			$admin->admin_user = $admin_user;
			$admin->admin_nickname = $admin_nickname;
			$admin->admin_pwd = md5($admin_pwd);
			$admin->status = $status;
			
			
			if($admin->save()){
				Helper::show_message('保存成功', Url::toRoute(['add']));
			}else {
				Helper::show_message('保存失敗', Url::toRoute(['index']));
			}
		}
		
		return $this->render('add');
	}
	

	
	/**
	 *  ajax 判斷用戶是否存在 
	 */
	public function actionVerifyinfo()
	{
		$admin_user = Yii::$app->request->post('admin_user');
		
		$count = User::find()->where('admin_user = :admin_user',[':admin_user'=>$admin_user])->count();
		
		$result['name'] = $count;
		
		echo json_encode($result);
	}
	
	
	/**
	 * 編輯管理員
	 * @return string
	 */
	public function actionEdit()
	{
		$id = Yii::$app->request->get('id');
		
		if(empty($id)){
			$this->redirect(Url::to('index'));
		}
		$admin = User::find()->where('admin_id=:admin_id',[':admin_id'=>$id])->one();
		
		if(Yii::$app->request->isPost){
			$status = Yii::$app->request->post('status');
			$admin->status = $status;
			
			if($admin->save()){
				Helper::show_message('保存成功', Url::toRoute(['index']));
			}else {
				Helper::show_message('保存失敗', Url::toRoute(['index']));
			}
		}
		
		return $this->render('edit',['admin'=>$admin]);
	}
	
	
	
	

	/**
	 * 管理員修改密碼
	 * @return string
	 */
	// 	public function actionChangepassword()
	// 	{
	// 		$admin_id =Yii::$app->user->identity->admin_id;
	// 		$db = Yii::$app->db;
	// 		if($_POST){
		
	// 			$admin_user = isset($_POST['admin_user'])?trim($_POST['admin_user']):'';
	// 			$admin_nickname = isset($_POST['admin_nickname'])?trim($_POST['admin_nickname']):'';
	// 			$password = isset($_POST['password'])?trim($_POST['password']):'';
	// 			$query_password = isset($_POST['query_password'])?trim($_POST['query_password']):'';
	
	// 			if($password!='******'){
	// 				$password = md5($password);
	// 				$sql = "UPDATE `y_movie_admin` SET admin_user='{$admin_user}',admin_nickname='{$admin_nickname}',admin_pwd='{$password}'  WHERE admin_id='{$admin_id}' ";
	
	// 			}else{
	// 				$sql = "UPDATE `y_movie_admin` SET admin_user='{$admin_user}',admin_nickname='{$admin_nickname}' WHERE admin_id='{$admin_id}' ";
	// 			}
	
	// 			$commen = $db->beginTransaction();
	// 			try{
	// 				$db->createCommand($sql)->execute();
	// 				$commen->commit();
	// 				Helper::show_message('保存成功', Url::toRoute(['index']));
	// 			}catch(Exception $e){
	// 				$commen->rollBack();
	// 				Helper::show_message('保存失敗','#');
	// 			}
	// 		}
	
	
	// 		$sql = "SELECT * FROM `y_movie_admin` WHERE admin_id='{$admin_id}' ";
	// 		$data = Yii::$app->db->createCommand($sql)->queryOne();
	// 		return $this->render('index',['data'=>$data]);
	// 	}
	
	
	
}