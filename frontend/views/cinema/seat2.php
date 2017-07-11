<?php
	$this->title = '憶條街電影購票';
	use frontend\views\myasset\PublicAsset;
	use frontend\views\myasset\Seat2Asset;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	
	PublicAsset::register($this);
	Seat2Asset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<header class="navbar navbar-default navbar-fixed-top" style="width: 100%; ">
<div class="container">
	<div class="row">
		<div class="col-xs-2">
			<div class="nav-wrap-left">
				<a href="javascript:history.back()"><i class="fa fa-angle-left fa-2x"></i></a>
			</div>
		</div>
		<div class="col-xs-8">
			<h4 class="title-text text-center"><?php echo $data['cinema_name']?></h4>
		</div>
		<div class="col-xs-2">
			<div class="nav-wrap-right">
				<a href="<?php 
					if(isset(Yii::$app->user->identity->user_id)){
						echo Url::toRoute('user/index');
					}else{
						echo Url::toRoute('user/login');
					}
				?>">
					<i class="fa fa-user-circle fa-lg"></i>
				</a>
			</div>
		</div>
	</div>
</div>
</header>

<div class="main container">

	<div class="cinema-info">
		<div class="movie-info">
			<h3><?php echo $data['movie_name']?></h3>
			<span><?php echo $data['room_name']?> <?php echo $data['date']?> <?php echo $data['time_begin']?>-<?php echo $data['time_end']?>結束</span>
		</div>

		<div class="seat-tips">
			<div id="legend"></div>
		</div>
	</div>

	<div class="seat-main">
		<div id="seat-map">
			<div class="front">屏幕(<?php echo $data['room_name']?>)</div>
		</div>
	</div>

	<div class="booking-info">
		<div class="booking-details">
			<p>已選座位</p>
			<ul id="selected-seats"></ul>
		</div>
		<div class="booking-price">
			<div class="price-left">
				<b><span id="total">0</span> MOP</b>
				<p><?php echo $data['price']?>×<span id="counter">0</span></p>
			</div>
            

			<button id="pay" class="checkout-button">確定選座</button>
		</div>
	</div>

</div>

<script type="text/javascript">
<?php $this->beginBlock('js_end') ?>


$(function() {

	var a = <?php echo $data['price'];?>;
	var seatArray = new Array();//已选座位
	var rowArray = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];//已选座位
	
	$(document).ready(function() {	
		var d = $("#selected-seats"),//座位区
		f = $("#counter"),
		c = $("#total");
		var e = $("#seat-map").seatCharts({
			map: [
					"aaaaaaa aaaaaaaaaaaaaa aaaaaaa",
					"aaaaaaa aaaaaaaaaaaaaa aaaaaaa",
					"aaaaaaa aaaaaaaaaaaaaa aaaaaaa",
					"aaaaaaa aaaaaaaaaaaaaa aaaaaaa",
					"aaaaaaa aaaaaaaaaaaaaa aaaaaaa",
					"aaaaaaaaaaaa aaaaaaaa",
					"aaaaaaaaaaaa aaaaaaaa",
					"aaaaaaaaaaaa aaaaaaaa",
					"aaaaaaaaaaaa aaaaaaaa",
					"aaaaaaaaaaaa aaaaaaaa",
					"__aaaaaaaaaa aaaaaa",
			],
			naming: {
				rows: ["B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L"],
				top: false,
				getLabel: function(h, i, g) {
					return g
				}
			},
			legend: {//定义图例
				node: $("#legend"),
				items: [["a", "available", "可選座"], ["a", "selected", "已選座"], ["a", "unavailable", "已售出"]]
			},
			click: function() {	//点击事件
				if (this.status() == "available") {	//可选座
					 // 限制只能選中4個座位
                    if(e.find('selected').length+1 > 4){
                        alert('一次最多購買4張');
                        return 'available';
                    }else{
// 						$("<li>" +(rowArray[this.settings.row])+ "行" + this.settings.label + "座</li>").attr("id", "cart-item-" + this.settings.id).data("seatId", this.settings.id).appendTo(d);
						$('<li style="cursor:pointer">'+(this.settings.row+1)+'行'+this.settings.label+'座<i class="fa fa-close" style="float:right;line-height:26px;margin-right:5px;"></i></li>')
                        .attr('id', 'cart-item-'+this.settings.id)
                        .attr('seat_id', this.settings.id)
                        .data('seatId', this.settings.id)
                        .appendTo(d);
						f.text(e.find("selected").length + 1);
						c.text(b(e) + a);
						
						seatArray.push(this.settings.id);
						return "selected"
                    }
				} else {
					if (this.status() == "selected") {	//已选中
						f.text(e.find("selected").length - 1);
						c.text(b(e) - a);
						seatArray.splice($.inArray(this.settings.id,seatArray),1);
						
						$("#cart-item-" + this.settings.id).remove();
						return "available"
					} else {	//已售出
						if (this.status() == "unavailable") {
							return "unavailable"
						} else {
							return this.style()
						}
					}
				}
			}
		});
		e.get(["<?php echo $seats_str;?>"]).status("unavailable")
		$(document).on('click','#selected-seats li',function(){
            //更新数量
            f.text(e.find("selected").length - 1);
			c.text(b(e) - a);
            e.get([$(this).attr("seat_id")]).status('available');
            //删除已预订座位
            $(this).remove();            
        });
	});
	function b(d) {
		var c = 0;
		d.find("selected").each(function() {
			c += a
		});
		return c
	}

	
	$("#pay").click(function() {
		
		var $_csrf = '<?php echo Yii::$app->request->csrfToken?>';
		var $_movieid = '<?php echo $data['movie_id']?>';
		$.post("/order/pickseat",{_csrf:$_csrf,seatArray:seatArray,movie_id:$_movieid},function(data){
			var obj = eval('(' + data + ')');
	        if(obj.code == 0){
	        	window.location.href = "<?php echo Url::to(['/order/pay']);?>?ssid="+obj.ssid;
	        } else if(obj.code == 1){
	        	alert(obj.msg);
	        	window.location.href = "<?php echo Url::to('/user/login')?>";
	        } else {
	        	alert(obj.msg);
	        	window.location.reload();
	        }
	    });

	});
});



<?php $this->endBlock() ?>
</script>	
	
<?php $this->registerJs($this->blocks['js_end'], \yii\web\View::POS_END); ?>
