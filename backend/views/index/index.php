<?php
	$this->title = 'Basic Message';
	use backend\views\myasset\PublicAsset;
	
	$this->title = '信息一覽';
	$this->params['breadcrumbs'][] = $this->title;
	
	PublicAsset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>

<!-- content start -->
<div class="r content" id="user_content">
    <div class="topNav">後臺管理系統&nbsp;&gt;&gt;&nbsp;<a href="/index/index">登錄信息</a></div>
    <div id="mainContent_content" class="pBox">
    	<div id="loginInfo" >
        	<h2>登錄信息</h2>
	        <div class="pBox" id="info">
	         歡迎您，<font color="red"><?php echo  $admin_nickname;?></font>
	         
		        <ul>
		        	<li><span><h2>總體情況:</h2></span></li>
		        	<li>
	                    <span>已賣出:</span>
	                    <span><?php echo 100;?>張</span>
	                </li>
	                
	                <li>
	                    <span>未賣出:</span>
	                    <span><?php echo 100;?>張</span>
	                </li>
	                
	                <li>
	                    <span>今日銷售:</span>
	                    <span><?php echo 100;?> </span>
	                </li>
	                
	                <li>
	                    <span>總銷售:</span>
	                    <span><?php echo 1000;?> </span>
	                </li>
	            </ul>
	        </div>
        </div>
	</div>
</div>
<!-- content end -->
