<?php
use app\models\MultiLang;		//Gói đa ngôn ngữ
$lang = MultiLang::layoutLang("static_footer");
?>
<?php
	$footer_id = "footer";
	$view_id = Yii::$app->controller->action->id;
	if(($view_id == 'home') || ($view_id == 'index')) $footer_id = "footer-home";
?>
        
        <script type="text/javascript">
            <?php
            /**
             * khi session add_item_bought được bật lên thi đã có sự trùng nhau giữa các đơn hàng cũ và giỏ hàng mới được add vào
             * sau khi thông báo lên thi hủy session add_item_bought
             */
            if(isset(Yii::$app->session['add_item_bought'])&&Yii::$app->session['add_item_bought']==true){?>
                jQuery(function ($){
                   jAlert("<?=$lang['message']?>",""); 
                });
            <?php
            unset(Yii::$app->session['add_item_bought']);
            }
        ?>
        </script>
        

    
