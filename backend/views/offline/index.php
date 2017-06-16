<?php
	$this->title = '線下賬單流水';
	use backend\views\myasset\PublicAsset;
	use backend\views\myasset\ThemeAssetDate;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	
	
	PublicAsset::register($this);
	ThemeAssetDate::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>


<!-- content start -->
<div class="r content" id="user_content">
	<div class="topNav"><?php echo yii::t('app','流水管理')?>&nbsp;&gt;&gt;&nbsp;<a href="#"><?php echo yii::t('app','線下流水')?></a></div>
	<?php
		$form = ActiveForm::begin([
			'method'=>'post',
			'enableClientValidation'=>false,
			'enableClientScript'=>false
		]); 
	?>
	<div class="search">
		<label>
			<span><?php echo yii::t('app','開始時間')?>:</span>
			<input type="text" id="start_time" name="start_time" value="<?php echo $start_time?>"  readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en',maxDate:'#F{$dp.$D(\'end_time\')}'})" class="Wdate"></input>
		</label>
		
		<label>
			<span><?php echo yii::t('app','結束時間')?>:</span>
			<input type="text" id="end_time"  name="end_time"    value="<?php echo $end_time?>"  readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en',minDate:'#F{$dp.$D(\'start_time\')}',startDate:'#F{$dp.$D(\'start_time\')}'})" class="Wdate"></input>
		</label>
		
		<br><br>
		
		<label>
			<span><?php echo yii::t('app','管理員')?>:</span>
			<select id="admin_id" name="admin_id">
				<option value="0"><?php echo yii::t('app','全部')?></option>
				
				<?php foreach ($admin as $row):?>
				<option value="<?php echo $row['admin_id']?>"<?php echo $row['admin_id'] == $admin_id ? "selected='selected'":'';?>><?php echo $row['admin_nickname']?></option>
				<?php endforeach;?>
            </select>
		</label>
		
		<label>
			<span><?php echo yii::t('app','電影名')?>:</span>
			<select id="movie_id" name="movie_id">
				<option value="0" ><?php echo yii::t('app','全部')?></option>
				<?php foreach ($movie as $row):?>
				<option value="<?php echo $row['movie_id']?>"<?php echo $row['movie_id'] == $movie_id ? "selected='selected'":'';?> ><?php echo $row['movie_name']?></option>
				<?php endforeach;?>
            </select>
		</label>
		
		<label>
			<span><?php echo yii::t('app','大廳名')?>:</span>
			<select id="room_id" name="room_id">
				<option value="0" ><?php echo yii::t('app','全部')?></option>
				<?php foreach ($room as $row):?>
				<option value="<?php echo $row['room_id']?>"<?php echo $row['room_id'] == $room_id ? "selected='selected'":'';?> ><?php echo $row['room_name']?></option>
				<?php endforeach;?>
            </select>
		</label>
		
		<span class="btn"><input style="cursor:pointer" type="submit" name="search" value="<?php echo yii::t('app','統計分析')?>"></input></span>
		<div style="margin-bottom:20px;">
			<span class="r"><button class="btn1" id="export">導出EXCEL</button></span>
		</div>
	</div>
	<?php 
		ActiveForm::end(); 
	?>
	
	<div class="searchResult">
	 <?php
		$form = ActiveForm::begin([
				'id'=>'off_form',
				'method'=>'post',
				'enableClientValidation'=>false,
				'enableClientScript'=>false
		]); 
	?>
	<table id="cabin_table">
		<thead>
			<tr>
                <th><?php echo yii::t('app','售票員')?></th>
                <th><?php echo yii::t('app','電影名')?></th>
                <th><?php echo yii::t('app','大廳名')?></th>
                <th><?php echo yii::t('app','座位')?></th>
                <th><?php echo yii::t('app','日期')?></th>
                <th><?php echo yii::t('app','數量')?></th>
                <th><?php echo yii::t('app','單價')?></th>
                <th><?php echo yii::t('app','總價')?></th>
            </tr>
		</thead>
		
		<tbody>
			<?php foreach ($offline_order as $row):?>
			<tr>
				<td><?php echo $row['admin_name'];?></td>
				<td><?php echo $row['movie_name']?></td>
				<td><?php echo $row['room_name']?></td>
				<td><?php echo $row['seat_names']?></td>
				<td><?php echo $row['order_time']?></td>
				<td><?php echo $row['count']?></td>
				<td><?php echo $row['price']?></td>
				<td><?php echo $row['total_money']?></td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<?php 
		ActiveForm::end(); 
	?>
	</div>
	<p class="records" style="margin:20px;float:right"><?php echo yii::t('app','總共售出 : ')?><span><?php echo $total_money ." MOP";?></span></p>
	
	
</div>
<!-- content end -->

<script type="text/javascript">
window.onload = function(){
	//点击导出export
	$("#export").on("click",function(){
		var start_time = $("#start_time").val();
		var end_time = $("#end_time").val();
		var movie_id = $("#movie_id").val();
		var room_id = $("#room_id").val();
		var admin_id = $("#admin_id").val();
		
		$.ajax({
            url:"<?php echo Url::toRoute(['export']);?>",
            type:'get',
            data:'start_time='+start_time+'&end_time='+end_time+'&movie_id='+movie_id+'&room_id='+room_id+'&admin_id='+admin_id,
            dataType:'json',
            async:false
		}).done(function(data){
		    var $a = $("<a>");
		    $a.attr("href",data.file);
		    $("body").append($a);
		    $a.attr("download",data.path);
		    $a[0].click();
		    $a.remove();
		});
		
	});
	
};
</script>