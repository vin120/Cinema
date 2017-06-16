<?php
namespace frontend\views\myasset;

use yii\web\AssetBundle;

class Seat1Asset extends AssetBundle
{

    public $sourcePath = '@frontend/views/static';
    public $css = [
    	'css/jquery.seat-charts.css',
    	'css/seat.css',
    ];

    public $js = [
		'js/jquery.seat-charts.min.js',
//     	'js/seat.js',	
    ];
}
