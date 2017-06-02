<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
	'timezone' => 'Asia/ShangHai',
    'controllerNamespace' => 'frontend\controllers',
	'modules' => [
		'api' => [
			'class' => 'frontend\modules\api\api',
		],
	],
    'components' => [
		'assetManager' => [
			'linkAssets' => true,
		],
        'request' => [
            'csrfParam' => '_csrf',
        ],
        'user' => [
            'identityClass' => 'frontend\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        	'idParam' => '__user',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
//             'errorAction' => 'error/index',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    	'mailer' => [
    		'class' => 'yii\swiftmailer\Mailer',
    		'viewPath'=>'@common/mail',
    		// send all mails to a file by default. You have to set
    		// 'useFileTransport' to false and configure a transport
    		// for the mailer to send real emails.
    		// 'useFileTransport' => false,
    		'transport'=>[
    			'class'=>'Swift_SmtpTransport',
    			'host'=>'smtp.163.com',
    			'username'=>'admin@163.com',
    			'password'=>'',
    			'port'=>'25',
    			'ecryption'=>'ssl',
    		],
    		'messageConfig'=>[
    			'charset'=>'UTF-8',
    			'from'=>['admin@163.com'=>'admin'],
    		],
    	],
    	
    		
    		
    ],
    'params' => $params,
];
