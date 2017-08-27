<?php
use app\models\MultiLang;		//Gói đa ngôn ngữ

$lang = MultiLang::layoutLang("main_edit");
$template_id = $_GET['tid'];
$template_resolution = Yii::$app->db->createCommand('SELECT resolution FROM tbl_template where template_id = ' . $template_id)->queryScalar();	

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$lang['bought']?></title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <script src="js/jquery-3.1.0.min.js" type="text/javascript"></script>
    <script src="js/jquery.lightbox_me.js" type="text/javascript"></script>
<?php
	//Nếu template này dạng landscape
	if($template_resolution == 1) { ?>
	<link rel="stylesheet" type="text/css" href="css/muslider_demo.css">
	<script src="js/run_effect.js" type="text/javascript"></script>
	<?php
	//Nếu template này dạng portrait
	} else { ?>
	<link rel="stylesheet" type="text/css" href="css/muslider_demo_potraint.css">
	<script src="js/run_effect_portrait.js" type="text/javascript"></script>
	<?php
	}
	?>
    <script src="js/jquery.muslider.js" type="text/javascript"></script>
    <script src="js/bootstrap.file-input.js" type="text/javascript"></script>
    <script src="js/dung-script.js" type="text/javascript"></script>
</head>
<body onresize="changesize()">
<div id="wrapper">
    <?php echo Yii::$app->controller->renderPartial('//layouts/top');?>

    <?=$content?>
	
    <div id="footer">
            <div class="padding-content">
                <div class="copyright">© 2016 MNS TV All rights reserved</div>
                <div class="social">
                    <span><a href=""><i class="fa fa-facebook" aria-hidden="true"></i></a></span>
                    <span><a href=""><i class="fa fa-google-plus-square" aria-hidden="true"></i></a></span>
                    <span><a href=""><i class="fa fa-twitter" aria-hidden="true"></i></a></span>
                    <span><a href=""><i class="fa fa-wordpress" aria-hidden="true"></i></a></span>
                    <span><a href=""><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></span>
                </div>
            </div>
        </div> <!-- end footer -->

</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('input[type=file]').bootstrapFileInput();
        $('.file-inputs').bootstrapFileInput();
        $("#container").muslider({
                    "animation_type": "horizontal",
                    "animation_duration": 600,
                    "animation_start": "manual"
                });
        var x = $('.slide').length;
        if(x < 2){
            $('.navslide').css("display", "none");
        }     
    });
</script>
</body>
</html>