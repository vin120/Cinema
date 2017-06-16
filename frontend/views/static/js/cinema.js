$(function () {

    var time_list = '';

    var movie_swiper = new Swiper('#movie-swiper', {
        slidesPerView: 4,
        centeredSlides: true,
        spaceBetween: 5,
        slideToClickedSlide:true,//点击滑动居中
        onSlideChangeEnd: function(swiper){
       
            var json_url = '/cinema/jsonurl?id='+swiper.activeIndex+'&room_id=';
        	
//            alert(swiper.activeIndex);return;
            
            $.ajax({
                type: 'GET',
                url: json_url,
                dataType: 'json',
                success: function(data){
                    var data = eval('(' + data + ')');
                    var result = '';
                    var time_result = '';
                    time_list = data.lists;
                    $('.movie-name').text(data.movie_name);
                    $('#score').text(data.score);
                    $('#movei_info').text(data.info);
                    var arrLen = data.lists.length;
                    for(var i=0; i<arrLen; i++){
                        if(i == 0){
                            result +=  '<li time-id="'+i+'" class="active">'+data.lists[i].time+'</li>';
                        }else{
                            result +=  '<li time-id="'+i+'">'+data.lists[i].time+'</li>';
                        }

                    }

                    var detailLen = data.lists[0].detail.length;
                    for(var i=0; i<detailLen; i++){
                        time_result +=  '<tr>'
                                    +'<td class="mt-time">'
                                    +'<div class="mt-time-wrap">'
                                    +'<strong>'+data.lists[0].detail[i].start+'</strong><em>'+data.lists[0].detail[i].end+'结束</em>'
                                    +'</div>'
                                    +'</td>'
                                    +'<td class="mt-info">'
                                    +'<div class="mt-lang">'
                                    +data.lists[0].detail[i].language
                                    +'</div>'
                                    +'<div class="mt-place">'
                                    +data.lists[0].detail[i].hall
                                    +'</div>'
                                    +'</td>'
                                    +'<td class="mt-price">'
                                    +'<span class="unit theme-color">'+data.lists[0].detail[i].price+'元</span><span class="origin-price">影院价:'+data.lists[0].detail[i].o_price+'元</span>'
                                    +'</td>'
                                    +'<td class="mt-buy">'
                                    +'<a class="mt-btn" href="seat.html">购票</a>'
                                    +'</td>'
                                    +'</tr>';

                    }

                    $('#tab').html(result);
                    $('#movietime-list').html(time_result);
                }
            });
        },
        onInit: function(swiper){
            //Swiper初始化了
        	var json_url = '/cinema/jsonurl?id=0';
            $.ajax({
                type: 'GET',
                url: json_url,
                dataType: 'json',
                success: function(data){
                    var data = eval('(' + data + ')');
                    var result = '';
                    var time_result = '';
                    time_list = data.lists;
                    $('.movie-name').text(data.movie_name);
                    $('#score').text(data.score);
                    $('#movei_info').text(data.info);
                    var arrLen = data.lists.length;
                    for(var i=0; i<arrLen; i++){
                        if(i == 0){
                            result +=  '<li time-id="'+i+'" class="active">'+data.lists[i].time+'</li>';
                        }else{
                            result +=  '<li time-id="'+i+'">'+data.lists[i].time+'</li>';
                        }

                    }

                    var detailLen = data.lists[0].detail.length;
                    for(var i=0; i<detailLen; i++){
                        time_result +=  '<tr>'
                            +'<td class="mt-time">'
                            +'<div class="mt-time-wrap">'
                            +'<strong>'+data.lists[0].detail[i].start+'</strong><em>'+data.lists[0].detail[i].end+'结束</em>'
                            +'</div>'
                            +'</td>'
                            +'<td class="mt-info">'
                            +'<div class="mt-lang">'
                            +data.lists[0].detail[i].language
                            +'</div>'
                            +'<div class="mt-place">'
                            +data.lists[0].detail[i].hall
                            +'</div>'
                            +'</td>'
                            +'<td class="mt-price">'
                            +'<span class="unit theme-color">'+data.lists[0].detail[i].price+'元</span><span class="origin-price">影院价:'+data.lists[0].detail[i].o_price+'元</span>'
                            +'</td>'
                            +'<td class="mt-buy">'
                            +'<a class="mt-btn" href="seat.html">购票</a>'
                            +'</td>'
                            +'</tr>';

                    }

                    $('#tab').html(result);
                    $('#movietime-list').html(time_result);
                }
            });
        }
    });

    $(".moive_time").on("click","li",function(){
        $(this).siblings('li').removeClass('active');  // 删除其他兄弟元素的样式
        $(this).addClass('active');                    // 添加当前元素的样式
        //alert($(this).attr("time-id"));
        var time_id = $(this).attr("time-id");

        var detailLen = time_list[time_id].detail.length;
        var detail = time_list[time_id].detail;
        var time_result = '';
        for(var i=0; i<detailLen; i++){
            time_result +=  '<tr>'
                +'<td class="mt-time">'
                +'<div class="mt-time-wrap">'
                +'<strong>'+detail[i].start+'</strong><em>'+detail[i].end+'结束</em>'
                +'</div>'
                +'</td>'
                +'<td class="mt-info">'
                +'<div class="mt-lang">'
                +detail[i].language
                +'</div>'
                +'<div class="mt-place">'
                +detail[i].hall
                +'</div>'
                +'</td>'
                +'<td class="mt-price">'
                +'<span class="unit theme-color">'+detail[i].price+'元</span><span class="origin-price">影院价:'+detail[i].o_price+'元</span>'
                +'</td>'
                +'<td class="mt-buy">'
                +'<a class="mt-btn" href="seat.html">购票</a>'
                +'</td>'
                +'</tr>';

        }
        $('#movietime-list').html(time_result);

    });



});