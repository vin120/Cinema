<?php
namespace frontend\controllers;

use Yii;
use yii\base\Controller;
use frontend\components\Helper;


class QrcodeController extends Controller
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
	
}