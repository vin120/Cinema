<?php
	use yii\helpers\Url;
?>
<img src="<?= Url::to(['test/qrcode','ssid'=>$ssid])?>" />
