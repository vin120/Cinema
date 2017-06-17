<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use frontend\models\MovieOnlineOrder;
use frontend\models\MovieSeat;



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
				
				$movieshow = MovieOnlineOrder::find()->where('charge_id = :charge_id',[':charge_id'=>$id])->one();
				$seats_arr = explode(',',$movieshow['seat_ids']);
				
				foreach ($seats_arr as $row){
					MovieSeat::updateAll(['status'=>2],
							'show_id = :show_id and seat_id = :seat_id',
							[':show_id'=>$movieshow['movie_show_id'],':seat_id'=>$row]);
				}
				
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