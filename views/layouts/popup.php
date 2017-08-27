<?php
use app\models\MultiLang;		//Gói đa ngôn ngữ

$lang = MultiLang::layoutLang("popup");
?>
<div class="v2-box-login">
    <div class="v2-popup-inner">
        <?= yii\authclient\widgets\AuthChoice::widget([
             'baseAuthUrl' => ['site/auth']
        ]) ?>
        <div class="v2-content_box_login">
            <p>MNS.TV Account</p>
            <div id="login-m">
                <form id="frm_login">
                    <span class="error email" style="display: none;"><?=$lang['message_email']?></span>
                    <input id="email" name="email" type="email" class="css-input" placeholder="Email">
                    <span class="error password" style="display: none;"><?=$lang['message_password']?></span>
                    <input id="password" name="password" type="password" class="css-input" placeholder="Password">
                    <label class="checkbox-inline view-template-clone"><input type="checkbox"><span><?=$lang['remember']?></span></label>
					<label class="v2-forgot-pass"><a href="javascript:void();">Forgot your password?</a></label>
                    <a href="javascript:void(0)" id="a_login" class="v2-css-btn-login"><?=$lang['login']?></a>
                </form>     
            </div>  
        </div>
        <a class="sprited close_x close_1" href="javascript:void(0)"></a>
    </div>   
</div> 

<div class="popup" id="popup-forgot-password">
    <div class="popup-inner">
        <div class="content-popup-trial">
            <div class="box_login">
                <div class="content_box_login">
                    <form id="frm_lost_password" onsubmit="return false;">
                        <span class="error email3" style="display: none;">Error Email</span>
                        <input type="text" name="email" class="css-input cs-forget-pass" id="email3" placeholder="Your Email...">
                        <a href="javascript:void(0)" id="a_lost_password" class="css-btn-update">Send</a>
                    </form>
                </div>
            </div>
        </div>
        <a class="sprited close_x close_1" href="javascript:void(0)"></a>
    </div> <!-- end popup-inner -->
</div> <!-- end popup -->

<script type="text/javascript">
    icon_view_cart_is_clicked=false;
    icon_view_cart_on_menu_clicked=false;

    $(document).ready(function () {
        //hệ thống đang popup form đăng ký/đăng nhập, nếu user click ở ngoài form này thi form này đóng
        $(document).click(function(event) {
            if ($(event.target).closest('.v2-box-login').get(0) == null&&$(event.target).closest('#a_login').get(0) == null&&$(event.target).closest('.v2-login').get(0) == null){
                $('.v2-box-login').hide();
            } 
            
            if ($(event.target).closest('#popup-forgot-password .popup-inner').get(0) == null&&$(event.target).closest('#a_lost_password').get(0) == null&&$(event.target).closest('.sprited.close_x.close_1').get(0) == null){
//                $('#popup-forgot-password').hide();
            } 
            
        });
        
        $("#a_lost_password").click(function (){
            submitLostpassword('<?=Yii::$app->request->getCsrfToken()?>');
        });
        $("#email3").keypress(function (ev) {
            var keycode = (ev.keyCode ? ev.keyCode : ev.which);
            if (keycode == '13') {
                submitLostpassword('<?=Yii::$app->request->getCsrfToken()?>');
            }
        });
        
        $("#a_login").click(function (){
            submitLogin('<?=Yii::$app->request->getCsrfToken()?>','<?php echo Yii::$app->controller->action->id;?>');
        });
        $("#email,#password").keypress(function (ev) {
            var keycode = (ev.keyCode ? ev.keyCode : ev.which);
            if (keycode == '13') {
                submitLogin('<?=Yii::$app->request->getCsrfToken()?>','<?php echo Yii::$app->controller->action->id;?>');
            }
        });
    });
    $("head").append('<link rel="stylesheet" type="text/css" href="/js/auth/authchoice.css">');
</script>
<script src="/js/auth/authchoice.js" type="text/javascript"></script>

