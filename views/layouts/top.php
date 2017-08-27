<?php 
use app\models\MultiLang;		//Gói đa ngôn ngữ

$lang = MultiLang::layoutLang("top");
$user_id = Yii::$app->session['user_id'];
$action = Yii::$app->controller->action->id;
$avatar = $GLOBALS['options']["default_avatar"];

if(strlen(Yii::$app->session['user_id']) > 0) { 
	$path =$GLOBALS['options']["user_template_url"];
	$user_path =$GLOBALS['user']["user_path"];
	$path = $path . "/" . $user_path;
	$avatar = $path . "/" .$GLOBALS['user']["avatar"];
	if(strlen($GLOBALS['user']["avatar"]) <= 1) $avatar ="/". $GLOBALS['options']["default_avatar"];
}

//Số lượng template trong giỏ hàng
$count_cart_template = Yii::$app->db->createCommand("SELECT COUNT(*) FROM tbl_order_session WHERE main_service=1 AND session_id=".Yii::$app->session['session_id'])->queryScalar();

//Số lượng template-option trong giỏ hàng
$count_cart_option = Yii::$app->db->createCommand("SELECT COUNT(*) FROM tbl_order_session WHERE main_service=2 AND session_id=".Yii::$app->session['session_id'])->queryScalar();
?>
<div id="top-home">
    <div class="padding-content">
        <div class="top-left">
            <img src="/images/logo.png" class="img-responsive">
        </div>
        <div class="top-right">
            <div class="menu">
                <ul>
                    <?php echo Yii::$app->controller->renderPartial('//layouts/topmenu',array('action' => $action, 'lang' => $lang));?>
                    <?php if(strval($user_id) <= 0) { //Trường hợp user chưa login?>
                    <li>                                
                        <a href="javascript:void(0)" class="v2-login"><span class="icon-menu6"></span><?=$lang['show-login-popup1']?></a>
                    </li>
                    <?php                    
                    } 
                    else{
                    ?>
                    <li<?php if(Yii::$app->controller->action->id=='bought'||Yii::$app->controller->action->id=='profile') echo ' class="active"';?>>                                
                        <a href="javascript:void(0)" title="<?php echo $GLOBALS['user']["full_name"];?>">
                            <span class="icon-menu6">
                                <img src="<?php echo $avatar;?>">
                            </span>
                            <?php 
                            if (5 > strlen(utf8_decode($GLOBALS['user']["full_name"]))) {
                                $string = $GLOBALS['user']["full_name"];
                            } else {
                                $string_cop = mb_substr($GLOBALS['user']["full_name"], 0,5,'UTF-8'); 
                                $string = $string_cop . "...";
                            }
                            echo $string;
                            ?>
                        </a>
                        <ul  class="sub-menu">
                            <li><a href="/profile"><i class="bg-sub_ sub-profile"></i>Profile</a></li>
                            <li><a href="/bought"><i class="bg-sub_ sub-your-template"></i>Your Template</a></li>
                            <li><a href="/logout"><i class="bg-sub_ sub-logout"></i>Logout</a></li>
                        </ul>
                    </li>
                    <?php                    
                    }                    
                    ?>
                </ul>
            </div>
            <div id="menu-mobile">
                <a href="javascript:void(0)" class="show-mm"><i class="fa fa-bars" aria-hidden="true"></i></a>
            </div>
            <?php
            if(
                    Yii::$app->controller->action->id=='plaza'
                    ||Yii::$app->controller->action->id=='confirmcart'
                    ||Yii::$app->controller->action->id=='detail'
                    ||Yii::$app->controller->action->id=='bought'
                    ||Yii::$app->controller->action->id=='profile'
                    ||Yii::$app->controller->action->id=='index'
					||Yii::$app->controller->action->id=='contact'
					||Yii::$app->controller->action->id=='page'
            ){
            ?>
                <div class="shopping-cart">
                    <a href="/confirmcart">
                        <img src="/images/icon-shop-cart.png" class="img-responsive">
                        <p class="number-cart" id="number-cart-option"><?=$count_cart_option?></p>
                        <p class="option-cart" id="number-cart-template"><?=$count_cart_template?></p>
                    </a>
                </div>
            <?php
            }
            ?>
            
            <div id="menumm">
                <ul>
                    <li><span class="close-mm"><i class="fa fa-times" aria-hidden="true"></i></span></li>
                    
                    <?php if(strval($user_id) <= 0) { //Trường hợp user chưa login?>
                    <li>                                
                        <li><a href="javascript:void(0)" class="v2-login">Login</a></li>
                    </li>
                    <?php                    
                    } 
                    else{
                    ?>
                    <li<?php if(Yii::$app->controller->action->id=='bought'||Yii::$app->controller->action->id=='profile') echo ' class="active"';?>>                                
                        <a href="javascript:void(0)" title="<?php echo $GLOBALS['user']["full_name"];?>">
<!--                            <span class="icon-menu6">
                                <img src="<?php echo $avatar;?>">
                            </span>-->
                            <?php 
                            if (5 > strlen(utf8_decode($GLOBALS['user']["full_name"]))) {
                                $string = $GLOBALS['user']["full_name"];
                            } else {
                                $string_cop = mb_substr($GLOBALS['user']["full_name"], 0,5,'UTF-8'); 
                                $string = $string_cop . "...";
                            }
                            echo $string;
                            ?>
                            <span class="icon-sub-menu"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                        </a>
                        
                        <ul id="sub-menu">
                            <li><a href="/profile"><i class="bg-sub_ sub-profile"></i>Profile</a></li>
                            <li><a href="/bought"><i class="bg-sub_ sub-your-template"></i>Your Template</a></li>
                            <li><a href="/logout"><i class="bg-sub_ sub-logout"></i>Logout</a></li>
                        </ul>
                    </li>
                    <?php                    
                    }                    
                    ?>
                    <li><a href="/"><span class="icon-menu1"></span>Home</a></li>
                    <li><a href="javascript:void(0);">More Infomation<span class="icon-sub-menu"><i class="fa fa-caret-down" aria-hidden="true"></i></span></a>
                        <ul id="sub-menu">
                            <li><a href="/page/what-is-mnstv">What mns.tv?</a></li>
                            <li><a href="/page/why-mns-tv">Why mns.tv?</a></li>
                        </ul>
                    </li>
                    <li><a href="/page/price">Price</a></li>
                    <li><a href="javascript:void(0);">Plaza<span class="icon-sub-menu"><i class="fa fa-caret-down" aria-hidden="true"></i></span></a>
                        <ul id="sub-menu">
                            <li><a href="/plaza?resolution=1">Landscape</a></li>
                            <li><a href="/plaza?resolution=2">Portrait</a></li>
                        </ul>
                    </li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
            </div> <!-- end menumm --> 
                    
        </div>
         
    </div>
     
</div>