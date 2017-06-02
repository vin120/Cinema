<?php
namespace backend\views\myasset;

use yii\web\AssetBundle;

class ThemeAssetMoreUpload extends AssetBundle
{

	public $sourcePath = '@backend/views/static';
	public $css = [
		//'css/jedate.css'
		'css/zyUpload.css'
	];

	public $js = [
		'js/uP_demo.js',
		'js/zyFile.js',
		'js/zyUpload.js'
	];
	
	public $depends = [
		'backend\views\myasset\PublicAsset',
	];
}