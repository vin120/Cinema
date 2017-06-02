<?php
	$this->title = '憶條街電影購票';
	use frontend\views\myasset\PublicAsset;
	use frontend\views\myasset\OrderdetailAsset;
	use yii\helpers\Url;
	PublicAsset::register($this);
	OrderdetailAsset::register($this);
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
				<h4 class="title-text text-center">訂單詳情</h4>
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
	<div class="row">
		<div class="col-xs-12 movie-info">
			<div class="movie-wrap">
				<div class="movie-name">
					<h3><?php echo $data['movie_name']?></h3>
					<span><?php 
						if($data['status'] == 0) echo "待付款";
						else if($data['status'] == 1) echo "已完成";
						else if($data['status'] == 2) echo "已過期";
					?></span>
				</div>
				<p class="movie-time"><?php echo $data['date']." (".$data['movie_type'].")"?></p>
				<p><?php echo $data['cinema_name']." ".$data['room_name']?></p>
				<p><?php echo $data['seats']?></p>
			</div>
			<div class="bottom-line"></div>
			<div class="qr-wrap">
				<p>驗證碼：<span><?php echo $data['order_code'];?></span></p>
				<div class="qr-img">
					<img class="center-block" src="<?php echo Url::to(['/order/qr','ssid'=>$data['order_code']])?>"/>
				</div>
			</div>
			<div class="bottom-line"></div>
			<div class="help-wrap">
				<p class="help-tips">請到影院內自助取票機或影院前台取票</p>
				<div class="help-text">
					<h4>取票機位置:</h4>
					<p>影院內自助取票區域或影院前台</p>
					<h4>如何取票:</h4>
					<p>憑訂單內的取票憑證取票</p>
				</div>
				<div class="help-img">
					<img src="<?php echo $baseUrl?>images/help.png"/>
				</div>
			</div>
		</div>
		<div class="col-xs-12 movie-price">
			<div class="price-wrap">
				<h3>訂單號：<span><?php echo $data['order_number'];?></span></h3>
				<h3>總&nbsp;&nbsp;&nbsp;&nbsp;價：<span><?php echo $data['total_money'];?></span> MOP</h3>
			</div>
		</div>

		<div class="col-xs-12 cinema-info">
			<div class="info-wrap">
				<h3><?php echo $data['cinema_name'];?></h3>
				<p><?php echo $data['address'];?></p>
			</div>
		</div>

		<div class="col-xs-12 ciname-contact">
			<div class="contact-wrap">
				<h3>客服電話</h3>
				<span><?php echo $data['cinema_phone'];?></span>
				<p>工作時間：<?php echo $data['cinema_work_time'];?></p>
			</div>
		</div>
	</div>
</div>