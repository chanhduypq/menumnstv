<?php 
use yii\widgets\ListView;
use yii\widgets\LinkPager;
?>
<?php if ($count_question=='0') { ?>
    <div class="title-v2-qs">
        <span>Questions &amp; Answers</span>
    </div>
    <div class="v2-btn-qs"><a href="javascript:void(0)" class="click-show-popup-qs">be the first to ask a question</a></div>
    <?php
} else {
    ?>                                            
    <div class="title-v2-qs">
        <span>Questions &amp; Answers</span>
        <div class="v2-number-ques-ans">
            <span><?php echo $count_question; ?> Questions</span>
            <span>/</span>
            <span><?php echo $count_answer; ?> Answers</span>
        </div>
    </div>                                            
    <div class="v2-btn-write-qs"><a href="javascript:void(0)" class="click-show-popup-qs">Write Questions</a></div>
    <div class="v2-box-comment">

    <?php 
    echo ListView::widget( [
                'dataProvider' => $full_comments,
                'viewParams'=>['path'=>$path], 
                'layout' => "{items}",
                'itemView' => '_item_comment',
                'emptyText'=>'',
        ] ); 
    ?>                                                    
    </div><!-- end v2-box-comment -->
    <div class="v2-btn-readmore">
        <div class="v2-center-btn-read">
            <!--<a href="javascript:void(0);" class="v2-btn-readmore_">Read More</a>-->
            <a href="javascript:void(0);" class="v2-btn-write-qs_ click-show-popup-qs">Write Questions</a>
        </div>
    </div>

    <div class="v2-pagination comments">
        <?php
            echo LinkPager::widget([
                    'pagination' => $full_comments->pagination,
            ]);

        ?>
    </div>
        <?php
    }
    ?>

    