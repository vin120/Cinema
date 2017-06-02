<?php
	$this->title = '訂單詳情';
	use backend\views\myasset\PublicAsset;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	
	PublicAsset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>

<style>
	div.div-box-float{
		overflow: hidden;
	}
	div.div-box-float .div-box-left-float{
		display: inline-block;
		width: 45%;
		float: left;
	}
	div.div-box-float .div-box-right-float{
		display: inline-block;
		width: 45%;
		float: left;
	}
	div.div-box-float > div label{
		display: block;
		line-height: 35px;
	}
	div.div-box-float > div label span{
		width: 45%;
		display: inline-block;
		text-align: left;
	}

</style>


<!-- content start -->
<div class="r content">
	<div class="topNav">訂單管理&nbsp;&gt;&gt;&nbsp;
	<a href="<?php echo Url::toRoute(['index']);?>">訂單信息</a>&nbsp;&gt;&gt;&nbsp;
	<a href="#">訂單詳情</a></div>
	
	<div class="searchResult">

		<div class="div-box-float">
			<div class="div-box-left-float">
				<label>訂單號： <?php echo $order['order_number']?></label>
			</div>
			<div class="div-box-right-float">
				<label>售票時間： <?php echo $order['pay_time']?></label>
			</div>
		</div>
	
		<hr style="border:1px dashed #666;"><br>
		<div class="div-box-float">
			<div class="div-box-left-float">
				<label>下單時間：<?php echo $order['order_time']?></label>
				<label>驗證碼：<?php echo $order['order_code']?></label>
				<label>影院：<?php echo $order['cinema_name']?></label>
				<label>大廳：<?php echo $order['room_name']?></label>
				<label>電影：<?php echo $order['movie_name']?></label>
				<label>位置：<?php echo $order['seats']?></label>
			</div>
			<div class="div-box-right-float">
				<label>手機號碼：<?php echo $order['phone']?></label>
				<label>上演日期：<?php if(isset($order['date'])){ echo $order['date'];}?></label>
				<label>上演時間：<?php if(isset($order['time'])){ echo $order['time'];}?></label>
				<label>購票數量：<?php echo $order['count']?></label>
				<label>單價：<?php echo $order['price']?></label>
				<label>總價：<?php echo $order['total_money']?></label>
			</div>
		</div>
		
	</div>
</div>
<!-- content end -->


<script type="text/javascript">
window.onload = function(){


}
</script>