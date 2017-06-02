<?php

namespace backend\models;

use Yii;

/**
* This is the model class for table "admin_log".
*
* @property integer $id
* @property string $admin_id
* @property string $admin_name
* @property string $add_time
* @property string $admin_ip
* @property string $admin_agent
* @property string $http_type
* @property string $operation_function
* @property string $operation_name
* @property string $operation_type
* @property string $request_param
* @property string $operation_info
* @property string $result
* @property string $describe
*/


class AdminLog extends \yii\db\ActiveRecord
{
	/**
	* @inheritdoc
	*/
	public static function tableName()
	{
		return 'admin_log';
	}
	
	/**
	* @inheritdoc
	*/
	public function rules()
	{
		return [
		[['admin_id', 'admin_name', 'add_time', 'http_type', 'operation_function', 'operation_name', 'operation_info'], 'required'],
		[['admin_id'], 'integer'],
		[['add_time'], 'safe'],
		[['request_param', 'operation_info', 'result', 'describe'], 'string'],
		[['admin_name', 'admin_ip', 'operation_name'], 'string', 'max' => 200],
		[['admin_agent'], 'string', 'max' => 250],
		[['http_type'], 'string', 'max' => 32],
		[['operation_function', 'operation_type'], 'string', 'max' => 100]
		];
	}
	
	
	/**
	* @inheritdoc
	*/
	public function attributeLabels()
	{
		return [
		'id' => Yii::t('app', 'ID'),
		'admin_id' => Yii::t('app', 'Admin ID'),
		'admin_name' => Yii::t('app', 'Admin Name'),
		'add_time' => Yii::t('app', 'Add Time'),
		'admin_ip' => Yii::t('app', 'Admin Ip'),
		'admin_agent' => Yii::t('app', 'Admin Agent'),
		'http_type' => Yii::t('app', 'Http Type'),
		'operation_function' => Yii::t('app', 'Operation Function'),
		'operation_name' => Yii::t('app', 'Operation Name'),
		'operation_type' => Yii::t('app', 'Operation Type'),
		'request_param' => Yii::t('app', 'Request Param'),
		'operation_info' => Yii::t('app', 'Operation Info'),
		'result' => Yii::t('app', 'Result'),
		'describe' => Yii::t('app', 'Describe'),
		];
	}
	
	//新增加自定义saveLog 函数
	public static function saveLog($operation_name,$operation_type,$operation_info,$result='')
	{
		$model = new self;
		
		$model->admin_id = Yii::$app->user->identity->id;
		$model->admin_name = Yii::$app->user->identity->username;
		$model->add_time = date("Y-m-d H:i:s");
		$model->admin_ip = Yii::$app->request->userIP;
		
		$headers = Yii::$app->request->headers;
		
		if ($headers->has('User-Agent')) {
			$model->admin_agent = $headers->get('User-Agent');
		}
	
		$model->http_type=Yii::$app->request->getMethod();
		$model->operation_function = Yii::$app->controller->module->id.'/'.Yii::$app->controller->id.'/'.Yii::$app->controller->action->id;
		$model->operation_name = $operation_name;
		$model->operation_type = $operation_type;
		
		$params = Yii::$app->request->getBodyParams();
		if(!empty($params)) {
			$model->request_param = serialize(Yii::$app->request->getBodyParams());
		}
		
		$model->operation_info = $operation_info;
		$model->result = $result;
		
		$model->save(false);
	
	}
}


