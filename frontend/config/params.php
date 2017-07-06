<?php
return [
    'adminEmail' => 'admin@example.com',
		
		
	// ============== 创蓝手机验证码接口 ==================
    // ============== 国内接口 ==================
    'un' => 'N2080352',
    'pw' => 'W1xG9Pht0K9f14',
    // ============== 国际接口 ==================
    'uns' => 'I8289053',
    'pws' => 'OiC4LzTA2m4563',
		
		
	// ============== ping++ ==================
	'API_KEY'   => 'sk_test_L04WvT9GaL8ODWTmXDnrrf9K',
// 	'API_KEY'   => 'sk_live_WLGWn5znDeH8nv58S0iTirPO',
	'PAPP_ID'   => 'app_i5mf90G4iTKSTuPW',
		
	// ============== 本地存放圖片的訪問路徑 ==================
	'img_url' => 'http://www.images.com',
		

	// ============== 微信获取用户信息 ==============
	'appid' =>'wx05a87acdc3d63406',
	'secret' => '1eca4c9c03b8e32490c68f90b9977901',
	'token'=>'weixin',
	'reflashtime'=>3600,
	'memcacheKey'=>'AccessToken',
		
	// ============== 获取图片 ==============
	'img_url' => 'http://www.movieimages.com',	//访问路径
		
	// ==============alipay支付路徑==============
	'pay_url' => 'order/p_pay',
	
	//支付完成后的回调地址
	'pay_success_url' => 'order/success',
	'pay_cancel_url'=> 'order/cancel',
		
		
	//自助取票機沒紙，通知這個手機號碼
	'notify_phone'=>"65430594",
		
		
	//==============wechat==============
	
	//app_id ,和app_secret
	'wx_app_id' => 'wxc2a5524786ccd3b4',
	'wx_app_secret' => '35f5499528f466c8fdb22984eb063586',
		
	//redirect_url ,该地址的域名需在微信公众号平台上进行设置，步骤为：登陆微信公众号平台 => 开发者中心 => 网页授权获取用户基本信息 => 修改
	'redirect_url' => 'http://www.macaoyi.com/order/getwxcode',
];
