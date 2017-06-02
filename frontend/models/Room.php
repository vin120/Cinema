<?php
namespace frontend\models;

use yii\db\ActiveRecord;

use Yii;

class Room extends ActiveRecord
{
	public static function tableName()
	{
		return "{{%y_room}}";
	}
	
	
	
	
}