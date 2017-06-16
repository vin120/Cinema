<?php
	$this->title = '管理員信息';
	use backend\views\myasset\PublicAsset;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	
	PublicAsset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>

<!-- content start -->
<div class="r content">
	<div class="topNav">管理員信息&nbsp;&gt;&gt;&nbsp;<a href="#">管理員列表</a></div>
	
	<div class="searchResult">
		<table id="order_table">
			<thead>
				<tr>
					<th>序號</th>
					<th>管理員名稱</th>
					<th>管理員帳號</th>
					<th>狀態</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($admin as $key =>$value):?>
				<tr>
					<td><?php echo ($key+1)?></td>
					<td><?php echo $value['admin_nickname']?></td>
					<td><?php echo $value['admin_user']?></td>
					<td><?php echo $value['status']==1 ? "啓用" :"禁用"?></td>
					<td>
						<a href="<?php echo Url::toRoute(['admin/edit','id'=>$value['admin_id']]);?>"><img src="<?php echo $baseUrl; ?>images/write.png"></a>
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
		<p class="records">記錄數:<em><?php echo $count;?></em></p>
		<div class="btn">
			<a href="<?php echo Url::toRoute(['admin/add']);?>"><input type="button" value="添加管理員"></input></a>
		</div>
	
	</div>
</div>
<!-- content end -->
