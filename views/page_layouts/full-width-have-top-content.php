<?php
	use yii\helpers\Html;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <link rel="stylesheet" type="text/css" href="/css/jquery.datepick.css">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/price.css">
    <link rel="stylesheet" type="text/css" href="/css/responsive.css">
    <!-- v.2.0 -->
    <link rel="stylesheet" type="text/css" href="/css/lightslider.css">
    <link rel="stylesheet" type="text/css" href="/css/polyglot-language-switcher.css">
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
    </head>
    <body>

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
                        Beautiful and Dynamic HTML5 Digital Signage Templates from our creative community for Food and Beverage.
                        <br/>Our Premium Templates can easily customized by replace the text, images and color in a minute.
                        <br/>They are compatible with wide range of the Digital Signage systems on the world from the Cloud Systems to Self-hosted services.
                    </div>
                </div>
            </div>
            <!-- end v2-content-detail -->
        </div>
        <!-- end v2-detail -->
			<div id="center-home">
            <?= $content ?>
			<?php echo Yii::$app->controller->renderPartial('//layouts/popup'); ?>
			</div>
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
                <div class="v2-infomation-mns">
                    <div class="v2-padding-content">
                        <div class="v2-col-md4">
                            <div class="v2-tt-mns"><span class="v2-icon-mns v2-icon-ifo-mns1"></span><span class="v2-tt-footer-mns">MNS TV?</span></div>
                            <div class="v2-description-mns">
                                <p class="v2-mns-tv1">The Digital Signage marketplace where you can find your dream beautiful and elegant Dynamic HTML5 Templates for your business.</p>
                                <p class="v2-mns-tv2">Our Premium Templates can easily customized by replacing the text, images and color in a minute. They are compatible with wide range of the Digital Signage systems on the world from the Cloud Systems to Self-hosted services.</p>
                            </div>
                        </div> <!-- end v2-col-md4 -->
                        <div class="v2-col-md4">
                            <div class="v2-tt-mns"><span class="v2-icon-mns v2-icon-ifo-mns2"></span><span class="v2-tt-footer-mns">Contact Us</span></div>
                            <div class="v2-description-mns">
                                <div class="v2-address-mns">
                                    <p>Sai Gon</p>
                                    <p>#21, A4 St, Ward 12, Tan Binh Dist</p>
                                </div>
                                <div class="v2-address-mns">
                                    <p>Da Nang</p>
                                    <p>#421, Tran Hung Dao</p>
                                </div>
                                <div class="v2-address-mns">
                                    <p>Phone &amp; Email</p>
                                    <p>093 550 0368 (En) | 097 848 1789 (Vi) <br/>hello@mns.tv</p>
                                </div>
                            </div>
                        </div> <!-- end v2-col-md4 -->
                        <div class="v2-col-md4">
                            <div class="v2-tt-mns"><span class="v2-icon-mns v2-icon-ifo-mns3"></span><span class="v2-tt-footer-mns">Meet MNS.TV</span></div>
                            <div class="v2-description-mns">
                                <div class="v2-help-mns">
                                    <p><a href="">About MNS.TV</a></p>
                                    <p><a href="">Why Buy With Us?</a></p>
                                </div>
                                <div class="v2-help-mns">
                                    <p><a href="">MNS.TV Careers</a></p>
                                </div>
                            </div>
                        </div> <!-- end v2-col-md4 -->
                        <div class="v2-col-md4">
                            <div class="v2-tt-mns"><span class="v2-icon-mns v2-icon-ifo-mns3"></span><span class="v2-tt-footer-mns">Help &amp; Support</span></div>
                            <div class="v2-description-mns">
                                <div class="v2-help-mns">
                                    <p><a href="">Support Center</a></p>
                                    <p><a href="">Meet The Team</a></p>
                                </div>
                                <div class="v2-help-mns">
                                    <p><a href="">Blog</a></p>
                                    <p><a href="">FAQ</a></p>
                                </div>
                                <div class="v2-help-mns">
                                    <p><a href="">Contact</a></p>
                                </div>
                            </div>
                            <div class="v2-tt-mns v2-tt-payment"><span class="v2-icon-mns v2-icon-ifo-mns33"></span><span class="v2-tt-footer-mns">Payment</span></div>
                            <div class="v2-icon-payment">
                                <a href="" class="v2-paypal"></a>
                                <a href="" class="v2-visa"></a>
                                <a href="" class="v2-mastercard"></a>
                            </div>
                        </div> <!-- end v2-col-md4 -->
                    </div>
                </div>
                <div class="padding-content">
                    <div class="copyright">
                        <span>©2016&nbsp;<a href="">mns.tv</a>&nbsp;|&nbsp;<a href="">hello@mns.tv</a>&nbsp;|&nbsp;<span>093 550 0368 (En)</span>&nbsp;|&nbsp;<span>097 848 1789 (Vi)</span></span>
                    </div>
                    <div class="social">
                        <a href="" class="v2-face"></a>
                        <a href="" class="v2-gplus"></a>
                        <a href="" class="v2-witter"></a>
                        <a href="" class="v2-youtube"></a>
                        <a href="" class="v2-pinterest"></a>
                    </div>
                </div>
            </div> <!-- end footer -->
			<a href="javascript:void(0)" title="go-to-top" id="go-top"></a>

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