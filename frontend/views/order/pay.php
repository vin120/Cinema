<?php
	$this->title = '憶條街電影購票';
	use frontend\views\myasset\PublicAsset;
	use frontend\views\myasset\PayAsset;
	use yii\helpers\Url;
	PublicAsset::register($this);
	PayAsset::register($this);
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
				<h4 class="title-text text-center">訂單</h4>
			</div>
			<div class="col-xs-2">
				<div class="nav-wrap-right">
					<a href="<?php echo Url::toRoute('user/index');?>">
						<i class="fa fa-user-circle fa-lg"></i>
					</a>
				</div>
			</div>
		</div>
	</div>
</header>

<div class="main container">
	<div class="row">
		<div class="col-xs-12 pay-time">
			<p>支付剩餘時間：<span id="time"></span></p>
		</div>

		<div class="col-xs-12 pay-info">
			<div class="info-wrap">
				<h3 class="movie-name"><?php echo $data['movie_name']?></h3>
				<p class="movie-time"><?php echo $data['date']." (".$data['movie_type'].")"?></p>
				<p><?php echo $data['cinema_name']." ".$data['room_name']?></p>
				<p><?php echo $data['seats']?></p>
			</div>
			<div class="bottom-line"></div>
			<div class="price-wrap">
				<span>票價</span>
				<span style="float: right;">含服務費<?php echo $data['service_price']?>MOP／張 <b><?php echo $data['price']?> MOP</b></span>
			</div>
		</div>

		<div class="col-xs-12 pay-phone">
			<p class="phone">手機號：<span id="phone"><?php echo $data['phone']?></span></p>
			<div class="bottom-line"></div>
			<p>手機號僅用於生成訂單，取票碼不再以短信發送</p>
		</div>

	</div>

	<div class="pay-btn">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<p>還需支付：<b><?php echo $data['total_money']?> MOP</b></p>
				</div>

				<div class="col-xs-12">
					<button class="btn center-block">確定付款</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
<?php $this->beginBlock('js_end') ?>

var now = "<?php echo $data['order_time']?>";

$('#time').countdown(now, function(event) {
	$(this).html(event.strftime('%M:%S'));
});


$("button").click(function(){
	window.location.href = "<?php echo Url::to(['/order/payment']);?>?ssid=<?php echo $data['order_code']?>";
})


<?php $this->endBlock() ?>
</script>	
<?php $this->registerJs($this->blocks['js_end'], \yii\web\View::POS_END); ?>

