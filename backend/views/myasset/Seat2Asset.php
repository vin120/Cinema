<?php
namespace backend\views\myasset;

use yii\web\AssetBundle;

class Seat2Asset extends AssetBundle
{

    public $sourcePath = '@backend/views/static';
    public $css = [
    	'css/bootstrap.css',
    	'css/jquery.seat-charts.css',
    	'css/override.css',
    	'css/common.css',
    	'css/seat2.css',
    ];

    public $js = [
    	'js/bootstrap.min.js',
    	'js/jquery.seat-charts.min.js',
    ];

 

}
