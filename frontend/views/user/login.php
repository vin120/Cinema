<?php
	$this->title = '憶條街電影購票';
	use frontend\views\myasset\PublicAsset;
	use frontend\views\myasset\LoginAsset;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
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
				<h4 class="title-text text-center">登陸</h4>
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
		<div class="col-xs-12 login-wrap">
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
				<div class="form-group">
					<?php echo $form->field($model,'user_phone')->textInput(["class"=>"form-control","id"=>"exampleInputEmail1","placeholder"=> Yii::t('app', '請輸入您的手機號')])?>
				</div>
				<div class="form-group">
					<?php echo $form->field($model,'user_password')->passwordInput(["class"=>"form-control","id"=>"exampleInputPassword1","placeholder"=> Yii::t('app', '請輸入您的密碼')])?>
				</div>
				<?php echo Html::submitButton('登陸',['class'=>'btn btn-danger','id'=>'submit','style'=>'width: 100%;margin: 10px 0'])?>    
			<?php ActiveForm::end();?>
			
		</div>
		<div class="col-xs-12 register-wrap">
			<a class="a_none" href="<?php echo Url::toRoute('user/regist')?>">
				<p id="register">註冊</p>
			</a>
			<a class="a_none" href="<?php echo Url::toRoute('user/findme')?>">
				<p id="forget">忘記密碼</p>
			</a>
		</div>
	</div>
</div>

