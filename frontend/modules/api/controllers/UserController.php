<?php

namespace frontend\modules\api\controllers;


use Yii;
use frontend\modules\api\controllers\BaseController;
use frontend\components\Helper;
use frontend\models\MovieOnlineOrder;
use frontend\models\User;


class UserController extends BaseController
{
	
	
	/**
	 * 獲取訂單列表
	 * @return number[]|string[]|number[]|string[]|\frontend\models\string[][]|\frontend\models\number[][]|\frontend\models\NULL[][]
	 */
	public function actionOrderlist()
	{
		$uid = Yii::$app->request->post('uid');
		$appsecret = Yii::$app->request->post('appsecret');
		$page =  Yii::$app->request->post('page');
		
		if(empty($uid) || empty($appsecret)){
			$response = ['code' => 2,'msg' => 'uid和appsecret不能爲空'];
			return $response;
			Yii::$app->end();
		}
	
		$user = User::find()->where('user_id = :user_id',[':user_id'=>$uid])->one();
		
		if(is_null($user)){
			$response = ['code' => 3,'msg' => '獲取不到用戶信息'];
			return $response;
			Yii::$app->end();
		}
		
		
		if($appsecret != $user['appsecret']){
			$response = ['code' => 4,'msg' => '沒有權限，請先登錄'];
			return $response;
			Yii::$app->end();
		}
		
		if(empty($page)){
			$response = ['code' => 5,'msg' => 'page不能爲空'];
			return $response;
			Yii::$app->end();
		}
		
		//獲取訂單列表
		$data['order'] = MovieOnlineOrder::getUserOrder($page, $user->user_phone);
		$response = ['code' => 1,'msg' => '獲取成功','data'=>$data];
		return $response;
		
	}
	
	
	/**
	 * 訂單詳情
	 * @return number[]|string[]|number[]|string[]|unknown[]
	 */
	public function actionOrderdetail()
	{
		$uid = Yii::$app->request->post('uid');
		$appsecret = Yii::$app->request->post('appsecret');
		$ssid =  Yii::$app->request->post('ssid');
		
		if(empty($uid) || empty($appsecret)){
			$response = ['code' => 2,'msg' => 'uid和appsecret不能爲空'];
			return $response;
			Yii::$app->end();
		}
		
		$user = User::find()->where('user_id = :user_id',[':user_id'=>$uid])->one();
		
		if(is_null($user)){
			$response = ['code' => 3,'msg' => '獲取不到用戶信息'];
			return $response;
			Yii::$app->end();
		}
		
		
		if($appsecret != $user['appsecret']){
			$response = ['code' => 4,'msg' => '沒有權限，請先登錄'];
			return $response;
			Yii::$app->end();
		}
		
		if(empty($ssid)){
			$response = ['code' => 5,'msg' => 'ssid不能爲空'];
			return $response;
			Yii::$app->end();
		}
		
		$online_order = MovieOnlineOrder::find()->where('order_code = :ssid',[':ssid'=>$ssid])->one();
		
		if(is_null($online_order)){
			$response = ['code' => 6,'msg' => '未找到相關訂單信息'];
			return $response;
			Yii::$app->end();
		}
		
		$data['order'] = MovieOnlineOrder::findOrderDetail($online_order);
		
		$data['order']['hall'] = $data['order']['room_name'];
		$data['order']['ssid'] = $data['order']['order_code'];
		
		unset($data['order']['price']);
		unset($data['order']['service_price']);
		unset($data['order']['phone']);
		unset($data['order']['room_name']);
		unset($data['order']['order_code']);
		
		$response = ['code' => 1,'msg' => '獲取成功','data'=>$data];
		
		return $response;
	}
	
	
}