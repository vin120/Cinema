<?php
	$this->title = '管理員信息';
	use backend\views\myasset\PublicAsset;
	use yii\helpers\Url;
	use yii\bootstrap\ActiveForm;
	
	PublicAsset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>


<!-- content start -->
<div class="r content">
	<div class="topNav">管理員信息&nbsp;&gt;&gt;&nbsp;<a href="<?php echo Url::to('index')?>">管理員列表</a>&nbsp;&gt;&gt;&nbsp;<a href="#">編輯管理員</a></div>
	<div class="searchResult">
	<div id="service_write" class=" write">
	<?php
		$form = ActiveForm::begin([
			'action' => ['edit','id'=>$_GET['id']],
			'method'=>'post',
			'id'=>'admin_edit',
			'options' => ['class' => 'type_add'],
			'enableClientValidation'=>false,
			'enableClientScript'=>false,
		]);
	?>
		<div class="form">
			<p>
				<label>
					<span><?php echo yii::t('app','管理員姓名')?>:</span>
					<input type="text" id="admin_nickname" name="admin_nickname" value="<?php echo $admin['admin_nickname']?>" readonly="readonly"></input>
				</label>
			</p>
			
			<p>
				<label>
					<span><?php echo yii::t('app','管理員帳號')?>:</span>
					<input type="text" id="admin_user" name="admin_user" value="<?php echo $admin['admin_user']?>" readonly="readonly"></input>
				</label>
			</p>
			
			<p>
				<label>
					<span class='max_l'><?php echo yii::t('app','狀態')?>:</span>
					<select name="status" id=status class='input_select'>
						<option value="1" <?= $admin['status']==1?"selected='selected'":''?>> <?= yii::t('app','启用')?></option>
						<option value="0" <?= $admin['status']==0?"selected='selected'":''?>> <?= yii::t('app','禁用')?></option>
					</select>
				</label>
			</p>
			
		</div>

		<div class="btn">
			<input style="cursor:pointer" type="submit" value="<?php echo yii::t('app','保存')?>"></input>
		</div>

	<?php
		ActiveForm::end();
	?>
	</div>
	</div>
	
	
</div>
<!-- content end -->

