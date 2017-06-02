<?php
namespace frontend\models;

use yii\db\ActiveRecord;

use Yii;

class MovieShow extends ActiveRecord
{
	public static function tableName()
	{
		return "{{%y_movie_show}}";
	}
	
	
	
	
}