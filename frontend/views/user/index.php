<?php
	$this->title = '憶條街電影購票';
	use frontend\views\myasset\PublicAsset;
	use frontend\views\myasset\UserAsset;
	use yii\helpers\Url;
	PublicAsset::register($this);
	UserAsset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>

<header class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="row">
			<div class="col-xs-2">
				<div class="nav-wrap-left">
					<a href="javascript:history.back()"><i class="fa fa-angle-left fa-2x"></i></a>
				</div>
			</div>
			<div class="col-xs-8">
				<h4 class="title-text text-center">我的</h4>
			</div>
			<div class="col-xs-2">
				<div class="nav-wrap-right">
					<a href="<?php echo Url::toRoute('index/index')?>">
						<i class="fa fa-home fa-lg"></i>
					</a>
				</div>
			</div>
		</div>
	</div>
</header>

<div class="main container">
	<div class="row user-wrap">
		<div class='frosted-glass'></div>
		<div class="user-info">
			<img src="<?php echo $baseUrl?>images/yitiaojie.png"/>
			<h4>憶條街</h4>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 user-order">
			<a class="a_none" href="<?php echo Url::toRoute('order/orderlist')?>">
				<img src="<?php echo $baseUrl?>images/yitiaojie.png"/>
				<p>訂單</p>
				<div class="user-more">
					<i class="fa fa-angle-right fa-lg"></i>
				</div>
			</a>
		</div>
		<div class="col-xs-12">
		    <a id="out_login" class="btn btn-default center-block" style="width:100%;margin-top:50px;" href="<?php echo Url::to('/user/logout')?>">退出登陸</a >
		</div>
	</div>
</div>