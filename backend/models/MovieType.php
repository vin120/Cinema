<?php
namespace backend\models;

use yii\db\ActiveRecord;

use Yii;

class MovieType extends ActiveRecord
{
	public static function tableName()
	{
		return "{{%y_movie_type}}";
	}
	
	
	
}