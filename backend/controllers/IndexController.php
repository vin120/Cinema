<?php
namespace backend\controllers;

use Yii;
use backend\components\Helper;
use yii\helpers\Url;

class IndexController extends BaseController
{
	public $enableCsrfValidation = false;
	public $layout = "myloyout";
	
	
	public function actionIndex()
	{
		return $this->render('index',[
        		'admin_user '=> Yii::$app->user->identity->admin_user,
        		'admin_nickname' => Yii::$app->user->identity->admin_nickname,
        ]);
	}
}