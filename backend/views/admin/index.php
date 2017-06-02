<?php
	$this->title = '管理員信息';
	use backend\views\myasset\PublicAsset;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	
	PublicAsset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>
<script type="text/javascript">
	var verify_admin_info = "<?php echo Url::toRoute(['admin/verifyadmininfo']);?>";
</script>

<!-- content start -->
<div class="r content">
	<div class="topNav">管理員管理&nbsp;&gt;&gt;&nbsp;
	<a href="#">修改信息</a>
	</div>
	<?php
		$form = ActiveForm::begin([
			'action' => ['index'],
			'method'=>'post',
			'id'=>'admin_form',
			'options' => ['class' => 'admin_form'],
			'enableClientValidation'=>false,
			'enableClientScript'=>false
		]);
	?>
	<div class="searchResult">
		<p>
			<span>帳號：</span>
			<input type="text" name="admin_user" maxlength="20" value="<?php echo $data['admin_user'] ?>" />
			<em class="required_tips">*</em>
			
		</p>
		<p>
			<span>昵稱：</span>
			<input type="text" name="admin_nickname" maxlength="20" value="<?php echo $data['admin_nickname'] ?>" />
			<em class="required_tips">*</em>
			
		</p>
		<p>
			<span>密码：</span>
			<input type="password" name="password" maxlength="20" value="******" />
			<em class="required_tips">*</em>
		</p>
		<p>
			<span>确认密码：</span>
			<input type="password" name="query_password" maxlength="20" value="******"  />
			<em class="required_tips">*</em>
		</p>

		<div class="btn">
			<input type="submit" value="保存"></input>
		</div>

	</div>
	<?php
		ActiveForm::end();
	?>
</div>
<!-- content end -->