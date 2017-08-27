<?php
use app\models\MultiLang;		//Gói đa ngôn ngữ
$lang = MultiLang::viewLang("confirmcartblank");
?>
<div class="padding-content">
    <div class="content-center">
        <div class="information">
            <div class="box-information margin-row-content">
                <div class="title-cart">
                    <p><?=$lang['cart-information']?></p>
                </div>
                <div class="no-shoping-cart">
                    <img src="images/icon-no-cart.png" class="img-responsive">
                    <p class="not-nth"><?=$lang['status-cart']?></p>
                    <p><?=$lang['message']?> <a>mns.tv</a></p>
                </div>
                <div class="btn-order-cart no-btn-order-cart">
                    <a href="plaza" class="btn-btn-mns"><i class="fa fa-long-arrow-left" aria-hidden="true"></i><?=$lang['template-plaza']?></a>
                </div>
            </div> <!-- end box-information -->
        </div> <!-- end information -->

        <div class="clr"></div>

    </div> <!-- end content-center -->
</div> <!-- end center -->