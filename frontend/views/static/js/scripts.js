function UI(){var a=new ButtonDecorator();var c=new CheckboxDecorator();var b=new SelectDecorator("select")}$(function(){UI();$("#case-study3 .detail-slider .bg .slides").cycle({speed:600,slides:"> figure",pager:"> .handles",log:false,swipe:true,});$("#case-study2 .detail-slider .slides").cycle({speed:600,slides:"> div",pager:"> .handles",log:false,swipe:true});$("#home-slider").cycle({speed:600,slides:"> div",pager:"#page-navigation",log:false,swipe:true});var a=new Tabber("#tab1",{changeTime:300,showTime:500,hashNavigation:false,onBeforeChange:function(){},onChange:function(){}});var d=new Tabber("#tab2",{changeTime:300,showTime:500,hashNavigation:false,onBeforeChange:function(){},onChange:function(){}});var c=new Tabber("#contact #main .cnt-tabs",{changedTime:300,showTime:800,hashNavigation:false});var b=new AjaxContactForm(".contact-form",{ajaxUrl:"/ajax/contact-form",errorSelector:".cf-error",successSelector:".cf-success",msgFadeTime:500,formFadeTime:500,onSuccess:function(e){},onFailure:function(e){},onResponse:function(e){}});if($("#case-study5").length>0){animationElements();cubeMove();$(document).on("click touchstart","#features_nav ul li a",function(f){f.preventDefault()})}});$(window).load(function(){topNav()});function drags(c){var b=c.parent().find(".handle-slider");var a=b.find(".slides").children().length;if(a==0){a=b.find(".slides > .slide").length}var d=new Slider(".handle-slider",{holdTime:8000,handles:false,animateTime:300});c.noUiSlider({range:[0,a-1],start:0,handles:1,slide:function(){var e=Math.round(c.val());d.moveToSlide(e)}})}function switchIMG(b){var i,g,j;var e=b.find(".second-image");var k=b.find(".first-image");var l=e.height();var a=e.width();var f=e.offset().left;var c=e.offset().top;var h=c-15;var d=c+l+100;$(document).on("mousemove",function(m){if(m.pageY>h&&m.pageY<d){j=l-(m.pageY-c);if((l/2)>j){e.removeClass("changed")}else{e.addClass("changed")}k.css({height:j})}else{e.addClass("changed")}});$(document).on("touchmove",function(o){var m=event.touches[0].pageX;var p=event.touches[0].pageY;var n=l-(p-c);if((m>f&&m<(f+a))&&(p>c-15&&p<(c+l-15))){o.preventDefault();k.css({height:n});if((l/2)>n){e.removeClass("changed")}else{e.addClass("changed")}}else{return true}})}function Animator(a,g,f,d,b,e,c){$(document).on(c,function(){var i=$(a);if(a==g){var h=$(g)}else{var h=$(i).find(g)}var j=$.inviewport(i,{threshold:e,container:window});h.addClass(b);if(j&&!$(h).hasClass(d)){$(h).addClass(d);$(h).removeClass(f)}else{if(!j&&$(h).hasClass(d)||!j&&!$(h).hasClass(f)){$(h).removeClass(d);$(h).addClass(f)}}})}function buildSideNav(){var f;var d=$("body.cases-studies").find(".phase-row");var b=d.length;var a=0;var c='<div id="page-navigation">';var e=$("#section-header");var g=$(window).scrollTop();d.each(function(){if(a==0){c=c+'<a href="#!section-header" class="top-btn"></a>'}a++;f=$(this).data("title");c=c+'<a href="#!'+$(this).attr("id")+'"><span>'+f+"</span></a>"});c=c+"</div>";$("body").prepend(c);$("#page-navigation").css({marginTop:-$("#page-navigation").height()/2});$("html, body").animate({scrollTop:g+1},1);if(g>e.next().offset().top){$(".top-btn").animate({opacity:1},500)}$(document).on("scroll",function(n){var m=$(window).innerHeight()/2;var o=$(".phase-row");var h=$("#page-navigation");var q=$("#page-navigation").find("a");var i=e.height();var r=$("#site-wrapper #footer-push");var j=r.length?r.offset().top:0;var k,l,p;g=$(window).scrollTop();if((g+m)>e.next().offset().top&&$(".top-btn").css("opacity")=="0"){$(".top-btn").animate({opacity:1},100)}o.each(function(){p=$(this).offset().top;l=$(this).height()+p;if((i-m)>g){q.removeClass("active");h.removeClass();h.addClass("header");if($(".top-btn").css("opacity")=="1"){$(".top-btn").animate({opacity:0},100)}}else{if(g>(j-m)){q.removeClass("active");h.removeClass();h.addClass("footer")}else{if((p-m)<g&&g<(l-m)){k=$(this).attr("id");q.removeClass("active");h.removeClass();h.addClass(k);h.find('a[href="#!'+k+'"]').addClass("active")}}}})});$("#page-navigation a, #back-to-top").on("click",function(k){var j,i;var h=$(k.target).closest("a").attr("href");h="#"+h.replace("#!","");i=$("body").find(h).offset().top;$("html, body").stop().animate({scrollTop:i+"px"},2000)})}function lastTitle(){var d=false;var j=$(".last-title h1").offset().top;var c=($(".last-title h1").height())/2;var a=$(".last-title").height()/2;var f=$(".last-title").offset().top;var i=$(window).innerHeight()/2;var g=$(window).innerHeight();var k=a+f;var h=a;var e;var b=0;$(document).on("scroll",function(l){if(d){return false}e=$(window).scrollTop();if(b>e){return true}h=k-i;if((e+g)>j&&e<h){d=true;b=e;l.preventDefault();console.log("animateStart");$("html").animate({scrollTop:h+"px"},function(){d=false})}})}function topNav(){var b;var a=$(".top-nav");a.show();if(Browser.isMobile()){setTimeout(function(){b=window.scrollY;if(b>60){a.addClass("animate")}$(document).on("scroll",function(){b=$(window).scrollTop();if(b>60){a.addClass("animate")}else{a.removeClass("animate")}})},2)}else{b=$(document).scrollTop()}if(b>60){a.addClass("animate")}$(document).on("scroll",function(){b=$(window).scrollTop();if(b>60){a.addClass("animate")}else{a.removeClass("animate")}})}function animationElements(){Animator("#hosting","#btn","","hatch","",-100,"scroll");Animator("#wireframing",".colors img","","animate","",-100,"scroll");Animator("#iphones","#text","fadeOut-withMotion leftToRight","fadeIn-withMotion","",-350,"scroll");Animator("#iphones","#iphone-account","fadeOut-withMotion rightToleft","fadeIn-withMotion","",-350,"scroll");Animator("#details","#iphone-creat","fadeOut-withMotion leftToRight","fadeIn-withMotion","",-350,"scroll");Animator("#details","#builder-txt","fadeOut-withMotion rightToleft","fadeIn-withMotion","",-350,"scroll");Animator("#detailsTwo","#profile-txt","fadeOut-withMotion leftToRight","fadeIn-withMotion","",-350,"scroll");Animator("#detailsTwo","#iphone-profile","fadeOut-withMotion rightToleft","fadeIn-withMotion","",-350,"scroll");Animator("#detailsLast","#iphone-last","fadeOut-withMotion leftToRight","fadeIn-withMotion","",-350,"scroll");Animator("#detailsLast","#features-txt","fadeOut-withMotion rightToleft","fadeIn-withMotion","",-350,"scroll");Animator("#testimonials","#imgS","changeF","changeS","",-400,"scroll");Animator("#testimonials","#imgF","changeS","changeF","",-400,"scroll");$(document).on("scroll",function(){var a=$(document).scrollTop();if(a>8150){$(".show").fadeIn(2000)}})}function cubeMove(){setTimeout(function(){$(document).on("scroll",function(){var b=window.scrollY;var a=$("#cube");if(b>3050&b<3300){a.removeClass("fadeOut").addClass("hatch")}else{a.removeClass("hatch");a.addClass("fadeOut")}})},2)}function tooltip(){$("#features > .shell > .half > span").on("mouseenter",function(){$(this).find(".tt").stop(true,true).fadeIn(200)});$("#details > .shell ").on("mouseenter",function(){$(this).find(".tt").stop(true,true).fadeIn(200)})}function relativeMovment(d,h,f,i){var g=$(window).width();var a=$(d).width();var e=parseInt($(h).css(f),10);var b=Math.floor((a-g)/i);$(h).css(f,e+b);e=e+b;$(window).bind("resize",function(){c(g,e,h,f)}).trigger("resize");function c(){var l=$(window).width();var j=Math.floor(Math.abs(g-l)/i);if(g<l){var k=e-j}if(g>l){var k=e+j}$(h).css(f,k)}};