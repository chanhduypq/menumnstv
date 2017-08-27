<?php 
use app\models\Template;
use app\models\MultiLang;  //Gói đa ngôn ngữ
use app\models\LayoutService;
use app\models\Service;

$lang = MultiLang::viewLang("confirmcart");
?>
<div class="v2-box-cart">
    <div class="v2-border-box-cart">
        
        <?php                            
        foreach ($carts as $key=>$value){?>
        
        <div id="<?=$key?>" class="v2-cart-content">
            <div class="box-v2-cart-content">
                <div class="left-v2-cart-content">
                    <div class="name-price-cart">
                        <div class="left-name-price-cart">
                            <p class="delete-product-cart-shop">
                                <?php
                                if($value['lock']==FALSE){?>
                                <a onclick="removeOrderSession(<?=$key?>);" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i></a>
                                <?php }?>                                
                            </p>
                            <div class="img-list-template <?php echo $value['thumb_confirm_class'];?>" id="thumb-confirm<?=$key?>">
                                <input type="hidden" value="<?php echo $value['video'];?>"/>
                                <img src="<?php echo $value['thumb'];?>" class="img-responsive<?php if($value['resolution']=='2') echo ' img-list-car-port';?>">
                            </div>                            
                        </div> <!-- end left-name-price-cart -->
                        <div class="right-name-price-cart">
                            <div class="center-right-npc">
                                <div class="name-left-cart"><?php echo $value['title'];?></div>
                                <div class="name-right-cart">
                                    <?php 
                                    if($value['lock']==true) 
                                        echo '<span style="visibility: hidden;"></span><span>'.$lang['purchased'].'</span>'; 
                                    else 
                                        echo '<span'.($value['num2']==0?' style="visibility: hidden;"':'').'>'.Template::showOldPrice($value['num'],$value['num2']).'</span><span>'.Template::showNewPrice($value['num'],$value['num2']).'</span>';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end name-price-cart -->
                </div>
                <div class="right-v2-cart-content">
                    <p class="price-cart_"><?php if($value['lock']==true) echo $lang['purchased']; else echo Template::showNewPrice($value['num'],$value['num2']);//LayoutService::showMoney($value['price']);?></p>
                </div>
            </div> <!-- end box-v2-cart-content -->
            <div class="box-v2-cart-content">
                <div class="left-v2-cart-content">
                    <div class="v2-services-cart">
                        <div class="left-name-price-cart">
                            <p class="v2-add-options-cart"><?=$lang['add-option']?></p>
                        </div> <!-- end left-name-price-cart -->
                        <div class="right-name-service-cart">
                            <div class="center-right-npc">
                                <div class="checkbox-name-function">
                                    
                                    <?php
                                    Service::setServices($services, $order_details, $key,$value['service_ids']);
                                    $i=1;
                                    foreach ($services as $service){
                                        ?>
                                        <div class="div-check-box-name-function hover-save-mns"<?php if($service['lock']==true) {echo ' style="cursor: default;';if(in_array($service['service_id'],$value['service_ids'])||(array_search($key, array_keys($order_details))!==FALSE&&in_array($service['service_id'], $order_details[$key]))) echo 'background-color: #f3e1ba;';echo '"';} else if(in_array($service['service_id'],$value['service_ids'])||(array_search($key, array_keys($order_details))!==FALSE&&in_array($service['service_id'], $order_details[$key]))) echo ' style="background-color: #f3e1ba;"';?>>
                                            <div class="left-function">
                                                <span class="check-box-funtion">
                                                    <input<?php echo $service['disabled'].$service['checked'];?> type="checkbox" name="service<?php echo $i++;?>" class="css-checkbox change-icon-save-mns">
                                                    <input type="hidden" name="out_id" value="<?=$key?>"/>
                                                    <input type="hidden" name="service_id" value="<?php echo $service['service_id'];?>"/>
                                                    <input type="hidden" name="price" value="<?php echo $service['price'];?>"/>
                                                </span>
                                                <span><?php echo $service['service_name'];?></span>
                                                <div class="icon-information">
                                                    <div class="popup-information v2-cart-popup">
                                                        <p><?php echo $service['service_name'];?> services</p>
                                                        <p><?php echo $service['description'];?></p>
                                                        <p>Price:<span></span><span><?php echo LayoutService::showServiceMoney($service['price']);?></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="right-funtion"><?php if($service['lock']==true) echo $lang['purchased']; else { echo '+&nbsp;<span>'.LayoutService::showServiceMoney($service['price'],'</span>&nbsp;');}?></p>
                                        </div>                                   
                                        
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end v2-services-cart -->
                </div>
                <div class="right-v2-cart-content">
                    <div class="v2-box-price-services">
                        <?php 
                        $i=1;
                        foreach ($services as $service){?>
                            <p class="service<?php echo $i++;?>"<?php if($service['checked']==' checked="checked"') echo ' style="visibility:inherit;"';?>><?php if($service['lock']==true) echo $lang['purchased']; else { echo LayoutService::showServiceMoney($service['price']);};?></p>                        
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div> <!-- end box-v2-cart-content -->
            <input type="hidden" name="sum" value="<?php echo $value['price'];?>"/>
        </div> <!-- end v2-cart-content -->
        
        <?php
        }
        ?>
        <!-- kết thúc danh mục template đã mua
        đến phần tổng tiền và payment -->
        <div class="v2-total-money">
            <div class="v2-right-total-money">
                <div class="v2-total-money-cart">
                    <p class="v2-subtotal-money">subtotal price:</p>
                    <div class="v2-number-total-price"><span><?php echo LayoutService::showMoney($sum_all);?></span></div>
                </div>
            </div>
            <div class="clr"></div>
            <div class="v2-btn-payment_">
                <div class="v2-left-payment">
                    <div class="v2-icon-payment">
                        <a href="" class="v2-paypal"></a>
                        <a href="" class="v2-visa"></a>
                        <a href="" class="v2-mastercard"></a>
                    </div>
                </div>
                <div class="v2-right-payment">
                    <a href="javascript:void(0);" class="click-pay-show">Payment<span></span></a>
                </div>
            </div> <!-- end v2-btn-payment_ -->
           
        </div> <!-- end v2-total-money -->
        <input type="hidden" id="sum_all" value="<?=$sum_all?>"/>
    </div> <!-- end v2-border-box-cart -->
    
</div> <!-- end v2-box-cart -->
<?php if(strlen(Yii::$app->session['user_id'])==0){?>
<div class="v2-infomation-payment pay-order-cart">
    <div class="v2-title-detail-l">Payment Infomations</div>
    <div class="v2-box-cart">
        <div class="v2-border-box-cart">
            <div class="box-pay-info">
                <div class="w-box-pay-info">
                    <div id="tab-container-pay" class='tab-container-pay'>
                        <ul class='etabs-pay'>
                            <li class='tab-pay'><a href="#login-pay">Login</a></li>
                            <li class='tab-pay'><a href="#register-pay">First purchase</a></li>
                        </ul>
                        <span class="border-pay">|</span>
                        <div class='panel-container-pay'>
                            <div id="login-pay">
                                <form id="frm_login_confirmcart">
                                    <div class="table-responsive">
                                        <table class="table login-pay-table">
                                            <tr>
                                                <td>Email <span>(*)</span></td>
                                                <td>
                                                    <span class="error email_confirmcart"></span>
                                                    <input name="email" type="email" id="email_confirmcart" class="css-input-pay">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Password <span>(*)</span></td>
                                                <td>
                                                    <span class="error password_confirmcart"></span>
                                                    <input name="password" type="password" id="password_confirmcart" class="css-input-pay">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><label class="checkbox-inline view-template-clone"><input type="checkbox">Remember</label></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><a id="a_login_confirmcart" href="javascript:void(0)" class="css-btn-pay">Login</a></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><a href="javascript:void(0)" class="css-btn2-pay click-password-form v2-forgot-pass">Forget password?</a></td>
                                            </tr>
                                        </table>
                                    </div>                                        
                                </form>

                            </div> <!-- end login-pay -->
                            <div id="register-pay">
                                <form id="frm_register_pay">
                                    <div class="table-responsive">
                                        <table class="table login-pay-table">
                                            <tr>
                                                <td>Gender <span>(*)</span></td>
                                                <td>
                                                    <span class="css-radio">
                                                        <input type="radio" name="gender" checked value="1"> Mr
                                                    </span>
                                                    <span>
                                                        <input type="radio" name="gender" value="2"> Ms
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Name <span>(*)</span></td>
                                                <td>                                                    
                                                    <span class="error fullname_register_pay"></span>
                                                    <input type="text" name="fullname" id="fullname_register_pay" class="css-input-pay">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Email <span>(*)</span></td>
                                                <td>
                                                    <span class="error email_register_pay"></span> 
                                                    <input type="text" name="email" id="email_register_pay" class="css-input-pay">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Confirm email <span>(*)</span></td>
                                                <td>
                                                    <span class="error email_confirm_register_pay"></span> 
                                                    <input type="text" class="css-input-pay" id="email_confirm_register_pay">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Phone <span>(*)</span></td>
                                                <td>
                                                    <span class="error phone_register_pay"></span> 
                                                    <input type="text" name="phone" id="phone_register_pay" class="css-input-pay">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Address <span>(*)</span></td>
                                                <td>
                                                    <span class="error address_register_pay"></span>
                                                    <input type="text" name="address" id="address_register_pay" class="css-input-pay">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>City <span>(*)</span></td>
                                                <td>
                                                    <span class="error city_register_pay"></span>
                                                    <input type="text" name="city" id="city_register_pay" class="css-input-pay">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><span class="error accept_register_pay"></span><label class="checkbox-inline view-template-clone"><input id="accept_register_pay" type="checkbox">Accept to receive our email instructions</label></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a id="a_paypal" href="javascript:void(0)" class="css-btn-pay-ment"><img src="/images/v2-icon-paypal.png" class="img-responsive"></a>
                                                    <a id="a_cod" href="javascript:void(0)" class="css-btn-code"><img src="/images/v2-icon-code.png" class="img-responsive"></a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div> 
                                    <input type="hidden" name="paypal_cod" id="paypal_cod"/>
                                    <input type="hidden" name="total_price" id="total_price" value="<?=$sum_all?>"/>
                                </form>
                            </div> <!-- end login-pay -->

                        </div> <!-- end panel-container -->
                    </div>
                </div> <!-- end w-box-pay-info -->
                <div class="v2-payment-info">
                    <div class="v2-icon-payment cart-payment_">
                        <a href="" class="v2-paypal active-hover">
                            <div class="popup_paypal">
                                <p>10% discount for Paypal check out.</p>
                                <p>We offer 10% off for Credit and Paypal checkout. This discount will be automatically applied to each order you place with us during the shopping cart checkout.</p>
                                <img src="images/v2-icon-popup-payment.png" class="img-responsive">
                                <p>We would greatly appreciate it if you kindly give us some feedback and comments, which would help us to improve our ability to serve you and other users.</p>
                            </div>
                        </a>
                        <a href="" class="v2-visa">
                            <div class="popup_visa">
                                <p>10% discount for Visa check out.</p>
                                <p>We offer 10% off for Credit and Paypal checkout. This discount will be automatically applied to each order you place with us during the shopping cart checkout.</p>
                                <img src="images/v2-icon-popup-payment.png" class="img-responsive">
                                <p>We would greatly appreciate it if you kindly give us some feedback and comments, which would help us to improve our ability to serve you and other users.</p>
                            </div>
                        </a>
                        <a href="" class="v2-mastercard">
                            <div class="popup_mastercard">
                                <p>10% discount for Mastercard check out.</p>
                                <p>We offer 10% off for Credit and Paypal checkout. This discount will be automatically applied to each order you place with us during the shopping cart checkout.</p>
                                <img src="images/v2-icon-popup-payment.png" class="img-responsive">
                                <p>We would greatly appreciate it if you kindly give us some feedback and comments, which would help us to improve our ability to serve you and other users.</p>       
                            </div>
                        </a>
                    </div>
                </div>
            </div> <!-- end box-pay-info -->
        </div> <!-- end v2-border-box-cart -->
    </div> <!-- end v2-box-cart -->
</div> <!-- end v2-infomation-payment -->
<?php }
else{?> 
<div class="v2-infomation-payment pay-order-cart">
    <div class="v2-title-detail-l">Payment Infomations</div>
    <div class="v2-box-cart">
        <div class="v2-border-box-cart">
            <div class="box-pay-info">
                <div class="w-box-pay-info">
                    <div id="tab-container-pay" class='tab-container-pay'>
                        <ul class='etabs-pay'>
                            <li class='tab-pay'><a href="#confirm-pay">Confirm</a></li>
                        </ul>
                        <!-- <span class="border-pay">|</span> -->
                        <div class='panel-container-pay'>
                            <div id="confirm-pay">
                                <form id="frm_register_pay">
                                    <div class="table-responsive">
                                        <table class="table login-pay-table">
                                            <tr>
                                                <td>Gender <span>(*)</span></td>
                                                <td>
                                                    <span>
                                                        <?php 
                                                        if(Yii::$app->session['gender']=='1'){
                                                            echo $lang['mr'];
                                                        }
                                                        else{
                                                            echo $lang['ms'];
                                                        }
                                                        ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Name <span>(*)</span></td>
                                                <td>                                                    
                                                    <span class="error fullname_register_pay"></span>
                                                    <input name="fullname" id="fullname_register_pay" type="text" class="css-input-pay" value="<?=Yii::$app->session['full_name']?>" disabled>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Email <span>(*)</span></td>
                                                <td>                                                    
                                                    <span class="error email_register_pay"></span>
                                                    <input name="email" id="email_register_pay" type="email" class="css-input-pay" value="<?=Yii::$app->session['email']?>" disabled>
                                                    <input type="hidden" value="<?=Yii::$app->session['email']?>" id="email_confirm_register_pay"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Phone <span>(*)</span></td>
                                                <td>
                                                    <span class="error phone_register_pay"></span> 
                                                    <input type="text" name="phone" id="phone_register_pay" class="css-input-pay" value="<?php if(isset($user['phone'])) echo $user['phone'];?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Address <span>(*)</span></td>
                                                <td>
                                                    <span class="error address_register_pay"></span>
                                                    <input type="text" name="address" id="address_register_pay" class="css-input-pay" value="<?php if(isset($user['address'])) echo $user['address']?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>City <span>(*)</span></td>
                                                <td>
                                                    <span class="error city_register_pay"></span>
                                                    <input type="text" name="city" id="city_register_pay" class="css-input-pay" value="<?php if(isset($user['city'])) echo $user['city']?>">
                                                </td>
                                            </tr>
<!--                                            <tr>
                                                <td>Code <span>(*)</span></td>
                                                <td>
                                                    <input type="text" class="css-input-security">
                                                    <input type="text" class="css-input-security text-ser" value="66f83v" disabled>
                                                    <a href="" class="v2-refresh-code"><img src="images/icon-refresh.png"></a>
                                                </td>
                                            </tr>-->
                                            <tr>
                                                <td></td>                                                
                                                <td><span class="error accept_register_pay"></span><label class="checkbox-inline view-template-clone"><input type="checkbox" id="accept_register_pay">Accept to receive our email instructions</label></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a id="a_paypal" href="javascript:void(0)" class="css-btn-pay-ment"><img src="/images/v2-icon-paypal.png" class="img-responsive"></a>
                                                    <a id="a_cod" href="javascript:void(0)" class="css-btn-code"><img src="/images/v2-icon-code.png" class="img-responsive"></a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div> 
                                    <input type="hidden" name="paypal_cod" id="paypal_cod"/>
                                    <input type="hidden" name="total_price" id="total_price" value="<?=$sum_all?>"/>
                                </form>
                            </div> <!-- end login-pay -->

                        </div> <!-- end panel-container -->
                    </div>
                </div> <!-- end w-box-pay-info -->
                <div class="v2-payment-info">
                    <div class="v2-icon-payment cart-payment_">
                        <a href="" class="v2-paypal active-hover">
                            <div class="popup_paypal">
                                <p>10% discount for Paypal check out.</p>
                                <p>We offer 10% off for Credit and Paypal checkout. This discount will be automatically applied to each order you place with us during the shopping cart checkout.</p>
                                <img src="images/v2-icon-popup-payment.png" class="img-responsive">
                                <p>We would greatly appreciate it if you kindly give us some feedback and comments, which would help us to improve our ability to serve you and other users.</p>
                            </div>
                        </a>
                        <a href="" class="v2-visa">
                            <div class="popup_visa">
                                <p>10% discount for Visa check out.</p>
                                <p>We offer 10% off for Credit and Paypal checkout. This discount will be automatically applied to each order you place with us during the shopping cart checkout.</p>
                                <img src="images/v2-icon-popup-payment.png" class="img-responsive">
                                <p>We would greatly appreciate it if you kindly give us some feedback and comments, which would help us to improve our ability to serve you and other users.</p>
                            </div>
                        </a>
                        <a href="" class="v2-mastercard">
                            <div class="popup_mastercard">
                                <p>10% discount for Msstercard check out.</p>
                                <p>We offer 10% off for Credit and Paypal checkout. This discount will be automatically applied to each order you place with us during the shopping cart checkout.</p>
                                <img src="images/v2-icon-popup-payment.png" class="img-responsive">
                                <p>We would greatly appreciate it if you kindly give us some feedback and comments, which would help us to improve our ability to serve you and other users.</p>       
                            </div>
                        </a>
                    </div>
                </div>
            </div> <!-- end box-pay-info -->
        </div> <!-- end v2-border-box-cart -->
    </div> <!-- end v2-box-cart -->
</div> <!-- end v2-infomation-payment -->
<?php 
}
?>
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" id="virtual_frm">
		<input type="hidden" value="_xclick" name="cmd">
		<input type="hidden" value="quangquangvu-facilitator@gmail.com" name="business">
		<input type="hidden" value="Instant Payment" name="item_name">
		<input type="hidden" value="0.00" name="shipping">
		<input type="hidden" value="1" name="no_shipping">
		<input type="hidden" value="1" name="no_note">
		<input type="hidden" value="USD" name="currency_code">
		<input type="hidden" value="PP-BuyNowBF" name="bn">
		<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img border="0" width="1" height="1" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" alt="">
		<input type="hidden" name="amount" id="amount" value="">
                <input type="hidden" name="item_number" value="" id="quantity">
</form>
<script>
    $(document).ready(function () {
        <?php
        //nếu user đã vào giỏ hàng, click nút thanh toán, rồi sau đó login. Như vay, sau khi login thi phải bật ngay form [Xác nhận thông tin ra]
        if(isset(Yii::$app->session['check_out'])&&Yii::$app->session['check_out']==true){
        ?>
        $(".v2-btn-payment_").hide();
        $(".pay-order-cart").show();
        
//        setOptionsAtConfirmcart();
        <?php
        }
        unset(Yii::$app->session['check_out']);
        ?>
                
        $(".error").hide();
        $("#virtual_frm").hide();
        $('#tab-container-pay').easytabs();
        
        $("#a_login_confirmcart").click(function (){
            submitLoginConfirmcart('<?=Yii::$app->request->getCsrfToken()?>');
        });
        $("#email_confirmcart,#password_confirmcart").keypress(function (ev) {
            var keycode = (ev.keyCode ? ev.keyCode : ev.which);
            if (keycode == '13') {
                submitLoginConfirmcart('<?=Yii::$app->request->getCsrfToken()?>');
            }
        });
        
        $("#a_paypal,#a_cod").click(function (){
            count_cart_option=document.getElementById("number-cart-option").innerHTML;
            count_cart_option=$.trim(count_cart_option);
            
            count_cart_template=document.getElementById("number-cart-template").innerHTML;
            count_cart_template=$.trim(count_cart_template);
            
            if(count_cart_option=='0'&&count_cart_template=='0'){
                jAlert(error_cart_empty,"");
                return;
            }
            if(validateRegisterPay()==false){
                return;
            }
            if($(this).attr('id')=='a_paypal'){
                $("#paypal_cod").val("paypal");    
            }
            else{
                $("#paypal_cod").val("cod");                
            }
            <?php
            /**
             * tại form action="https://www.sandbox.paypal.com/cgi-bin/webscr"
             * value của <input type="hidden" name="amount" id="amount" value=""> phai được quy đổi theo USD
             * tức là phai lấy value hiện tại (đang la giá trị tiếng Việt) chia cho tỉ giá USD
             * 
             * nếu đang ở mode tiếng Việt thi lấy value của price-rate-en trong tbl_option gán vào biến price_rate_en, còn nếu ở mode tiếng Anh thi gán rỗng cho biến price_rate_en
             * rồi truyền vao function submitRegisterPay
             */
            if(Yii::$app->session['language_id']=="en"){
                echo 'price_rate_en="";';
            }            
            ?>
            submitRegisterPay('<?=Yii::$app->request->getCsrfToken()?>',price_rate_en);
        });
        
    });
</script>