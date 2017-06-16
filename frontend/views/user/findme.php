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
				<h4 class="title-text text-center">找回密碼</h4>
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
			<p><span>輸入手機號</span> > 輸入驗證碼 > 設置密碼</p>
		</div>
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
		
		<div class="col-xs-12 phone-wrap">
			<?php echo $form->field($model,'area')->textInput(["class"=>"","id"=>"#","placeholder"=> Yii::t('app', '請輸入區號，澳門:853,中國大陸:86')])?>
			<?php echo $form->field($model,'user_phone')->textInput(["class"=>"","id"=>"#","placeholder"=> Yii::t('app', '請輸入您的手機號')])?>
		</div>

		<div class="col-xs-12 protocol-wrap">
			<input type="checkbox" id="read_me"> <p>我已閱讀並同意<span>《憶條街用戶協議》</span></p>
		</div>

		<div class="col-xs-12 btn-wrap">
			<?php echo Html::submitButton('獲取驗證碼',['class'=>'btn btn-danger','id'=>'url','disabled'=>'true','style'=>'width: 100%'])?>
		</div>
		<?php ActiveForm::end();?>
	</div>
</div>



<script type="text/javascript">
<?php $this->beginBlock('js_end') ?>

$(function() {
	
	$('#read_me').click(function(){
		if($('input[id="read_me"]').prop("checked")){
			$("#url").removeAttr("disabled");
		} else {
			$("#url").attr("disabled",true);
		}
	});
});

<?php $this->endBlock() ?>
</script>	
	
<?php $this->registerJs($this->blocks['js_end'], \yii\web\View::POS_END); ?>
