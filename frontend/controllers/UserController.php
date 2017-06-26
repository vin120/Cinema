<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use frontend\components\Helper;
use frontend\models\Code;
use frontend\models\User;
use frontend\components\MyUrl;

class UserController extends Controller 
{
	
	public $layout = 'mylayout';
	
	

	/** 登錄
	 * @return \yii\web\Response|string
	 */
	public function actionLogin()
	{
		$model = new User;
	
		
		if(isset(Yii::$app->user->identity->user_id)){
			return $this->redirect('/user/index');
			Yii::$app->end();
		}
		
		
		if (Yii::$app->request->isPost) {
			$post = Yii::$app->request->post();
			if ($model->login($post)) {
				//跳轉url
				MyUrl::RefferUrl();
			}
		}
		
		return $this->render("login", ['model' => $model]);
	
	}
	
	
	/** 登出
	 * @return \yii\web\Response
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();
		Yii::$app->session->destroy();
		
		$this->redirect('/user/login');
	}
	
	
	
	
	
	public function actionIndex()
	{
		if(!isset(Yii::$app->user->identity->user_id)){
			return $this->redirect('/user/login');
			Yii::$app->end();
		}
		
		
// 		$user_id = Yii::$app->user->identity->user_id;
		
		
		
		return $this->render('index');
	}
	
	
	
	/** 
	 * 個人訂單列表
	 * @return string
	 */
	public function actionOrderlist()
	{
		return $this->render('orderlist');
	}
	
	
	
	
	/**
	 * 注册页面
	 * @return string
	 */
	public function actionRegist()
	{
		$model = new User;
		
		if(Yii::$app->request->isPost){
			$post = Yii::$app->request->post();
			
			if($model->verifyPhone($post)){
				$this->redirect('/user/verify');
			}
		}
		
		return $this->render('regist',['model'=>$model]);
		
	}
	
	
	/**
	 * 忘記密碼頁面
	 * @return string
	 */
	public function actionFindme()
	{
		$model = new User;
		
		if(Yii::$app->request->isPost){
			$post = Yii::$app->request->post();
				
			if($model->verifyPhonefindme($post)){
		
				$this->redirect('/user/verify');
			}
		}
		
		return $this->render('findme',['model'=>$model]);
	}
	
	
	/**
	 * 驗證頁面
	 * @return string
	 */
	public function actionVerify()
	{
		if(empty(Yii::$app->session->get('regist_phone'))){
			$this->redirect('/user/regist');
			Yii::$app->end();
		}
		
		
		$model = new Code();
		if(Yii::$app->request->isPost){
			$post = Yii::$app->request->post();
			
			if($model->verityCode($post)){
				$this->redirect('/user/password');
			}
			
		}
		
		
		return $this->render('verify',['model'=>$model]);
	}
	
	
	
	/**
	 * 輸入密碼頁面
	 * @return string
	 */
	public function actionPassword()
	{
		
		$regist_phone = Yii::$app->session->get('regist_phone');
		$regist_code = Yii::$app->session->get('regist_code');
		
		$code = Code::find()->where('c_phone = :c_phone ',[':c_phone'=>$regist_phone])->one();
		$model = new User;
		
		//如果session爲空或者或者找不到code信息
		if(empty($regist_code) || empty($regist_code) || empty($code)){
			$this->redirect('/user/regist');
			Yii::$app->end();
		}
		
		//code的驗證過期 或者 驗證碼不對
		if($code->c_time < time() || $code->c_code != $regist_code){
			$this->redirect('/user/regist');
			Yii::$app->end();
		}
		
		if(Yii::$app->request->isPost){
			$post = Yii::$app->request->post();
			
			if($model->verityPassword($post)){
				$this->redirect('/user/index');
			}
		}
		
		
		return $this->render('password',['model'=>$model]);
	}
	
	
	
// 	public function actionTestttttt()
// 	{
// 		$model = new User;
		
// 		if(Yii::$app->request->isPost){
// 			//手機號碼
// 			$user_phone = Yii::$app->request->post('user_phone');
				
				
				
// 			//密碼
// 			$user_password = Yii::$app->request->post('user_password');
// 			//驗證碼
// 			$c_code = Yii::$app->request->post('c_code');
				
// 			$code = Code::find()->where('c_phone = :cp and c_code = :cd',[':cp'=>$user_phone,':cd' =>$c_code])->one();
				
// 			if($code) {
		
// 				if(time() - $code->c_time < 300){
						
// 					if($c_code != $code->c_code){
// 						//驗證碼錯誤
// 						//後續處理  todo
// 						return $this->redirect(['user/regist']);
// 					}
						
// 					//入庫
// 					//查找數據庫中有沒有這個手機號碼，如果有，就更新密碼，如果沒有，就插入
// 					$user = User::find()->where('user_phone = :up',[':up' =>$user_phone]) ->one();
// 					if(!$user) {
// 						$user = new User();
// 						$user->user_phone = $user_phone;
// 						$user->user_addtime = time();
// 						$user->user_stat = 0;
// 					}
		
// 					$user->user_title = $user_phone;
// 					$user->user_password = $user_password;
// 					$user->user_name="";
// 					$user->user_sex=0;
// 					$user->user_img="";
// 					$user->openid="";
// 					$user->appsecret="";
// 					$user->save();
						
						
// 					return $this->redirect(['user/login']);
// 				} else {
						
// 					//註冊碼過期
// 					//todo
// 					return $this->redirect(['user/regist'],["message"=>"驗證碼過期"]);
// 				}
		
					
// 			} else {
// 				return $this->redirect(['user/regist']);
// 			}
// 		}
		
		
		
// 		return $this->render('regist',['model'=>$model]);
// 	}
	
	
	
	
	/**
	 * 註冊時發送驗證碼
	 */
// 	public function actionSendmsntoregist()
// 	{
		
// 		$user_phone = Yii::$app->request->get('user_phone');
// 		$area = Yii::$app->request->get('area');
		
		
// 		$c_code = Helper::get_rand_number('100000','999999',1)[0];
// 		$response = [];
// 		$ch = '';

// 		//判斷手機的區號，格式
// 		if($user_phone) {
// 			if($area == 86) {
// 				if(!Helper::isPhone($user_phone)){
// 					$response['error'] = 1;
// 					$response['message'] = "請填寫正確的手機號碼";
// 					echo json_encode($response);
// 					exit;
// 				}
				
				
// // 				//查找數據庫中是否已經存在該用戶了 
// // 				$user = User::find()->where('user_phone = :up',[':up'=>$user_phone])->one();
// // 				if($user) {
// // 					//如果存在，則直接返回錯誤信息
// // 					$response['error'] = 1;
// // 					$response['message'] = "該用戶已經註冊過了";
// // 					echo json_encode($response);
// // 					exit;
// // 				}
				
				
// 				//国内通道
// 				$ch = Helper::sendMSN($user_phone, $c_code);
// 			} else {
// 				//國外通道
// 				$phones = $area.$phone;
// 				$ch = Helper::sendInternational($phones, $c_code);
// 			}
			
// 			$response['message'] = $ch;
	
// 			//保存驗證碼到數據庫中
// 			Code::saveCode($user_phone,$c_code);
			
// 			$response['error'] = 0;
// 			echo json_encode($response);
// 			exit;
		
// 		} else {
// 			$response['error'] = 1;
// 			$response['message'] = "請填寫正確的手機號碼";
// 			echo json_encode($response);
// 			exit;
// 		}
		
// 	}
	
	
	
}




