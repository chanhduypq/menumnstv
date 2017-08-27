<?php

use app\models\MultiLang;  //Gói đa ngôn ngữ
use app\models\LayoutService;

$lang = MultiLang::viewLang("showshoppingcart");
$lang1 = MultiLang::viewLang("_showshoppingcart");

?>
<table class="table content_table content-table-cart">
    <tbody>
        <?php
        foreach ($carts as $key=>$value){?>
        <tr>
            <td class="color_text">
                <p class="delete-product-cart left-delete">
                    <?php
                    if($carts["$key"]['lock']==FALSE){?>
                    <a id="<?=$key?>" href="javascript:void(0);" class="add-to-cart1 remove_value_cart"><i class="fa fa-times" aria-hidden="true"></i></a>
                    <?php 
                    }
                    ?>
                </p>
                <div class="img-list-template-car">
                    <img src="<?php echo $value['thumb'];?>" class="img-responsive<?php if($value['resolution']=='2') echo ' img-list-car-port';?>">
                </div>
                <div class="description-list">
                    <p><?=$value['title']?></p>
                    <p><i class="fa fa-check" aria-hidden="true"></i><?=$lang1['added-to-cart']?></p>
                </div>
                <p class="border-right-table"></p>
            </td>
            <td class="center-top-bottom bold-css-car">
                <p><?php echo LayoutService::showMoney($value['price']);?></p>
            </td>
            <input type="hidden" name="sum" value="<?php echo $value['price'];?>"/>
        </tr>
        <?php
        }
        ?>


        <tr class="money-shop-card">
            <td>
                <p class="view-cart"><a href="/confirmcart"><?= $lang['view-cart'] ?></a></p>
                <p class="total-money"><?= $lang['total'] ?></p>
            </td>
            <td class="padding-total-money"><?php echo LayoutService::showMoney($sum_all);?></td>
        </tr>
        <input type="hidden" id="sum_all" value="<?=$sum_all?>"/>
    </tbody>
</table>