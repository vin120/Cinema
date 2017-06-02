<?php
namespace backend\controllers;

use Yii;
use backend\components\Helper;
use yii\helpers\Url;
use backend\models\MovieOnlineOrder;

class OrderController extends BaseController 
{
	public $enableCsrfValidation = false;
	public $layout = "myloyout";
	
	/** 
	 *  訂單首頁
	 * @return string
	 */
	public function actionIndex()
	{
		
		
		//更改「已過期」 並且 「未支付」的訂單 「狀態」 	
		MovieOnlineOrder::updateAll(['status'=>2],'status = 0 and order_time < :time',[':time'=>date("Y-m-d H:i:s",strtotime('-15 minutes'))]);
		
		$count = MovieOnlineOrder::find()->count();
		
		$data = MovieOnlineOrder::find()->orderBy('id desc')->limit(10)->all();
		
		return $this->render('index',[
			'data'=>$data,
			'count'=>$count,
			'pag'=>1,
		]);
	}
	
	
	/**
	 * ajax獲取訂單分頁 
	 */
	public function actionGetorderpage()
	{
		$pag = isset($_GET['pag']) ? $_GET['pag'] == 1 ? 0 : ($_GET['pag'] - 1) * 10 : 0;
		
		
		$result = MovieOnlineOrder::find()->orderBy('id desc')->offset($pag)->limit(10)->asArray()->all();

		if($result) {
			echo json_encode($result);
		} else {
			echo 0;
		}
	}
	
	
	/**
	 *  訂單詳情
	 * @return string
	 */
	public function actionDetail() 
	{
		$order_id = isset($_GET['id'])?trim($_GET['id']):'';
		
		$online_order = MovieOnlineOrder::find()->where('id = :id',[':id'=>$order_id])->one();
		
		$order = MovieOnlineOrder::findOrderDetail($online_order);
		
		
		return $this->render('detail',[
			'order'=>$order,	
		]);
	}
	
}