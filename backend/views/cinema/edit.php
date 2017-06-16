<?php
	$this->title = '影院信息';
	use backend\views\myasset\PublicAsset;
	use yii\helpers\Url;
	use backend\views\myasset\ThemeAssetUeditor;
	use backend\views\myasset\ThemeAssetDate;
	use yii\widgets\ActiveForm;
	use backend\components\Helper;
	
	
	PublicAsset::register($this);
	ThemeAssetUeditor::register($this);
	ThemeAssetDate::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>
<style>
	/*Editor*/
    #desc{ display: inline-block; width: 50%; vertical-align: top; }
	ul.choose-ul{
		list-style: none;
		margin: 0px;
		padding: 0px;
		overflow: hidden;
		position: relative;
	    top: 1px;
	}
	ul li.choose-ul-li{
		width: 100px;
		list-style: none;
		display: inline-block;
		background: #3f7fcf;
		line-height: 35px;
		float: left;
		text-align: center;
		cursor: pointer;
		border: 1px solid #e0e9f4;
		border-bottom: 0px;
		color: #fff;
	}
	.curr-choose{
		color: #585858 !important;
		background: #fff !important;
	}
	.choose-div-content{
		border: 1px solid #e0e9f4;
		width: 100%;
		/*height:200px;*/
		padding-bottom: 20px;
	}

</style>
<style type="text/css">
	div.apartment-box-div{
		margin-left: 1%;
	}
	span.add_btn_tr{
		display: inline-block;
		width:80px;
		height: 35px;
		line-height: 35px;
		background: #3f7fcf;
		margin-top:20px;
		text-align: center;
		cursor: pointer;
	}
	table.table-box-apartment{
		width: 99%;
		/*margin: auto;*/
	}
	table.table-box-apartment th{
		border: 0px;

	}
	table.table-box-apartment input[type='text']{
		width:150px;
		line-height: 35px;
		height:35px;
		box-sizing: border-box;
	}
	table.table-box-apartment .div-attr-big-box{
		display: inline-block;
		width: 500px;
		height:120px;
		overflow-y: auto;
		border: 1px solid #e0e9f4;
		text-align: left;
	}
	table.table-box-apartment .div-attr-big-box .attr-span-box{
		display: inline-block;
		width:115px;
		padding: 2px;

	}
	table.table-box-apartment select{
		width: 100px;
	}
	table.table-box-apartment thead th{
		padding: 5px 0px;
	}
	table.table-box-apartment tbody td{
		padding: 10px;
	}
</style>


<!-- content start -->
<div class="r content">
	<div class="topNav">影院管理&nbsp;&gt;&gt;&nbsp;
	<a href="<?php echo Url::toRoute(['cinema/index']);?>">影院配置</a>&nbsp;&gt;&gt;&nbsp;
	<a href="#">编辑影院信息</a>
	</div>
	
	<div class="searchResult">
		<input type='hidden' name="cinema_id" value="<?php echo $cinema_basic['cinema_id'] ?>" />
		<ul class="choose-ul">
			<li class="choose-ul-li <?php echo $table==1?"curr-choose":'' ?>">基本信息</li>
			<li class="choose-ul-li <?php echo $table==2?"curr-choose":'' ?>">大廳配置</li>
			<li class="choose-ul-li <?php echo $table==3?"curr-choose":'' ?>">上映電影</li>
		</ul>	
		
		
		<!-- table1 start -->
		<div class="choose-div-content  <?php echo $table!=1?"hidden":'' ?> ">
		<?php
			$form = ActiveForm::begin([
				'id'=>'cinema_basic_form',
				'action'=>'cinemabasicedit',
				'method'=>'post',
	            'options' =>['class'=> 'cinema_basic_form'],
				'enableClientValidation'=>false,
				'enableClientScript'=>false
			]);
		?>
			<input type='hidden' name="cinema_id" value="<?php echo $cinema_basic['cinema_id'] ?>" />
			
			<p>
				<span>影院名：</span>
				<input type="text" name="cinema_name" value="<?php echo $cinema_basic['cinema_name']?>"/>
				<em class="required_tips">*</em>
			</p>
			<p>
				<span>影院電話：</span>
				<input type="text" name="cinema_phone" value="<?php echo $cinema_basic['cinema_phone']?>" />
				<em class="required_tips">*</em>
			</p>
			<p>
				<span>影院地址：</span>
				<input type="text" name="cinema_address" value="<?php echo $cinema_basic['cinema_address']?>"/>
				<em class="required_tips">*</em>
			</p>
			<p>
				<span>最低价格：</span>
				<input type="text" name="low_price" value="<?php echo $cinema_basic['low_price']?>" />
				<em class="required_tips">*</em>
			</p>
			<p>
				<span>工作时间：</span>
				<input type="text" name="cinema_work_time" value="<?php echo $cinema_basic['cinema_work_time']?>" />
				<em class="required_tips">*</em>
			</p>
			<p>
				<span>狀態：</span>
				<select name="status">
					<option value="1" <?php echo $cinema_basic['status']==1?"selected='selected'":"" ?>>啓用</option>
					<option value="0" <?php echo $cinema_basic['status']==0?"selected='selected'":"" ?>>禁用</option>
				</select>
			</p>
			
			<div class="btn">
				<input type="submit" value="保存"></input>
				<a href="<?php echo Url::toRoute(['cinema/index']);?>"><input type="button" value="返回"></input></a>
			</div>

		<?php ActiveForm::end();?>
		</div>
		<!-- table1 end -->
		
		
		
		<!-- table2 start-->
		<div class="choose-div-content <?php echo $table!=2?"hidden":'' ?>">
		<?php
			$form = ActiveForm::begin([
				'id'=>'room_basic_form',
				'action'=>'roombasicsave',
				'method'=>'post',
	            'options' =>['class'=> 'room_basic_form'],
				'enableClientValidation'=>false,
				'enableClientScript'=>false
			]);
		?>
			<input type='hidden' name="cinema_id" value="<?php echo $cinema_basic['cinema_id'] ?>" />
	
			<div class="apartment-box-div service-box-div">
			<span class="add_btn_tr add_room_tr">添加</span>
			<table class="table-box-apartment room_table">
				<thead>
				<tr>
					<th>大廳名</th>
					<th>大廳類型</th>
					<th>總共座位</th>
					<th>可賣座位</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
				</thead>
				<tbody>
				<?php
					$room_number = '';
					foreach($cinema_room as $k=>$row){
						$room_number .=  ($k+1).',';
				?>
					<tr>
						<td>
							<input type="hidden" name="room_attr[]" value="<?php echo $row['id'] ?>" />
							<select class="room_name_selelct" name="room_name[]" style="width:130px;">
							<option value="0">請選擇</option>
							<?php foreach($room as $val){?>
								<option value="<?php echo $val['room_id'] ?>" <?php echo $row['room_id']==$val['room_id']?"selected='selected'":"" ?> ><?php echo $val['room_name'] ?></option>
							<?php }?>
							</select>
						</td>
						
						<td>
						<select name="room_type[]">
							<option value="1" <?php echo $row['room_type']=='1'?"selected='selected'":""?>>類型1</option>
							<option value="2" <?php echo $row['room_type']=='2'?"selected='selected'":""?>>類型2</option>
						</select>
						</td>
						
						<td>
							<input style="width: 80px;" type="text" value="<?php echo $row['total_seat'];?>" placeholder="總座位" name="total_seat[]" maxlength="4" onkeyup="clearNoInt(this)" onblur="clearNoInt(this)" />
						</td>
						
						<td>
							<input style="width: 80px;" type="text" value="<?php echo $row['sale_seat'];?>" placeholder="可賣座位" name="sale_seat[]" maxlength="4" onkeyup="clearNoInt(this)" onblur="clearNoInt(this)" />
						</td>
						<td>
							<select name="r_status[]">
								<option value="1" <?php echo $row['status']=='1'?"selected='selected'":"" ?> >啓用</option>
								<option value="0" <?php echo $row['status']=='0'?"selected='selected'":"" ?> >禁用</option>
							</select>
						</td>
						<td>
							<a  id="r<?php echo ($k+1)?>_<?php echo $row['id'] ?>"  class="delete"><img src="<?php echo $baseUrl; ?>images/delete.png"></a>
						</td>
					</tr>
					<?php }?>
				</tbody>
			</table>
			</div>
			
			<div class="btn">
					<input type="submit" value="保存"></input>
					<a href="<?php echo Url::toRoute(['cinema/index']);?>"><input type="button" value="返回"></input></a>
				</div>

			<?php ActiveForm::end();?>
		</div>
		<!-- table2 end -->
		
		
		
		
		<!-- table3 start-->
		<div class="choose-div-content  <?php echo $table!=3?"hidden":'' ?>">
			<?php
			$form = ActiveForm::begin([
				'id'=>'movie_form',
				'action'=>'moviesave',
				'method'=>'post',
	            'options' =>['class'=> 'movie_form'],
				'enableClientValidation'=>false,
				'enableClientScript'=>false
			]);
			?>
			<input type='hidden' name="cinema_id" value="<?php echo $cinema_basic['cinema_id'] ?>" />

			<div class="apartment-box-div">
				<span class="add_btn_tr add_movie_tr">添加</span>
				<table class="table-box-apartment movie_table">
					<thead>
					<tr>
						<th>大廳名</th>
						<th>電影名</th>
						<th>電影版本</th>
						<th>價格</th>
						<th>影院價格</th>
						<th>開始時間</th>
						<th>結束時間</th>
						<th>状态</th>
						<th>操作</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach($movie_show as $k=>$row){ ?>
						<tr>
							<td>
								<input type="hidden" name="movie_show_attr[]" value="<?php echo $row['id'] ?>" />
								<select class="room_name_selelct" name="room_name[]" style="width:80px;">
								<option value="0">請選擇</option>
								<?php foreach($room_select as $val){?>
									<option value="<?php echo $val['room_id'] ?>" <?php echo $row['room_id']==$val['room_id']?"selected='selected'":"" ?> ><?php echo $val['room_name'] ?></option>
								<?php }?>
								</select>
							</td>
							
							
							<td>
								<select class="movie_name_select" name="movie_name[]" style="width:100px;">
								<option value="0">請選擇</option>
								<?php foreach($movie as $val){?>
									<option value="<?php echo $val['movie_id'] ?>" <?php echo $row['movie_id']==$val['movie_id']?"selected='selected'":"" ?> ><?php echo $val['movie_name'] ?></option>
								<?php }?>
								</select>
							</td>
							
							
							
							<td>
								<select class="movie_type_name_select" name="movie_type[]" style="width:80px;">
								<option value="0">請選擇</option>
								<?php foreach($movie_type as $val){?>
									<option value="<?php echo $val['type_id'] ?>" <?php echo $row['type_id']==$val['type_id']?"selected='selected'":"" ?> ><?php echo $val['type_name'] ?></option>
								<?php }?>
								</select>
							</td>
							
							<td><input style="width: 40px;" type="text" value="<?php echo $row['price'];?>" placeholder="價格" name="price[]" maxlength="4" onkeyup="clearNoInt(this)" onblur="clearNoInt(this)" /></td>
							<td><input style="width: 40px;" type="text" value="<?php echo $row['real_price'];?>" placeholder="影院定價" name="real_price[]" maxlength="4" onkeyup="clearNoInt(this)" onblur="clearNoInt(this)" /></td>
							<td><input style="width: 120px" type="text" id="s_time<?php echo($k+1)?>" name="s_time[]" placeholder="<?php echo yii::t('app','please choose')?>" value="<?php echo empty($row['time_begin'])?"":$row['time_begin'];?>" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en',maxDate:'#F{$dp.$D(\'e_time<?php echo($k+1)?>\')}'})" class="Wdate" ></input></td>
							<td><input style="width: 120px" type="text" id="e_time<?php echo($k+1)?>" name="e_time[]" placeholder="<?php echo yii::t('app','please choose')?>" value="<?php echo empty($row['time_end'])?"":$row['time_end'];?>" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en',minDate:'#F{$dp.$D(\'s_time<?php echo($k+1)?>\')}',startDate:'#F{$dp.$D(\'s_time<?php echo($k+1)?>\')}'})" class="Wdate" ></input></td>
							
							<td>
								<select name="m_status[]">
									<option value="1" <?php echo $row['status']==1?"selected='selected'":'' ?> >啓用</option>
									<option value="0"  <?php echo $row['status']==0?"selected='selected'":'' ?> >禁用</option>
								</select>
							</td>
							
							<td>
								<a href="<?php echo Url::toRoute(['cinema/seat','showid'=>$row['id']]);?>"><img src="<?php echo $baseUrl; ?>images/text.png"></a>
								<a id="m<?php echo ($k+1)?>_<?php echo $row['id'] ?>" class="delete"><img src="<?php echo $baseUrl; ?>images/delete.png"></a>
							</td>
						</tr>
						<?php }?>
						
					</tbody>
				</table>
				</div>
				
				<!-- 分页 -->
				<div class="center" id="movie_page_div"> </div>
      			 
				<div class="btn">
						<input type="submit" value="保存"></input>
						<a href="<?php echo Url::toRoute(['cinema/index']);?>"><input type="button" value="返回"></input></a>
					</div>

				<?php ActiveForm::end();?>
		</div>
		<!-- table3 end-->

	</div>
</div>
<!-- content end -->






<script type="text/javascript">
window.onload = function(){
	
	var room_index_tr = "<?php echo count($cinema_room) ?>";
	var movie_index_tr = "<?php echo count($movie_show)?>";
	
	$("ul.choose-ul li.choose-ul-li").on('click',function(){
		var obj = $(this);
		var cinema_id = $("input[type='hidden'][name='cinema_id']").val();
		var index = $("ul.choose-ul li.choose-ul-li").index(this);
		var is_checked = $(this).hasClass('curr-choose');
		if(is_checked){
			return false;
		}else{
			if(cinema_id=='' &&　(index == 1 || index == 2)){
				return false;
			}

			$("ul.choose-ul li.choose-ul-li").each(function(e){
				$(this).removeClass('curr-choose');
			});
			$("div.choose-div-content").each(function(e){
				$(this).addClass('hidden');
			});
			obj.addClass('curr-choose');
			$("div.choose-div-content").eq(index).removeClass('hidden');

		}
	});

	
	//大廳配置添加  (table2)
	$("span.add_room_tr").on('click',function(){
		
		var str = '';
		str += '<tr>';
		str += '<td>';
		str += '<input type="hidden" name="room_attr[]" value="" />';
		str += '<select class="room_name_selelct" name="room_name[]" style="width:130px;">';
		str += '<option value="0">请选择</option>';
		<?php foreach($room as $row){?>
			str += '<option value="<?php echo $row['room_id'] ?>"><?php echo $row['room_name'] ?></option>';
		<?php }?>
		str += '</select>';
		str += '</td>';
		str +='<td>';
		str +='<select name="room_type[]">';
		str +='<option value="1">類型1</option>';
		str +='<option value="2">類型2</option>';
		str +='</select>';
		str += '</td>';
		str += '<td>';
		str += '<input style="width: 80px;" value="" type="text" value="" placeholder="總座位" name="total_seat[]" maxlength="4" onkeyup="clearNoInt(this)" onblur="clearNoInt(this)" />';
		str += '</td>';
		str += '<td>';
		str += '<input style="width: 80px;" value="" type="text" value="" placeholder="總座位" name="sale_seat[]" maxlength="4" onkeyup="clearNoInt(this)" onblur="clearNoInt(this)" />';
		str += '</td>';
		str += '<td>';
		str += '<select name="r_status[]">';
		str += '<option value="1">啓用</option>';
		str += '<option value="0">禁用</option>';
		str += '</select>';
		str += '</td>';
		str += '<td>';
		str += '<a  id="r'+(parseInt(room_index_tr)+1)+'_"  class="delete"><img src="<?php echo $baseUrl; ?>images/delete.png"></a>';
		str += '</td>';
		str += '</tr>';
		
		var room_number = $("input[type='hidden'][name='room_number']").val();
		room_number = room_number + (parseInt(room_index_tr)+1) +',';
		$("input[type='hidden'][name='room_number']").val(room_number);
		$("table.room_table tbody").prepend(str);

		room_index_tr = parseInt(room_index_tr) + 1;
	});



	

	//上映電影 (table3)
	$("span.add_movie_tr").on('click',function(){
		var str = '';
		str += '<tr>';
		str += '<td>';
		str += '<input type="hidden" name="movie_show_attr[]" value="" />';
		str += '<select class="room_name_selelct" name="room_name[]" style="width:80px;">';
		str += '<option value="0">請選擇</option>';
		<?php foreach($room_select as $val){?>
			str +='<option value="<?php echo $val['room_id'] ?>"><?php echo $val['room_name'] ?></option>';
		<?php }?>
		str +='</select>';
		str +='</td>';	

		
		str +='<td>';
		str +='<select class="movie_name_select" name="movie_name[]" style="width:100px;">';
		str +='<option value="0">請選擇</option>';
		<?php foreach($movie as $val){?>
			str +='<option value="<?php echo $val['movie_id'] ?>"><?php echo $val['movie_name'] ?></option>';
		<?php }?>
		str +='</select>';
		str +='</td>';

		str +='<td>';
		str +='<select class="movie_type_name_select" name="movie_type[]" style="width:80px;">';
		str +='<option value="0">請選擇</option>';
		<?php foreach($movie_type as $val){?>
			str +='<option value="<?php echo $val['type_id'] ?>"><?php echo $val['type_name'] ?></option>';
		<?php }?>
		str +='</select>';
		str +='</td>';

		
		str +='<td><input style="width: 40px;" type="text" value="" placeholder="價格" name="price[]" maxlength="4" onkeyup="clearNoInt(this)" onblur="clearNoInt(this)" /></td>';
		str +='<td><input style="width: 40px;" type="text" value="" placeholder="影院定價" name="real_price[]" maxlength="4" onkeyup="clearNoInt(this)" onblur="clearNoInt(this)" /></td>';
		str +='<td><input style="width: 120px;" type="text" id="s_time'+(parseInt(movie_index_tr)+1)+'" name="s_time[]" placeholder="please choose" value="" readonly onfocus="WdatePicker({  dateFmt:\'yyyy-MM-dd HH:mm:ss\',lang:\'en\',maxDate:\'#F{$dp.$D(\\\'e_time'+(parseInt(movie_index_tr)+1)+'\\\')}\'})" class="Wdate" ></input></td>';
		str +='<td><input style="width: 120px;" type="text" id="e_time'+(parseInt(movie_index_tr)+1)+'" name="e_time[]" placeholder="please choose" value="" readonly onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\',lang:\'en\',minDate:\'#F{$dp.$D(\\\'s_time'+(parseInt(movie_index_tr)+1)+'\\\')}\',startDate:\'#F{$dp.$D(\\\'s_time'+(parseInt(movie_index_tr)+1)+'\\\')}\'})" class="Wdate" ></input></td>';
		str +='<td>';
		str +='<select name="m_status[]">';
		str +='<option value="1">啓用</option>';
		str +='<option value="0">禁用</option>';
		str +='</select>';
		str +='</td>';	
		str +='<td>';
		str +='<a id="m'+(parseInt(movie_index_tr)+1)+'_" class="delete"><img src="<?php echo $baseUrl; ?>images/delete.png"></a>';
		str +='</td>';
		str +='</tr>';
		
		$("table.movie_table tbody").prepend(str);
		movie_index_tr = parseInt(movie_index_tr) + 1;

	});




	//上映電影 (table3)  分页
	<?php $movie_count = (int)ceil($movie_count/10);
	   if($movie_count >1){
	?>
	$('#movie_page_div').jqPaginator({
	    totalPages: <?php echo $movie_count;?>,
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
		                data:'pag='+num+'&id='+<?php echo $cinema_basic['cinema_id'];?>,
		             	dataType:'json',
		            	success:function(data){
		                	var str = '';
		            		if(data != 0){
		    	                $.each(data,function(key){
		    	            		str += '<tr>';
		    	            		str += '<td>';
		    	            		str += '<input type="hidden" name="movie_show_attr[]" value="'+data[key]['id']+'" />';
		    	            		str += '<select class="room_name_selelct" name="room_name[]" style="width:80px;">';
		    	            		str += '<option value="0">請選擇</option>';
		    	            		
		    	            		<?php foreach($room_select as $val){?>
		    	            			str +='<option value="<?php echo $val['room_id'] ?>"';
		    	            			if(data[key]['room_id'] == '<?php echo $val['room_id']?>'){
											str += "selected='selected'";
			    	            		}
		    	            			str +='><?php echo $val['room_name'] ?></option>';
		    	            		<?php }?>
		    	            		
		    	            		str +='</select>';
		    	            		str +='</td>';	

		    	            		
		    	            		str +='<td>';
		    	            		str +='<select class="movie_name_select" name="movie_name[]" style="width:100px;">';
		    	            		str +='<option value="0">請選擇</option>';
		    	            		<?php foreach($movie as $val){?>
		    	            			str +='<option value="<?php echo $val['movie_id'] ?>"';
		    	            			if(data[key]['movie_id'] == '<?php echo $val['movie_id']?>'){
											str += "selected='selected'";
			    	            		}
		    	            			str +='><?php echo $val['movie_name'] ?></option>';
	    	            			<?php }?>

		    	            		str +='</select>';
		    	            		str +='</td>';


		    	            		str +='<td>';
		    	            		str +='<select class="movie_type_name_select" name="movie_type[]" style="width:80px;">';
		    	            		str +='<option value="0">請選擇</option>';
		    	            		<?php foreach($movie_type as $val){?>
		    	            			str +='<option value="<?php echo $val['type_id'] ?>"';
		    	            			if(data[key]['type_id'] == '<?php echo $val['type_id']?>'){
											str += "selected='selected'";
			    	            		}
		    	            			str +='><?php echo $val['type_name'] ?></option>';
	    	            			<?php }?>

		    	            		str +='</select>';
		    	            		str +='</td>';		
		    	            		str +='<td><input style="width: 40px;" type="text" value="'+data[key]['price']+'" placeholder="價格" name="price[]" maxlength="4" onkeyup="clearNoInt(this)" onblur="clearNoInt(this)" /></td>';
		    	            		str +='<td><input style="width: 40px;" type="text" value="'+data[key]['real_price']+'" placeholder="影院定價" name="real_price[]" maxlength="4" onkeyup="clearNoInt(this)" onblur="clearNoInt(this)" /></td>';
		    	            		str +='<td><input type="text" id="s_time'+(key+1)+'" name="s_time[]" placeholder="please choose" value="'+data[key]['time_begin']+'" readonly onfocus="WdatePicker({  dateFmt:\'yyyy-MM-dd HH:mm:ss\',lang:\'en\',maxDate:\'#F{$dp.$D(\\\'e_time'+(key+1)+'\\\')}\'})" class="Wdate" ></input></td>';
		    	            		str +='<td><input type="text" id="e_time'+(key+1)+'" name="e_time[]" placeholder="please choose" value="'+data[key]['time_end']+'" readonly onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\',lang:\'en\',minDate:\'#F{$dp.$D(\\\'s_time'+(key+1)+'\\\')}\',startDate:\'#F{$dp.$D(\\\'s_time'+(key+1)+'\\\')}\'})" class="Wdate" ></input></td>';
		    	            		str +='<td>';
		    	            		str +='<select name="m_status[]">';
		    	            		var status_enable  = data[key]['status']==1?"selected='selected'":"";
		    	            		var status_disable = data[key]['status']==0?"selected='selected'":"";
		    	            		str +='<option value="1"'+ status_enable+'>啓用</option>';
		    	            		str +='<option value="0"'+status_disable+'>禁用</option>';
		    	            		str +='</select>';
		    	            		str +='</td>';	
		    	            		str +='<td>';
		    	            		str +='<a href="<?php echo Url::toRoute(['cinema/seat']);?>?showid='+data[key]['id']+'"><img src="<?php echo $baseUrl; ?>images/text.png"></a>';
		    	            		str +='<a id="m'+(key+1)+'_'+data[key]['id']+'" class="delete"><img src="<?php echo $baseUrl; ?>images/delete.png"></a>';
		    	            		str +='</td>';
		    	            		str +='</tr>';
		                          });
		    	                $("table.movie_table tbody").html(str);
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
		var id = val.split('_')[1];
		var first_str = val.substr(0,1);
		
		if(first_str == 'r'){	 
			//table2 信息刪除
			if(id!=''){
				$.ajax({
				url:"<?php echo Url::toRoute(['cinema/deleteroombaisc']);?>",
				type:'POST',
				dataType:'json',
				data:'id='+id,
				async:false,
				success:function(data){	
					if(data == 0){
						Alert("删除失败");
					}else{
						$("table.room_table tbody td ").find("a[id='"+val+"']").parents('tr').remove();
					}
				}
				});
			}else{
				$("table.room_table tbody td ").find("a[id='"+val+"']").parents('tr').remove();
			}
			
		}else if(first_str == 'm'){
			//table3 信息刪除 
			if(id!=''){
				$.ajax({
				url:"<?php echo Url::toRoute(['cinema/deletemovieattr']);?>",
				type:'POST',
				dataType:'json',
				data:'id='+id,
				async:false,
				success:function(data){	
					if(data == 0){
						Alert("删除失败");
					}else{
						$("table.movie_table tbody td ").find("a[id='"+val+"']").parents('tr').remove();
					}
				}
				});
			}else{
				$("table.movie_table tbody td ").find("a[id='"+val+"']").parents('tr').remove();
			}
		}
		
		$(".ui-widget-overlay").removeClass("ui-widget-overlay");//移除遮罩效果
 	    $("#promptBox").hide();
	});

	$(document).on('click',"#promptBox >span.op,#promptBox > .btn .cancel_but",function(){
 	   
 	   $(".ui-widget-overlay").removeClass("ui-widget-overlay");//移除遮罩效果
 	   $("#promptBox").hide();
	});



	
	//基本信息保存驗證
	$("form#cinema_basic_form").submit(function(){
		var error_str = "<em class='error_tips'>必填字段</em>";
		var flag = 1;
		//验证文本框不能为空
		$("form#cinema_basic_form input[type='text']").each(function(){
			$(this).parents('p').find("em.error_tips").remove();
			var this_val = $(this).val();
			if(this_val == ''){
				$(this).parents('p').append(error_str);flag = 0;return false;
			}
		});
		if(flag == 0){return false;}
	});

	
	
	//大廳配置驗證
	$("form#room_basic_form").submit(function(){
		// var error_str = "<em class='error_tips'>必填字段</em>";
		var cinema_id = $("form#room_basic_form input[type='hidden'][name='cinema_id']").val();
		var len = $("form#room_basic_form table tbody tr").length;
		if(len == 0){return false;}
		var flag = 1;
		var room_arr = new Array();
		
		//验证是否选择了大廳
		$("form#room_basic_form table select[class='room_name_selelct']").each(function(){
			var this_val = $(this).val();
			// alert(this_val);
			if(this_val == 0){
				Alert("請先選擇大廳");flag=0;return false;
			}else{
				if($.inArray(this_val,room_arr)>=0){
					Alert("該大廳已經添加過了");flag=0;return false;
				}
				room_arr.push(this_val);
			}
		});

		
		//验证文本框不能为空
		$("form#room_basic_form input[type='text']").each(function(){
			$(this).parents('p').find("em.error_tips").remove();
			var this_val = $(this).val();
			if(this_val == ''){
				Alert("請填寫座位");flag=0;return false;
			}

		});

		if(flag == 0){return false;}
		
	});

	
	//上映電影保存
	$("form#movie_form").submit(function(){
		var error_str = "<em class='error_tips'>必填字段</em>";
		var len = $("form#movie_form table tbody tr").length;
		if(len == 0){return false;}
		var flag = 1;

		
		//验证文本框不能为空
		$("form#movie_form input[type='text']").each(function(){
			$(this).parents('p').find("em.error_tips").remove();
			var this_val = $(this).val();
			if(this_val == ''){
				Alert("請先填寫必要的內容");flag=0;return false;
			}
		});


		//验证是否选择了大廳
		$("form#movie_form table select[class='room_name_selelct']").each(function(){
			var this_val = $(this).val();
			// alert(this_val);
			if(this_val == 0){
				Alert("請先選擇大廳");flag=0;return false;
			}
		});



		//验证是否选择了電影
		$("form#movie_form table select[class='movie_name_select']").each(function(){
			var this_val = $(this).val();
			// alert(this_val);
			if(this_val == 0){
				Alert("請先選擇電影");flag=0;return false;
			}
		});

		//验证是否选择了電影版本
		$("form#movie_form table select[class='movie_type_name_select']").each(function(){
			var this_val = $(this).val();
			// alert(this_val);
			if(this_val == 0){
				Alert("請先選擇電影版本");flag=0;return false;
			}
		});
		
		
		if(flag == 0){return false;}

	});

}



</script>