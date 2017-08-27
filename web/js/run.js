var specialChars = "<>@!#$%^&*()_+[]{}?:|'\"\\~`=";
var check_address = function(string){
    for(i = 0; i < specialChars.length;i++){
        if(string.indexOf(specialChars[i]) > -1){
            return true
        }
    }
    return false;
}
var specialDigitChars = "<>@!#$%^&*()_+[]{}?:;|'\"\\,./~`-=0123456789";
var check_city = function(string){
    for(i = 0; i < specialDigitChars.length;i++){
        if(string.indexOf(specialDigitChars[i]) > -1){
            return true
        }
    }
    return false;
}
function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}


function validateRating(){
    if($.trim($("#popup-comment-rating #v2-input-content-qs").val())==""){
        jAlert("Please input [Rating Content]","");
        $("#popup-comment-rating #v2-input-content-qs").focus();
        return false;
    }
    return true;
    
}

function validateQuestion(){
    if($.trim($("#popup-new-question #v2-input-content-qs").val())==""){
        jAlert("Please input [question content]","");
        $("#popup-new-question #v2-input-content-qs").focus();
        return false;
    }
    return true;
}

/**
 * date 24/10/2016
 * validate form login
 */
function validateAnswer(){
    if($.trim($("#popup-reply-question #v2-input-content-qs").val())==""){
        jAlert("Please input [answers content]","");
        $("#popup-reply-question #v2-input-content-qs").focus();
        return false;
    }
    return true;
}



/**
 * date 24/10/2016
 * validate form login
 */
function validateLogin(){
    flag=true;
    $(".error.email,.error.password").hide();
    if($.trim($("#email").val())==""){
        $(".error.email").html(error_email_empty).show();        
        flag=false;
    }
    if($("#password").val()==""){
        $(".error.password").html(error_password_empty).show();        
        flag=false;
    }
    return flag;
}
/**
 * date 22/12/2016
 * validate form login tại trang giỏ hàng
 */
function validateLoginInPageConfirmcart(){
    flag=true;
    $(".error.email_confirmcart,.error.password_confirmcart").hide();
    if($.trim($("#email_confirmcart").val())==""){
        $(".error.email_confirmcart").html(error_email_empty).show();        
        flag=false;
    }
    if($("#password_confirmcart").val()==""){
        $(".error.password_confirmcart").html(error_password_empty).show();        
        flag=false;
    }
    return flag;
}
/**
 * date 12/11/2016
 * validate form login
 */
function validateLoginCommon1(){
    flag=true;
    $(".error.email,.error.password").hide();
    if($.trim($("#email_common").val())==""){
        $(".error.email").html(error_email_empty).show();        
        flag=false;
    }
    if($("#password_common").val()==""){
        $(".error.password").html(error_password_empty).show();        
        flag=false;
    }
    return flag;
}

/**
 * date 22/12/2016
 * validate form lần đầu thanh toán
 */
function validateRegisterPay(){
    flag=true;
    $(".error.fullname_register_pay,.error.email_register_pay,.error.email_confirm_register_pay,.error.phone_register_pay,.error.address_register_pay,.error.accept_register_pay,.error.city_register_pay").hide();
    if($.trim($("#fullname_register_pay").val())==""){
        $(".error.fullname_register_pay").html(error_fullname_empty).show();        
        flag=false;
    }    
    if($.trim($("#email_register_pay").val())==""){
        $(".error.email_register_pay").html(error_email_empty).show();        
        flag=false;
    }
    else{
        if(!isEmail($.trim($("#email_register_pay").val()))){
            $(".error.email_register_pay").html(error_email_illegal).show();        
            flag=false;
        }
    }
    if($.trim($("#email_confirm_register_pay").val())==""){
        $(".error.email_confirm_register_pay").html(error_confirm_email_empty).show();        
        flag=false;
    }
    else{
        if(!isEmail($.trim($("#email_confirm_register_pay").val()))){
            $(".error.email_confirm_register_pay").html(error_email_illegal).show();        
            flag=false;
        }
    }
    if($.trim($("#email_confirm_register_pay").val())!=$.trim($("#email_register_pay").val())){
        $(".error.email_confirm_register_pay").html(error_confirm_email_not_match).show();        
        flag=false;
    }
    if($.trim($("#phone_register_pay").val())==""){
        $(".error.phone_register_pay").html(error_phone_empty).show();        
        flag=false;
    }
    else{
        var regex = /^[0-9.+-\s]+$/;
        if(regex.test($.trim($("#phone_register_pay").val()))==false){
            $(".error.phone_register_pay").html(error_phone_illegal).show();        
            flag=false;
        }
    }
    
    if($.trim($("#address_register_pay").val())==""){
        $(".error.address_register_pay").html(error_address_empty).show();        
        flag=false;
    } 
    else{
        if(check_address($.trim($("#address_register_pay").val()))==true){
            $(".error.address_register_pay").html(error_address_illegal).show();        
            flag=false;
        }
    }
    if($.trim($("#city_register_pay").val())==""){
        $(".error.city_register_pay").html(error_city_empty).show();        
        flag=false;
    } 
    else{
        if(check_city($.trim($("#city_register_pay").val()))==true){
            $(".error.city_register_pay").html(error_city_illegal).show();        
            flag=false;
        }
    }
    if(!$("#accept_register_pay").is(':checked')){
        $(".error.accept_register_pay").html(error_accept_empty).show();        
        flag=false;
    }
    
    return flag;
}

/**
 * date 25/10/2016
 * validate form quên mật khẩu
 */
function validateLostpassword(){
    flag=true;
    $(".error.email3").hide();
    if($.trim($("#email3").val())==""){
        $(".error.email3").html(error_email_empty).show();        
        flag=false;
    }
    
    return flag;
}
//==========detail.php===========ST
/**
 * Gọi funtion bx slider trong detail.php
 * funtion này bỏ rồi không dùng nữa
 */
function bxSliderDetail(){
        $('.bxslider').bxSlider({
        video: true,
        useCSS: false,
        touchEnabled:true,
        autoplay: true,
        onSliderLoad:function(currentIndex){
            $("video").trigger("play");
        },
        preventDefaultSwipeY:true,
        pagerCustom: '#bx-pager'
        });

        $('video').click(function(){
        this[this.paused ? 'play' : 'pause']();
    });
}

/**
 * tại các page có tab
 * khi user chọn qua lại giữa các tab
 * thi phai thêm hoặc update #xxx vào link của icon language trên top menu
 * nếu language đang là vi thi thêm hoặc update #xxx vào link của icon en, còn language đang là en thi làm cho icon vi
 */
function updateLinkForIconLanguage(language_current){
    /**
     * lúc đầu, url đang có #xxx
     * rồi user nhấp F5 để reload 
     * thi tại link của icon language chưa active, phai thêm #xxx đó vào
     */
    url=window.location;
    url=url.toString();
    tab="";
    if(url.indexOf("#")!=-1){
        temp=url.split("#");
        if(temp.length==2&&temp[1]!=""){
            tab=temp[1];
        }        
    }    
    if(language_current=='vi'){
        url_at_icon_en_current=$(".language").find("a").eq(1).attr("href");
        if(tab!=""){
            url_at_icon_en_current=url_at_icon_en_current+"#"+tab; 
        }        
        $(".language").find("a").eq(1).attr("href",url_at_icon_en_current);
    }
    else if(language_current=='en'){
        url_at_icon_vi_current=$(".language").find("a").eq(0).attr("href");
        if(tab!=""){
            url_at_icon_vi_current=url_at_icon_vi_current+"#"+tab; 
        }         
        $(".language").find("a").eq(0).attr("href",url_at_icon_vi_current);
    }
    /**
     * các tab tại page plaza, profile
     */
    $('#tab-container').bind('easytabs:after', function(id,val,t){  
        updateLinkForIconLanguageAfterClickTab(language_current);
    });
    /**
     * các tab tại page confirmcart
     */
    $('#tab-container-pay').bind('easytabs:after', function(id,val,t){  
        updateLinkForIconLanguageAfterClickTab(language_current);
    });
}
/**
 * trong function updateLinkForIconLanguage xử lý nhiều đoạn code giống nhau
 * nên cho những đoạn code nó vào function này rồi gọi ra
 */
function updateLinkForIconLanguageAfterClickTab(language_current){
    url=window.location;
    url=url.toString();
    temp=url.split("#");
    tab=temp[1];
    if(language_current=='vi'){
        url_at_icon_en_current=$(".language").find("a").eq(1).attr("href");
        if(url_at_icon_en_current.indexOf("#")!=-1){
            temp=url_at_icon_en_current.split("#");
            url_at_icon_en_new=temp[0]+"#"+tab;
        }
        else{
            url_at_icon_en_new=url_at_icon_en_current+"#"+tab;
        }    
        $(".language").find("a").eq(1).attr("href",url_at_icon_en_new);
    }
    else if(language_current=='en'){
        url_at_icon_vi_current=$(".language").find("a").eq(0).attr("href");
        if(url_at_icon_vi_current.indexOf("#")!=-1){
            temp=url_at_icon_vi_current.split("#");
            url_at_icon_vi_new=temp[0]+"#"+tab;
        }
        else{
            url_at_icon_vi_new=url_at_icon_vi_current+"#"+tab;
        }    
        $(".language").find("a").eq(0).attr("href",url_at_icon_vi_new);
    }
}
//==========run và runcode không dùng nữa=============ST
function submitLogin(csrf,action){
    if(validateLogin()==false){
        return;
    }
    //nếu user đang đứng tại giỏ hàng và đã chọn hàng, click nút thanh toán, rồi sau đó login. Như vay, sau khi login thi phải bật ngay form [Xác nhận thông tin ra]
    if($(".table.table-bordered.content_table #sum_all").length>0&&$(".btn-btn-mns.show-thank1.click-pay-show:visible").length==0){
        url='?check_out=1';
    }
    else{
        url='';
    }
    $.ajax({ 
        async: false,
        cache: false,                                
        url: '/login'+url,
        type: "POST",
        data : $("form#frm_login").serialize() + "&_csrf=" +csrf,
        success: function(data, textStatus, jqXHR) {
            $(".error.email,.error.password").hide();
            if($.trim(data)!=""){
                if($.trim(data)=='email'){
                    $(".error.email").html(error_email_not_exist).show();
                }
                else if($.trim(data)=='password'){
                    $(".error.password").html(error_password_not_exist).show();
                }
            }
            else{                                      
                if(action=="index"){
                    window.location='plaza';
                }
                else{
                    if(icon_view_cart_is_clicked==false){
                        window.location.reload();
                    }
                    else{// Khi login bằng cách click vào icon view-cart (cả menu lẩn popup) 
                        icon_view_cart_is_clicked=false;
                        window.location='confirmcart';
                    }

                }

            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.responseText);
        }
    });
}
function submitLoginConfirmcart(csrf){
    if(validateLoginInPageConfirmcart()==false){
        return;
    }
    $.ajax({ 
        async: false,
        cache: false,                                
        url: '/login?check_out=1',
        type: "POST",
        data : $("form#frm_login_confirmcart").serialize() + "&_csrf=" +csrf,
        success: function(data, textStatus, jqXHR) {
            $(".error.email_confirmcart,.error.password_confirmcart").hide();
            if($.trim(data)!=""){
                if($.trim(data)=='email'){
                    $(".error.email_confirmcart").html(error_email_not_exist).show();
                }
                else if($.trim(data)=='password'){
                    $(".error.password_confirmcart").html(error_password_not_exist).show();
                }
            }
            else{                                      
                window.location='/confirmcart';
            }
        }
    });
}

function submitRegisterPay(csrf,price_rate_en){
    $.ajax({ 
        async: false,
        cache: false,                                
        url: '/registerpay',
        type: "POST",
        data : $("form#frm_register_pay").serialize() + "&_csrf=" +csrf,
        success: function(data, textStatus, jqXHR) {
            if($.trim(data)!=""){
                $(".error.email_register_pay").html(data).show();
            }
            else{
                if($("#paypal_cod").val()=="cod"){
                    jAlert(complete_paypal_cod,"");
                    setTimeout(function (){
                        window.location="/bought";
                    },2000);
                    
                }
                else{
                    if(price_rate_en!=""){//nếu đang ở mode tiếng Việt
                        /**
                         * tại form action="https://www.sandbox.paypal.com/cgi-bin/webscr"
                         * value của <input type="hidden" name="amount" id="amount" value=""> phai được quy đổi theo USD
                         * tức là phai lấy value hiện tại (đang la giá trị tiếng Việt) chia cho tỉ giá USD (price_rate_en được lấy trong tbl_option ra)
                         */
                        total_price=parseInt($("#total_price").val());
                        total_price=total_price/price_rate_en;
                        total_price=total_price.toFixed(2);
                        $("#amount").val(total_price);
                        
                    }
                    else{//nếu đang ở mode tiếng Anh
                        $("#amount").val($("#total_price").val());
                    }
                    quantity=parseInt($("#number-cart-template").html())+parseInt($("#number-cart-option").html());
                    $("#quantity").val(quantity);
                    $("#virtual_frm").submit();
                }
                
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
            }
    });
}
function submitLoginCommon(csrf,action){
    if(validateLoginCommon1()==false){
        return;
    }
    //nếu user đang đứng tại giỏ hàng và đã chọn hàng, click nút thanh toán, rồi sau đó login. Như vay, sau khi login thi phải bật ngay form [Xác nhận thông tin ra]
    if($(".table.table-bordered.content_table #sum_all").length>0&&$(".btn-btn-mns.show-thank1.click-pay-show:visible").length==0){
        url='?check_out=1';
    }
    else{
        url='';
    }
    $.ajax({ 
        async: false,
        cache: false,                                
        url: '/login'+url,
        type: "POST",
        data : $("form#frm_login_common").serialize() + "&_csrf=" +csrf,
        success: function(data, textStatus, jqXHR) {
            $(".error.email,.error.password").hide();
            if($.trim(data)!=""){
                if($.trim(data)=='email'){
                    $(".error.email").html(error_email_not_exist).show();
                }
                else if($.trim(data)=='password'){
                    $(".error.password").html(error_password_not_exist).show();
                }
            }
            else{                                     
                if(action=="index"){
                    window.location='plaza';
                }
                else{
                    if(icon_view_cart_is_clicked==false){
                        window.location.reload();
                    }
                    else{// Khi login bằng cách click vào icon view-cart (cả menu lẩn popup) 
                        icon_view_cart_is_clicked=false;
                        window.location='confirmcart';
                    }

                }

            }
        }
    });
}

function submitLostpassword(csrf){
    if(validateLostpassword()==false){
        return;
    }
    $.ajax({ 
        async: false,
        cache: false,                                
        url: '/lostpassword',
        type: "POST",
        data : $("form#frm_lost_password").serialize() + "&_csrf=" +csrf,
        success: function(data, textStatus, jqXHR) {
            $(".error.email3").hide();
            if($.trim(data)!=""){
                $(".error.email3").html($.trim(data)).show();
            }
            else{
                window.location.reload();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
            }
    });
}