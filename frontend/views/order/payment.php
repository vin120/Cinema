<?php
	$this->title = '憶條街電影購票';
	use frontend\views\myasset\PublicAsset;
	use frontend\views\myasset\PingPlusPlusAsset;
	use yii\helpers\Url;
	PublicAsset::register($this);
	PingPlusPlusAsset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>

訂單號:<?php echo $data['order_code']?>
<br>
時間：<?php echo $data['date']?>
<br>
座位：<?php echo $data['seats']?>
<br>
影片：<?php echo $data['movie_name']?>
<br>
電影院：<?php echo $data['cinema_name']?>
<br>
大廳：<?php echo $data['room_name']?>
<br>
總價：<?php echo $data['total_money']?>
<br>

<div id="wrap">
	<label><input name="payMethod" type="radio" value="1" checked="checked"/>支付寶 </label>
</div>

<div class="pay-btn">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<button class="btn center-block">確定付款</button>
				</div>
			</div>
		</div>
	</div>



<script type="text/javascript">
<?php $this->beginBlock('js_end') ?>

$(function() {

	var url = "<?php echo Yii::$app->params['pay_url'];?>";
	var channel = $('input:radio:checked').val();
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
                });
            }
        }
    }

	$("button").click(function(){
		wap_pay(channel);
	});

	
});

<?php $this->endBlock() ?>
</script>	
<?php $this->registerJs($this->blocks['js_end'], \yii\web\View::POS_END); ?>
