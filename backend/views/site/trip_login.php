<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\views\myasset\LoginAsset;
use yii\helpers\Url;

LoginAsset::register($this);

$this->title = 'Manager System';
$this->params['breadcrumbs'][] = $this->title;

$baseUrl = $this->assetBundles[LoginAsset::className()]->baseUrl . '/';

//$curr_language = Yii::$app->language;
?>

<header id="mainHeader">
    <h1 id="logo">
        <!--<img src="<?= $baseUrl ?>/images/logo.png"> -->
    </h1>
</header>
<main>
    <div id="loginForm">
        <h2><?= \Yii::t('app', '登錄');?></h2>
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <div class="formBox">
                <div>
                    <label class="clearfix">
                        <?=
                        Html::activeTextInput($model, 'username',['class'=>"l",'maxlength'=>20,'required'=>'required',
                            'oninvalid'=>'setCustomValidity("'. \Yii::t('app', '用戶名不能爲空').'")',
                            'oninput'=>'setCustomValidity("")',
                            'autofocus'=>'autofocus','placeholder' =>\Yii::t('app', '用戶名')])
                        ?>
                        <span class="imgBox l"><img src="<?= $baseUrl ?>/images/login_user.png"></span>
                    </label>
                    <em class="wrongBox">Please ...</em>
                </div>
                <div>
                    <label class="clearfix">
                        <?= Html::activePasswordInput($model, 'password',['class'=>"l",'maxlength'=>20,'required'=>'required',
                            'oninvalid'=>'setCustomValidity("'. \Yii::t('app', '密碼不能爲空').'")',
                            'oninput'=>'setCustomValidity("")',
                            'placeholder'=>\Yii::t('app', '密碼')])?>
                        <span class="imgBox l"><img src="<?= $baseUrl ?>/images/login_pw.png"></span>
                    </label>
                    <em class="wrongBox">Please ...</em>
                </div>
                <div id="passwordthis">
				</div>
                <div id="remember">
                    <label>
                        <?= Html::activeCheckbox($model, 'rememberMe') ?>
                    </label>
                </div>
                <div id="btnBox">
                    <input type="submit" class="btn1" name="login-button" value="<?= \Yii::t('app', '登錄');?>" />
                    <input type="reset" class="btn2" value="<?= \Yii::t('app', '重置');?>" />
                </div>
            </div>
            
        <?php ActiveForm::end(); ?>
    </div>
</main>


<?php

$this->registerJs('
		
	var errorMessage = \''.$model->getFirstError('password') .'\';
	
	window.onload=function(){
		
		if(errorMessage != \'\'){
			$("#passwordthis").append("<strong class=\'point\' style=\'color:red;\'>用戶名或者密碼錯誤，請確認無誤後再輸入</strong>");
		}

}	

', \yii\web\View::POS_END);

?>

