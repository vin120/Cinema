
$(function(){
	var data =  $("#demo").attr('data');
	//alert(data);
	//若含有限制文件上传数量则使用设置的文件上传数量，否则默认只能上传10张图片
	var file_data = data.split('/');
	var num = 10;
	var flag = 0;
	for(var i in file_data){
		  if(file_data[i] == 'file_num'){
		  num = i;flag=1;}
		  }
	if(flag == 1){
		num = parseInt(num) +1 ;
		num = file_data[num];
	}
	//alert(num);
	//return false;
	// 初始化插件
	$("#demo").zyUpload({
		width            :   "650px",                 // 宽度
		//height           :   "400px",                 // 宽度
		itemWidth        :   "120px",                 // 文件项的宽度
		itemHeight       :   "100px",                 // 文件项的高度
		url              :   data,  					// 上传文件的路径
		multiple         :   true,                    // 是否可以多个文件上传
		dragDrop         :   true,                    // 是否可以拖动上传文件
		del              :   true,                    // 是否可以删除文件
		finishDel        :   false,  				  // 是否在上传文件完成后删除预览
		file_length 	 : num,
		
		/* 外部获得的回调接口 */
		onSelect: function(files, allFiles){                    // 选择文件的回调方法
			console.info("当前选择了以下文件：");
			console.info(files);
			console.info("之前没上传的文件：");
			console.info(allFiles);
		},
		onDelete: function(file, surplusFiles){                     // 删除一个文件的回调方法
			console.info("当前删除了此文件：");
			console.info(file);
			console.info("当前剩余的文件：");
			console.info(surplusFiles);
		},
		
		onSuccess: function(file){                    // 文件上传成功的回调方法
			console.info("此文件上传成功：");
			console.info(file);
		},
		onFailure: function(file){                    // 文件上传失败的回调方法
			console.info("此文件上传失败：");
			console.info(file);
		},
		
		onComplete: function(responseInfo){           // 上传完成的回调方法
			console.info("文件上传完成");
			console.info(responseInfo);
		}
	});
	
});

