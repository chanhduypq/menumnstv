<?php 
use yii\widgets\ListView;

$upload_template_url = $GLOBALS['options']['upload_template_url'];
?>

<div class="v2-detail">
    <video id="bgvid" autoplay loop>
        <source src="/video/bgvideo.mp4" type="video/mp4">
    </video>
    <div class="v2-content-detail top-v2-cd-portrait">
        <div class="v2-top-content-detail v2-top-cd-portrait">
            <div class="tivi-template-left-p">
                <div class="tivi-video">
                    <video width="400" autoplay loop>
                        <source src="/video/beats_m.mp4" type="video/mp4">
                    </video>
                </div>
            </div>
            <div class="v2-text-top-content-detail v2-ttcd-portrait">
                <div class="premium-html5">Prenium HTML5<br/>Digital Signage Templates</div>
                <div class="detail-prenium">
                    Beautiful and Dynamic HTML5 Digital Signage Templates from our creative community 
                    for Food and Beverage. Our Premium Templates can easily customized by replace the text, 
                    images and color in a minute. They are compatible with wide range of the 
                    Digital Signage systems on the world from the Cloud Systems to Self-hosted services.
                </div>
                <div class="more-infor"><a href="#"><span>More Infomation</span><i class="fa fa-angle-double-right" aria-hidden="true"></i></a></div>
            </div>
        </div>
        <div class="v2-search css-v2-search-portrait">
            <div class="v2-input-search">
                <form action="" method="GET" id="frm-search">
                    <span><a href="javascript:void(0);" onclick="$('#frm-search').submit();"><img src="/images/v2-icon-search.png"></a></span>
                    <span><input name="q" value="<?php echo $key;?>" type="text" placeholder="Enter Keywords. Find Your Dream Digital Signage Templates" class="v2-css-search"></span>
                    <input type="hidden" name="resolution" value="2"/>
                </form>
            </div>
            <div id="polyglotLanguageSwitcher">
                <form action="">
                    <select id="polyglot-language-options">
                        <option id="v2-p" value="" selected>Portrait</option>
                        <option id="v2-l" value="">Landscape</option>                        
                    </select>
                </form>
            </div>
        </div>
        <div class="v2-category">
            <div class="v2-item">
                <ul id="content-slider" class="content-slider">
                    <?php
                    foreach ($catalogues as $catalogue){?>
                        <li>
                            <?php 
                            if($catalogue['icon_path']!=""){
                                echo '<img src="/'.$catalogue['icon_path'].'">';
                            }
                            else{
                                echo '<img src="/images/v2-category.png">';
                            }
                            ?>                            
                            <p><a href=""><?php echo $catalogue['catalogue_name'];?></a></p>
                        </li>
                    <?php 
                    }
                    ?>
                </ul>
            </div>
        </div>  
    </div>                    
</div>
<div id="center-home">
    <div class="v2-padding-content">
        <div class="v2-featured-digital">
            <div class="v2-title-f-d">
                Featured Digital Signage Templates of the month
            </div>
            <div class="v2-content-f-d">
                We're focusing on the menu for Food Restaurant, it will help increase of up to 33% in additional sales through the<br/>
                use of digital signage. So not only is digital signage a great way to improve your brand awareness, but it's a great way to boost sales,<br/>
                too - every marketers dream! Improving interaction with your customers is always going to have a positive outcome.
            </div>
        </div> <!-- end v2-featured-digital -->
        <div class="v2-template panel-container1">
            <?php

                echo ListView::widget( [
                        'dataProvider' => $dataProvider,
                        'viewParams'=>['upload_template_url'=>$upload_template_url], 
                        'layout' => "{items}",
                        'itemView' => '_template',
                        'emptyText'=>'',
                ] ); 

            ?>

        </div> <!-- end v2-template -->
        <div class="v2-btn-l-p">
            <p>Choose your Dream Digital Signage Template</p>
            <div class="v2-choose-l-p">
                <div class="v2-btn-l"><a href="/plaza?resolution=1" class="load_landscape">Landscape Temp</a></div>
                <div class="v2-btn-p"><a href="/plaza?resolution=2" class="active">Portrait Temp</a></div>
            </div>
        </div>
    </div>
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
    <?php echo Yii::$app->controller->renderPartial('//layouts/popup'); ?>                
</div>






<script type="text/javascript">
    jQuery(function ($){
       jQuery('.bottom').tipso({
            position: 'bottom',
            background: 'rgba(0,0,0,0.75)',
            useTitle: false,
        }).css('cursor','pointer');  
    });
</script>