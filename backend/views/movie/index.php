<?php
	$this->title = '電影配置';
	use backend\views\myasset\PublicAsset;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	
	PublicAsset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>



<!-- content start -->
<div class="r content">
	<div class="topNav">影院管理&nbsp;&gt;&gt;&nbsp;<a href="#">電影配置</a></div>
	
	<div class="searchResult">
		<input type="hidden" id="pag_input" value="<?php echo $pag;?>" />
		<?php
		$form = ActiveForm::begin([
			'id'=>'movie_index_form',
			'action'=>'delete',
			'method'=>'post',
			'enableClientValidation'=>false,
			'enableClientScript'=>false
		]);
		?>
		<table id="movie_table">
			<thead>
				<tr>
					<th><input type="checkbox"></input></th>
					<th>序号</th>
					<th>電影名</th>
					<th>封面圖片</th>
					<th>上映時間</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $key=>$row) {?>
				<tr>
					<td><input type="checkbox" name="ids[]" value="<?php echo $row['movie_id']?>"></input></td>
					<td><?php echo ($key+1)?></td>
					<td><?php echo $row['movie_name']?></td>
					<td><img src="<?php echo  Yii::$app->params['img_url'].'/'. $row['img_url']?>" align="absmiddle" width="90" height="50"/></td>
					<td><?php echo $row['on_time']?></td>
					<td><?php echo $row['status']==1?"啓用":"禁用";?></td>
					<td class="op_btn">
						<a href="<?php echo Url::toRoute(['movie/edit','id'=>$row['movie_id']]);?>"><img src="<?php echo $baseUrl; ?>images/write.png"></a>
						<a id="<?php echo $row['movie_id']?>" class="delete"><img src="<?php echo $baseUrl; ?>images/delete.png"></a>
					</td>
				</tr>
				<?php }?>
			</tbody>
		</table>
		<?php
			ActiveForm::end();
		?>
		<p class="records">记录数:<em><?php echo $count;?></em></p>
		<div class="btn">
			<a href="<?php echo Url::toRoute(['movie/add']);?>"><input type="button" value="添加"></input></a>
			<input id="del_submit" type="button" value="<?php echo yii::t('app','删除选择项')?>"></input>
		</div>

		<!-- 分页 -->
       <div class="center" id="page_div"> </div>
	</div>
</div>
<!-- content end -->


<script type="text/javascript">
window.onload = function(){


    <?php $total = (int)ceil($count/10);
	   if($total >1){
	?>
		$('#page_div').jqPaginator({
		    totalPages: <?php echo $total;?>,
		    visiblePages: 10,
		    currentPage: 1,
		    wrapper:'<ul class="pagination"></ul>',
		    first: '<li class="first"><a href="javascript:void(0);">首页</a></li>',
		    prev: '<li class="prev"><a href="javascript:void(0);">«</a></li>',
		    next: '<li class="next"><a href="javascript:void(0);">»</a></li>',
		    last: '<li class="last"><a href="javascript:void(0);">尾页</a></li>',
		    page: '<li class="page"><a href="javascript:void(0);">{{page}}</a></li>',
		    onPageChange: function (num, type) {
		    	var this_page = $("input#pag_input").val();
		    	if(this_page==num){$("input#pag_input").val('fail');return false;}

		    	$.ajax({
	                url:"<?php echo Url::toRoute(['getmoviepage']);?>",
	                type:'get',
	                data:'pag='+num,
	             	dataType:'json',
	            	success:function(data){
	                	var str = '';
	            		if(data != 0){
	    	                $.each(data,function(key){
	    	                	str += '<tr>';
								str += '<td><input type="checkbox" name="ids[]" value="'+data[key]['movie_id']+'"></input></td>';
								str += '<td>'+(key+1)+'</td>';
								str += '<td>'+data[key]['movie_name']+'</td>';
								str += "<td><img src="+"<?php echo Yii::$app->params['img_url']?>"+"/"+data[key]['img_url']+ " align='absmiddle' width='90' height='50'/></td>"
								str += '<td>'+data[key]['on_time']+'</td>';
								var state = data[key]['status']==1?"啓用":"禁用";
								str += '<td>'+state+'</td>';
								str += '<td class="op_btn">';
								str += '<a href="<?php echo Url::toRoute(['movie/edit']);?>?id='+data[key]['movie_id']+'"><img src="<?php echo $baseUrl; ?>images/write.png" ></a>';
								str += '<a class="delete" id="'+data[key]['movie_id']+'" ><img src="<?php echo $baseUrl; ?>images/delete.png"></a>';
								str += '</td>';
								str += '</tr>';

	                          });
	    	                $("table#movie_table > tbody").html(str);
	    	            }
	            	}
	            });

	       	// $('#text').html('当前第' + num + '页');
	    	}
		});
	<?php }?>


	//delete删除确定button
	$(document).on('click',"#promptBox > .btn .confirm_but",function(){
		var val = $(this).attr('id');
		location.href="<?php echo Url::toRoute(['delete']);?>"+"?id="+val;
	});

	//delete删除确定button
	$(document).on('click',"#promptBox > .btn .confirm_but_more",function(){
		$("form#movie_index_form").submit();
	});

	$(document).on('click',"#promptBox >span.op,#promptBox > .btn .cancel_but",function(){
 	   $("#seleteselect").val("");
 	   $(".ui-widget-overlay").removeClass("ui-widget-overlay");//移除遮罩效果
 	   $("#promptBox").hide();
	});

}
</script>