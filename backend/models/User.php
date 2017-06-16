<?php
namespace backend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    
    
    public $admin_pwd2 = '';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%y_movie_admin}}';
    }
    
    
    
    public function attributeLabels()
    {
    	return [
    			'admin_user' =>Yii::t('app','管理員帳號：'),
    			'admin_pwd' => Yii::t('app', '管理員密碼：'),
    			'admin_nickname' => Yii::t('app','管理員姓名：'),
    	];
    }
    
    
    
    /**
     * @inheritdoc
     */
    public function behaviors_()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['admin_id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['admin_user' => $username,'status'=>1,'admin_grade'=>1]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
       return "admin";
    }


    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
       return $this->getAuthKey() === "admin";
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
    	
    	if(md5($password) == $this->admin_pwd){
    		return true;
    	}else{
    		return false;
    	}
			
//        return Yii::$app->security->validatePassword($password, $this->travel_agent_password);
    }
    
    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}

