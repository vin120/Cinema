<?php
namespace frontend\controllers;


use yii\web\Controller;
use frontend\components\Helper;
use Yii;
use frontend\models\Cinema;



class IndexController extends Controller
{
	public $layout = "mylayout";
	
	public function actionIndex()
	{
		$cinema = Cinema::find()->where('status = 1')->orderBy('cinema_id desc')->all();
		
	
		return $this->render('index',['cinema'=>$cinema]);
	}
}