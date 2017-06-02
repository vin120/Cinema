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
        	<h2>登录信息</h2>
	        <div class="pBox" id="info">
	         欢迎您，<font color="red"><?php echo  $admin_real_name;?></font>
		        <ul>
		           <li>
		           		<span></span>
		           </li>
	            </ul>
	        </div>
        </div>
	</div>
</div>
<!-- content end -->
