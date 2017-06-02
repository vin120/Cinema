<?php

namespace  frontend\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

use yii\helpers\ArrayHelper;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;


class BaseController extends ActiveController
{
	public $enableCsrfValidation = false;
	public $modelClass = "";//if not define must be unset defalut action
	public $serializer = [
		'class' => 'yii\rest\Serializer',
		'collectionEnvelope' => 'items',
	];
	
	
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$behaviors['authenticator'] = [
// 			'class' => HttpBasicAuth::className(),
			// 这个地方使用`ComopositeAuth` 混合认证
			'class' => CompositeAuth::className (),
			// `authMethods` 中的每一个元素都应该是 一种 认证方式的类或者一个 配置数组
			'authMethods' => [
					HttpBasicAuth::className (),
					HttpBearerAuth::className (),
					QueryParamAuth::className ()
			]
		];
	
		$behaviors['contentNegotiator'] = [
			'class' => ContentNegotiator::className (),
			'formats' => [
				'application/json' => Response::FORMAT_JSON,
				'application/xml' => Response::FORMAT_XML
			]
		];
		$headers=Yii::$app->response->headers;
		$headers->add("Access-Control-Allow-Origin","*");
		$headers->add("Access-Control-Allow-Headers","Origin, Content-Type, Authorization, Accept,X-Requested-With");
		$headers->add("Access-Control-Allow-Methods","POST, GET, OPTIONS");
			
		return $behaviors;
	}
	
	
	
	public function actions() {
		$actions = parent::actions ();
		//注销系统自带的实现方法
		unset ($actions ['index'], $actions ['update'], $actions ['create'], $actions ['delete'], $actions ['view']);
		return $actions;
	}
	
	
}