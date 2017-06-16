<?php
namespace frontend\models;

use yii\db\ActiveRecord;

use Yii;

class Movie extends ActiveRecord
{
	public static function tableName()
	{
		return "{{%y_movie}}";
	}
}