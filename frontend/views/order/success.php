<?php
	$this->title = '憶條街電影購票';
	use frontend\views\myasset\PublicAsset;
	use yii\helpers\Url;
	PublicAsset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>


支付成功
<a href="<?php echo Url::to('/order/orderlist')?>">查看訂單</a>
