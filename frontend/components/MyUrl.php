<?php

namespace frontend\components;

use Yii;


class MyUrl 
{
	
	/**
	 * 记录在登录前的url 
	 */
	public static function SetUrlCookie($url)
	{
		$cookies = Yii::$app->response->cookies;
		$cookies->add(new \yii\web\Cookie([
			'name' => 'seat_url',
			'value' => $url,
			'expire' =>time() + 300,
		]));
		
		
	}
	
	
	/**
	 * 跳转的路径
	 */
	public static function RefferUrl()
	{
		$cookies = Yii::$app->request->cookies;
		
		if (isset($cookies['seat_url']) && !empty($cookies['seat_url'])){
			$seat_url = $cookies['seat_url']->value;
			Yii::$app->getResponse()->redirect($seat_url);
		}else{
			Yii::$app->getResponse()->redirect(['/index/index']);
		}
	}
	
	
	
}