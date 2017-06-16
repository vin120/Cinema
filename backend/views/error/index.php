<?php
	$this->title = '错误';
	use backend\views\myasset\PublicAsset;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	
	
	PublicAsset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>


<!-- content start -->
<div class="r content">
	<div class="topNav">错误信息&nbsp;&gt;&gt;&nbsp;<a href="#">错误</a></div>
	<div id="mainContent_content" class="pBox">
    	<div id="loginInfo" >
        	<h2>禁止访问</h2>
	        <div class="pBox" id="info">
	         <p>服务器拒绝了你的请求,</p>
	         <p>请确认你拥有所需的访问权限</p>
	        </div>
        </div>
	</div>
</div>
<!-- content end -->

