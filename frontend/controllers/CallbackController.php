<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use frontend\models\MovieOnlineOrder;



class CallbackController extends Controller
{
	public $enableCsrfValidation = false;
	
	public function actionWebhooks()
	{
		$event = json_decode(file_get_contents("php://input"));
		
		// 对异步通知做处理
		if (!isset($event->type)) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
			exit("fail");
		}
		switch ($event->type) {
			case "charge.succeeded":
				// 开发者在此处加入对支付异步通知的处理代码
				$text = json_decode(file_get_contents("php://input"),true);
				$id = $text['data']['object']['id'];
				MovieOnlineOrder::updateAll(['status'=>1],'charge_id = :charge_id',[':charge_id'=>$id]);
			
				header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
				break;
			case "refund.succeeded":
				// 开发者在此处加入对退款异步通知的处理代码
				header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
				break;
			default:
				header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
				break;
		}
	}
		
}