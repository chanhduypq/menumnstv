<?php 
use yii\widgets\ListView;
use yii\widgets\LinkPager;

$upload_template_url = $GLOBALS['options']['upload_template_url'];

?>

<div class="v2-template panel-container1 v2-w-template-land">
    <?php
             
        echo ListView::widget( [
                'dataProvider' => $dataProvider,
                'viewParams'=>['upload_template_url'=>$upload_template_url], 
                'layout' => "{items}",
                'itemView' => $item_view,
                'emptyText'=>'',
        ] ); 

    ?>

    <div class="v2-pagination">
        <?php
            echo LinkPager::widget([
                    'pagination' => $dataProvider->pagination,
            ]);

        ?>
    </div> <!-- end v2-pagination -->
</div> <!-- end v2-template -->

<?php echo Yii::$app->controller->renderPartial('search',array('params' => $params, 'catalogues' => $catalogues));?>

<script type="text/javascript">
    jQuery(function ($){
       jQuery('.bottom').tipso({
            position: 'bottom',
            background: 'rgba(0,0,0,0.75)',
            useTitle: false,
        }).css('cursor','pointer'); 
        
    });
</script>

