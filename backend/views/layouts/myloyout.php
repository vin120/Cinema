<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;

use backend\views\myasset\PublicAsset;

$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';

$controller = Yii::$app->controller->id;

$menu = Yii::$app->view->params['menu'];


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<!-- header start -->
<header id="header">
    <div class="l" id="title">
    	<h1><?= \Yii::t('app', '後臺管理系統') ?></h1>
    </div>
    <div class="r" id="user">
        <div class="l" id="user_img">
            <img src="<?=$baseUrl ?>images/user.png">
        </div>
        <div class="r">
            <span id="userName"><?= Yii::$app->user->identity->admin_user;?></span>
            <span id="exit"><a href="/site/logout" ><?= \Yii::t('app', '註銷') ?></a></span>
        </div>
    </div>
</header>
<!-- header end -->
<!-- main start -->
<main id="main">
    <!-- asideNav start -->
    <aside id="asideNav" class="l">
       <nav id="asideNav_open">
            <?php foreach($menu as $k=>$row){
                $child = array();
                foreach ($row['child'] as $value) {
                    $child[] = $value['controller'];
                }
             ?>
            <!-- 一级 -->
            <ul>
                <li <?= in_array($controller,$child)? ' class="open"':'' ?>>
                    <a><img src="<?=$baseUrl ?>images/icon.png"><?php echo $row['menu_name'] ?></a>
                </li>
                <?php if(isset($row['child'])){ ?>
                <!-- 二级 -->
                <ul style="<?= in_array($controller,$child) ? 'display: block;':'display: none;' ?>">
                    <?php foreach($row['child'] as $v){ ?>
                    <li<?= $controller==$v['controller']? ' class="active"':'' ?>><a href="<?php echo Url::toRoute([$v['controller'].'/'.$v['action']]);?>"><?php echo $v['menu_name']?></a></li>
                    <?php }?>
                </ul>
                <?php }?>
            </ul>
            <?php } ?>
            
            <a href="#" id="closeAsideNav"><img src="<?=$baseUrl ?>images/asideNav_close.png"></a>
        </nav>
        <nav id="asideNav_close">
            <ul>
                <li><img src="<?=$baseUrl ?>images/routeManage_icon.png"></li>
                <a href="#" id="openAsideNav"><img src="<?=$baseUrl ?>images/asideNav_open.png"></a>
            </ul>
        </nav>
    </aside>
    <!-- asideNav end -->
    <!-- content start -->
    <?= $content ?>
    <!-- content end -->
</main>
<!-- main end -->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
