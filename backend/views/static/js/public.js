$(document).ready(function() {

	//delete删除弹框
	$(document).on('click',".delete",function(e) {
		var val = $(this).attr('id');
		 $(".ui-widget-overlay").remove();
		 $("#promptBox").remove();
		 var str = "<div class='ui-widget-overlay ui-front'></div>";
		 var str_con = '<div id="promptBox" class="pop-ups write ui-dialog" >';
			str_con += '<h3>消息</h3>';
			str_con += '<span class="op"><a class="close r"></a></span>';
			str_con += '<p>你确定需要删除吗?</p>';
			str_con += '<p class="btn">';
			str_con += '<input type="button" class="confirm_but" value="确定"></input>';
			str_con += '<input type="button" class="cancel_but" value="取消"></input>';
			str_con += '</p></div>';

		 //$("#promptBox").before(str);
		 $(document.body).append(str);
		 $(document.body).append(str_con);
		 //$("#promptBox").removeClass('hide');

		 $(".btn > .confirm_but").attr('id',val);
	 });

	//多选删除弹框
	$("#del_submit").on('click',function(){
		 $(".ui-widget-overlay").remove();
		 $("#promptBox").remove();

		 var str = "<div class='ui-widget-overlay ui-front'></div>";
		 var str_con = '<div id="promptBox" class="pop-ups write ui-dialog" >';
			str_con += '<h3>消息</h3>';
			str_con += '<span class="op"><a class="close r"></a></span>';
			str_con += '<p>确定需要删除所选项？</p>';
			str_con += '<p class="btn">';
			str_con += '<input type="button" class="confirm_but_more" value="确定"></input>';
			str_con += '<input type="button" class="cancel_but" value="取消"></input>';
			str_con += '</p></div>';
		 var no_str = '<div id="promptBox" class="pop-ups write ui-dialog" >';
			no_str += '<h3>提示消息</h3>';
			no_str += '<span class="op"><a class="close r"></a></span>';
			no_str += '<p>请选择删除项</p>';
			no_str += '<p class="btn">';
			no_str += '<input type="button" class="cancel_but" value="取消"></input>';
			no_str += '</p></div>';

		var checkbox = $("table  tbody input[type='checkbox']:checked").length;
		 if(checkbox == 0){
			 $(document.body).append(str);
			 $(document.body).append(no_str);
		 }else{
			 $(document.body).append(str);
			 $(document.body).append(str_con);
		 }
	 });

	 //鼠标拖拽
	 var _move=false;//移动标记
	 var _x,_y;//鼠标离控件左上角的相对位置
     $(document).on('click',"#promptBox >h3",function(){
         //alert("click");//点击（松开后触发）
     }).mousedown(function(e){
         _move=true;
         _x=e.pageX-parseInt($("#promptBox").css('left'));
         _y=e.pageY-parseInt($("#promptBox").css('top'));
//	     $("#promptBox").fadeTo(20, 0.5);//点击后开始拖动并透明显示
     });


     $(document).on('mousemove',"#promptBox >h3",function(e){
    	 $("#promptBox >h3").css('cursor','move');	//出现移动图标
         if(_move){
             var x=e.pageX-_x;//移动时根据鼠标位置计算控件左上角的绝对位置
             if (x < 0) {
            	 x = 0;
             } else if (x > $(window).width() - $("#promptBox").width()) {
            	 x = $(window).width() - $("#promptBox").width();
             }
             var y=e.pageY-_y;
             if(y < 0){
            	 y = 0;
             }else if (y > $(window).height()){
            	 y = $(window).height();
             }
             $("#promptBox").css({top:y,left:x});//控件新位置
         }
     }).mouseup(function(){
    	 _move=false;
//	     $("#promptBox").fadeTo("fast", 1);//松开鼠标后停止移动并恢复成不透明
     });


	   //close
	   $(document).on('click',"#promptBox >span.op,#promptBox > .btn .cancel_but",function(){
		   $(".ui-widget-overlay").addClass('hide');
		   $("#promptBox").addClass('hide');
	   })

	 //表格全选反选功能
		$('table th input:checkbox').on('click' , function(){
	        var that = this;
	        $(this).closest('table').find('tr > td:first-child input:checkbox')
	        .each(function(){
	            this.checked = that.checked;
	            $(this).closest('tr').toggleClass('selected');
	        });
	    });


		//添加编辑页面取消填写按钮
		$(".btn > .cancle").on('click',function(){
			$("form input#code").val('');
			$("form input#code_chara").val('');
			$("form input#name").val('');
			$("form textarea#desc").val('');
			$("form input#detail_title").val('');
			$("form textarea#detail_desc").val('');
			$("form input#voyage_name").val('');
			$("form input#voyage_num").val('');
			$("form textarea#desc").val('');
			$("form input#ticket_price").val('');
			$("form input#ticket_taxes").val('');
			$("form input#harbour_taxes").val('');
			$("form input#deposit_ratio").val('');
		});



		// 动态改变右边部分宽度
		changeMainRWith();
		$(window).resize(function(){
			changeMainRWith();
		});

		// asideNav点击事件
		$("body").on("click","#asideNav li",function(){
			if ($(this).next().prop("tagName") === "UL") {
				if ($(this).hasClass("open")) {
					$(this).parent().find("ul").css("display","none");
					$(this).parent().find("ul").prev("li").removeClass("open");
				} else {
					$(this).next().css("display","block");
					$(this).addClass("open");
				}
			} else {
				$(".active").removeClass("active");
				$(this).addClass("active");
			}
		});

		// 左边导航关闭
		$("body").on("click","#closeAsideNav",function(){
			$("#asideNav_open").css("display","none");
			$("#asideNav_close").css("display","block");
			$("#asideNav").css("width",$("#asideNav_close").width() + "px");
			changeMainRWith();
		});

		// 左边导航打开
		$("body").on("click","#openAsideNav",function(){
			$("#asideNav_close").css("display","none");
			$("#asideNav_open").css("display","block");
			$("#asideNav").css("width",$("#asideNav_open").width() + "px");
			changeMainRWith();
		});

		// tab功能
		$("body").on("click",".tab_title li",function(){
			var index = $(".tab_title li").index($(this));
			$(".tab_title > .active").removeClass("active");
			$(".tab_content > .active").removeClass("active");
			$(this).addClass("active");
			$($(".tab_content > div")[index]).addClass("active");
		});


});



//动态改变右边部分宽度
function changeMainRWith() {
	$("#main > .r").css("width",($("#main").width() - 44 - $("#asideNav").width())+"px");
}


//封装alert
function Alert(info){
	$(".ui-widget-overlay").remove();
	 $("#promptBox").remove();
	 var str = "<div class='ui-widget-overlay ui-front'></div>";
	 var str_con = '<div id="promptBox" class="pop-ups write ui-dialog" >';
		str_con += '<h3>Prompt</h3>';
		str_con += '<span class="op"><a class="close r"></a></span>';
		str_con += '<p>'+info+'</p>';
		str_con += '<p class="btn">';
		str_con += '<input type="button" class="cancel_but" value="OK"></input>';
		str_con += '</p></div>';

	 //$("#promptBox").before(str);
	 $(document.body).append(str);
	 $(document.body).append(str_con);
}


//09/05/2016 12:12:23
function createDate(time){
	var date = time.substr(0,10);
	var year = date.split('-');
	date = year[2]+'/'+year[1]+'/'+year[0]+' '+time.substr(11,8);
	return date;
}
