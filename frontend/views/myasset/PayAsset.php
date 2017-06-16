<?php
namespace frontend\views\myasset;

use yii\web\AssetBundle;

class PayAsset extends AssetBundle
{
	public $sourcePath = '@frontend/views/static';
	public $css = [
		'css/pay.css',
	];
	
	public $js = [
		'js/jquery.countdown.min.js',
	];
	
	
	
}