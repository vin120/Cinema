<?php
namespace backend\controllers;

use Yii;
use backend\components\Helper;
use yii\helpers\Url;


class AdminController extends BaseController
{
	public $enableCsrfValidation = false;
	public $layout = "myloyout";
	
	
	/**
	 * 管理員
	 * @return string
	 */
	public function actionIndex()
	{
		$admin_id =Yii::$app->user->identity->admin_id;
		$db = Yii::$app->db;
		if($_POST){
			
			$admin_user = isset($_POST['admin_user'])?trim($_POST['admin_user']):'';
			$admin_nickname = isset($_POST['admin_nickname'])?trim($_POST['admin_nickname']):'';
			$password = isset($_POST['password'])?trim($_POST['password']):'';
			$query_password = isset($_POST['query_password'])?trim($_POST['query_password']):'';
		
			if($password!='******'){
				$password = md5($password);
				$sql = "UPDATE `y_admin` SET admin_user='{$admin_user}',admin_nickname='{$admin_nickname}',admin_pwd='{$password}'  WHERE admin_id='{$admin_id}' ";
		
			}else{
				$sql = "UPDATE `y_admin` SET admin_user='{$admin_user}',admin_nickname='{$admin_nickname}' WHERE admin_id='{$admin_id}' ";
			}

			$commen = $db->beginTransaction();
			try{
				$db->createCommand($sql)->execute();
				$commen->commit();
				Helper::show_message('保存成功', Url::toRoute(['index']));
			}catch(Exception $e){
				$commen->rollBack();
				Helper::show_message('保存失敗','#');
			}
		}
		
		
		$sql = "SELECT * FROM `y_admin` WHERE admin_id='{$admin_id}' ";
		$data = Yii::$app->db->createCommand($sql)->queryOne();
		return $this->render('index',['data'=>$data]);
	}
}