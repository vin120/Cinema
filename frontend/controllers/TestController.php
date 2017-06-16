<?php
namespace frontend\controllers;

use Yii;
use yii\base\Controller;
use frontend\components\Helper;


class TestController extends Controller
{
	public $layout = false;
	public function actionIndex()
	{
		$ssid = Yii::$app->request->get('ssid');
		return $this->render('index',['ssid'=>$ssid]);
	}
	
	public function actionQrcode()
	{
		$ssid = Yii::$app->request->get('ssid');	
		return Helper::qrcode($ssid);
	}
	
	
	public function actionTest()
	{
		$c_code = Helper::get_rand_number('100000','999999',1);
		
		echo $c_code[0];
// 		echo Helper::get_order_sn();
	}
	
}