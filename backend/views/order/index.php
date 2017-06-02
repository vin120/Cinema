<?php
	$this->title = '訂單管理';
	use backend\views\myasset\PublicAsset;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	
	PublicAsset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>

<!-- content start -->
<div class="r content">
	<div class="topNav">訂單管理&nbsp;&gt;&gt;&nbsp;<a href="#">訂單信息</a></div>
	
	<div class="searchResult">
		<input type="hidden" id="pag_input" value="<?php echo $pag;?>" />
		<table id="order_table">
			<thead>
				<tr>
					<th>序號</th>
					<th>訂單號</th>
					<th>聯繫電話</th>
					<th>數量</th>
					<th>下單時間</th>
					<th>支付狀態</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $key =>$value):?>
				<tr>
					<td><?php echo ($key+1)?></td>
					<td><?php echo $value['order_number']?></td>
					<td><?php echo $value['phone']?></td>
					<td><?php echo $value['count']?></td>
					<td><?php echo $value['order_time']?></td>
					<td><?php echo $value['status']==1 ? "<div style='color:green'>已支付</div>" : ($value['status'] == 2 ? "<div style='color:red'>已過期</div>" :"<div style='color:red'>未支付</div>")?></td>
					<td>
						<?php if ($value['status'] == 1):?>
						<a href="<?php echo Url::toRoute(['order/detail','id'=>$value['id']]);?>"><img src="<?php echo $baseUrl; ?>images/text.png"></a>
						<?php endif;?>
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
		<p class="records">記錄數:<em><?php echo $count;?></em></p>
	
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
	                url:"<?php echo Url::toRoute(['getorderpage']);?>",
	                type:'get',
	                data:'pag='+num,
	             	dataType:'json',
	            	success:function(data){
	                	var str = '';
	            		if(data != 0){
	    	                $.each(data,function(key){

	    	                	str += '<tr>';
								str += '<td>'+(key+1)+'</td>';
								str += '<td>'+data[key]['order_number']+'</td>';
								str += '<td>'+data[key]['phone']+'</td>';
								str += '<td>'+data[key]['count']+'</td>';
								str += '<td>'+data[key]['order_time']+'</td>';
								var order_status = data[key]['status'] == 1 ? "<div style='color:green'>已支付</div>" : (data[key]['status'] == 2 ? "<div style='color:red'>已過期</div>" : "<div style='color:red'>未支付</div>");
								str += '<td>'+order_status+'</td>';								
								str += '<td>';
								if(data[key]['status']==1) {
									str += '<a href="<?php echo Url::toRoute(['order/detail']);?>?id='+data[key]['id']+'"><img src="<?php echo $baseUrl; ?>images/text.png"></a>';
								}
								str += '</td>';
								str += '</tr>';


	                          });
	    	                $("table#order_table > tbody").html(str);
	    	            }
	            	}
	            });

	       	// $('#text').html('当前第' + num + '页');
	    	}
		});
	<?php }?>
}
</script>