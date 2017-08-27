<?php 
use app\models\LayoutService;
use app\models\Template;


use app\models\MultiLang;  //Gói đa ngôn ngữ

$lang = MultiLang::viewLang("detail_landscape");
$lang1 = MultiLang::viewLang("confirmcart");

$template_path = Yii::$app->db->createCommand("SELECT path FROM tbl_template where template_id=" . $detailData['template_id'])->queryScalar();
$upload_template_folder = $GLOBALS['options']["upload_template_folder"] . "/" . $template_path . "/";
$files = scandir($upload_template_folder . "video");
$upload_template_url = $GLOBALS['options']["upload_template_url"];

$path = Yii::$app->db->createCommand("SELECT option_value FROM tbl_option WHERE option_name='user_template_url'")->queryScalar();
?>


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
<div id="center-home">
    <div class="v2-padding-content">
        <div class="v2-left-detail-l">
            <div class="v2-breadcrumb">
                <a href="/">home</a>
                <a href="/plaza?catalogue_id=<?= $detailData['catalogue_01_id'] ?>"><?= $detailData['catalogue_name'] ?></a>
                <a href="javascript:void(0);" class="active"><?= $detailData['title'] ?></a>
            </div>
            <div class="v2-title-detail-l">
                <?= $detailData['title'] ?> - Premium HTML5 Digital Signage Templates
            </div>
            <div class="v2-rate-detail-l">
                <div class="left">
                    <div class="v2-statistic">
                        <div class="v2-left-statistic">
                            <span><?= $detailData['view_count'] ?></span>
                            <?php if($detailData['download']==true) echo '<a href="/download?id='.$detailData['template_id'].'">';?><span class="<?php if($detailData['download']==true) echo 'download'; else echo 'cart';?>"><?= $detailData['bought_count'] ?></span><?php if($detailData['download']==true) echo '</a>';?>
                            <span><?= $detailData['cmt_count'] ?></span>
                        </div>
                        <div class="v2-right-statistic v2-mg-stt-detail-l">
                            <?php echo Template::showRanking($detailData['ranking']);?>
                        </div>
                    </div>
                </div>
                <div class="right">
                    <div class="social-detail-l">
                        <a href="" class="v2-face"></a>
                        <a href="" class="v2-gplus"></a>
                        <a href="" class="v2-witter"></a>
                        <a href="" class="v2-youtube"></a>
                        <a href="" class="v2-pinterest"></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-v2-top-dtl">
            <div class="v2-left-detail-l">
                <div class="box-v2-view-tpel">
                    <div class="view-template-edit-portrait">
                        <div class="slider-template">
                            <ul class="v2-slider v2-slider-portrait">
                                <li>
                                    <video width="400" id="video1" autoplay loop>
                                        <source src="<?=$upload_template_url . "/" . $detailData['path'] . "/video/" . $detailData['video']?>_m.mp4" type="video/mp4">
                                    </video>
                                </li>
                            </ul>
                        </div>                   
                    </div>
                    <div class="description-template-portrait">
                        <div class="pager-slide">
                            <ul id="bx-pager" class="bx-pager">
                                <?php
                            
                            $index=1;
                            $i=1;
                            
                            $landscape_thumb_width=$GLOBALS['options']["portrait_thumb_width"];
                            $landscape_thumb_height=$GLOBALS['options']["portrait_thumb_height"];
                            foreach ($files as $file){
                                if($file!="."&&$file!=".."&&strtolower(substr($file, -3))=="jpg"){                                    
                                    ?>
                                <li>
                                    <a data-slide-index="<?= LayoutService::getMediumVideo($upload_template_folder, $upload_template_url, $template_path, $file) ?>" href="javascript:void(0);" onmouseover="changeValueVideoPopup('<?= LayoutService::getSmallVideo($upload_template_folder, $upload_template_url, $template_path, $file) ?>');" class="thumb-detail-p ajax-waiting" id="thumb-detail1<?php echo $index++;?>">
                                        <img src="<?php echo $upload_template_url."/$template_path/video/$file";?>" />
                                    </a>
                                </li>

                        <?php                                     
                                }
                            }
							?>
                                
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="v2-btn-view-demo">
                    <a target="_blank" href="<?php echo $upload_template_url."/$template_path/index.html";?>">view live demo<span></span></a>
                </div>
                <div class="v2-box-information-detail">
                    <div id="tab-container" class='tab-container'>
                        <ul class='etabs'>
                            <li class='tab'><a href="#overview">overview</a></li>
                            <li class='tab'><a href="#detail">detail</a></li>
                            <li class='tab'><a href="#ratings">ratings</a></li>
                            <li class='tab'><a href="#question">questions</a></li>
                        </ul>
                        <div class='panel-container' id="center-position">
                            <div id="overview">
                                <div class="v2-des-detail-l">
                                    <?php echo $detailData['content'];?>
                                    <!--<img src="/images/v2-img-des.jpg" class="img-responsive">-->
                                </div>
                                <div class="v2-questions">
                                    <div class="pd-v2-questions">
                                        
                                        <?php
                                        echo Yii::$app->controller->renderPartial("detail_comment", array(
                                                                                                            'full_comments'=>$full_comments,
                                                                                                            'count_question'=>$count_question,
                                                                                                            'count_answer'=>$count_answer,
                                                                                                            'path'=>$path,
                                                                                                            )
                                                );
                                        ?>
                                        
                                        
                                        
                                        <?php
                                        echo Yii::$app->controller->renderPartial("detail_rating", array(
                                                                                                            'full_ratings'=>$full_ratings,
                                                                                                            'ratings'=>$ratings,
                                                                                                            'path'=>$path,
                                                                                                            )
                                                );
                                        ?>
                                    </div>
                                </div>  <!-- end v2-questions -->          
                            </div>
                            <div id="detail">
                                <div class="v2-des-detail-l">
                                    <?php echo $detailData['content'];?>
                                    <!--<img src="/images/v2-img-des-detail.jpg" class="img-responsive">-->
                                </div>                                
                                <div class="pd-v2-questions">
                                    
                                    <?php
                                    echo Yii::$app->controller->renderPartial("detail_rating", array(
                                                                                                        'full_ratings'=>$full_ratings,
                                                                                                        'ratings'=>$ratings,
                                                                                                        'path'=>$path,
                                                                                                        )
                                            );
                                    ?>
                                </div> <!-- end pd-v2-questions -->
                            </div> <!-- end detail -->
                            <div id="ratings">
                                <div class="v2-questions">
                                    <div class="pd-v2-questions">
                                        
                                        <?php
                                        echo Yii::$app->controller->renderPartial("detail_rating", array(
                                                                                                            'full_ratings'=>$full_ratings,
                                                                                                            'ratings'=>$ratings,
                                                                                                            'path'=>$path,
                                                                                                            )
                                                );
                                        ?>
                                    </div>
                                </div>  <!-- end v2-questions --> 
                            </div> <!-- end ratings -->
                            <div id="question">
                                <div class="v2-questions">
                                    <div class="pd-v2-questions">
                                        <?php
                                        echo Yii::$app->controller->renderPartial("detail_comment", array(
                                                                                                            'full_comments'=>$full_comments,
                                                                                                            'count_question'=>$count_question,
                                                                                                            'count_answer'=>$count_answer,
                                                                                                            'path'=>$path,
                                                                                                            )
                                                );
                                        ?>
                                    </div> <!-- end pd-v2-questions -->
                                </div>  <!-- end v2-questions -->
                            </div> <!-- end question -->
                        </div> <!-- end panel-container -->
                    </div> <!-- end tab-container -->
                </div> <!-- end v2-box-information-detail -->
                <div class="v2-sililar-templates">
                    <p class="v2-title-s-t">View Similar Templates</p>
                    <div class="v2-slider-similar-temp">
                        <ul id="view-similar-l" class="v2-sli-detail-st panel-container1">                            
                            <?php
                            $models = $catalogueProvider->models;                            
                            for ($i = 0; $i < count($models); $i++) {
                                $model=$models[$i];
                                $thumb2 = $upload_template_url . "/" . $model['path'] . "/assets/image/" . $model['thumb'];
                                $video = "";
                                $video = $upload_template_url . "/" . $model['path'] . "/video/" . $model['video'] . "_s.mp4";
                            ?>
                                <li>
                                    <div class="v2-col-md-3 v2-col-sm-3 v2-col-xs-3">
                                        <div class="hover-cart-portrait-detail" id="show-video<?= "-" . $model['template_id'] ?>" onmouseover="changeValueVideo(1);">
                                            <input type="hidden" value="<?= $video ?>"/>
                                            <div class="img-portrait-plaza">
                                                <a href="/detail/<?= $model['slug'] ?>">
                                                    <img src="<?= $thumb2 ?>" class="img-responsive">
                                                    <div class="v2-bg-05-p"></div>
                                                </a>
                                                <div class="v2-detail-p">
                                                    <div class="v2-description-price-p">
                                                        <div class="v2-statistic-p">
                                                            <div class="v2-left-statistic-p">
                                                                <span><?= $model['view_count'] ?><i>(view)</i></span>
                                                                <span class="<?php if($model['download']==true) echo 'download'; else echo 'cart';?>"><?= $model['bought_count'] ?><i>(download)</i></span>
                                                                <span><?= $model['cmt_count'] ?><i>(comment)</i></span>
                                                            </div>
                                                            <div class="v2-right-statistic-p">
                                                                <?php echo Template::showRanking($model['ranking']);?>
                                                            </div>
                                                        </div>
                                                        <?php echo $model['bottom_icon']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="description-price-p"><?= $model['title'] ?></div>
                                        </div>
                                        <input type="hidden" class="hidden_<?=$model['template_id']?>" value=""/>
                                    </div> <!-- end v2-col-md-3 -->
                                </li>
                            <?php
                            }
                            ?>
                        </ul> <!-- v2-sli-detail-st -->
                    </div> <!-- v2-slider-similar-temp -->
                    <div class="popup-video-template-landscape">
                       <video width="400" loop class="w-video" id="landscape_video_tag">
                            <source src="" type="video/mp4">
                        </video>
                    </div>
                    <div class="popup-video-template-portrait">
                       <video width="400" loop class="w-video" id="portrait_video_tag">
                            <source src="" type="video/mp4">
                        </video>
                    </div>
                </div> <!-- end v2-sililar-templates -->
            </div> <!-- end v2-left-detail-l -->
            <div class="v2-right-detail-l">
                <div class="box-v2-rdl">
                    <div class="content-box-v2-rdl">
                        <div class="v2-price-detail-l">
                            <p>Price:</p>
                            <p>
                                <span<?php if(Template::showOldPrice($detailData['num'],$detailData['num2'])=='&nbsp;&nbsp;') echo ' style="visibility:hidden;"';?>><?php echo Template::showOldPrice($detailData['num'],$detailData['num2']);?></span>
                                <span><?php echo Template::showNewPrice($detailData['num'],$detailData['num2']);?></span>
                            </p>
                        </div>
                        <div class="v2-box-service-detail-l">
                            <p>Recommended services:</p>
                            <div class="v2-check-box-svdl">
                                <?php
                                $subtotal_price=0;
                                if($detailData['num']-$detailData['num2']>0){
                                    $subtotal_price+=$detailData['num']-$detailData['num2'];
                                }
                                foreach ($services as $service){                                    
                                    if($service['price']>0&&(in_array($service['service_id'], $service_ids_in_order) || in_array($service['service_id'], $service_ids_in_session))){
                                        $subtotal_price+=$service['price'];
                                    }    
                                ?>
                                <div class="v2-row-service">
                                    <div class="left">
                                        <span class="check-box-funtion">
                                            <input<?php echo LayoutService::iff_in_array(' disabled="disabled"', $service['service_id'], $service_ids_in_order); echo LayoutService::iff_in_array(' checked="checked"', $service['service_id'], $service_ids_in_order,$service_ids_in_session);?> type="checkbox" name="" class="css-checkbox change-icon-detail">                                            
                                            <input type="hidden" name="out_id" value="<?=$detailData['template_id']?>"/>
                                            <input type="hidden" name="service_id" value="<?php echo $service['service_id'];?>"/>
                                            <input type="hidden" name="price" value="<?php echo $service['price'];?>"/>
                                        </span>
                                        <span class="v2-name-cloud"><?php echo $service['service_name'];?></span>
                                        <div class="icon-information">
                                            <div class="popup-information">
                                                <p><?php echo $service['service_name'];?> services</p>
                                                <p><?php echo $service['description'];?></p>
                                                <p>Price:                                                    
                                                    <span><?php echo LayoutService::showServiceMoney($service['price']);?></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div> <!-- end left -->
                                    <div class="right">
                                        <span class="v2-price-sv-detail">
                                            <!--$12-->
                                        </span>
                                        <span class="v2-price-sale-detail"><?php echo LayoutService::showServiceMoney($service['price']);?></span>
                                    </div>
                                </div> <!-- end v2-row-service -->
                                <?php
                                }
                                ?>                                
                                
                            </div> <!-- end v2-check-box-svdl -->
                        </div>    
                    </div> <!-- end content-box-v2-rdl -->
                </div> <!-- end box-v2-rdl -->
                <div class="v2-subtotal-price">
                    <img src="/images/bg-total-price.png" class="img-responsive">
                    <div class="box-v2-sub-price">
                        <div class="pd-v2-box-sub-price">
                            <div class="content-box-v2-rdl float-ctb-v2-rdl">
                                <span class="v2-span-sub-tol">Subtotal Price:</span>
                                <span class="v2-number-sub-tol">$<?php echo number_format($subtotal_price, 2, ".", ",");?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-v2-rdl -margin-box-v2-rdl">
                    <div class="content-box-v2-rdl">
                        <div class="v2-btn-add-cart-dl">
                            
                            <?php 
                            if($lock==FALSE){?>
                            <a onclick="changeValueCartAtDetail(<?php echo $detailData['template_id'];?>,this);" href="javascript:void(0);" class="add-to-cart1">
                                <img src="/images/v2-btn-add-cart.png" class="img-responsive">
                            <?php                                 
                            }
                            else{
                            ?>
                            <a onclick="updateValueCartAtDetail(<?php echo $detailData['template_id'];?>,this);" href="javascript:void(0);" class="add-to-cart1">
                                <img src="/images/v2-btn-update-cart.png" class="img-responsive">
                            <?php
                            }
                            ?>
                            </a>
                            
                        </div>
                        <div class="v2-icon-payment v2-payment-detail-l">
                            <a href="" class="v2-paypal"></a>
                            <a href="" class="v2-visa"></a>
                            <a href="" class="v2-mastercard"></a>
                        </div>
                    </div>
                </div>
            </div> <!-- end v2-right-detail-l -->
        </div> <!-- end box-v2-top-dtl -->
    </div> <!-- end v2-padding-content -->

    
</div> <!-- end center-home -->
<div class="popup-shop-cart">
    <div class="content-popup-shop-cart" id="content-popup-shop-cart">
        
    </div>
    <span class="close-popup-cart"><i class="fa fa-times" aria-hidden="true"></i></span>
</div>  
<div id="footer-home">
<?php echo Yii::$app->controller->renderPartial("//layouts/footer");?>
</div>
<div class="popup" id="popup-new-question">
    <div class="popup-inner">
        <div class="content-popup-trial">
            <div class="v2-popup-question">
                <p class="v2-title-new-question">new question</p>
                <div class="v2-name-popup-qs">
                    <span><?php echo Yii::$app->session['full_name'];?></span>
                    
                </div>
                <form action="/comment" method="POST" onsubmit="return validateQuestion();">
                    <div class="v2-box-form">
                        <p>question content:</p>
                        <textarea name="content" id="v2-input-content-qs"></textarea>
                    </div>                    
                    <div class="v2-btn-post-popup"><input type="submit" value="Post"></div>
                    <input type="hidden" name="parent_id" value="0"/>
                    <input type="hidden" name="template_id" value="<?php echo $detailData['template_id'];?>"/>
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>"/>
                </form>
            </div>
        </div>
        <a class="sprited close_x close_1" href="javascript:void(0)"></a>
    </div> <!-- end popup-inner -->
</div> <!-- end popup -->
<div class="popup" id="popup-reply-question">
    <div class="popup-inner">
        <div class="content-popup-trial">
            <div class="v2-popup-question">
                <p class="v2-title-new-question">reply question</p>
                <div class="v2-name-popup-qs">
                    <span><?php echo Yii::$app->session['full_name'];?></span>
                    
                </div>
                <form action="/comment" method="POST" onsubmit="return validateAnswer();">
                    <div class="v2-box-form">
                        <p>question content</p>
                        <textarea id="v2-questions-ct" disabled>Can the Monstroid theme be updates to Monstroid2?</textarea>
                        
                    </div>
                    <div class="v2-box-form">
                        <p>answers content:</p>
                        <textarea name="content" id="v2-input-content-qs"></textarea>                        
                    </div>
                    <div class="v2-btn-post-popup"><input type="submit" value="Post"></div>
                    <input type="hidden" name="parent_id" value="0"/>
                    <input type="hidden" name="template_id" value="<?php echo $detailData['template_id'];?>"/>
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>"/>
                </form>
            </div>
        </div>
        <a class="sprited close_x close_1" href="javascript:void(0)"></a>
    </div> <!-- end popup-inner -->
</div> <!-- end popup -->
<div class="popup" id="popup-comment-rating">
    <div class="popup-inner">
        <div class="content-popup-trial">
            <div class="v2-popup-question">
                <p class="v2-title-new-question">Ratings</p>
                <div class="v2-name-popup-qs">
                    <span><?php echo Yii::$app->session['full_name'];?></span>
                    
                </div>
                <div class="v2-number-click-ranting">
                    <div class="jr-ratenode jr-rating"></div>
                    <div class="jr-ratenode jr-rating "></div>
                    <div class="jr-ratenode jr-rating "></div>
                    <div class="jr-ratenode jr-nomal "></div>
                    <div class="jr-ratenode jr-nomal "></div>
                </div>
                <form action="/rating" method="POST" onsubmit="return validateRating();">
                    <div class="v2-box-form">
                        <p>Rating Content:</p>
                        <textarea name="content" id="v2-input-content-qs"></textarea>
                    </div>
                    <div class="v2-btn-post-popup"><input type="submit" value="Post"></div>
                    <input type="hidden" name="value"/>
                    <input type="hidden" name="template_id" value="<?php echo $detailData['template_id'];?>"/>
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>"/>
                </form>
            </div>
        </div>
        <a class="sprited close_x close_1" href="javascript:void(0)"></a>
    </div> <!-- end popup-inner -->
</div> <!-- end popup -->
<input type="hidden" id="user_id" value="<?php echo Yii::$app->session['user_id'];?>"/>

<script type="text/javascript">
    $(document).ready(function () {
//        $('#bx-pager').lightSlider({
//            item: 5,
//            loop: false,
//            slideMove: 2,
//            controls: true,
//            easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
//            speed: 600,
//            responsive: [
//                {
//                    breakpoint: 800,
//                    settings: {
//                        item: 3,
//                        slideMove: 1,
//                        slideMargin: 6,
//                    }
//                },
//                {
//                    breakpoint: 480,
//                    settings: {
//                        item: 2,
//                        slideMove: 1
//                    }
//                }
//            ]
//        });
        $('#view-similar-l').lightSlider({
            item:2,
            loop:false,
            slideMove:1,
            controls: true,
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
                    breakpoint:768,
                    settings: {
                        item:1,
                        slideMove:1
                      }
                }
            ]
        });
        
        $('#tab-container').easytabs();
        $('.r-star-dl').start(function(cur){
            $("#popup-comment-rating .jr-ratenode").removeClass('.jr-rating').addClass("jr-nomal");
            $("#popup-comment-rating .jr-ratenode").each(function(i,e){
                if (i < cur)
                {
                    $(this).addClass('jr-rating').removeClass("jr-nomal");
                }
            });
            
        });
        
        jQuery('.bottom').tipso({
            position: 'bottom',
            background: 'rgba(0,0,0,0.75)',
            useTitle: false,
        }).css('cursor','pointer'); 
        
        $(".v2-pagination.ratings a").each(function( index ) {
            href=$(this).attr("href");
            if(href.indexOf("type")!=-1){
                href=href.replace("comment","rating");
            }
            else{
                href+="&type=rating";
            }
            $(this).attr("href",href);
        });
        $(".v2-pagination.comments a").each(function( index ) {
            href=$(this).attr("href");
            if(href.indexOf("type")!=-1){
                href=href.replace("rating","comment");
            }
            else{
                href+="&type=comment";
            }
            $(this).attr("href",href);
        });
       
    });
</script>
