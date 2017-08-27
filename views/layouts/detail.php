<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo Yii::$app->controller->renderPartial('//layouts/static_head');?>
</head>
<body>
<div class="modal"></div>
<div id="wrapper-home">
    <?php echo Yii::$app->controller->renderPartial('//layouts/top');?>

    <?=$content?>
	<?php echo Yii::$app->controller->renderPartial('//layouts/popup');?>
    <?php echo Yii::$app->controller->renderPartial('//layouts/static_footer'); ?>
    
    <a href="javascript:void(0)" title="go-to-top" id="go-top"></a>
</div>	
</body>

</html>