<?php
namespace frontend\models;

use yii\db\ActiveRecord;

use Yii;

class Code extends ActiveRecord
{
	public static function tableName()
	{
		return "{{%y_code}}";
	}
	
	
	
	public function rules()
	{
		return [
			[['c_code'], 'required', 'message' =>'驗證碼不能爲空', 'on' => ['veritycode']],
			[['c_code'],'validateCode','on'=>['veritycode']],
		];
	}
	
	
	
	
	public function validateCode()
	{
		if(!$this->hasErrors()){
			$user_phone = Yii::$app->session->get('regist_phone');
		
			$data = self::find()->where('c_phone = :c_phone',[':c_phone' =>$user_phone])->one();
			if(is_null($data)){
				$this->addError("c_code","驗證碼錯誤");
			}
			
			if($data->c_code != $this->c_code){
				$this->addError("c_code","驗證碼錯誤");
			} 
			
			if($data->c_time < time()){
				$this->addError("c_code","驗證碼已過期,請重新獲取");
			}
		}
	}
	
	
	
	/**保存驗證碼到數據庫中
	 * @param unknown $user_phone 手機號碼
	 * @param unknown $c_code 驗證碼
	 */
	public static function saveCode($user_phone,$c_code)
	{
		//入庫
		$time = time()+300;
		$code = self::find()->where('c_phone = :cp',[':cp'=>$user_phone])->one();
		
		//如果數據沒有數據，就插入
		if(!$code) {
			$code  = new Code();
			$code->c_phone = $user_phone;
		}
			
		//否則就修改
		$code->c_code = $c_code;
		$code->c_time = $time;
		$code->save();
			
	}
	
	
	
	public function verityCode($data)
	{
		$this->scenario = "veritycode";
		
		if($this->load($data) && $this->validate()){
			
			//使用session記錄驗證碼
			Yii::$app->session->set('regist_code',$this->c_code);
			
			return true;
		}
		
		return false;
	}
	
	
	
}