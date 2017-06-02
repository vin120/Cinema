<?php

namespace backend\components;

use Yii;
use yii\web\Controller;

$assets = '@backend/views/static';
$baseUrl = Yii::$app->assetManager->publish($assets);

class Helper extends Controller
{


	/**文件上传**/
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
		/**确认框,确定和取消跳转不同(1次弹出框)**/
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
		
		//return 2015-12-14 12:23:34
		public static function GetCreateTime($time){//时间格式转换
			$time = explode(' ', $time);
			$year = explode('/', $time[0]);
			$date = $year[2].'-'.$year[1].'-'.$year[0].' '.$time[1];
			
			return $date;
		}
		
		//return 09/05/2016 12:23:13
		public static function GetDate($time){
			$time = explode(' ', $time);
			$year = explode('-', $time[0]);
			$date = $year[2].'/'.$year[1].'/'.$year[0].' '.$time[1];
			return $date;
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
		
		
		
			
}