<!DOCTYPE html>
<html lang="en">
    <head>
        <?php echo Yii::$app->controller->renderPartial('//layouts/static_head'); ?>
    </head>
    <body>

        <div id="wrapper-home">
            <?php echo Yii::$app->controller->renderPartial('//layouts/top'); ?>

            <?= $content ?>
            <div class="popup-shop-cart">
                <div class="content-popup-shop-cart" id="content-popup-shop-cart">

                </div>
                 <span class="close-popup-cart"><i class="fa fa-times" aria-hidden="true"></i></span>
            </div>
            <div class="v2-our-services" data-parallax="scroll" data-image-src="/images/v2-bg-ourser.jpg">
                <div class="v2-padding-content">
                    <div class="v2-featured-s">
                        <div class="v2-title-o-s">
                            Our Services
                        </div>
                        <div class="v2-content-o-s">
                            Digital Signage Services. Browse our HTML5 responsive event templates below. You can easily customize any of our event <br/>
                            website templates with Webflow's code-free design tools, then connect your new event website to our powerful CMS
                        </div>
                    </div> <!-- end v2-featured-digital -->
                    <div class="v2-content-our-sv">
                        <div class="v2-left-cos">
                            <div class="v2-list-cos"><span class="v2-bg1-list-cos"></span><span class="v2-tt-cos">Digital Signage Design</span></div>
                            <div class="v2-description-cos">
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
                            </div>
                        </div>
                        <div class="v2-right-cos">
                            <div class="v2-list-cos"><span class="v2-bg2-list-cos"></span><span class="v2-tt-cos">Dev</span></div>
                            <div class="v2-description-cos">
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
                            </div>
                        </div>
                    </div> <!-- end v2-content-our-sv -->
                    <div class="v2-content-our-sv">
                        <div class="v2-left-cos">
                            <div class="v2-list-cos"><span class="v2-bg3-list-cos"></span><span class="v2-tt-cos">Digital Signage Setting</span></div>
                            <div class="v2-description-cos">
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
                            </div>
                        </div>
                        <div class="v2-right-cos">
                            <div class="v2-list-cos"><span class="v2-bg4-list-cos"></span><span class="v2-tt-cos">Help Center</span></div>
                            <div class="v2-description-cos">
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
                            </div>
                        </div>
                    </div> <!-- end v2-content-our-sv -->
                </div>
            </div>    
            <div id="footer-home">
                <?php echo Yii::$app->controller->renderPartial('//layouts/footer');?>
            </div> <!-- end footer -->


            <a href="javascript:void(0)" title="go-to-top" id="go-top"></a>
            <?php echo Yii::$app->controller->renderPartial('//layouts/static_footer'); ?>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#content-slider").lightSlider({
                    item:6,
                    loop:false,
                    slideMove:2,
                    controls: false,
                    easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
                    speed:600,
                    responsive : [
                        {
                            breakpoint:800,
                            settings: {
                                item:3,
                                slideMove:1,
                                slideMargin:6,
                              }
                        },
                        {
                            breakpoint:480,
                            settings: {
                                item:2,
                                slideMove:1
                              }
                        }
                    ]
                });
                $('#polyglotLanguageSwitcher').polyglotLanguageSwitcher({
                    effect: 'fade',
                    testMode: true,
                    onChange: function(evt){
                        load_portrait();
                        return true;
                    }
                });
                
//                $('.v2-our-services').parallax({imageSrc: 'images/v2-bg-ourser.jpg'});

            });
        </script>
    </body>
</html>