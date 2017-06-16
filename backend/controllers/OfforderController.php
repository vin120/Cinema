<?php
namespace backend\controllers;

use Yii;
use backend\components\Helper;
use yii\helpers\Url;
use backend\models\MovieOfflineOrder;
use backend\models\User;



class OfforderController extends BaseController
{
	public $enableCsrfValidation = false;
	public $layout = "myloyout";

	
	/** 
	 *  訂單首頁
	 * @return string
	 */
	public function actionIndex()
	{
		
		$count = MovieOfflineOrder::find()->count();
		
		$data = MovieOfflineOrder::find()->orderBy('id desc')->limit(10)->asArray()->all();
		
		
		
		foreach ($data as $key=>$row){
			$data[$key]['admin_name'] = User::find()->where('admin_id = :admin_id',[':admin_id'=>$row['admin_id']])->one()['admin_nickname'];
		}
		
		
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
		
		
		$result = MovieOfflineOrder::find()->orderBy('id desc')->offset($pag)->limit(10)->asArray()->all();

		foreach ($result as $key=>$row){
			$result[$key]['admin_name'] = User::find()->where('admin_id = :admin_id',[':admin_id'=>$row['admin_id']])->one()['admin_nickname'];
		}
		
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
	
		$offline_order = MovieOfflineOrder::find()->where('id = :id',[':id'=>$order_id])->one();
		
		$order = MovieOfflineOrder::findOrderDetail($offline_order);
	
		
		
	
		return $this->render('detail',[
				'order'=>$order,
		]);
	}
	
	
	
	
}