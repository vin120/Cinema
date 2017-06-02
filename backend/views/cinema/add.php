<?php
	$this->title = '影院';
	use backend\views\myasset\PublicAsset;
	use yii\helpers\Url;
	use backend\views\myasset\ThemeAssetUeditor;
	use yii\widgets\ActiveForm;
	
	PublicAsset::register($this);
	ThemeAssetUeditor::register($this);
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
		background-color:#ccc;
		cursor:not-allowed;
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
<!-- content start -->
<div class="r content">
	<div class="topNav">影院管理&nbsp;&gt;&gt;&nbsp;
	<a href="<?php echo Url::toRoute(['cinema/index']);?>">影院配置</a>&nbsp;&gt;&gt;&nbsp;
	<a href="#">添加影院信息</a>
	</div>
	
	<div class="searchResult">
		<input type='hidden' name="cinema_id" value="" />

		<ul class="choose-ul">
			<li class="choose-ul-li curr-choose">基本信息</li>
			<li class="choose-ul-li">大廳配置</li>
			<li class="choose-ul-li">上映電影</li>
		</ul>	

		<div class="choose-div-content">
		<?php
			$form = ActiveForm::begin([
				'id'=>'cinema_basic_form',
				'action'=>'add',
				'method'=>'post',
	            'options' =>['class'=> 'cinema_basic_form'],
				'enableClientValidation'=>false,
				'enableClientScript'=>false
			]);
		?>
			<p>
				<span>影院名：</span>
				<input type="text" name="cinema_name"/>
				<em class="required_tips">*</em>
			</p>
			<p>
				<span>影院電話：</span>
				<input type="text" name="cinema_phone"  />
				<em class="required_tips">*</em>
			</p>
			
			<p>
				<span>影院地址：</span>
				<input type="text" name="cinema_address" />
				<em class="required_tips">*</em>
			</p>
			<p>
				<span>最低价格：</span>
				<input type="text" name="low_price"  />
				<em class="required_tips">*</em>
			</p>
			<p>
				<span>狀態：</span>
				<select name="state">
					<option value="1">啓用</option>
					<option value="0">禁用</option>
				</select>
			</p>
			

			<div class="btn">
				<input type="submit" value="保存"></input>
				<a href="<?php echo Url::toRoute(['cinema/index']);?>"><input type="button" value="返回"></input></a>
			</div>

		<?php ActiveForm::end();?>
		</div>
		<div class="choose-div-content hidden">
		
		</div>
		<div class="choose-div-content hidden">
		</div>


	</div>
</div>
<!-- content end -->

<script type="text/javascript">
window.onload = function(){
	

	$("ul.choose-ul li.choose-ul-li").on('click',function(){
		var obj = $(this);
		var index = $("ul.choose-ul li.choose-ul-li").index(this);
		var is_checked = $(this).hasClass('curr-choose');
		if(is_checked){
			return false;
		}else{
			if(index == 1 || index == 2){
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



	//表单保存
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


}
</script>