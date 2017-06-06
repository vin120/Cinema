<?php
namespace frontend\views\myasset;

use yii\web\AssetBundle;

class PayWayAsset extends AssetBundle
{

    public $sourcePath = '@frontend/views/static';
    public $css = [
    	
    	'css/iCkeck/custom.css',
    	'css/iCkeck/skins/square/red.css',
    	'css/pay-way.css',
    ];

    public $js = [
		'js/icheck.min.js',
    	'js/jquery.countdown.min.js',
    ];
}
