<?php
use app\models\MultiLang;		//Gói đa ngôn ngữ
$lang = MultiLang::viewLang("runcode");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tivi</title>
    <link rel="stylesheet" type="text/css" href="css/jquery.datepick.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/responsive.css">
    <script src="js/jquery-3.1.0.min.js" type="text/javascript"></script>
    <script src="js/jquery.hashchange.min.js" type="text/javascript"></script>
    <script src="js/jquery.easytabs.js" type="text/javascript"></script>
    <script src="js/jquery.lightbox_me.js" type="text/javascript"></script>
    <script src="js/jquery.datepick.js" type="text/javascript"></script>
    <script src="js/bootstrap.file-input.js" type="text/javascript"></script>
    <script src="js/dung-script.js" type="text/javascript"></script>
</head>
<body onload="time()">

<div id="wrapper-home">
    <video id="bgvid" autoplay loop>
        <source src="video/polina.mp4" type="video/mp4">
    </video>
    <div class="bg-Filters"></div>
    <div id="top-home" class="top-home-tv">
        <div class="padding-content">
            <div class="top-left">
                <img src="images/logo.png" class="img-responsive">
            </div>
             <div class="top-right">

                <div class="clock-tv">
                    <div id="clock"></div>
                </div>

            </div>
            <!-- end top-right -->
        </div>
        <!-- end padding-content -->
    </div>
    <!-- end top -->
    <div id="center-home">
        <div class="padding-content">
            <div class="tivi-template none-margin">
                <div class="tivi-template-left">
                    <div class="tivi-video">
                        <video width="400" autoplay loop>
                          <source src="video/ds16.mp4" type="video/mp4">
                        </video>
                    </div>
                </div>
                <div class="tivi-template-right">
                    <div class="tivi-video">
                        <video width="400" autoplay loop>
                          <source src="video/ds16.mp4" type="video/mp4">
                        </video>
                    </div>
                </div>
				<?php
					$runcode = "";
					if(isset($_GET['run'])) $runcode = $_GET['run'];
				?>
                <div class="box-btn-home">
                    <form id="run_form" name="run_form" action="runcode" method="get">
                        <div class="box-input-code">
								<input type="text" id="run" name="run" value="<?=$runcode?>" placeholder="Nhập mã code" class="input-code">
								<a href="javascript:{}" onclick="document.getElementById('run_form').submit(); return false;"  class="btn-input-code" ><img src="images/icon-code.png"></a>
						</div>
                    </form>
                    <p class="title-code"><?php echo ($msg != "" ? '<font color=red>' . $msg . '</font>' : '<?=$lang['message']?>') ?></p>
                </div>
            </div>
        </div>
    </div>

    <div id="footer-home">
        <div class="padding-content">
            <div class="copyright copyright-tivi">
                <span>©2016&nbsp;<a href="">mns.tv</a>&nbsp;|&nbsp;<a href="">hello@mns.tv</a>&nbsp;|&nbsp;<span>093 550 0368 (Kỳ)</span>&nbsp;<span>097 848 1789 (Han)</span></span>
            </div>
        </div>
    </div> <!-- end footer -->

</div>

</body>
</html>