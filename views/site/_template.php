<?php
use app\models\Template;

$thumb2 = $upload_template_url . "/" . $model['path'] . "/assets/image/" . $model['thumb'];
$video = "";
$video = $upload_template_url . "/" . $model['path'] . "/video/" . $model['video'] . "_s.mp4";

?>
<?php if($model['resolution'] == 1){?>
<div class="v2-col-md-3 v2-col-sm-3 v2-col-xs-3">
    <div class="hover-cart-landscape" id="show-video<?= "-" . $model['template_id'] ?>">
        <input type="hidden" value="<?= $video ?>"/>
        <div class="img-landscape-plaza">
            <a href="/detail/<?= $model['slug'] ?>">
                <img src="<?= $thumb2 ?>" class="img-responsive">
                <div class="v2-bg-05"></div>
            </a>
            
        </div>
        <div class="description-price">
            <div class="v2-description-price">
                <div class="v2-statistic">
                    <div class="v2-left-statistic">
                        <span><?= $model['view_count'] ?></span>
                        <span class="<?php if($model['download']==true) echo 'download'; else echo 'cart';?>"><?= $model['bought_count'] ?></span>
                        <span><?= $model['cmt_count'] ?></span>
                    </div>
                    <div class="v2-right-statistic">
                        <?php echo Template::showRanking($model['ranking']);?>
                    </div>
                </div>
                <div class="v2-name-price">
                    <div class="v2-left-n-p">
                        <span><?= $model['title'] ?></span>
                    </div>
                    <div class="v2-right-n-p">
                        <span<?php if(Template::showOldPrice($model['num'],$model['num2'])!='&nbsp;&nbsp;') echo ' class="v2-cost"';?>><?php echo Template::showOldPrice($model['num'],$model['num2']);?></span>
                        <span class="v2-cost-sale"><?php echo Template::showNewPrice($model['num'],$model['num2']);?></span>
                    </div>
                </div>
            </div>
            <?php echo $model['bottom_icon']; ?>
        </div>
    </div>
    <input type="hidden" class="hidden_<?=$model['template_id']?>" value=""/>
</div> 
<?php
}
else{?>
<div class="v2-col-md-3 v2-col-sm-3 v2-col-xs-3">
    <div class="hover-cart-portrait" id="show-video<?= "-" . $model['template_id'] ?>">
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
</div>
<?php } ?>
