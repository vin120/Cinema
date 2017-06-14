<?php
namespace frontend\models;

use yii\db\ActiveRecord;

use Yii;

class MovieOfflineOrder extends ActiveRecord
{
	public static function tableName()
	{
		return "{{%y_movie_offline_order}}";
	}
	
	
	
}