<?php
use app\models\TemplateDetail;
use app\models\MultiLang;		//Gói đa ngôn ngữ

$lang = MultiLang::layoutLang("static_head");
$lang_success = MultiLang::viewLang("success_message");
$lang_error = MultiLang::viewLang("error_message");
?>
<meta charset="UTF-8">
<?php
	$acction = Yii::$app->controller->action->id;
	
	
	$title = $lang['title'];
	switch ($acction) {
		case 'detail' : 
			$url = Yii::$app->request->get()['url'];
			$templateDetail = TemplateDetail::getTemplateDetail($url);
			$title = $templateDetail->title;
			break;
		case 'plaza' : 
			$title = $lang['title-plaza'];
			break;
		case 'bought' : 
			$title = $lang['title-bought'];
			break;
		case 'home' : 
			$title = $lang['title-home'];
			break;
                case 'confirmcart' : 
                    $title = 'Cart';
                    break;
	}
?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="Mns tv, Digital menu, thuc don dien tu, digital signage, man hinh menu, menu nha hang, menu mon an, menu café, bang thuc don, quang cao ki thuat so, signage" />
    <meta name="description" content="<?=$lang['description']?>"/>

    <title><?=$title?></title>
	<meta name="keywords" content="digital signage,digital signage solution,bảng menu điện tử, giải pháp quảng cáo online, quảng cáo điện tử,giải pháp quảng cáo đa phương tiện kĩ thuật số,mns tv,bảng thực đơn điện tử,menu cửa hàng thức ăn nhanh,giải pháp digital signage cho các nhà bán lẻ" />
	<link rel="canonical" href="http://mns.tv/lua-chon-giao-dien-quang-cao-digital-menu-cho-he-thong-mns-tv-digital-signage/" />
	<meta property="og:locale" content="en_US" />
	<meta property="og:type" content="article" />
	<meta property="og:title" content="<?=$lang['og-title']?>" />
	<meta property="og:url" content="http://mns.tv/lua-chon-giao-dien-quang-cao-digital-menu-cho-he-thong-mns-tv-digital-signage/" />
	<meta property="og:site_name" content="MNS TV" />

        <script type="text/javascript">
            /**
             * trong file dung-script.js, có một số xử lý hiển thị giá tiền kèm theo chuỗi [ k] sau khi user chọn/hủy template hoặc các dịch vụ con
             * mà những đoạn code đó k nằm trong function mà nằm trong kiểu như thế này $(".change-icon-save-mns").change( function(){....});
             * nên k truyền parameter vào được
             * do đó, để 1 biến toàn cục money_unit='k' hoặc money_unit='$' nằm trên cùng để các đoạn code kia có thể gọi biến này mà hiển thị ra cho đúng theo language
             */
            money_unit='<?php echo $GLOBALS['options']['money-unit-en'];?>';
            /**
             * lang_cost này hoặc có giá trị là "Chi phí" hoặc là "Cost", được lấy từ tbl_multilang ra
             * "Chi phí" hoặc "Cost" này hiển thị chỗ này
             *     khi user rê chuộc vào các icon dịch vụ phía dưới mỗi template, thi sẽ hiển thị nội dung chi tiết của icon đó là gì (title cho mỗi icon)
             */
            <?php
            $lang_cost = MultiLang::viewLang("confirmcart");
            ?>
            lang_cost='<?php echo $lang_cost['cost'];?>';
            /**
             * khai báo biến toàn cục cho tất cả các message error lẫn success
             * để các file js gọi vào
             */
            complete_paypal_cod='<?php echo str_replace("'", "\'", $lang_success['complete_paypal_cod']);?>';
            
            error_email_exist='<?php echo str_replace("'", "\'", $lang_error['error_email_exist']);?>';
            error_password_not_exist='<?php echo str_replace("'", "\'", $lang_error['error_password_not_exist']);?>';
            error_email_not_exist='<?php echo str_replace("'", "\'", $lang_error['error_email_not_exist']);?>';
            error_accept_empty='<?php echo str_replace("'", "\'", $lang_error['error_accept_empty']);?>';
            error_city_illegal='<?php echo str_replace("'", "\'", $lang_error['error_city_illegal']);?>';
            error_city_empty='<?php echo str_replace("'", "\'", $lang_error['error_city_empty']);?>';
            error_address_illegal='<?php echo str_replace("'", "\'", $lang_error['error_address_illegal']);?>';
            error_address_empty='<?php echo str_replace("'", "\'", $lang_error['error_address_empty']);?>';
            error_phone_illegal='<?php echo str_replace("'", "\'", $lang_error['error_phone_illegal']);?>';
            error_phone_empty='<?php echo str_replace("'", "\'", $lang_error['error_phone_empty']);?>';
            error_confirm_email_not_match='<?php echo str_replace("'", "\'", $lang_error['error_confirm_email_not_match']);?>';
            error_confirm_email_empty='<?php echo str_replace("'", "\'", $lang_error['error_confirm_email_empty']);?>';
            error_fullname_empty='<?php echo str_replace("'", "\'", $lang_error['error_fullname_empty']);?>';
            error_cart_empty='<?php echo str_replace("'", "\'", $lang_error['error_cart_empty']);?>';
            error_email_illegal='<?php echo str_replace("'", "\'", $lang_error['error_email_illegal']);?>';
            error_password_empty='<?php echo str_replace("'", "\'", $lang_error['error_password_empty']);?>';
            error_email_empty='<?php echo str_replace("'", "\'", $lang_error['error_email_empty']);?>';
            
            voted=<?php if (isset($GLOBALS['voted'])&&$GLOBALS['voted']==true) echo "true";else echo "false";?>;
        </script>
<?php
	if($acction == 'detail') {
?>
	<link rel="stylesheet" type="text/css" href="/css/jquery.datepick.css">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/responsive.css">
    <!-- v.2.0 -->
    <link rel="stylesheet" type="text/css" href="/css/lightslider.css">
    <link rel="stylesheet" type="text/css" href="/css/polyglot-language-switcher.css">
    <link rel="stylesheet" type="text/css" href="/css/jquery-rating.css">
    <script src="/js/jquery-3.1.0.min.js" type="text/javascript"></script>
    <script src="/js/jquery.hashchange.min.js" type="text/javascript"></script>
    <script src="/js/jquery.easytabs.js" type="text/javascript"></script>
    <script src="/js/jquery.lightbox_me.js" type="text/javascript"></script>
    <script src="/js/jquery.datepick.js" type="text/javascript"></script>
    <script src="/js/bootstrap.file-input.js" type="text/javascript"></script>
    <script src="/js/jquery.actual.js" type="text/javascript"></script>    
    <script src="/js/jquery.alerts.js" type="text/javascript"></script>
    <link href="/css/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />
    <script src="/js/run.js" type="text/javascript"></script>
    <script src="/js/dung-script1.js" type="text/javascript"></script>
    <!-- v2.0 -->
    <script src="/js/lightslider.js" type="text/javascript"></script>
    <script src="/js/jquery.polyglot.language.switcher.js" type="text/javascript"></script>
    <script src="/js/parallax.js" type="text/javascript"></script>
    <script src="/js/jquery-rating.js" type="text/javascript"></script>
<?php
	} else 	if(($acction == 'plaza') || ($acction == 'search')){ 
?>
    <link rel="stylesheet" type="text/css" href="/css/jquery.datepick.css">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/responsive.css">

    <link rel="stylesheet" type="text/css" href="/css/lightslider.css">
    <link rel="stylesheet" type="text/css" href="/css/polyglot-language-switcher.css">
    <script src="/js/jquery-3.1.0.min.js" type="text/javascript"></script>
    <script src="/js/jquery.hashchange.min.js" type="text/javascript"></script>
    <script src="/js/jquery.easytabs.js" type="text/javascript"></script>
    <script src="/js/jquery.lightbox_me.js" type="text/javascript"></script>
    <script src="/js/jquery.datepick.js" type="text/javascript"></script>
    <script src="/js/bootstrap.file-input.js" type="text/javascript"></script>
    <script src="/js/dung-script1.js" type="text/javascript"></script>

    <script src="/js/lightslider.js" type="text/javascript"></script>
    <script src="/js/jquery.polyglot.language.switcher.js" type="text/javascript"></script>
    <script src="/js/parallax.js" type="text/javascript"></script>
    
    
    <script src="/js/jquery.actual.js" type="text/javascript"></script>    
    <script src="/js/run.js" type="text/javascript"></script>
    <script src="/js/jquery.alerts.js" type="text/javascript"></script>
    <link href="/css/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />
<?php
	}
        else if($acction == 'bought'){?>
    <link rel="stylesheet" type="text/css" href="css/jquery.datepick.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/responsive.css">
    <!-- v.2.0 -->
    <link rel="stylesheet" type="text/css" href="css/lightslider.css">
    <link rel="stylesheet" type="text/css" href="css/polyglot-language-switcher.css">
    <script src="js/jquery-3.1.0.min.js" type="text/javascript"></script>
    <script src="/js/jquery.actual.js" type="text/javascript"></script>
    <script src="js/jquery.hashchange.min.js" type="text/javascript"></script>
    <script src="js/jquery.easytabs.js" type="text/javascript"></script>
    <script src="js/jquery.lightbox_me.js" type="text/javascript"></script>
    <script src="js/jquery.datepick.js" type="text/javascript"></script>
    <script src="js/bootstrap.file-input.js" type="text/javascript"></script>
    <script src="js/dung-script1.js" type="text/javascript"></script>
    <!-- v2.0 -->
    <script src="js/lightslider.js" type="text/javascript"></script>
    <script src="js/jquery.polyglot.language.switcher.js" type="text/javascript"></script>
    <script src="js/parallax.js" type="text/javascript"></script>
    <script src="/js/run.js" type="text/javascript"></script>  
	
        <?php    
        }
        else if($acction == 'confirmcart'){?>
        <link rel="stylesheet" type="text/css" href="css/jquery.datepick.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/responsive.css">
        <!-- v.2.0 -->
        <link rel="stylesheet" type="text/css" href="css/lightslider.css">
        <link rel="stylesheet" type="text/css" href="css/polyglot-language-switcher.css">
        <link rel="stylesheet" type="text/css" href="css/jquery-rating.css">
        <script src="js/jquery-3.1.0.min.js" type="text/javascript"></script>
        <script src="/js/jquery.actual.js" type="text/javascript"></script>
        <script src="js/jquery.hashchange.min.js" type="text/javascript"></script>
        <script src="js/jquery.easytabs.js" type="text/javascript"></script>
        <script src="js/jquery.lightbox_me.js" type="text/javascript"></script>
        <script src="js/jquery.datepick.js" type="text/javascript"></script>
        <script src="js/bootstrap.file-input.js" type="text/javascript"></script>
        <script src="js/dung-script1.js" type="text/javascript"></script>
        <script src="/js/run.js" type="text/javascript"></script>
        <script src="/js/jquery.alerts.js" type="text/javascript"></script>
        <link href="/css/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />
        <!-- v2.0 -->
        <script src="js/lightslider.js" type="text/javascript"></script>
        <script src="js/jquery.polyglot.language.switcher.js" type="text/javascript"></script>
        <script src="js/parallax.js" type="text/javascript"></script>
        <script src="js/jquery-rating.js" type="text/javascript"></script>
	
        <?php    
        }
        else if($acction == 'index'){
?>
    <link rel="stylesheet" type="text/css" href="/css/jquery.datepick.css">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/responsive.css">
    <link rel="stylesheet" type="text/css" href="/css/lightslider.css">
    <link rel="stylesheet" type="text/css" href="/css/polyglot-language-switcher.css">
    <script src="/js/jquery-3.1.0.min.js" type="text/javascript"></script>
    <script src="/js/jquery.actual.js" type="text/javascript"></script>
    <script src="/js/jquery.hashchange.min.js" type="text/javascript"></script>
    <script src="/js/jquery.easytabs.js" type="text/javascript"></script>
    <script src="/js/jquery.lightbox_me.js" type="text/javascript"></script>
    <script src="/js/jquery.datepick.js" type="text/javascript"></script>
    <script src="/js/bootstrap.file-input.js" type="text/javascript"></script>
    <script src="/js/dung-script1.js" type="text/javascript"></script>
    <script src="/js/lightslider.js" type="text/javascript"></script>
    <script src="/js/jquery.polyglot.language.switcher.js" type="text/javascript"></script>
    <script src="/js/parallax.js" type="text/javascript"></script>
	<script src="/js/run.js" type="text/javascript"></script>
    <script src="/js/jquery.alerts.js" type="text/javascript"></script>
    <link href="/css/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />
<?php
	}
        else {
?>
    <link rel="stylesheet" type="text/css" href="/css/jquery.datepick.css">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/responsive.css">
    <!-- v.2.0 -->
    <link rel="stylesheet" type="text/css" href="/css/lightslider.css">
    <link rel="stylesheet" type="text/css" href="/css/polyglot-language-switcher.css">
    <link rel="stylesheet" type="text/css" href="/css/jquery-rating.css">
    <script src="/js/jquery-3.1.0.min.js" type="text/javascript"></script>
    <script src="/js/jquery.hashchange.min.js" type="text/javascript"></script>
    <script src="/js/jquery.easytabs.js" type="text/javascript"></script>
    <script src="/js/jquery.lightbox_me.js" type="text/javascript"></script>
    <script src="/js/jquery.datepick.js" type="text/javascript"></script>
    <script src="/js/bootstrap.file-input.js" type="text/javascript"></script>
    <script src="/js/dung-script1.js" type="text/javascript"></script>
	<script src="/js/run.js" type="text/javascript"></script>
    <!-- v2.0 -->
    <script src="/js/lightslider.js" type="text/javascript"></script>
    <script src="/js/jquery.polyglot.language.switcher.js" type="text/javascript"></script>
    <script src="/js/parallax.js" type="text/javascript"></script>
    <script src="/js/jquery-rating.js" type="text/javascript"></script>
<?php
	}
?>
<link rel="stylesheet" href="/css/tipso.css">
<script type="text/javascript" src="/js/tipso.js"></script>