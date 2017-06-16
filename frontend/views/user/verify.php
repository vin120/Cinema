<?php
	$this->title = '憶條街電影購票';
	use frontend\views\myasset\PublicAsset;
	use frontend\views\myasset\LoginAsset;
	use yii\helpers\Url;
	use yii\bootstrap\ActiveForm;
	use yii\bootstrap\Html;
	PublicAsset::register($this);
	LoginAsset::register($this);
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
				<h4 class="title-text text-center">驗證碼</h4>
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

<div class="main container">
	<div class="row">
		<div class="col-xs-12 tips-wrap">
			<p><span>輸入手機號</span> > <span>輸入驗證碼</span> > 設置密碼</p>
		</div>
		<?php
			if (Yii::$app->session->hasFlash('info')) {
				echo Yii::$app->session->getFlash('info');
			} 
		?>
		
		<?php
			$form = ActiveForm::begin([
				'method'=>'post',
				'enableClientValidation'=>false,
				'enableClientScript'=>false,
				'fieldConfig' => [
					'template' => '{input}{error}',		
				],
			]); 
		?>
		<div class="col-xs-12 verify-wrap">
			<?php echo $form->field($model,'c_code')->textInput(["class"=>"","placeholder"=> Yii::t('app', '請輸入驗證碼')])?>
		</div>
		<div class="col-xs-12 btn-wrap">
			<?php echo Html::submitButton('提交驗證碼',['class'=>'btn btn-danger','style'=>'width: 100%'])?>
		</div>
		<?php ActiveForm::end();?>
	</div>
</div>