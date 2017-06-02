<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    	//这个是支付的
    	'payment' => [
    		'class'=>'common\widgets\payment\Instance',
    		
    		'weixinjspi_config' => [
    			'code'      => 2,
    			'appid'     => 'wx88ee3ca8c06be5c6',//微信的appid
    			'secret'    => '48180f87c2693d50b29d822d019999',//appsecret，在申请完公众号以后可以看到
    			'mch_id'    => '13260614455',//商户号
    			'key'       => '16ceshi',//key需要设置
    			'cert_path' => '',//可以不用	填写
    			'key_path'  => '',//可以不用填写
    		],
    	],
    ],
	
];
