<?php
	$this->title = '憶條街電影購票';
	use frontend\views\myasset\PublicAsset;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	
	PublicAsset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>
<header class="navbar navbar-default navbar-fixed-top">
<div class="container">
	<div class="row">
		<div class="col-xs-2">
             
		</div>
		<div class="col-xs-8">
			<h4 class="title-text text-center">影院</h4>
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
	<div class="row">
		<?php foreach($cinema as $row) :?>
	
		<div class="col-xs-12">
			<a class="a_none" href="<?php echo Url::toRoute(['cinema/index','id'=>$row['cinema_id']]); ?>">
				<div class="cinema__ResultItem">
					<div class="flex">
						<span class="name ell"><?php echo $row['cinema_name']?></span><span class="price flex-auto">
							<?php echo $row['low_price']?> MOP <i>起</i>
						</span>
					</div>
					<div class="flex">
						<div class="addr ell">
							<?php echo $row['cinema_address']?>
						</div>
					</div>
					<div class="label-box">
						<span class="label label-default">VIP</span>
						<span class="label label-success">IMAX</span>
						<span class="label label-info">小吃</span>
						<span class="label label-warning">巨幕</span>
					</div>
				</div>
			</a>
		</div>
		<?php endforeach;?>
	</div>
</div>

