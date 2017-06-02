<?php
namespace backend\views\myasset;

use yii\web\AssetBundle;

class ThemeAssetUpload extends AssetBundle
{

	public $sourcePath = '@backend/views/static';
	public $css = [
		//'css/jedate.css'
	];

	public $js = [
		'js/uploadPreview.js'
	];
	
	public $depends = [
		'backend\views\myasset\PublicAsset',
	];
}