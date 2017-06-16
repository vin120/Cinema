<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'DnGDCrCv3YrGIOZS7b_TLgfqOugmT3gs',
        ],
    	'db'=>array(
    		'class' => 'yii\db\Connection',
   			'dsn' => 'mysql:host=localhost:3306;dbname=zh_trip',
//         	'dsn' => 'mysql:host=bisheng.8800.org:18671;dbname=cruise_one_system',//bisheng.8800.org:18671
    		'username' => 'root',//phpdb
    		'password' => '123456',//pdb2468
    		'charset' => 'utf8',
    	),
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
