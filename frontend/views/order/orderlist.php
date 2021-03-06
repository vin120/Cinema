<?php
	$this->title = '憶條街電影購票';
	use frontend\views\myasset\PublicAsset;
	use frontend\views\myasset\OrderlistAsset;
	use yii\helpers\Url;
	PublicAsset::register($this);
	OrderlistAsset::register($this);
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
					<a href="<?php echo Url::toRoute('index/index')?>">
						<i class="fa fa-home fa-lg"></i>
					</a>
				</div>
			</div>
		</div>
	</div>
</header>



<div class="main container"  id='content'>
	<div class="row lists">
	
	</div>


</div>


<script type="text/javascript">
<?php $this->beginBlock('js_end') ?>


$(function() {
	var page = 0;
	var size = 10;
	
	$("#content").dropload({
		scrollArea: window,
		domUp: {
			domClass: "dropload-up",
			domRefresh: '<div class="dropload-refresh">↓下拉刷新</div>',
			domUpdate: '<div class="dropload-update">↑釋放更新</div>',
			domLoad: '<div class="dropload-load"><span class="loading"></span>加載中...</div>'
		},
		domDown: {
			domClass: "dropload-down",
			domRefresh: '<div class="dropload-refresh">↑上拉加載更多</div>',
			domLoad: '<div class="dropload-load"><span class="loading"></span>加載中...</div>',
			domNoData: '<div class="dropload-noData">無更多數據</div>'
		},
		loadUpFn: function(me) {
			var json_url = '<?php echo Url::to("/order/jsonurl");?>?page=1';
			$.ajax({
				type: "GET",
				url: json_url,
				dataType: "json",
				success: function(data) {
					
					var result = "";
					var status = "";
					if(typeof(data.lists) != "undefined"){
						if(data.lists.length > 0){
							for (var i = 0; i < data.lists.length; i++) {
								switch (data.lists[i].status) {
								case 0:
									status = '<a href="<a href="<?php echo Url::to('/order/pay');?>?ssid='+data.lists[i].ssid+'" class="line-btn order-status status-f a_none">付款</a>';
									break;
								case 1:
									status = '<span class="order-status">待取票</span>';
									break;
								case 2:
									status = '<span class="order-status status-f">已失效</span>';
									break;
								case 3:
									status = '<span class="order-status status-f" style="color:green">已取票</span>';
								
								}
								result += '<div class="col-xs-12 order-wrap"><a class="a_none" href="<?php echo Url::to(['/cinema/index']);?>?id='+data.lists[i].cinema_id+'"><div class="order-cinema"><span>' + data.lists[i].cinema_name + '</span><div class="order-more"><i class="fa fa-angle-right"></i></div></div></a><div class="bottom-line"></div><a class="a_none" href="<?php echo Url::toRoute(['/order/orderdetail']);?>?ssid='+data.lists[i].ssid+'"><div class="order-info"><div class="movie-img"><img src="<?php echo Yii::$app->params['img_url'];?>/' + data.lists[i].pic + '"/></div><div class="movie-info"><h4>' + data.lists[i].movie_name + data.lists[i].counts + "</h4><p>" + data.lists[i].date + "</p><p>" + data.lists[i].hall + "  " + data.lists[i].seats + '</p></div></div></a><div class="bottom-line"></div><div class="order-price"><p>總價：<span>' + data.lists[i].price + "</span> MOP</p>" + status + "</div></div>"
							}
							setTimeout(function() {
								$(".lists").html(result);
								me.resetload();
								page = 1;
								me.unlock();
								me.noData(false);
							},
							1000)
							
							// if (data.lists.length < 10) {
							// 	me.lock();
							// 	me.noData();
							// }

						}else{
							me.lock();
							me.noData();
							$('.dropload-down').html('<div class="dropload-noData">暫無數據</div>');
						}
					}else{
						me.lock();
						me.noData();
						$('.dropload-down').html('<div class="dropload-noData">暫無數據</div>');
					}
					
					
				},
				error: function(xhr, type) {
					alert("Ajax error!");
					me.resetload();
				}
			})
		},
		loadDownFn: function(me) {
			page++;
			var result = "";
			var status = "";
			var json_url = '<?php echo Url::to("/order/jsonurl");?>?page='+page;
			$.ajax({
				type: "GET",
				url: json_url,
				dataType: "json",
				success: function(data) {
					// var data = eval("(" + data + ")");
// 					console.log(data);
					// console.log(data.lists);
					if(typeof(data.lists) != "undefined"){
						if(data.lists.length > 0){
							var arrLen = data.lists.length;
							
							for (var i = 0; i < arrLen; i++) {
								switch (data.lists[i].status) {
								case 0:
									status = '<a href="<?php echo Url::to('/order/pay');?>?ssid='+data.lists[i].ssid+'" class="line-btn order-status status-f a_none">付款</a>';
									break;
								case 1:
									status = '<span class="order-status">待取票</span>';
									break;
								case 2:
									status = '<span class="order-status status-f">已失效</span>';
									break;
								case 3:
									status = '<span class="order-status status-f" style="color:green">已取票</span>';
								
								}
								result += '<div class="col-xs-12 order-wrap"><a class="a_none" href="<?php echo Url::to(['/cinema/index']);?>?id='+data.lists[i].cinema_id+'"><div class="order-cinema"><span>' + data.lists[i].cinema_name + '</span><div class="order-more"><i class="fa fa-angle-right"></i></div></div></a><div class="bottom-line"></div><a class="a_none" href="<?php echo Url::toRoute(['/order/orderdetail']);?>?ssid='+data.lists[i].ssid+'"><div class="order-info"><div class="movie-img"><img src="<?php echo Yii::$app->params['img_url'];?>/' + data.lists[i].pic + '"/></div><div class="movie-info"><h4>' + data.lists[i].movie_name + data.lists[i].counts + "</h4><p>" + data.lists[i].date + "</p><p>" + data.lists[i].hall + "  " + data.lists[i].seats + '</p></div></div></a><div class="bottom-line"></div><div class="order-price"><p>總價：<span>' + data.lists[i].price + "</span> MOP</p>" + status + "</div></div>"
							}

							if (arrLen < 10) {
								me.lock();
								me.noData();
							}
							setTimeout(function() {
								$(".lists").append(result);
								me.resetload()
							},
							1000)
						}else{
							me.lock();
							me.noData();
							$('.dropload-down').html('<div class="dropload-noData">暫無數據</div>');
						}
					}else{
						me.lock();
						me.noData();
						$('.dropload-down').html('<div class="dropload-noData">暫無數據</div>');
					}
					
					
				},
				error: function(xhr, type) {
					me.resetload();
				}
			})
		},
		threshold: 50
	})
});

<?php $this->endBlock() ?>
</script>	
	
<?php $this->registerJs($this->blocks['js_end'], \yii\web\View::POS_END); ?>
