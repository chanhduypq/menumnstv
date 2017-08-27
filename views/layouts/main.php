<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo Yii::$app->controller->renderPartial('//layouts/static_head'); ?>
</head>
<body>

<div id="wrapper-home">
    <?php echo Yii::$app->controller->renderPartial('//layouts/top'); ?>
    <!-- end top -->
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
    
             <!-- end v2-featured-digital -->
            <?= $content ?>
            
            
        
        
        
        <?php echo Yii::$app->controller->renderPartial('//layouts/popup'); ?>
    

    

    <div id="footer-home">
        <?php echo Yii::$app->controller->renderPartial('//layouts/footer');?>
    </div> <!-- end footer -->
<a href="javascript:void(0)" title="go-to-top" id="go-top"></a>
    <?php echo Yii::$app->controller->renderPartial('//layouts/static_footer'); ?>

</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#content-slider").lightSlider({
                loop:true,
                keyPress:true
            });
         $('#polyglotLanguageSwitcher').polyglotLanguageSwitcher({
                effect: 'fade',
                testMode: true,
            });
        
        // $('.v2-our-services').parallax({imageSrc: 'images/v2-bg-ourser.jpg'});
    });
</script>
</body>
</html>