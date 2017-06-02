<?php
namespace frontend\views\myasset;

use yii\web\AssetBundle;

class PublicAsset extends AssetBundle
{

    public $sourcePath = '@frontend/views/static';
    public $css = [
    	'css/bootstrap.css',
    	'css/font-awesome.min.css',
    	'css/animate.css',
    	'css/override.css',
    	'css/common.css',
        'css/style.css',
    ];

    public $js = [
		'js/jquery.min.js',
    	'js/bootstrap.min.js',	
    ];

    //依赖关系
    public $depends = [
    ];

}
