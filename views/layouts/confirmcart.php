<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo Yii::$app->controller->renderPartial('//layouts/static_head'); ?>
</head>
<body>
<div class="modal"></div>
<div id="wrapper-home">
    <?php echo Yii::$app->controller->renderPartial('//layouts/top'); ?>
    
    <div class="v2-plaza-land">
        <video id="bgvid" autoplay loop>
            <source src="/video/bgvideo.mp4" type="video/mp4">
        </video>
        <div class="v2-plaza-detail-land">
            <div class="v2-top-content-plaza-land">
                    <div class="premium-html5">Prenium HTML5 Digital Signage Templates</div>
                    <div class="detail-prenium">
                        Beautiful and Dynamic HTML5 Digital Signage Templates from our creative community 
                        for Food and Beverage.<br/>Our Premium Templates can easily customized by replace the text, images and color in a minute.<br/>They are compatible with wide range of the 
                        Digital Signage systems on the world from the Cloud Systems to Self-hosted services.
                    </div>
            </div>  
        </div>  <!-- end v2-content-detail -->                  
    </div> <!-- end v2-detail -->
    <div id="center-home">
        <div class="v2-padding-content">
            <div class="v2-cart-new">
                <div class="v2-breadcrumb">
                    <a href="/">home</a>
                    <a href="javascript:void(0);" class="active">your cart</a>
                </div>
                <div class="v2-title-detail-l">
                    Cart Infomations
                </div>            
            </div> <!-- end v2-cart-new -->
            
            <?= $content ?>
        </div> <!-- end v2-padding-content -->
        <div class="popup-video-template-confirm-landscape">
           <video width="400" loop class="w-video" id="landscape_video_tag">
                <source src="" type="video/mp4">
            </video>
        </div>
        <div class="popup-video-template-confirm-portrait">
           <video width="400" loop class="w-video" id="portrait_video_tag">
                <source src="" type="video/mp4">
            </video>
        </div>
        <?php echo Yii::$app->controller->renderPartial('//layouts/popup');?>
    </div> <!-- end center-home -->
  
    <div id="footer-home">
        <?php echo Yii::$app->controller->renderPartial('//layouts/footer');?>
    </div> <!-- end footer -->
<a href="javascript:void(0)" title="go-to-top" id="go-top"></a>
    <?php echo Yii::$app->controller->renderPartial('//layouts/static_footer'); ?>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        
        $('#tab-container-pay').easytabs();
    });
</script>
</body>
</html>