<?php
namespace frontend\models;

use yii\db\ActiveRecord;

use Yii;

class Cinema extends ActiveRecord
{
	public static function tableName()
	{
		return "{{%y_cinema}}";
	}
	
}