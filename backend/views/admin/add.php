<?php
	$this->title = '管理員信息';
	use backend\views\myasset\PublicAsset;
	use yii\helpers\Url;
	use yii\bootstrap\ActiveForm;
	
	PublicAsset::register($this);
	$baseUrl = $this->assetBundles[PublicAsset::className()]->baseUrl . '/';
?>


<style>
<!--
 .write input[type="password"] {
    height: 30px;
    width: 180px;
}
-->
</style>


<!-- content start -->
<div class="r content">
	<div class="topNav">管理員信息&nbsp;&gt;&gt;&nbsp;<a href="<?php echo Url::to('index')?>">管理員列表</a>&nbsp;&gt;&gt;&nbsp;<a href="#">添加管理員</a></div>
	<div class="searchResult">
	<div id="service_write" class=" write">
	<?php
		$form = ActiveForm::begin([
			'action' => ['add'],
			'method'=>'post',
			'id'=>'admin_add',
			'options' => ['class' => 'type_add'],
			'enableClientValidation'=>false,
			'enableClientScript'=>false,
		]);
	?>
		<div class="form">
		
			<p>
				<label>
					<span><?php echo yii::t('app','管理員姓名')?>:</span>
					<input type="text" id="admin_nickname" name="admin_nickname" ></input>
					<em class="required_tips">*</em>
				</label>
			</p>
			
			<p>
				<label>
					<span><?php echo yii::t('app','管理員帳號')?>:</span>
					<input type="text" id="admin_user" name="admin_user" ></input>
					<em class="required_tips">*</em>
				</label>
			</p>
			
			<p>
				<label>
					<span><?php echo yii::t('app','管理員密碼')?>:</span>
					<input type="password" id="admin_pwd" name="admin_pwd" ></input>
					<em class="required_tips">*</em>
				</label>
			</p>
			
			<p>
				<label>
					<span><?php echo yii::t('app','請再輸入密碼')?>:</span>
					<input type="password" id="admin_pwd2" name="admin_pwd2" ></input>
					<em class="required_tips">*</em>
				</label>
			</p>
			
			
			<p>
				<label>
					<span class='max_l'><?php echo yii::t('app','狀態')?>:</span>
					<select name="status" id=status class='input_select'>
						<option value='1' ><?php echo yii::t('app','啓用')?></option>
						<option value='0' ><?php echo yii::t('app','禁用')?></option>
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

<script type="text/javascript">
window.onload = function(){

	$("form#admin_add").submit(function(){
		var error_str = "<em class='error_tips'>必填字段</em>";
		var admin_user = $("form#admin_add input[type='text'][name='admin_user']").val();
		var admin_pwd = $("form#admin_add input[type='password'][name='admin_pwd']").val();
		var admin_pwd2 = $("form#admin_add input[type='password'][name='admin_pwd2']").val();
		var flag = 1;

		
		$("form#admin_add input[type='text']").each(function(){
			$(this).parents('p').find("em.error_tips").remove();
			var this_val = $(this).val();
			if(this_val == ''){
				$(this).parents('p').append(error_str);flag = 0;return false;
			}

		});
		if(flag == 0){return false;}
		
		$("form#admin_add input[type='password']").each(function(){
			$(this).parents('p').find("em.error_tips").remove();
			var this_val = $(this).val();
			if(this_val == ''){
				$(this).parents('p').append(error_str);flag = 0;return false;
			}

		});
		if(flag == 0){return false;}

		//验证密码长度
		if(admin_pwd.length <6){
			$("form#admin_add input[type='password'][name='admin_pwd']").parents('p').append("<em class='error_tips'>密码长度需超过6位</em>");flag = 0;return false;
		}
	

		if(admin_pwd != admin_pwd2){
			$("form#admin_add input[type='password'][name='admin_pwd2']").parents('p').append("<em class='error_tips'>密码不一致</em>");flag = 0;return false;
		}

		if(flag == 0){return false;}

		//验证账号
		$.ajax({
			url:'<?php echo Url::toRoute(['admin/verifyinfo']);?>',
			type:'POST',
			dataType:'json',
			data:'admin_user='+admin_user,
			async:false,
			success:function(data){
				var name = data['name'];
				if(name!=0){
					$("form#admin_add input[type='text'][name='admin_user']").parents('p').append("<em class='error_tips'>账户名已存在,请更换</em>");flag = 0;
				}
			}
		});
		if(flag == 0){return false;}

	});
}
</script>
