<?php 
use yii\widgets\ListView;

$upload_template_url = $GLOBALS['options']['upload_template_url'];

?>
<div class="v2-template panel-container1 v2-w-template-land">
    
    <?php
             
        echo ListView::widget( [
                'dataProvider' => $bought_dataProvider,
                'viewParams'=>['upload_template_url'=>$upload_template_url], 
                'layout' => "{items}",
                'itemView' => '_template_list',
                'emptyText'=>'',
        ] ); 

    ?>
    
</div> <!-- end v2-template -->
<?php echo Yii::$app->controller->renderPartial('search',array('params' => $params, 'catalogues' => $catalogues));?>

<script type="text/javascript">
    $(document).ready(function () {
               
        jQuery('.bottom').tipso({
            position: 'bottom',
            background: 'rgba(0,0,0,0.75)',
            useTitle: false,
        }).css('cursor','pointer'); 

    });

</script>