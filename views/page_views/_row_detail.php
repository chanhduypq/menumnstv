<?php
use yii\widgets\ListView;
use app\models\Option;
use app\models\MultiLang;		//Gói đa ngôn ngữ

<?php

	echo ListView::widget( [
			'dataProvider' => $dataProvider,
			'layout' => "{items}",
			'itemView' => '_row_detail',
			'emptyText'=>'',
	] ); 

?>

?>