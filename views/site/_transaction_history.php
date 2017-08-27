<?php
use app\models\LayoutService;

if($model['order_type']=='1'){
    $image="/images/btn-paypal.png";
}
else{
    $image="/images/btn-cod.png";
}

?>
<tr>
    <td><?=$model['order_id']?></td>
    <td><?=$model['name']?></td>
    <td><?=$model['order_time'];?></td>
    <td><?php echo $lang_payment["order_status_".$model['order_status']];?></td>
    <td>
        <div class="toltal-money-history"><?php echo LayoutService::showMoney($model['total_price'],'<span> ');?></span></div>
        <p><img src="<?=$image?>" width="100" class="img-responsive"></p>
    </td>
</tr>
