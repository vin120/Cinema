<?php


namespace frontend\modules\api;

use Yii;
use yii\base\Theme;

class api extends \yii\base\Module
{
	
	public $controllerNamespace = 'frontend\modules\api\controllers';

	public $layout="@frontend/modules/api/themes/basic/layouts/main.php";
	
	public function init()
	{
		parent::init();
		\Yii::$app->view->theme = new Theme([
			'basePath' => '@frontend/modules/api/themes/basic',
			'pathMap' => ['@frontend/modules/api/views'=>'@frontend/modules/api/themes/basic'],
			'baseUrl' => '@frontend/modules/api/themes/basic',
		]);
		

		\Yii::$app->errorHandler->errorAction = 'api/error/index';
		\Yii::$app->user->enableSession = false;
		
	}
}