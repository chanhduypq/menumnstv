<?php 
use yii\widgets\ListView;
use yii\widgets\LinkPager;
?>
<div class="title-v2-qs">
    <span>Customer Ratings &amp; Reviews</span>
    <div class="v2-number-ques-ans">
        <span><?php echo count($full_ratings->models); ?> Reviews</span>
    </div>                                        
</div>
<div class="v2-customer-ratings">
    <p><?php if(count($full_ratings->models)==0) echo 'Be the first To Ratings';?></p>
    <div class="v2-click-ra-detail-l">
        <div class="left-v2-click-rdl">
            <div class="r-lv2-click-rdl">
                <div class="r-star-dl click-show-popup-rating">
                    <div   class="jr-ratenode jr-nomal"></div>
                    <div   class="jr-ratenode jr-nomal"></div>
                    <div   class="jr-ratenode jr-nomal"></div>
                    <div   class="jr-ratenode jr-nomal"></div>
                    <div   class="jr-ratenode jr-nomal"></div>
                    <!--<span></span><span></span><span></span><span></span><span></span>-->
                </div>
                <div class="r-nunber-lv2-click-rdl"><?php echo count($full_ratings->models); ?>&nbsp;Reviews</div>
            </div> <!-- end r-lv2-click-rdl -->
            <div class="v2-l-click-here"><img src="/images/v2-click-here-l.png" class="img-responsive"></div>
        </div> <!-- end left-v2-click-rdl -->
        <div class="right-v2-click-rdl">
            <div class="right-star-rdl">
                <div class="box-rstrdl">
                    <span class="v2-yelow-star"></span>
                    <span class="v2-yelow-star"></span>
                    <span class="v2-yelow-star"></span>
                    <span class="v2-yelow-star"></span>
                    <span class="v2-yelow-star"></span>
                </div>
                <p>(<?php echo $ratings['5']; ?>)</p>
            </div> <!-- right-star-rdl -->
            <div class="right-star-rdl">
                <div class="box-rstrdl">
                    <span class="v2-yelow-star"></span>
                    <span class="v2-yelow-star"></span>
                    <span class="v2-yelow-star"></span>
                    <span class="v2-yelow-star"></span>
                    <span class="v2-no-star"></span>
                </div>
                <p>(<?php echo $ratings['4']; ?>)</p>
            </div> <!-- right-star-rdl -->
            <div class="right-star-rdl">
                <div class="box-rstrdl">
                    <span class="v2-yelow-star"></span>
                    <span class="v2-yelow-star"></span>
                    <span class="v2-yelow-star"></span>
                    <span class="v2-no-star"></span>
                    <span class="v2-no-star"></span>
                </div>
                <p>(<?php echo $ratings['3']; ?>)</p>
            </div> <!-- right-star-rdl -->
            <div class="right-star-rdl">
                <div class="box-rstrdl">
                    <span class="v2-yelow-star"></span>
                    <span class="v2-yelow-star"></span>
                    <span class="v2-no-star"></span>
                    <span class="v2-no-star"></span>
                    <span class="v2-no-star"></span>
                </div>
                <p>(<?php echo $ratings['2']; ?>)</p>
            </div> <!-- right-star-rdl -->
            <div class="right-star-rdl">
                <div class="box-rstrdl">
                    <span class="v2-yelow-star"></span>
                    <span class="v2-no-star"></span>
                    <span class="v2-no-star"></span>
                    <span class="v2-no-star"></span>
                    <span class="v2-no-star"></span>
                </div>
                <p>(<?php echo $ratings['1']; ?>)</p>
            </div> <!-- right-star-rdl -->
        </div> <!-- end right-v2-click-rdl -->
    </div>
    <div class="v2-comment-ranting-dl">
        <?php
        echo ListView::widget( [
                'dataProvider' => $full_ratings,
                'viewParams'=>['path'=>$path], 
                'layout' => "{items}",
                'itemView' => '_item_rating',
                'emptyText'=>'',
        ] ); 
        ?>
        


    </div> 
    
    <div class="v2-pagination ratings">
        <?php
            echo LinkPager::widget([
                    'pagination' => $full_ratings->pagination,
            ]);

        ?>
    </div>
    
</div> <!-- end v2-customer-ratings -->
