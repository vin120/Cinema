<?php
namespace frontend\views\myasset;

use yii\web\AssetBundle;

class OrderlistAsset extends AssetBundle
{
	public $sourcePath = '@frontend/views/static';
	public $css = [
		'css/order.css',
		'css/dropload.css',
	];
	
	public $js = [
		'js/dropload.min.js',	
	];
	
	
	
}