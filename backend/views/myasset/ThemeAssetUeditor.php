<?php
namespace backend\views\myasset;

use yii\web\AssetBundle;

class ThemeAssetUeditor extends AssetBundle
{

	public $sourcePath = '@backend/views/static';
	public $css = [
		
	];

	public $js = [
		'js/ueditor/ueditor.config.js',
		'js/ueditor/ueditor.all.js',
	];
	
	public $depends = [
		'backend\views\myasset\PublicAsset',
	];
}