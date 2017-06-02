<?php
	$this->title = 'Basic Message';
	use backend\views\myasset\PublicAsset;
	
	PublicAsset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>

<!-- content start -->
<div class="r content" id="user_content">
    <div class="topNav">后台管理系统&nbsp;&gt;&gt;&nbsp;<a href="/site/index">登录信息</a></div>
    <div id="mainContent_content" class="pBox">
    	<div id="loginInfo" >
        	<h2>无权限访问</h2>
        	<div class="pBox" id="info">
        		<p>对不起,你没有权限访问此网页，请联系管理员。</p>
	        </div>
        </div>
	</div>
</div>
<!-- content end -->
