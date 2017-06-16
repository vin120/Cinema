<?php 
	use yii\widgets\ActiveForm;
	use yii\helpers\Url;
	
?>

<body>
	<?php
		$form = ActiveForm::begin([
			'id'=>'regist',
			'action'=>'order',
			'method'=>'post',
            'options' =>['class'=> 'test'],
			'enableClientValidation'=>false,
			'enableClientScript'=>false
		]);
	?>
	
	
	movie_show_id<input type="text" id="movie_show_id" name="movie_show_id" />
	phone<input type="text" id="phone" name="phone" />
	seat<input type="text" id="seat" name="seat" />
	count<input type="text" id="count" name="count" />
	total_money<input type="text" id="total_money" name="total_money" />
	
	<input type="submit">
	
	<?php  ActiveForm::end();?>
	
</body>