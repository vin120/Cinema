$(document).ready(function() {

	//文本框点击获取焦点隐藏错误提示
	$(document).on("click","input[type='text']",function(){
		$(this).parents('p').find("em.error_tips").remove();
	});
	$(document).on("click","input[type='password']",function(){
		$(this).parents('p').find("em.error_tips").remove();
	});
	$(document).on("click","textarea",function(){
		$(this).parents('p').find("em.error_tips").remove();
	});

	//地区表单提交验证信息有效性
	$("form#zone_form").submit(function(){
		var error_str = "<em class='error_tips'>必填字段</em>";
		var zone_id = $("form#zone_form input[type='hidden'][name='zone_id']").val();
		var name = $("form#zone_form input[name='name']").val();
		var zone_name = $("form#zone_form select[name='zone_name']").val();
		var zone_name1 = $("form#zone_form select[name='zone_name1']").val();
		var parent_zone_id = '';
		var level = 1;
		if(zone_name1==0){
			parent_zone_id = zone_name;
			level = 1;
			$("form#zone_form input[type='hidden'][name='parent_zone_id']").val(parent_zone_id);
		}else{
			parent_zone_id = zone_name1;
			level = 2;
			$("form#zone_form input[type='hidden'][name='parent_zone_id']").val(parent_zone_id);
		}

		// alert(zone_name);return false;
		var flag = 1;
		$("form#zone_form input[type='text']").each(function(){
			$(this).parents('p').find("em.error_tips").remove();
			var this_val = $(this).val();
			if(this_val == ''){
				$(this).parents('p').append(error_str);flag = 0;return false;
			}

		});
		if(flag == 0){return false;}

		$.ajax({
			url:verify_zone_name,
			type:'POST',
			dataType:'json',
			data:'zone_name='+name+'&zone_id='+zone_id,
			async:false,
			success:function(data){
				if(data==0){ 
					flag =1;
				}
				else{
					$("form#zone_form input[type='text'][name='zone_name']").parents('p').append("<em class='error_tips'>地区已存在，请更换</em>");flag = 0;return false;
				}
			}
		});
		if(flag == 0){return false;}

		if(zone_id!=''){
			//验证地区是否可选修改的父级
			$.ajax({
				url:verify_parent_zone_usable,
				type:'POST',
				dataType:'json',
				data:'parent_zone_id='+parent_zone_id+'&zone_id='+zone_id+'&level='+level,
				async:false,
				success:function(data){
					if(data==0){ 
						$("form#zone_form select[name='zone_name1']").parents('p').append("<em class='error_tips'>当前地区存在子级,指定父级不可选,请更换</em>");flag = 0;return false;
					}
				}
			});
			if(flag == 0){return false;}
		}



		// return false;
	});


	//服务设施表单提交验证
	$("form#service_form").submit(function(){
		var error_str = "<em class='error_tips'>必填字段</em>";
		var service_id = $("form#service_form input[type='hidden'][name='service_id']").val();
		var service_name = $("form#service_form input[type='text'][name='service_name']").val();
		var service_attr = $("form#service_form textarea[name='service_attr']").val();
		var flag = 1;
		$("form#service_form input[type='text']").each(function(){
			$(this).parents('p').find("em.error_tips").remove();
			var this_val = $(this).val();
			if(this_val == ''){
				$(this).parents('p').append(error_str);flag = 0;return false;
			}

		});
		if(flag == 0){return false;}

		//验证多行文本是否存在多个重复值
		if(service_attr == '' ){
			$("form#service_form textarea[name='service_attr']").parents('p').append(error_str);flag = 0;return false;
		}
		//将字符串中成的全部中文逗号转化英文逗号
		var reg = /，/gi;
		service_attr = service_attr.replace(reg,',');	//将中文逗号转化成英文逗号
		// alert(service_attr);
		var reg = / /gi;
		service_attr = service_attr.replace(reg,',');	//去除字符串中间空格
		// alert(service_attr);
		// alert(service_attr);return false;
		if (service_attr.substr(0,1)==','){		//去除字符串第一个逗号
			service_attr=service_attr.substr(1);	
		}
		
	    var reg = /,$/gi;
		service_attr = service_attr.replace(reg,'');	//去除最右边逗号

		var ary = service_attr.split(',');
		var s = service_attr+",";

		$("form#service_form textarea[name='service_attr']").parents('p').find("em.error_tips").remove();
		for(var i=0;i<ary.length;i++) {
		    if(s.replace(ary[i]+",","").indexOf(ary[i]+",")>-1) {
		    	$("form#service_form textarea[name='service_attr']").parents('p').append("<em class='error_tips'>存在重复属性,请更换</em>");flag = 0;return false;
		    }
		}
		if(flag == 0){return false;}

		//验证设施名唯一
		$.ajax({
			url:verify_service_name,
			type:'POST',
			dataType:'json',
			data:'service_name='+service_name+'&service_id='+service_id,
			async:false,
			success:function(data){
				if(data==0){ 
					flag =1;
				}
				else{
					$("form#service_form input[type='text'][name='service_name']").parents('p').append("<em class='error_tips'>设施名已存在，请更换</em>");flag = 0;return false;
				}
			}
		});
		if(flag == 0){return false;}


		// alert('yes');return false;

	});


	//用户信息添加编辑验证账号和手机号和邮箱唯一性

	$("form#user_form").submit(function(){
		var error_str = "<em class='error_tips'>必填字段</em>";
		var user_id = $("form#user_form input[type='hidden'][name='user_id']").val();
		var user_name = $("form#user_form input[type='text'][name='user_name']").val();
		var phone_number = $("form#user_form input[type='text'][name='phone_number']").val();
		var email = $("form#user_form input[type='text'][name='email']").val();
		var password = $("form#user_form input[type='password'][name='password']").val();
		var query_password = $("form#user_form input[type='password'][name='query_password']").val();
		var flag = 1;
		$("form#user_form input[type='text']").each(function(){
			$(this).parents('p').find("em.error_tips").remove();
			var this_val = $(this).val();
			if(this_val == ''){
				$(this).parents('p').append(error_str);flag = 0;return false;
			}

		});
		if(flag == 0){return false;}
		
		$("form#user_form input[type='password']").each(function(){
			$(this).parents('p').find("em.error_tips").remove();
			var this_val = $(this).val();
			if(this_val == ''){
				$(this).parents('p').append(error_str);flag = 0;return false;
			}

		});
		if(flag == 0){return false;}

		//验证手机号
		if(!(/^1[34578]\d{9}$/.test(phone_number))){ 
				//格式不正确
				$("form#user_form input[type='text'][name='phone_number']").parents('p').append("<em class='error_tips'>手机号格式有误</em>");flag = 0;return false;
		} 


		//验证邮件
		var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/; //验证邮箱的正则表达式
		if(!reg.test(email)){
			//邮箱格式有误
			$("form#user_form input[type='text'][name='email']").parents('p').append("<em class='error_tips'>邮箱格式有误</em>");flag = 0;return false;
		}


		//验证密码长度
		if(password.length <6){
			$("form#user_form input[type='password'][name='password']").parents('p').append("<em class='error_tips'>密码长度需超过6位</em>");flag = 0;return false;
		}
		//验证密码有效性
		var num = 0;  
	    var number = 0 ;  
	    var bigLetter = 0 ;  
	    var chars = 0 ;  
		
		//判断新密码不能是纯数字，纯英文，纯字母
	    if (password.search(/[0-9]/) != -1) {  
	        num += 1;  
	        number =1;  
	    }  
	    if (password.search(/[A-Za-z]/) != -1) {  
	        num += 1;  
	        bigLetter = 1 ;  
	    }  
	    if (password.search(/[^A-Za-z0-9]/) != -1) {  
	        num += 1;  
	        chars = 1 ;  
	    }  
	    if (num >= 2 && (password.length >= 6 && password.length <= 16)) {  
	    	$("form#user_form input[type='password'][name='password']").parents('p').find("em.error_tips").remove();
	    }else if(password.length < 6 || password.length > 16){ 
	        $("form#user_form input[type='password'][name='password']").parents('p').append("<em class='error_tips'>密码由6-16个字符组成</em>");flag = 0;return false;
	    }

		if(password != query_password){
			$("form#user_form input[type='password'][name='query_password']").parents('p').append("<em class='error_tips'>密码不一致</em>");flag = 0;return false;

		}

		if(flag == 0){return false;}

		//验证账号,手机号,邮箱唯一性
		$.ajax({
			url:verify_user_info,
			type:'POST',
			dataType:'json',
			data:'user_name='+user_name+'&phone_number='+phone_number+'&email='+email+'&user_id='+user_id,
			async:false,
			success:function(data){
				var name = data['name'];
				var phone = data['phone'];
				var email = data['email'];

				if(name!=0){
					$("form#user_form input[type='text'][name='user_name']").parents('p').append("<em class='error_tips'>账户名已存在,请更换</em>");flag = 0;
				}
				if(phone!=0){
					$("form#user_form input[type='text'][name='phone_number']").parents('p').append("<em class='error_tips'>手机号已存在,请更换</em>");flag = 0;
				}
				if(email!=0){
					$("form#user_form input[type='text'][name='email']").parents('p').append("<em class='error_tips'>邮箱已存在,请更换</em>");flag = 0;
				}
			}
		});
		if(flag == 0){return false;}

		// alert(12345);return false;


	});


	$("form#admin_form").submit(function(){
		var error_str = "<em class='error_tips'>必填字段</em>";
		var admin_id = $("form#admin_form input[type='hidden'][name='admin_id']").val();
		var user_name = $("form#admin_form input[type='text'][name='user_name']").val();
		var real_name = $("form#admin_form input[type='text'][name='real_name']").val();
		var password = $("form#admin_form input[type='password'][name='password']").val();
		var query_password = $("form#admin_form input[type='password'][name='query_password']").val();
		var flag = 1;
		$("form#admin_form input[type='text']").each(function(){
			$(this).parents('p').find("em.error_tips").remove();
			var this_val = $(this).val();
			if(this_val == ''){
				$(this).parents('p').append(error_str);flag = 0;return false;
			}

		});
		if(flag == 0){return false;}
		
		$("form#admin_form input[type='password']").each(function(){
			$(this).parents('p').find("em.error_tips").remove();
			var this_val = $(this).val();
			if(this_val == ''){
				$(this).parents('p').append(error_str);flag = 0;return false;
			}

		});
		if(flag == 0){return false;}

		//验证密码长度
		if(password.length <6){
			$("form#admin_form input[type='password'][name='password']").parents('p').append("<em class='error_tips'>密码长度需超过6位</em>");flag = 0;return false;
		}

		//验证密码有效性
		var num = 0;  
	    var number = 0 ;  
	    var bigLetter = 0 ;  
	    var chars = 0 ;  
		
		//判断新密码不能是纯数字，纯英文，纯字母
	    if (password.search(/[0-9]/) != -1) {  
	        num += 1;  
	        number =1;  
	    }  
	    if (password.search(/[A-Za-z]/) != -1) {  
	        num += 1;  
	        bigLetter = 1 ;  
	    }  
	    if (password.search(/[^A-Za-z0-9]/) != -1) {  
	        num += 1;  
	        chars = 1 ;  
	    }  
	    if (num >= 2 && (password.length >= 6 && password.length <= 16)) {  
	    	$("form#admin_form input[type='password'][name='password']").parents('p').find("em.error_tips").remove();
	    }else if(password.length < 6 || password.length > 16){ 
	        $("form#admin_form input[type='password'][name='password']").parents('p').append("<em class='error_tips'>密码由6-16个字符组成</em>");flag = 0;return false;
	    }else if(num == 1){  
//	        if(number==1){  
//	        	if(password != '888888'){
//	        		$("form#admin_form input[type='password'][name='password']").parents('p').append("<em class='error_tips'>不能全为数字</em>");flag = 0;return false; 
//	        	}
//	        }   
//	        if(bigLetter==1){  
//	        	$("form#admin_form input[type='password'][name='password']").parents('p').append("<em class='error_tips'>不能全为字母</em>");flag = 0;return false; 
//	        }  
//	        if(chars==1){  
//	        	$("form#admin_form input[type='password'][name='password']").parents('p').append("<em class='error_tips'>不能全为字符</em>");flag = 0;return false; 
//	        }  
	    }  


		if(password != query_password){
			$("form#admin_form input[type='password'][name='query_password']").parents('p').append("<em class='error_tips'>密码不一致</em>");flag = 0;return false;
		}
		if(flag == 0){return false;}
		//验证账号唯一性
		$.ajax({
			url:verify_admin_info,
			type:'POST',
			dataType:'json',
			data:'user_name='+user_name+'&admin_id='+admin_id,
			async:false,
			success:function(data){

				if(data!=0){
					$("form#admin_form input[type='text'][name='user_name']").parents('p').append("<em class='error_tips'>账户名已存在,请更换</em>");flag = 0;
				}
			}
		});
		if(flag == 0){return false;}


	});

	$("form#insurance_form").submit(function(){
		var error_str = "<em class='error_tips'>必填字段</em>";
		var insurance_name = $("form#insurance_form input[name='insurance_name']").val();
		var insurance_id = $("form#insurance_form input[type='hidden'][name='insurance_id']").val();
		var flag = 1;
		$("form#insurance_form input[type='text']").each(function(){
			$(this).parents('p').find("em.error_tips").remove();
			var this_val = $(this).val();
			if(this_val == ''){
				$(this).parents('p').append(error_str);flag = 0;return false;
			}

		});
		if(flag == 0){return false;}

		//验证保险名称唯一性
		$.ajax({
			url:verify_insurance_name,
			type:'POST',
			dataType:'json',
			data:'insurance_name='+insurance_name+'&insurance_id='+insurance_id,
			async:false,
			success:function(data){
				if(data!=0){
					$("form#insurance_form select[name='insurance_name']").parents('p').append("<em class='error_tips'>账户名已存在,请更换</em>");flag = 0;
				}
			}
		});
		if(flag == 0){return false;}

		// return false;
	});


	//关于我们提交保存验证
	$("form#about_form").submit(function(){
		var error_str = "<em class='error_tips'>必填字段</em>";
		var name = $("form#about_form input[name='name']").val();
		var about_id = $("form#about_form input[type='hidden'][name='about_id']").val();

		var flag = 1;
		$.ajax({
			url:verify_about_name,
			type:'POST',
			dataType:'json',
			data:'name='+name+'&about_id='+about_id,
			async:false,
			success:function(data){
				if(data!=0){
					$("form#about_form input[name='name']").parents('p').append("<em class='error_tips'>标题已存在,请更换</em>");flag = 0;
				}
			}
		});
		if(flag == 0){return false;}

	});

	//活动分类保存验证
	$("form#activitytype_form").submit(function(){
		var error_str = "<em class='error_tips'>必填字段</em>";
		var name = $("form#activitytype_form input[name='name']").val();
		var activitytype_id = $("form#activitytype_form input[type='hidden'][name='activitytype_id']").val();

		var flag = 1;
		$.ajax({
			url:verify_activitytype_name,
			type:'POST',
			dataType:'json',
			data:'name='+name+'&activitytype_id='+activitytype_id,
			async:false,
			success:function(data){
				if(data!=0){
					$("form#activitytype_form input[name='name']").parents('p').append("<em class='error_tips'>活动名称已存在,请更换</em>");flag = 0;
				}
			}
		});
		if(flag == 0){return false;}

	});



});


function clearNoNum(obj)    
{    
    //先把非数字的都替换掉，除了数字和.    
    obj.value = obj.value.replace(/[^\d.]/g,"");    
    //保证只有出现一个.而没有多个.    
    obj.value = obj.value.replace(/\.{2,}/g,".");    
    //必须保证第一个为数字而不是.    
    obj.value = obj.value.replace(/^\./g,"");    
    //保证.只出现一次，而不能出现两次以上    
    obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");    
    //只能输入两个小数  
    obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');   
} 

function clearNoInt(obj){

	if(obj.value.substr(0, 1)==0 && obj.value.length>1){
		obj.value=obj.value.replace(/[^1-9]/,'');
	}else{
		obj.value=obj.value.replace(/\D/g,'');
	}

}