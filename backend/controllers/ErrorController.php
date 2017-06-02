<?php
namespace backend\controllers;

use Yii;
use backend\controllers\BaseController;

class ErrorController extends BaseController
{
	public function actionIndex()
	{
		$this->layout = "myloyout";
		return $this->render('index');
	}
}