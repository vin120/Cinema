<?php
namespace frontend\views\myasset;

use yii\web\AssetBundle;

class CinemaAsset extends AssetBundle
{

    public $sourcePath = '@frontend/views/static';
    public $css = [
    	'css/swiper.min.css',
    	'css/cinema.css',	
    ];

    public $js = [
		'js/swiper.min.js',
//     	'js/cinema.js',
    ];
}
