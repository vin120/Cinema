<?php
namespace frontend\models;


use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use frontend\components\Helper;
use frontend\models\Code;


class User extends ActiveRecord implements IdentityInterface
{
	
	
	public $area;
	public $repassword;
	private $_user = false;
	
	
	public static function tableName()
	{
		return "{{%y_user}}";
	}
	
	
	
	public function rules()
	{
		return [
			[['user_phone'], 'required', 'message' =>'手機號不能爲空', 'on' => ['login','verifyphone','verifyphonefindme']],
			[['user_password'],'required','message'=>'密碼不能爲空','on'=>['login','veritypassword']],
			[['repassword'],'required','message'=>'重複密碼不能爲空','on'=>['veritypassword']],
			[['repassword'],'compare','compareAttribute'=>'user_password','message'=>'兩次密碼必須一致','on'=>['veritypassword']],
			[['user_password','repassword'],'string','length'=>[6,12],'tooLong'=>'密碼最多爲12位','tooShort'=>'密碼最少爲6位','on'=>['veritypassword']],
			[['area'],'required','message'=>'區號不能爲空','on'=>['verifyphone','verifyphonefindme']],
			[['user_password'],'validatePass','on'=>['login']],
			[['user_phone'],'validatePhone','on'=>['verifyphone']],
			[['user_phone'],'validatePhoneFindme','on'=>['verifyphonefindme']],
			[['user_title','user_name','user_password','user_sex','user_img','user_phone','user_addtime','openid','user_stat','appsecret'],'safe','on'=>['saveinfo']],	
		];
	}
	
	
	
	/**
	 * 自定義登錄密碼驗證規則
	 */
	public function validatePass()
	{
		if(!$this->hasErrors()){
			$data = self::find()->where('user_phone = :user_phone and user_password = :user_password',[':user_phone' =>$this->user_phone ,':user_password'=>md5($this->user_password)])->one();
			if(is_null($data)){
				$this->addError("user_password","用戶名或密碼錯誤");
			}
		}
	}
	
	
	/**
	 *  自定義手機號碼驗證規則
	 */
	public function validatePhone()
	{
		if(!$this->hasErrors()){
			if($this->area == '86'){
				//中國大陸
				if(!Helper::isPhone($this->user_phone)){
					$this->addError("user_phone","用輸入正確的手機號碼");
				}
			}else if($this->area == '853'){
				//澳門
				if(!Helper::isMacauPhone($this->user_phone)){
					$this->addError("user_phone","用輸入正確的手機號碼");
				}
			}
			
			//驗證手機號碼是否已經被註冊
			$phone = self::find()->where('user_phone = :user_phone',[':user_phone'=>$this->user_phone])->one();
			if(!is_null($phone)){
				$this->addError("user_phone","該手機號碼已被註冊");
			}
		}
	}
	
	/**
	 *  自定義手機號碼驗證規則
	 */
	public function validatePhoneFindme()
	{
		if(!$this->hasErrors()){
			if($this->area == '86'){
				//中國大陸
				if(!Helper::isPhone($this->user_phone)){
					$this->addError("user_phone","用輸入正確的手機號碼");
				}
			}else if($this->area == '853'){
				//澳門
				if(!Helper::isMacauPhone($this->user_phone)){
					$this->addError("user_phone","用輸入正確的手機號碼");
				}
			}
				
			//驗證手機號碼是否已經被註冊
			$phone = self::find()->where('user_phone = :user_phone',[':user_phone'=>$this->user_phone])->one();
			if(is_null($phone)){
				$this->addError("user_phone","帳號不存在，請重新輸入");
			}
		}
	}
	
	
	
	
	/**
	 * 登录函数
	 * @param unknown $data 登录的帐号和密码
	 * @return boolean
	 */
	public function login($data)
	{
		$this->scenario = "login";
		
		if($this->load($data) && $this->validate()) {
			
			$lifetime = 24*3600*7;
			return Yii::$app->user->login($this->getUser(),$lifetime);
		}
		return false;
	}
	
	
	
	
	/** 
	 * 驗證手機號碼
	 * @param unknown $data
	 * @return boolean
	 */
	public function verifyPhone($data)
	{
		$this->scenario = "verifyphone";
		if($this->load($data) && $this->validate()){
			//發送手機驗證碼
			$this->sendMsn();
			return true;
		}
		
		return false;
	}
	
	/**
	 * 驗證手機號碼
	 * @param unknown $data
	 * @return boolean
	 */
	public function verifyPhonefindme($data)
	{
		$this->scenario = "verifyphonefindme";
		if($this->load($data) && $this->validate()){
			//發送手機驗證碼
			$this->sendMsn();
			return true;
		}
		
		return false;
	}
	
	

	/**
	 * 驗證密碼
	 * @param unknown $data
	 * @return boolean
	 */
	public function verityPassword($data)
	{
		$this->scenario = "veritypassword";
		
		if($this->load($data) && $this->validate()){
			
			//保存用戶信息，並且登錄
			return $this->saveInfoAndLogin($data);
		}
	
		return false;
	}
	
	
	
	
	/**
	 * 保存數據到數據庫中,並且登錄
	 * @param unknown $post
	 */
	protected function saveInfoAndLogin($post)
	{
		$this->scenario = 'saveinfo';
		
		$user_phone = Yii::$app->session->get("regist_phone");
		$user = self::find()->where('user_phone = :user_phone',[':user_phone'=>$user_phone])->one();
		$data = [];	
		$lifetime = 24*3600*7;
		
		if (is_null($user)){
			//新用戶
			$data['User']['user_title'] = $user_phone;
			$data['User']['user_name'] = "";
			$data['User']['user_sex'] = 0;
			$data['User']['user_img'] = "";
			$data['User']['user_phone'] = Yii::$app->session->get("regist_phone");
			$data['User']['user_password'] = md5($post['User']['user_password']);
			$data['User']['user_addtime'] = time();
			$data['User']['openid'] = "";
			$data['User']['user_stat'] = 0;
			$data['User']['appsecret'] = "";
			$this->load($data);
			$this->save();
			return Yii::$app->user->login($this->getUser(),$lifetime);
			
		} else {
			//舊用戶，忘記密碼的
			$user->updateAll(['user_password' => md5($this->user_password)],'user_phone = :user_phone',[':user_phone'=>$user->user_phone]);
			return Yii::$app->user->login($user->getUser(),$lifetime);
		}
	}
	
	
	/**
	 *  發送驗證碼
	 */
	protected function sendMsn()
	{
		$code = Code::find()->where('c_phone = :cp',[':cp'=>$this->user_phone])->one();
		
		//使用session記錄手機號碼
		Yii::$app->session->set('regist_phone',$this->user_phone);
		$c_code = Helper::get_rand_number('100000','999999',1)[0];
		
		if(!is_null($code)){
			
			if(time() < $code->c_time - 240 ){
				Yii::$app->session->setFlash('info', '請一分鐘後再次獲取驗證碼');
				
			} 
		}else{
			
			if($this->area == '86'){
				$ch = Helper::sendMSN($this->user_phone, $c_code);
			
			} else {
			
				$phones = $this->area.$this->user_phone;
				$ch = Helper::sendInternational($phones, $c_code);
			}
		}
		
		//保存驗證碼到數據庫中
		Code::saveCode($this->user_phone,$c_code);
		
	}
	


	
	/**
	 * 如果想通过Yii::$app->user->identity->xxxxx 获取相关的属性信息，必须要使用Yii::$app->user->login()函数，就必须要实现IdentityInterface这个接口
	 * IdentityInterface 接口的方法，规定要重写
	 */
	public static function findIdentity($id)
	{
		return static::findOne(['user_id' => $id]);
	}
	

	/**
	 * 如果想通过Yii::$app->user->identity->xxxxx 获取相关的属性信息，必须要使用Yii::$app->user->login()函数，就必须要实现IdentityInterface这个接口
	 * IdentityInterface 接口的方法，规定要重写
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		
		$user = new User;
		
		if ("123456" === $token) {
			return new static($user);
		}
      
        return null;
	}
	
	/**
	 * 如果想通过Yii::$app->user->identity->xxxxx 获取相关的属性信息，必须要使用Yii::$app->user->login()函数，就必须要实现IdentityInterface这个接口
	 * IdentityInterface 接口的方法，规定要重写
	 */
	public function getId()
	{
		return $this->user_id;
	}
	
	/**
	 * 如果想通过Yii::$app->user->identity->xxxxx 获取相关的属性信息，必须要使用Yii::$app->user->login()函数，就必须要实现IdentityInterface这个接口
	 * IdentityInterface 接口的方法，规定要重写
	 */
	public function getAuthKey()
	{
		return "user";
	}
	
	/**
	 * 如果想通过Yii::$app->user->identity->xxxxx 获取相关的属性信息，必须要使用Yii::$app->user->login()函数，就必须要实现IdentityInterface这个接口
	 * IdentityInterface 接口的方法，规定要重写
	 */
	public function validateAuthKey($authKey)
	{
		return $authKey == "user";
	}
	
	
	
	/** find user by user_phone
	 * @param unknown $user_phone
	 * @return \frontend\models\User
	 */
	protected static function findByUserphone($user_phone)
	{
		return static::findOne(['user_phone' => $user_phone]);
	}
	
	
	
	/**
	 * 根据user_phone查找相关用户
	 * @return boolean|\app\modules\admin\models\Admin
	 */
	protected function getUser()
	{
		if ($this->_user === false) {
			$this->_user = self::findByUserphone($this->user_phone);
		}
	
		return $this->_user;
	}
	
}