<?php
use app\models\Template;

$thumb2 = $upload_template_url . "/" . $model['path'] . "/assets/image/" . $model['thumb'];
$video = "";
$video = $upload_template_url . "/" . $model['path'] . "/video/" . $model['video'] . "_s.mp4";

?>
<?php if($model['resolution'] == 1){?>
<div class="v2-col-md12">
    <div class="v2-w-col-land">
        <div class="v2-padding-col-md12">
            <div class="v2-img-land-list" id="v2-land-list<?= $model['template_id'] ?>">
                <input type="hidden" value="<?= $video ?>"/>
                <a href="/detail/<?= $model['slug'] ?>">
                    <img src="<?= $thumb2 ?>" class="img-responsive">
                    <div class="v2-bg-05"></div>
                </a>      
            </div>
            <div class="v2-detail-list">
                <div class="v2-name-plaza-l-list"><?= $model['title'] ?></div>
                <div class="v2-price-plaza-l-list">
                    <span<?php if(Template::showOldPrice($model['num'],$model['num2'])=='&nbsp;&nbsp;') echo ' style="display:none;"';?>><?php echo Template::showOldPrice($model['num'],$model['num2']);?></span>
                    <span><?php echo Template::showNewPrice($model['num'],$model['num2']);?></span>
                </div>
                <div class="v2-des-plaza-l-list">
                    <?php                     
                    if (200 > strlen(utf8_decode(strip_tags($model['content'])))) {
                        $string = strip_tags($model['content']);
                    } else {
                        $string_cop = mb_substr(strip_tags($model['content']), 0,200,'UTF-8'); 
                        $string = $string_cop . "...";
                    }
                    echo $string;
                    ?>
                </div>
                <div class="v2-btn-plaza-l-list">
                    <?php echo $model['bottom_icon']; ?>
                </div>
            </div> <!-- end v2-detail-list -->
        </div> <!-- end v2-padding-col-md12 -->
        <div class="v2-statis-land-list">
            <div class="v2-statistic-p">
                <div class="v2-left-statistic-p">
                    <span><?= $model['view_count'] ?><i>(view)</i></span>
                    <span class="<?php if($model['download']==true) echo 'download'; else echo 'cart';?>"><?= $model['bought_count'] ?><i>(<?php if($model['num']==0) echo 'download'; else echo 'cart';?>)</i></span>
                    <span><?= $model['cmt_count'] ?><i>(comment)</i></span>
                </div>
                <div class="v2-right-statistic-p">
                    <?php echo Template::showRanking($model['ranking']);?>
                </div>
            </div>
        </div> <!-- end v2-statis-land-list -->
    </div> <!-- end v2-w-col-land -->
    <input type="hidden" class="hidden_<?=$model['template_id']?>" value=""/>
</div> <!-- end v2-col-md12 -->
<?php
}
else{?>
<div class="v2-col-md5">
    <div class="v2-box-col-md5">
        <div class="v2-img-col-md5-list" id="v2-port-list<?= $model['template_id'] ?>">
            <input type="hidden" value="<?= $video ?>"/>
            <a href="/detail/<?= $model['slug'] ?>">
                <img src="<?= $thumb2 ?>" class="img-responsive">
                <div class="v2-bg-05-p"></div>
            </a>
        </div>
        <div class="v2-detail-p-list">
            <div class="v2-padding-detail-p-list">
                <div class="v2-name-plaza-p-list"><?= $model['title'] ?></div>
                <div class="v2-des-plaza-p-list">
                    <?= $model['content'] ?>
                </div>
                <div class="v2-price-plaza-p-list">
                    <span<?php if(Template::showOldPrice($model['num'],$model['num2'])=='&nbsp;&nbsp;') echo ' style="display:none;"';?>><?php echo Template::showOldPrice($model['num'],$model['num2']);?></span>
                    <span><?php echo Template::showNewPrice($model['num'],$model['num2']);?></span>
                </div>
                <div class="v2-statistic v2-stt-plaza-p-l">
                    <div class="v2-left-statistic">
                        <span><?= $model['view_count'] ?></span>
                        <span class="<?php if($model['download']==true) echo 'download'; else echo 'cart';?>"><?= $model['bought_count'] ?></span>
                        <span><?= $model['cmt_count'] ?></span>
                    </div>
                    <div class="v2-right-statistic">
                        <?php echo Template::showRanking($model['ranking']);?>
                    </div>
                </div>
                <div class="v2-btn-plaza-p-list">
                    <?php echo $model['bottom_icon']; ?>
                </div>
            </div> <!-- end v2-padding-detail-p-list -->
        </div> <!-- end v2-detail-p -->
    </div> <!-- end v2-box-col-md5 -->
    <input type="hidden" class="hidden_<?=$model['template_id']?>" value=""/>
    </div> <!-- end v2-col-md5 -->
<?php } ?>
