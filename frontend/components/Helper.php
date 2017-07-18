<?php

namespace frontend\components;

use Yii;
use yii\web\Controller;
use frontend\models\MovieOnlineOrder;
use Pingpp\WxpubOAuth;



$assets = '@frontend/views/static';
$baseUrl = Yii::$app->assetManager->publish($assets);

require_once dirname(dirname(__FILE__)).'/components/Phpqrcode/phpqrcode.php';
require_once dirname(dirname(__FILE__)).'/components/pingpp/init.php';
class Helper extends Controller
{


	/**
	 * 		文件上传
	 * */
	public static function upload_file($input_name, $file_path='./', $type='image', $allow_size=2){
		// 上传的文件
		$file=$_FILES[$input_name];
	
		// 错误信息
		$error='';
			
		// 允许上传的文件类型数组
		$allow_type=array(
				'image'=>array(
						'jpg'=>'image/jpeg',
						'png'=>'image/png',
						'gif'=>'image/gif',
				),
				'pdf'=>array(
						'pdf'=>'application/pdf',
				),
				// 这里可以继续添加文件类型
		);
	
		// 检查上传文件的类型是否在允许的文件类型数组里
		if( !in_array($file['type'], $allow_type[$type]) ){
			$error="Please upload".implode('、', array_keys($allow_type[$type]) )."Format of the file";
			//Helper::show_message($error);die;
		}
	
		// 检查上传文件的大小是否超过指定大小
		$size=$allow_size*1024*1024;
		if( $file['size'] > $size ){
			$error="You upload the file size please don't over{$allow_size}MB";
			//Helper::show_message($error);die;
		}
	
		// 错误状态
		switch($file['error']){
			case 1:
				$error='You have uploaded file size is more than the size of the server configuration';
				//Helper::show_message($error);die;
			case 2:
				$error='You uploaded file size is more than the size of the form setting';
				//Helper::show_message($error);die;
			case 3:
				$error='Network problems, please check your Internet connection?';
				//Helper::show_message($error);die;
			case 4:
				$error='Please select you want to upload files';
				//Helper::show_message($error);die;
		}
	
		// 自动生成目录
		if ( !file_exists($file_path) ) {
			mkdir($file_path, 0777, true);
		}
	
		if($error){
			return array(
					'error'=>1,
					'warning'=>$error,
			);
		}
	
		// 生成保存到服务器的文件名
		$filename=date('YmdHis').mt_rand(1000,9999).".".array_search($file['type'], $allow_type[$type]);
		// 保存上传文件到本地目录
		if( move_uploaded_file($file['tmp_name'], $file_path."/".$filename) ){
			return array(
					'error'=>0,
					'filename'=>$filename,
			);
		}
	}
	
	
	
	public static function show_message($info, $url=''){
		header('Content-Type:text/html;charset=utf-8');
		?>
		<style>
		.pop-ups { position: fixed; top: 50%; left: 50%; background-color: #fff; border: 1px solid #e0e9f4; box-shadow: 1px 1px 1px #cbcbcb; box-sizing: border-box; overflow: hidden; }
		.pop-ups h3 { padding: 16px; margin: 0; background: #3f7fcf; text-align: center; color: #fff; }
		.pop-ups h3 a { display: inline-block; width: 28px; height: 28px; margin-top: -4px; background: url(img/lg_close.png) no-repeat; }
		#promptBox {
	    position: absolute;
	    top: 30%;
	    left: 40%;
	    width: 300px;
	    font: 14px Arial;
	    }
	    .btn input:last-child {
	    background-color: #ffb752;
	    border: medium none;
	    color: #fff;
	    cursor: pointer;
		}
	    
		#promptBox p {
		    text-align: center;
		}
	    .ui-dialog {
	    background: #fff none repeat scroll 0 0;
	    left: 525px;
	    padding: 0;
	    position: absolute;
	    top: 200px;
	    z-index: 1050 !important;
		}
	    .pop-ups {
	    position: fixed;
	    border: 1px solid #e0e9f4;
	    box-shadow: 1px 1px 1px #cbcbcb;
	    box-sizing: border-box;
	    overflow: hidden;
		}
		div {
	    display: block;
		}
		.ui-widget-overlay {
		background: rgba(0, 0, 0, 0.25);
		opacity: 1 !important;
		filter: alpha(opacity = 100) !important;
		z-index: 1040 !important;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%
		}
		
		.ui-front{z-index: 100;}
			</style>
			<div class='ui-widget-overlay ui-front'></div>
			<div id="promptBox" class="pop-ups write ui-dialog">
			<h3><?php echo yii::t('app','消息')?></h3>
			<p><?php echo yii::t('app',$info)?></p>
			<p class="btn">
			<input type="button" style="margin-right:0;padding: 4px 10px;" onclick="showmessage();" class="cancel_but" value="<?php echo yii::t('app','Ok')?>"></input>
			</p></div>
		<script type="text/javascript">
			function showmessage(){
				document.getElementById("promptBox").style.display = "none";
					<?php
					if($url && $url !='#'){
					echo "location='{$url}'";
					}else{
						echo "history.back();";
					}
					?>
				}
				</script>
			<?php 
			}
			
			
		/**
		 * 确认框,确定和取消跳转不同(1次弹出框)
		 */
		public static function show_message_query($info, $url='',$url_f=''){
			header('Content-Type:text/html;charset=utf-8');
			/*
			 if($url && $url !='#'){
				echo "<script>alert('{$info}');location='{$url}';</script>";
				}else if($url == '#'){
				echo "<script>alert('{$info}');</script>";
				}else{
				echo "<script>alert('{$info}');history.back();</script>";
				}*/
			echo "<script>var r = confirm('{$info}');
			if(r == true){
			location='{$url}';
			}else{
			location='{$url_f}';
			}
			</script>";
		}
		
		
		
		/**
		 * curl 发送请求
		 * @param unknown $url	需要發送請求的地址
		 * @param string $post  是否需要post
		 * @param string $cookie
		 * @param string $cookiejar
		 * @param string $referer
		 * @return mixed
		 */
		public static function vcurl($url, $post = '', $cookie = '', $cookiejar = '', $referer = '')
		{
			$tmpInfo = '';
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				
			if($referer) {
				curl_setopt($curl, CURLOPT_REFERER, $referer);
			} else {
				curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
			}
			if($post) {
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
			}
			if($cookie) {
				curl_setopt($curl, CURLOPT_COOKIE, $cookie);
			}
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$tmpInfo = curl_exec($curl);
			if (curl_errno($curl)) {
				echo '<pre><b>错误:</b><br />'.curl_error($curl);
			}
			curl_close($curl);
			return $tmpInfo;
		}
		
		
		
		/**
		 * curl發送請求
		 * @param unknown $url
		 * @param unknown $postFields
		 * @return mixed
		 */
		private function _curlPost($url,$postFields)
		{
			$postFields = http_build_query($postFields);
			$ch = curl_init ();
			curl_setopt ( $ch, CURLOPT_POST, 1 );
			curl_setopt ( $ch, CURLOPT_HEADER, 0 );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt ( $ch, CURLOPT_URL, $url );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postFields );
			$result = curl_exec ( $ch );
			curl_close ( $ch );
			return $result;
		}
		
		
		
		
		/**
		 * 创蓝国内手机验证码接口
		 * @param unknown $mobile [手機號碼]
		 * @param unknown $msg [驗證碼]
		 * @param number $needstatus
		 * @return unknown
		 */
		public static function sendMSN($mobile,$msg,$needstatus = 1)
		{
			//创蓝接口参数
			$postArr = array (
					'un' => Yii::$app->params['un'],
					'pw' => Yii::$app->params['pw'],
					'msg' =>'【憶條街】您的验证码是'.$msg.'，验证码 5 分钟内有效。请勿将验证码转发他人！',
					'phone' => $mobile,
					'rd' => $needstatus
			);
			$result = self::_curlPost('http://sms.253.com/msg/send',$postArr);
			return $result;
		}
		
		
		/**
		 * 请求发送
		 * @return string 返回状态报告
		 */
		private function _request($url){
			$ch=curl_init();
			curl_setopt($ch,CURLOPT_HEADER,0);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_URL,$url);
			$result=curl_exec($ch);
			curl_close($ch);
			return $result;
		}
		
	
		
		/**
		 * 国际短信发送
		 * @param unknown $phone [手機號碼]
		 * @param unknown $content 短信内容
		 * @param number $isreport 是否需要状态报告
		 */
		public  static function sendInternational($phone,$content,$isreport=0){
			$text = '【憶條街】您的驗證碼是'.$content.'，驗證碼 5 分鍾內有效。請勿將驗證碼轉發他人！';
			$requestData=array(
					'un'=>Yii::$app->params['uns'],
					'pw'=>Yii::$app->params['pws'],
					'sm'=>$text,
					'da'=>$phone,
					'rd'=>$isreport,
					'rf'=>2,
					'tf'=>3,
			);
			$param='un='.Yii::$app->params['uns'].'&pw='.Yii::$app->params['pws'].'&sm='.urlencode($text).'&da='.$phone.'&rd='.$isreport.'&rf=2&tf=3';
			$url='http://222.73.117.140:8044/mt?'.$param;//单发接口
			//$url='http://222.73.117.140:8044/batchmt'.'?'.$param;//群发接口
			return self::_request($url);
		}
		
		
		
		
		public static function notifyNoPaper($phone,$isreport=0)
		{
			$text = '【憶條街】自助售票機缺紙，請及時補充！';
			$requestData=array(
					'un'=>Yii::$app->params['uns'],
					'pw'=>Yii::$app->params['pws'],
					'sm'=>$text,
					'da'=>$phone,
					'rd'=>$isreport,
					'rf'=>2,
					'tf'=>3,
			);
			$param='un='.Yii::$app->params['uns'].'&pw='.Yii::$app->params['pws'].'&sm='.urlencode($text).'&da='.$phone.'&rd='.$isreport.'&rf=2&tf=3';
			$url='http://222.73.117.140:8044/mt?'.$param;//单发接口
			//$url='http://222.73.117.140:8044/batchmt'.'?'.$param;//群发接口
			return self::_request($url);
		}
		
		
		
		/**
		 * 生成二维码
		 * @param string $url url连接
		 * @param integer $size 尺寸 纯数字
		 */
		public static function qrcode($url,$size=4){
			return \QRcode::png($url,false,QR_ECLEVEL_L,$size,2,false,0xFFFFFF,0x000000);
		}
		
		
		
		
		/**
		 * 大陸手机号码格式验证
		 * @param unknown $data
		 * @return number
		 */
		public static function isPhone($data) {
			$search ='/^1(3|4|5|7|8)\d{9}$/';
			if (preg_match($search, $data)) {
				return true;
			} else {
				return false;
			}
		}
		
		
		
		/**
		 * 澳門手机号码格式验证
		 * @param unknown $data
		 * @return boolean
		 */
		public static function isMacauPhone($data) {
			$search ='/^6\d{7}$/';
			if (preg_match($search, $data)) {
				return true;
			} else {
				return false;
			}
		}
		
		
		
		/** 生成不重复的随机数
		 * @param number $start 需要生成的数字开始范围
		 * @param number $end 结束范围
		 * @param number $length 需要生成的随机数个数
		 */
		public static function get_rand_number($start=1,$end=10,$length=4){
			$connt=0;
			$temp=array();
			while($connt<$length){
				$temp[]=rand($start,$end);
				$data=array_unique($temp);
				$connt=count($data);
			}
			sort($data);
			return $data;
		}
		
		
		
		/**
		 * 获取用户ip
		 * @return string|unknown
		 */
		public static function getIp() {
			if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) $ip = getenv("HTTP_CLIENT_IP");
			else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) $ip = getenv("HTTP_X_FORWARDED_FOR");
			else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) $ip = getenv("REMOTE_ADDR");
			else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) $ip = $_SERVER['REMOTE_ADDR'];
			else $ip = "unknown";
			return ($ip);
		}
		
		
		
		/**
		 *  汇率查询
		 * @return mixed|string
		 */
		public static function rate(){
			$appkey = Yii::$app->params['appKey'];
			//************实时汇率查询换算************
			$url = "http://op.juhe.cn/onebox/exchange/currency";
			$params = array(
					"from" => "cny",//转换汇率前的货币代码
					"to" => "mop",//转换汇率成的货币代码
					"key" => $appkey,//应用APPKEY(应用详细页查询)
			);
			$paramstring = http_build_query($params);
			$content = self::vcurl($url,$paramstring);
			$result = json_decode($content,true);
			if($result){
				if($result['error_code']=='0'){
					$mop = $result['result'][1]['result'];
					return $mop;
				}else{
					return $result['error_code'].":".$result['reason'];
				}
			}else{
				return "请求失败";
			}
		}
		
		
		
		/**
		 * 订单驗證碼
		 * @return string
		 */
		public static function get_order_sn(){
			return date('YmdHi') . str_pad(mt_rand(1, 99999), 4, '0', STR_PAD_LEFT);
		}
		
		
		/**
		 * 获取一定范围内的随机数字
		 * 跟rand()函数的区别是 位数不足补零 例如
		 * rand(1,9999)可能会得到 465
		 * rand_number(1,9999)可能会得到 0465  保证是4位的
		 * @param integer $min 最小值
		 * @param integer $max 最大值
		 * @return string
		 */
		function rand_number ($min=1, $max=9999) {
			return sprintf("%0".strlen($max)."d", mt_rand($min,$max));
		}
		
		
		/**
		 * 訂單流水號
		 * @return string
		 */
		public static function createOrderno()
		{
			$my_code = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			 
			$order_sn = $my_code[intval(date('m'))].(intval(date('d')) < 10 ? intval(date('d')) : $my_code[(intval(date('d'))-10)]).date('Y')
			.substr(time(),-5).substr(microtime(),2,5)
			.sprintf('%02d', rand(0, 99));
			 
			return $order_sn;
		}
		
		
		
		/**
		 * ping++  支付
		 */
		public static function pay($money,$orderNo,$channel)
		{

			$api_key = Yii::$app->params['API_KEY'];
			$app_id = Yii::$app->params['PAPP_ID'];
			
			//引入你的签名私钥
			$path = dirname(dirname(__FILE__)).'/components/pingpp/rsa_private_key.pem';
			\Pingpp\Pingpp::setPrivateKeyPath($path);
				
				
			//$extra用于设置支付渠道所需的额外参数，额外参数多数是可选，请根据需求来决定。详情看参考文档
			$extra = [];
			
			switch ($channel) {
				case 'alipay_wap':
					$extra = array(
						// success_url 和 cancel_url 在本地测试不要写 localhost ，请写 127.0.0.1。URL 后面不要加自定义参数
						'success_url' =>Yii::$app->request->getHostInfo().'/'. Yii::$app->params['pay_success_url'],
						'cancel_url' => Yii::$app->request->getHostInfo().'/'.Yii::$app->params['pay_cancel_url'],
					);
					break;
				case 'bfb_wap':
					$extra = array(
					'result_url' => 'http://example.com/result',// 百度钱包同步回调地址
					'bfb_login' => true// 是否需要登录百度钱包来进行支付
					);
					break;
				case 'upacp_wap':
					$extra = array(
					'result_url' => 'http://example.com/result'// 银联同步回调地址
					);
					break;
				case 'wx_pub':
					
					$wx_code = $_COOKIE['wx_code'];
					
					$cookies = Yii::$app->request->cookies;
					
					$wx_code = $cookies->getValue('wx_code');
					
					$wx_app_id = Yii::$app->params['wx_app_id'];
					$wx_app_secret = Yii::$app->params['wx_app_secret'];
					$open_id = WxpubOAuth::getOpenid($wx_app_id, $wx_app_secret, $wx_code);
					$extra = array(
						'open_id' => $open_id// 用户在商户微信公众号下的唯一标识，获取方式可参考 pingpp-php/lib/WxpubOAuth.php
					);
					break;
				case 'wx_pub_qr':
					$extra = array(
					'product_id' => 'Productid'// 为二维码中包含的商品 ID，1-32 位字符串，商户可自定义
					);
					break;
				case 'yeepay_wap':
					$extra = array(
					'product_category' => '1',// 商品类别码参考链接 ：https://www.pingxx.com/api#api-appendix-2
					'identity_id'=> 'your identity_id',// 商户生成的用户账号唯一标识，最长 50 位字符串
					'identity_type' => 1,// 用户标识类型参考链接：https://www.pingxx.com/api#yeepay_identity_type
					'terminal_type' => 1,// 终端类型，对应取值 0:IMEI, 1:MAC, 2:UUID, 3:other
					'terminal_id'=>'your terminal_id',// 终端 ID
					'user_ua'=>'your user_ua',// 用户使用的移动终端的 UserAgent 信息
					'result_url'=>'http://example.com/result'// 前台通知地址
					);
					break;
				case 'jdpay_wap':
					$extra = array(
					'success_url' => 'http://example.com/success',// 支付成功页面跳转路径
					'fail_url'=> 'http://example.com/fail',// 支付失败页面跳转路径
					/**
					*token 为用户交易令牌，用于识别用户信息，支付成功后会调用 success_url 返回给商户。
					*商户可以记录这个 token 值，当用户再次支付的时候传入该 token，用户无需再次输入银行卡信息
					*/
					'token' => 'dsafadsfasdfadsjuyhfnhujkijunhaf' // 选填
					);
					break;
			}
			
			
			\Pingpp\Pingpp::setApiKey($api_key); //设置API-KEY
				
			try {
				//create方法表示发送支付请求到ping++平台，$ch表示请求成功时返回的charge对象（json格式），服务器端如果发起请求成功，此时只需要把charge对象传递给APP客户端，交给客户端处理
				$ch = \Pingpp\Charge::create([
						'subject'   => '电影票', //关于这些参数的意义，请参考文档https://www.pingxx.com/api#api-c-new
						'body'      => '憶條街电影票',
						'amount'    => $money,
						'order_no'  => $orderNo,
						'currency'  => 'cny',    //货币代码
						'extra'     => $extra,
						'channel'   => $channel,
						'client_ip' => $_SERVER['REMOTE_ADDR'],
						'app'       =>  ['id' => $app_id]
				]);
				
				return $ch;
				
			} catch (\Pingpp\Error\Base $e) { //如果发起支付请求失败，则抛出异常
				// 捕获报错信息
				if ($e->getHttpStatus() != NULL) {
					header('Status: ' . $e->getHttpStatus());
					echo $e->getHttpBody();
				} else {
					echo $e->getMessage();
				}
			}
			
		}
		
		
		
		/**
		 * App中的 支付宝 支付
		 * @param unknown $money
		 * @param unknown $orderNo
		 * @param unknown $channel
		 */
		public static function AppPay($money,$orderNo,$channel)
		{
			$api_key = Yii::$app->params['API_KEY'];
			$app_id = Yii::$app->params['PAPP_ID'];
			
			//引入你的签名私钥
			$path = dirname(dirname(__FILE__)).'/components/pingpp/rsa_private_key.pem';
			\Pingpp\Pingpp::setPrivateKeyPath($path);
			$extra = array();
			\Pingpp\Pingpp::setApiKey($api_key); //设置API-KEY
			
			
			try {
				//create方法表示发送支付请求到ping++平台，$ch表示请求成功时返回的charge对象（json格式），服务器端如果发起请求成功，此时只需要把charge对象传递给APP客户端，交给客户端处理
				$ch = \Pingpp\Charge::create([
					'subject'   => '电影票', //关于这些参数的意义，请参考文档https://www.pingxx.com/api#api-c-new
					'body'      => '憶條街电影票',
					'amount'    => $money,
					'order_no'  => $orderNo,
					'currency'  => 'cny',    //货币代码
// 					'extra'     => $extra,
					'channel'   => $channel,
					'client_ip' => $_SERVER['REMOTE_ADDR'],
					'app'       =>  ['id' => $app_id]
				]);
				
				return $ch;
				
			} catch (\Pingpp\Error\Base $e) { //如果发起支付请求失败，则抛出异常
				// 捕获报错信息
				if ($e->getHttpStatus() != NULL) {
					header('Status: ' . $e->getHttpStatus());
					echo $e->getHttpBody();
				} else {
					echo $e->getMessage();
				}
			}
			
		}
		
		
		
		
		

		/* *
		 * 验证 webhooks 签名方法：
		 * raw_data：Ping++ 请求 body 的原始数据即 event ，不能格式化；
		 * signature：Ping++ 请求 header 中的 x-pingplusplus-signature 对应的 value 值；
		 * pub_key_path：读取你保存的 Ping++ 公钥的路径；
		 * pub_key_contents：Ping++ 公钥，获取路径：登录 [Dashboard](https://dashboard.pingxx.com)->点击管理平台右上角公司名称->开发信息-> Ping++ 公钥
		 */
		function verify_signature($raw_data, $signature, $pub_key_path) {
		    $pub_key_contents = file_get_contents($pub_key_path);
		    // php 5.4.8 以上，第四个参数可用常量 OPENSSL_ALGO_SHA256
		    return openssl_verify($raw_data, base64_decode($signature), $pub_key_contents, 'sha256');
		}
		
		/**
		 *  获取星期
		 * @param unknown $date
		 * @return string
		 */
		public static function getWeek($date)
		{
			//强制转换日期格式
			$date_str=date('Y-m-d',strtotime($date));
		
			//封装成数组
			$arr=explode("-", $date_str);
				
			//参数赋值
			//年
			$year=$arr[0];
				
			//月，输出2位整型，不够2位右对齐
			$month=sprintf('%02d',$arr[1]);
				
			//日，输出2位整型，不够2位右对齐
			$day=sprintf('%02d',$arr[2]);
				
			//时分秒默认赋值为0；
			$hour = $minute = $second = 0;
				
			//转换成时间戳
			$strap = mktime($hour,$minute,$second,$month,$day,$year);
				
			//获取数字型星期几
			$number_wk=date("w",$strap);
				
			//自定义星期数组
			$weekArr=array("周日","周一","周二","周三","周四","周五","周六");
				
			//获取数字对应的星期
			return $weekArr[$number_wk];
		
		}
		
		
		
		/**
		 * 判断 今天，明天，后天，和星期几
		 * @param unknown $date
		 * @return string
		 */
		public static function getToday($date)
		{
			//强制转换日期格式
			$date_str=date('Y-m-d',strtotime($date));
			
			//封装成数组
			$arr=explode("-", $date_str);
			
			//日，输出2位整型，不够2位右对齐
			$day=sprintf('%02d',$arr[2]);
			
			$now = date('Y-m-d',time());
			
			//封装成数组
			$now_arr=explode("-", $now);
			
			
			$now_day = sprintf('%02d',$now_arr[2]);
			
			if($day == $now_day){
				return "今天";
			} else if( $day - $now_day == 1){
				return "明天";
			} else if ($day - $now_day == 2) {
				return "后天";
			} else {
				return self::getWeek($date);
			}
			
		}
		
		
		/**
		 * 获取几月几号
		 * @param unknown $date
		 * @return string
		 */
		public static function getTimeFormat($date)
		{
			$tmp_time = explode(" ", $date)[0];
			
			$format_arr = explode("-", $tmp_time);
			
			$response = $format_arr[1] ."月".$format_arr[2]."日";
			
			return $response;
		}
		
		
		
		
		/**
		 * 去除二维数组的重复内容
		 * @param unknown $array
		 */
		public static function MyArrayUnique($array2D,$stkeep=false,$ndformat=true)
		{
			
			if(!empty($array2D)){
				$joinstr = "+++++";
				// 判断是否保留一级数组键 (一级数组键可以为非数字)
				if($stkeep) $strArray = array_keys($array2D);
				// 判断是否保留二级数组键 (所有二级数组键必须相同)
				if($ndformat) $ndArr = array_keys(end($array2D));
				//降维,也可以用implode,将一维数组转换为用逗号连接的字符串
				foreach ($array2D as $v){
					$v = join($joinstr,$v);
					$temp[] = $v;
				}
					
				//去掉重复的字符串,也就是重复的一维数组
				$temp = array_unique($temp);
				//再将拆开的数组重新组装
				foreach ($temp as $k => $v){
					if($stkeep) $k = $stArr[$k];
					if($ndformat){
						$tempArr = explode($joinstr,$v);
						foreach($tempArr as $ndkey => $ndval) $output[$k][$ndArr[$ndkey]] = $ndval;
					}
					else $output[$k] = explode($joinstr,$v);
				}
					
				return $output;
				
			} else {
				
				return $array2D;
				
			}
			
		}
		
		
		/**
		 * 获取微信code的重定向前的url
		 * @param unknown $ssid
		 * @return string
		 */
		public static function GetWxCodeUrl($ssid)
		{
			$redirect_url = Yii::$app->params['redirect_url'];
			$wx_app_id = Yii::$app->params['wx_app_id'];
			$wx_app_secret = Yii::$app->params['wx_app_secret'];
			
			$code_url = WxpubOAuth::createOauthUrlForCode($wx_app_id, $redirect_url,$ssid);
			
			return $code_url;
		}
			
		
		/**
		 * 判断是否微信打开
		 * @return boolean
		 */
		public static function isWechatBrowser()
		{
			if (strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') !== false ) {
				return true;
			}
			
			return false;
		}
		
		
}