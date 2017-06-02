<?php
namespace backend\views\myasset;

use yii\web\AssetBundle;

class ThemeAssetDate extends AssetBundle
{

	public $sourcePath = '@backend/views/static';
	public $css = [
		//'css/jedate.css'
		'css/zyUpload.css'
	];

	public $js = [
		'js/My97DatePicker/WdatePicker.js'
	];
	
	public $depends = [
		'backend\views\myasset\PublicAsset',
	];
}
