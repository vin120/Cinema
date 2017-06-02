<?php
	$this->title = '電影管理';
	use backend\views\myasset\PublicAsset;
	use backend\views\myasset\Seat1Asset;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	
	PublicAsset::register($this);
	Seat1Asset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>

<div class="r content" id="user_content">
	<div class="topNav">影院管理&nbsp;&gt;&gt;&nbsp;
		<a href="<?php echo Url::toRoute(['cinema/index']);?>">影院配置</a>&nbsp;&gt;&gt;&nbsp;
		<a href="#">编辑影院信息</a>
	</div>
	
	<div id="mainContent_content" class="pBox" style="height: 1500px">
		<div class="movie-info">
			<h3><?php echo $data['movie_name']?></h3>
			<span><?php echo $data['cinema_name']?>(<?php echo $data['room_name']?>) <?php echo $data['date']?> <?php echo $data['time_begin']?>-<?php echo $data['time_end']?>結束</span>
		</div>

		<div class="seat-tips">
			<div id="legend"></div>
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
				<button id="close" class="close-button">禁用座位</button>
				<button id="open" class="open-button">开启座位</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
<?php $this->beginBlock('js_end') ?>

$(function () {

    
    var seatArray = new Array();//已选座位
    $(document).ready(function() {
        var $cart = $('#selected-seats'); //座位区

        var sc = $('#seat-map').seatCharts({
            map: [  //座位图
                'aaaaaaaaaaaa aaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                '_aaaaaaaaaaa aaaaaaaaaaa',
                '_aaaaaaaaaaa aaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaa',
                '_aaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaaaaaa',
                '__aaaaaaaaaa aaaaaaaaaa',
                'aaaaaaaaaaaa aaaaaaaa',
            ],
            naming : {
                top : false,
                getLabel : function (character, row, column) {
                    return column;
                }
            },
            legend : { //定义图例
                node : $('#legend'),
                items : [
                    [ 'a', 'available',   '可选座' ],
                    [ 'a', 'selected',    '已选座' ],
                    [ 'a', 'unavailable', '已售出'],
                    [ 'a', 'forbidden',   '已禁用'],
                ]
            },
            click: function () { //点击事件
                if (this.status() == 'available') { //可选座
                    $('<li>'+(this.settings.row+1)+'行'+this.settings.label+'座</li>')
                            .attr('id', 'cart-item-'+this.settings.id)
                            .data('seatId', this.settings.id)
                            .appendTo($cart);

                    seatArray.push(this.settings.id);
                    return 'selected';
                } else if (this.status() == 'selected') { //已选中

                    seatArray.splice($.inArray(this.settings.id,seatArray),1);
                    //删除已预订座位
                    $('#cart-item-'+this.settings.id).remove();
                    //可选座
                    return 'available';
                } else if (this.status() == 'unavailable') { //已售出
                    return 'unavailable';
                }  else if (this.status() == 'forbidden') { //已禁止
                    $('<li>'+(this.settings.row+1)+'行'+this.settings.label+'座</li>')
                        .attr('id', 'cart-item-'+this.settings.id)
                        .data('seatId', this.settings.id)
                        .appendTo($cart);

                    seatArray.push(this.settings.id);

                    return 'f-selected';
                }  else if (this.status() == 'f-selected') { //已选中

                    seatArray.splice($.inArray(this.settings.id,seatArray),1);

                    //删除已预订座位
                    $('#cart-item-'+this.settings.id).remove();
                    //可选座
                    return 'forbidden';
                } else {
                    return this.style();
                }
            }
        });
        
        //已售出的座位
        sc.get(["<?php echo $seats_str;?>"]).status('unavailable');

        //被禁止的座位
        sc.get(["<?php echo $seats_forbidden?>"]).status('forbidden');

        
        var $_csrf = '<?php echo Yii::$app->request->csrfToken?>';
		var $_movieid = '<?php echo $data['movie_id']?>';



        
        $('#close').click(function(){
            
        	$.post("/cinema/closeseat",{_csrf:$_csrf,seatArray:seatArray,movie_id:$_movieid},function(data){
    			var obj = eval('(' + data + ')');
    	        if(obj.code == 0){
    	        	window.location.reload();
				} else {
					alert(obj.msg);
					window.location.reload();
				}
    	    });


        });

        
        $('#open').click(function(){
        	$.post("/cinema/openseat",{_csrf:$_csrf,seatArray:seatArray,movie_id:$_movieid},function(data){
    			var obj = eval('(' + data + ')');
    	        if(obj.code == 0){
    	        	window.location.reload();
				} else {
		        	alert(obj.msg);
		        	window.location.reload();
		        }
    	    });

        });

    });

});






<?php $this->endBlock() ?>
</script>	
	
<?php $this->registerJs($this->blocks['js_end'], \yii\web\View::POS_END); ?>
