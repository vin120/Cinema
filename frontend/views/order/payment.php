<?php
	$this->title = '憶條街電影購票';
	use frontend\views\myasset\PublicAsset;
	use frontend\views\myasset\PingPlusPlusAsset;
	use frontend\views\myasset\PayWayAsset;
	use yii\helpers\Url;
	PublicAsset::register($this);
	PingPlusPlusAsset::register($this);
	PayWayAsset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>

<header class="navbar navbar-default navbar-fixed-top">
<div class="container">
      <div class="row">
          <div class="col-xs-2">
              <div class="nav-wrap-left">
                  <a style="border:none;" href="javascript:history.back()"><i class="fa fa-angle-left fa-2x"></i></a>
              </div>
          </div>
          <div class="col-xs-8">
              <h4 class="title-text text-center">付款</h4>
          </div>
          <div class="col-xs-2">
              <div class="nav-wrap-right">
                  <a style="border:none;" href="<?php echo Url::toRoute('user/index');?>">
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
			<p>支付剩余時間：<span id="time"></span></p>
		</div>

	
		<?php 
			$cookies = Yii::$app->request->cookies;
			if(!isset($cookies['wx_code'])):
		?>
			<div class="col-xs-12 pay-way">
				<img src="<?php echo $baseUrl?>images/alipay_g2.png"/>
				<div class="way-text">
					<h3>支付寶支付</h3>
					<span>推薦有支付寶賬戶的用戶使用</span>
				</div>
				<div class="icheckbox">
					<input value="1" type="radio" name="square-radio" checked>
				</div>
			</div>
		<?php endif;?>
		
        <?php 
			$cookies = Yii::$app->request->cookies;
			if(isset($cookies['wx_code'])):
		?>
		<div class="col-xs-12 pay-way">
			<img src="<?php echo $baseUrl?>images/weixin_g2.png"/>
			<div class="way-text">
				<h3>微信支付</h3>
				<span>推薦安裝微信6.0及以上版本的用戶使用</span>
			</div>
			<div class="icheckbox">
				<input value="2" type="radio" name="square-radio" checked>
			</div>
		</div>
		<?php endif;?>
		
		
		<div class="col-xs-12 pay-way" style="margin-top:0px;">
			<img src="<?php echo $baseUrl?>images/bank_g2.png"/>
			<div class="way-text">
				<h3>銀行卡支付</h3>
				<span>信用卡儲蓄卡付款，無需開通網銀</span>
			</div>
			<div class="icheckbox">
				<input value="3" type="radio" name="square-radio">
			</div>
		</div>
	</div>

	<div class="pay-btn">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<p>需支付：<b><?php echo $data['total_money']?>MOP</b></p>
				</div>

				<div class="col-xs-12">
					<button id="pay" class="btn center-block">確定付款</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
<?php $this->beginBlock('js_end') ?>

$(function() {


	var now = "<?php echo $data['order_time']?>";

// 	$('#time').countdown(now, function(event) {
// 		$(this).html(event.strftime('%M:%S'));
// 	});


$('#time').countdown(now).on('update.countdown', function(event){  
	$(this).html(event.strftime('%M:%S'));  
}).on('finish.countdown', function(event){
	$(this).html(event.strftime('%M:%S'));  
	setTimeout(function(){
		alert('支付超时，该订单已失效！');
		window.location.href = "<?php echo Url::to('/index/index')?>";
	},1000);
});


	$('input').iCheck({

		checkboxClass: 'icheckbox_square-red',  // 注意square和red的對應關系

		radioClass: 'iradio_square-red',

		increaseArea: '20%' // optional

	});


	$("#pay").click(function(){
		var pay_way = $("input[type='radio']:checked").val();
		if(pay_way == 1){
			wap_pay(1) 
		}else if(pay_way == 2){
			wap_pay(2)
		}else if(pay_way == 3){
			wap_pay(3) 
		}
	});

	$(".pay-way").click(function(){
		$(this).find('input').iCheck('check');
	});

	
	var url = "<?php echo Yii::$app->request->getHostInfo().'/'.Yii::$app->params['pay_url'];?>";

	var _csrf = '<?php echo Yii::$app->request->csrfToken?>';
	   
	function wap_pay(channel) {

        if(url.length == 0 || !url.startsWith('http')){
            alert("请填写正确的URL");
            return;
        }

        var ssid = "<?php echo $data['order_code']?>";
        
        var xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded;charset=UTF-8");
		xhr.send("channel="+channel+"&ssid="+ssid+"&_csrf="+_csrf);
        
        
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                console.log(xhr.responseText);
                pingpp.createPayment(xhr.responseText, function(result, err) {
                    console.log(result);
                    console.log(err.msg);
                    console.log(err.extra);
                    if (result == "success") {
                        // 只有微信公众账号 wx_pub 支付成功的结果会在这里返回，其他的支付结果都会跳转到 extra 中对应的 URL。
                    	window.location.href = "<?php echo Url::to('/order/success')?>";
                    } else if (result == "fail") {
                        // charge 不正确或者微信公众账号支付失败时会在此处返回
                    	window.location.href = "<?php echo Url::to('/order/cancel')?>";
                    } else if (result == "cancel") {
                        // 微信公众账号支付取消支付
                    	window.location.href = "<?php echo Url::to('/order/cancel')?>";
                    }
                });
            }
        }
    }
});

<?php $this->endBlock() ?>
</script>	
<?php $this->registerJs($this->blocks['js_end'], \yii\web\View::POS_END); ?>
