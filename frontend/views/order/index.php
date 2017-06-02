<?php
?>

<body>
<button style="width:400;height:400px;" id="pay">支付</button>

</body>

<script type="text/javascript" src="https://one.pingxx.com/lib/pingpp_one.js"></script>

<script>


document.addEventListener('pingpp_one_ready',function(){
    document.getElementById('pay').addEventListener('click',function(){
        pingpp_one.init({
            app_id:'xxxx',                     //该应用在 Ping++ 的应用 ID
            order_no:'xxxx',                     //订单在商户系统中的订单号
            amount:1,                            //订单价格，单位：人民币 分
            // 壹收款页面上需要展示的渠道，数组，数组顺序即页面展示出的渠道的顺序
            // upmp_wap 渠道在微信内部无法使用，若用户未安装银联手机支付控件，则无法调起支付
            channel:['alipay_wap','wx_pub','wx_wap','upacp_wap','yeepay_wap','jdpay_wap','bfb_wap'],
            charge_url:'http://192.168.0.3/order/pay',  //商户服务端创建订单的 url
            charge_param:{a:1,b:2},                      //(可选，用户自定义参数，若存在自定义参数则壹收款会通过 POST 方法透传给 charge_url)
            open_id:'wx1234567890',                      //(可选，使用微信公众号支付时必须传入)
            debug:true                                   //(可选，debug 模式下会将 charge_url 的返回结果透传回来)
        },function(res){

            //debug 模式下获取 charge_url 的返回结果
            if(res.debug&&res.chargeUrlOutput){
                console.log(res.chargeUrlOutput);
            }
            if(!res.status){

                //处理错误
                alert(res.msg);
            }
            else{

                //debug 模式下调用 charge_url 后会暂停，可以调用 pingpp_one.resume 方法继续执行
                if(res.debug&&!res.wxSuccess){
                	pingpp_one.resume();
//                     if(confirm('当前为 debug 模式，是否继续支付？')){
//                         pingpp_one.resume();
//                     }
                }
                //若微信公众号渠道需要使用壹收款的支付成功页面，则在这里进行成功回调，
                //调用 pingpp_one.success 方法，你也可以自己定义回调函数
                //其他渠道的处理方法请见第 2 节
                else pingpp_one.success(function(res){
                    if(!res.status){
                        alert(res.msg);
                    }
                },function(){
                    //这里处理支付成功页面点击“继续购物”按钮触发的方法，
                    //例如：若你需要点击“继续购物”按钮跳转到你的购买页，
                    //则在该方法内写入 window.location.href = "你的购买页面 url"

                    window.location.href='http://192.168.0.3';//示例
                });
            }
        });
    });
});

</script>