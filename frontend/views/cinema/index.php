<?php
	$this->title = '憶條街電影購票';
	use frontend\views\myasset\PublicAsset;
	use frontend\views\myasset\CinemaAsset;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	
	PublicAsset::register($this);
	CinemaAsset::register($this);
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
				<h4 class="title-text text-center">影片</h4>
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
<div class="main container-fluid">
	<div class="row cinema">
		<div class="cinema-info">
			<h3><?php echo $cinema['cinema_name']?></h3>
			<p><?php echo $cinema['cinema_address']?></p>
		</div>
		<div class="cinema-tel">
			<a href="tel:<?php echo $cinema['cinema_phone']?>">
				<img src="<?php echo $baseUrl?>images/phone.png" alt="phone">
			</a>
		</div>
	</div>

    <!-- movie Swiper -->
	<div class="swiper-container" id="movie-swiper">
		<div class="swiper-wrapper">
		<?php foreach ($movie as $row):?>
			<div class="swiper-slide"><img src="<?php echo Yii::$app->params['img_url'].'/'.$row['img_url']?>" alt="pic"></div>
		<?php endforeach;?>
		</div>
        <!-- Add Pagination -->
        <!--<div class="swiper-pagination"></div>-->
	</div>

	<div class="row movie-container">
		<h4>
			<span class="movie-name"><?php echo $movie[0]['movie_name']?></span>
			<span class="label movie-score"><span id="score"><?php echo $movie[0]['score']?></span>分</span>
		</h4>
		<p id="movei_info">
			<?php echo $movie[0]['duration']?> |<?php echo $movie[0]['style']?> | <?php echo $movie[0]['charactor']?>
		</p>

	</div>
	
    <!-- time -->
	<ul class="moive_time" id="tab">
	<?php foreach ($movie_time as $key => $value): ?>
		<li time-id="<?php $key?>"  <?php  if($key == 0){ echo "class='active'";}?> >
			<?php echo $value;?>
		</li>

	<?php endforeach;?>
	</ul>

	<div class="movietime-list">
		<table>
			<tbody id="movietime-list">
			
            </tbody>
        </table>
    </div>

</div>

<script type="text/javascript">
<?php $this->beginBlock('js_end') ?>

$(function () {

    var time_list = '';
    
    var movie_swiper = new Swiper('#movie-swiper', {
        slidesPerView: 4,
        centeredSlides: true,
        spaceBetween: 5,
        slideToClickedSlide:true,//点击滑动居中
        onSlideChangeEnd: function(swiper){

        	var json_url = '<?php echo Url::to("/cinema/jsonurl");?>'+'?id='+swiper.activeIndex+'&cinema_id=<?php echo $cinema_id?>';

            $.ajax({
                type: 'GET',
                url: json_url,
                dataType: 'json',
                success: function(data){

                    var result = '';
                    var time_result = '';
                    time_list = data.lists;
                    $('.movie-name').text(data.movie_name);
                    $('#score').text(data.score);
                    $('#movei_info').text(data.info);

                    if(data.lists.length > 0){
                        var arrLen = data.lists.length;
                        for(var i=0; i<arrLen; i++){
                            if(i == 0){
                                result +=  '<li time-id="'+i+'" class="active">'+data.lists[i].time+'</li>';
                            }else{
                                result +=  '<li time-id="'+i+'">'+data.lists[i].time+'</li>';
                            }

                        }

                        var detailLen = data.lists[0].detail.length;
                        
                        for(var i=0; i<detailLen; i++){
                            time_result +=  '<tr>'
                                        +'<td class="mt-time">'
                                        +'<div class="mt-time-wrap">'
                                        +'<strong>'+data.lists[0].detail[i].start+'</strong><em>'+data.lists[0].detail[i].end+'结束</em>'
                                        +'</div>'
                                        +'</td>'
                                        +'<td class="mt-info">'
                                        +'<div class="mt-lang">'
                                        +data.lists[0].detail[i].language
                                        +'</div>'
                                        +'<div class="mt-place">'
                                        +data.lists[0].detail[i].hall
                                        +'</div>'
                                        +'</td>'
                                        +'<td class="mt-price">'
                                        +'<span class="unit theme-color">'+data.lists[0].detail[i].price+'元</span><span class="origin-price">影院价:'+data.lists[0].detail[i].o_price+'元</span>'
                                        +'</td>'
                                        +'<td class="mt-buy">'
                                        +'<a class="mt-btn" href="<?php echo Url::toRoute('cinema/seat')?>?show_id='+data.lists[0].detail[i].show_id+'">购票</a>'
                                        +'</td>'
                                        +'</tr>';

                        }

                        $('#tab').html(result);
                        $('#movietime-list').html(time_result);
                    }else{
                        $('#tab').html("");
                        $('#movietime-list').html("");
                    }
                    
                }
            });
        },
        onInit: function(swiper){
            //Swiper初始化了

            var json_url = '<?php echo Url::to("/cinema/jsonurl");?>'+'?id=0&cinema_id=<?php echo $cinema_id?>';

            $.ajax({
                type: 'GET',
                url: json_url,
                dataType: 'json',
                success: function(data){
                    // console.log(data.lists);
                    var result = '';
                    var time_result = '';
                    time_list = data.lists;
                    $('.movie-name').text(data.movie_name);
                    $('#score').text(data.score);
                    $('#movei_info').text(data.info);

                    if(data.lists.length > 0){
                        
                        var arrLen = data.lists.length;
                        for(var i=0; i<arrLen; i++){
                            if(i == 0){
                                result +=  '<li time-id="'+i+'" class="active">'+data.lists[i].time+'</li>';
                            }else{
                                result +=  '<li time-id="'+i+'">'+data.lists[i].time+'</li>';
                            }

                        }

                        
                        var detailLen = data.lists[0].detail.length;
                        for(var i=0; i<detailLen; i++){
                            time_result +=  '<tr>'
                                +'<td class="mt-time">'
                                +'<div class="mt-time-wrap">'
                                +'<strong>'+data.lists[0].detail[i].start+'</strong><em>'+data.lists[0].detail[i].end+'结束</em>'
                                +'</div>'
                                +'</td>'
                                +'<td class="mt-info">'
                                +'<div class="mt-lang">'
                                +data.lists[0].detail[i].language
                                +'</div>'
                                +'<div class="mt-place">'
                                +data.lists[0].detail[i].hall
                                +'</div>'
                                +'</td>'
                                +'<td class="mt-price">'
                                +'<span class="unit theme-color">'+data.lists[0].detail[i].price+'元</span><span class="origin-price">影院价:'+data.lists[0].detail[i].o_price+'元</span>'
                                +'</td>'
                                +'<td class="mt-buy">'
                                +'<a class="mt-btn" href="<?php echo Url::toRoute('cinema/seat')?>?show_id='+data.lists[0].detail[i].show_id+'">购票</a>'
                                +'</td>'
                                +'</tr>';

                        }
                        
                        

                        $('#tab').html(result);
                        $('#movietime-list').html(time_result);
                    }else{
                        $('#tab').html("");
                        $('#movietime-list').html("");
                    }
                    
                }
            });
        }
    });

    $(".moive_time").on("click","li",function(){
        $(this).siblings('li').removeClass('active');  // 删除其他兄弟元素的样式
        $(this).addClass('active');                    // 添加当前元素的样式
   
        var time_id = $(this).attr("time-id");

        var detailLen = time_list[time_id].detail.length;
        var detail = time_list[time_id].detail;
        var time_result = '';
        for(var i=0; i<detailLen; i++){
            time_result +=  '<tr>'
                +'<td class="mt-time">'
                +'<div class="mt-time-wrap">'
                +'<strong>'+detail[i].start+'</strong><em>'+detail[i].end+'结束</em>'
                +'</div>'
                +'</td>'
                +'<td class="mt-info">'
                +'<div class="mt-lang">'
                +detail[i].language
                +'</div>'
                +'<div class="mt-place">'
                +detail[i].hall
                +'</div>'
                +'</td>'
                +'<td class="mt-price">'
                +'<span class="unit theme-color">'+detail[i].price+'元</span><span class="origin-price">影院价:'+detail[i].o_price+'元</span>'
                +'</td>'
                +'<td class="mt-buy">'
                +'<a class="mt-btn" href="<?php echo Url::toRoute('cinema/seat')?>?show_id='+detail[i].show_id+'">购票</a>'
                +'</td>'
                +'</tr>';

        }
        $('#movietime-list').html(time_result);

    });



});


<?php $this->endBlock() ?>
</script>	
	
<?php $this->registerJs($this->blocks['js_end'], \yii\web\View::POS_END); ?>
