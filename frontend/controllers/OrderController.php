<?php
namespace frontend\controllers;


use yii\web\Controller;
use frontend\components\Helper;
use Yii;
use frontend\models\MovieSeat;
use frontend\models\MovieOnlineOrder;
use frontend\models\MovieShow;




class OrderController extends Controller
{

	public $layout = 'mylayout';
	
	
	/**
	 * 位置選擇
	 */
	public function actionPickseat()
	{
		$response = [];
		
		
		if(!isset(Yii::$app->user->identity->user_id)){
			$response = ['code'=> 1,'msg' => "請先登錄"];
			echo json_encode($response);
			Yii::$app->end();
		}
		
		
		if(Yii::$app->request->isPost){
			if(!empty(Yii::$app->request->post('seatArray'))){
				$movie_id = Yii::$app->request->post('movie_id');
				$seatArray = Yii::$app->request->post('seatArray');
				
				$movie_show = MovieShow::find()->where('id = :id',[':id'=>$movie_id])->one();
				
				$online_order = new MovieOnlineOrder();
				
				$seat_ids = "";
				$seat_names = "";
				
				$transaction = Yii::$app->db->beginTransaction();
				try{
					
					foreach($seatArray as $row){
						
						$movie_seat = MovieSeat::find()->where('seat_id = :seat_id and id = :id',[':seat_id'=>$row,':id'=>$movie_id])->one();
						if(!is_null($movie_seat)){
							$response = ['code'=> 4,'msg' => "該位置已被預訂"];
							echo json_encode($response);
							Yii::$app->end();
						}
						
						$seat = new MovieSeat();
						$seat->show_id = $movie_id;
						$seat->seat_id = $row;
						$seat->seat_name = explode("_", $row)[0]."排".explode("_", $row)[1]."座";
						$seat->cur_time = date("Y-m-d H:i:s",time());
						$seat->status = 1;
							
						$seat_ids .= $row.",";
						$seat_names .= explode("_", $row)[0]."排".explode("_", $row)[1]."座".",";
							
						$seat->save();
					}
					
					
					$online_order->movie_show_id = $movie_id;
					$online_order->phone = Yii::$app->user->identity->user_phone;
					$online_order->seat_ids = rtrim($seat_ids,",");
					$online_order->seat_names = rtrim($seat_names,",");
					$online_order->order_time = date("Y-m-d H:i:s",time());
					$online_order->price = $movie_show->price;
					$online_order->count = count($seatArray);
					$online_order->total_money = (int)$movie_show->price * (int)count($seatArray);
					$online_order->order_code = date('Ymd',time()).Helper::rand_number().Helper::rand_number();//Helper::get_order_sn();
					$online_order->order_number = Helper::createOrderno();
					$online_order->status = 0;
					
					$online_order->save();
					
					$transaction->commit();
					
					$response = ['code'=> 0,'ssid'=>$online_order->order_code];
					
				} catch (Exception $e){
					
					$transaction->rollBack();
					$response = ['code'=> 2,'msg' => "出現了錯誤"];
				}
			} else {
				$response = ['code'=> 3,'msg' => "請選擇座位"];
			}
		}
		
		echo json_encode($response);
		
	}
	
	
	
	/**
	 * 支付界面
	 * @return \yii\web\Response|string
	 */
	public function actionPay()
	{
		if(!isset(Yii::$app->user->identity->user_id)){
			return $this->redirect('/user/login');
			Yii::$app->end();
		}
		
		if(empty(Yii::$app->request->get("ssid"))){
			return $this->redirect('/index/index');
			Yii::$app->end();
		}
		
		$ssid = Yii::$app->request->get('ssid');
		
		$online_order = MovieOnlineOrder::find()->where('order_code = :ssid',[':ssid'=>$ssid])->one();
		
		if(is_null($online_order)){
			return $this->redirect('/index/index');
			Yii::$app->end();
		}
		
		$data = MovieOnlineOrder::findOrderDetail($online_order);
		
		
		return $this->render('pay',['data'=>$data]);
	}
	
	
	
	
	/**
	 * 訂單列表界面
	 * @return \yii\web\Response|string
	 */
	public function actionOrderlist()
	{
		if(!isset(Yii::$app->user->identity->user_id)){
			return $this->redirect('/user/login');
			Yii::$app->end();
		}
		return $this->render('orderlist');
	}
	
	
	public function actionOrderdetail()
	{
		
		if(!isset(Yii::$app->user->identity->user_id)){
			return $this->redirect('/user/login');
			Yii::$app->end();
		}
		
		if(empty(Yii::$app->request->get("ssid"))){
			return $this->redirect('/index/index');
			Yii::$app->end();
		}
	
		$ssid = Yii::$app->request->get('ssid');
		
		$online_order = MovieOnlineOrder::find()->where('order_code = :ssid',[':ssid'=>$ssid])->one();
		
		if(is_null($online_order)){
			return $this->redirect('/index/index');
			Yii::$app->end();
		}
		
		$data = MovieOnlineOrder::findOrderDetail($online_order);
		
		
		return $this->render('orderdetail',['data'=>$data]);
	}
	
	
	public function actionQr()
	{
		$ssid = Yii::$app->request->get('ssid');
		return Helper::qrcode($ssid);
	}
	
	
	/**
	 *獲取用戶訂單數據 
	 */
	public function actionJsonurl()
	{
		$page = Yii::$app->request->get("page");
		$phone = Yii::$app->user->identity->user_phone;
		
		$datas = MovieOnlineOrder::getUserOrder($page, $phone);
		echo json_encode($datas);
	}
	

	
	/**
	 * 支付頁面
	 * @return string
	 */
	public function actionPayment()
	{
		if(!isset(Yii::$app->user->identity->user_id)){
			return $this->redirect('/user/login');
			Yii::$app->end();
		}
		
		if(empty(Yii::$app->request->get("ssid"))){
			return $this->redirect('/index/index');
			Yii::$app->end();
		}
		
		$ssid = Yii::$app->request->get('ssid');
		
		$online_order = MovieOnlineOrder::find()->where('order_code = :ssid',[':ssid'=>$ssid])->one();
		
		if(is_null($online_order)){
			return $this->redirect('/index/index');
			Yii::$app->end();
		}
		
		
		//超過15分鐘，跳轉回首頁
		if(strtotime($online_order->order_time) < time()-900){
			return $this->redirect('/index/index');
			Yii::$app->end();
		}
		
	
		$data = MovieOnlineOrder::findOrderDetail($online_order);
	
		
		
		return $this->render('payment',['data'=>$data]);
	}
	
	
	/**
	 * P++支付接口 
	 */
	public function actionP_pay()
	{
		
		if(Yii::$app->request->isPost){
			$ssid = Yii::$app->request->post("ssid");
			$channel = Yii::$app->request->post("channel");
			
			
			$online_order = MovieOnlineOrder::find()->where('order_code = :ssid',[':ssid'=>$ssid])->one();
			$data = MovieOnlineOrder::findOrderDetail($online_order);
			
			
			if($channel == 1){
				$channel = "alipay_wap";
			} else if($channel == 2) {
				$channel = "wx_pub";
			}
			
			//汇率
			$mop = Helper::rate();
			$money = $data['total_money'] *100 * $mop;

			//調用支付接口
			$ch = Helper::pay($money, $data['order_number'], $channel);

			//返回的消息
			$response = json_decode($ch,true);
			$id = $response['id'];
			
			//寫入支付方式
			MovieOnlineOrder::updateAll(['payment'=>$channel,'charge_id'=>$id,'pay_time'=>date("Y-m-d H:i:s",time())],'order_number = :order_number',[':order_number'=>$data['order_number']]);
			echo $ch;
			
		}
	}
		
	/**
	 * 支付成功后的跳转页面
	 * @return string
	 */
	public function actionSuccess()
	{
		return $this->render("success");
	}
	
	/**
	 * 取消支付后的跳转页面
	 * @return string
	 */
	public function actionCancel()
	{
		return $this->render('cancel');
	}
	

	
	/**
	 * 判断是否在微信客户端打开链接
	 * 如果是就跳转到微信code的重定向url地址
	 * 如果不是就跳到支付宝支付界面
	 */
	public function actionGetcode()
	{
		$ssid = Yii::$app->request->get('ssid');
		$url = Helper::GetWxCodeUrl($ssid);
		
		$isWechat = helper::isWechatBrowser();
		
		if($isWechat){
			header("Location: $url");
			exit();
		} else {
			$this->redirect(['order/payment','ssid'=>$ssid]);
		}
	}
	
	
	/**
	 * 通过微信重定向url获取code，
	 * 并且把code设置为cookie
	 */
	public function actionGetwxcode()
	{
		$code = Yii::$app->request->get('code');
		$ssid = Yii::$app->request->get('state');
	
		
		if(!empty($code)){
			$cookies = Yii::$app->response->cookies;
			$cookies->add(new \yii\web\Cookie([
					'name' => 'wx_code',
					'value' => $code,
					'expire'=>time()+3600,
			]));
		}
		
		$this->redirect(['/order/payment','ssid'=>$ssid]);
	
	}
	
	
	
	
	// 	public function actionResult()
	// 	{
	// 		return $this->render('result');
	// 	}
	
}