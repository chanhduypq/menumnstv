<div class="box-v2-comment-rtdl">
    <div class="v2-name-qs">
        <?php if (strlen($model['avatar']) <= 1) { ?>
            <div class="v2-abbreviate-name"><?php echo $model['full_name'][0]; ?></div>
            <?php
        } else {
            $user_path = $model['user_path'];
            $avatar = $path . "/" . $user_path . "/" . $model['avatar'];
            ?>
            <div class="v2-admin-abbreviate"><img src="<?php echo $avatar; ?>"></div>
            <?php
        }
        ?>
        <div class="v2-full-name-ques">
            <span><?php echo $model['full_name']; ?></span>

        </div>
    </div> <!-- end v2-name-qs -->
    <div class="v2-number-ranting_">
        <div class="box-rstrdl">
            <?php echo $model['html_ranking']; ?>
        </div>
    </div>
    <div class="v2-content-ranting_">
        <p><?php echo $model['content']; ?></p>
    </div>
</div>