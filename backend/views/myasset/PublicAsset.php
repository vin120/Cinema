<?php
namespace backend\views\myasset;

use yii\web\AssetBundle;

class PublicAsset extends AssetBundle
{

    public $sourcePath = '@backend/views/static';
    public $css = [
        'css/public.css',
    	'css/page.css',
    	'css/base.css',
    ];

    public $js = [
        'js/jquery-2.2.3.min.js',
        'js/My97DatePicker/WdatePicker.js',
        'js/public.js',
    	'js/jqPaginator.js',
    	'js/template.js',
    	'js/js_session.js',
        'js/verifyjs.js',
    ];

    //依赖关系
    public $depends = [
    ];

}
