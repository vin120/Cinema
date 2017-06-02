<?php
namespace frontend\controllers;


use yii\web\Controller;
use frontend\components\Helper;
use Yii;
use frontend\models\Cinema;



class ErrorController extends Controller
{
	public $layout = "mylayout";
	
	public function actionIndex()
	{
		return $this->render('index');
	}
}