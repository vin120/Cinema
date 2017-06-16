<?php
	$this->title = '憶條街電影購票';
	use frontend\views\myasset\PublicAsset;
	use frontend\views\myasset\PayResultAsset;
	use yii\helpers\Url;
	PublicAsset::register($this);
	PayResultAsset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>

<header class="navbar navbar-default navbar-fixed-top">
<div class="container">
	<div class="row">
		<div class="col-xs-2">
			<div class="nav-wrap-left">
				<a href="<?php echo Url::to('/index/index');?>">
					<i class="fa fa-home fa-lg"></i>
				</a>
			</div>
		</div>
		<div class="col-xs-8">
			<h4 class="title-text text-center">憶條街電影購票</h4>
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
		<div class="col-xs-12 text-center pay-result">
		<!-- 支付成功 -->
		<h1><i class="fa fa-check"></i></h1>
		<h2>支付成功</h2>
		<span>請到訂單查看購票信息</span>
		<button id="order" class="btn btn-default center-block">查看訂單</button>
		</div>
	</div>
</div>

<script type="text/javascript">
<?php $this->beginBlock('js_end') ?>

	$("#order").click(function(){
		window.location.href = "<?php echo Url::to('/order/orderlist')?>"
	});

<?php $this->endBlock() ?>
</script>	
<?php $this->registerJs($this->blocks['js_end'], \yii\web\View::POS_END); ?>
