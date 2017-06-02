<?php
	$this->title =  yii::t('app','大廳配置');
	use backend\views\myasset\PublicAsset;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;

	PublicAsset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>

<!-- content start -->
<div class="r content" id="shoreExcursions_content">
<div class="topNav"><?php echo yii::t('app','影院管理')?>&nbsp;&gt;&gt;&nbsp;<a href="<?php echo Url::toRoute(['index']);?>"><?php echo yii::t('app','大廳管理')?></a></div>
	<div class="searchResult">
	<div id="service_write" class=" write">
	<?php
		$form = ActiveForm::begin([
			'action' => ['edit','id'=>$type['type_id']],
			'method'=>'post',
			'id'=>'type_id',
			'options' => ['class' => 'type_edit'],
			'enableClientValidation'=>false,
			'enableClientScript'=>false
		]);
	?>
		<div class="form">
			<p>
				<label>
					<span class='max_l'><?php echo yii::t('app','大廳名')?>:</span>
					<input type="text" id="name" name="name" value="<?php echo $type['type_name']?>"></input>
				</label>
			</p>
			<input type="hidden">
			<p>
				<label>
					<span class='max_l'><?php echo yii::t('app','狀態')?>:</span>
					<select name="status" id=status class='input_select'>
						<option value='1'  <?php echo  $type['status'] == 1 ? "selected='seletcted'" : '';?> ><?php echo yii::t('app','啓用')?></option>
						<option value='0'  <?php echo  $type['status'] == 0 ? "selected='seletcted'" : '';?> ><?php echo yii::t('app','禁用')?></option>
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
