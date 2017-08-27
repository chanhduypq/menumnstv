<div class="v2-ct-comment">
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
            <span></span>

        </div>
    </div> <!-- end v2-name-qs -->
    <div class="v2-ques-ct-comment">
        <div class="v2-margin-ques-ct-comment">
            <div class="v2-user-ans">
                <span>q:</span>
                <span><?php echo $model['content']; ?>?</span>
            </div>
            <div class="v2-btn-reply-cm">
                <a href="javascript:void(0);" class="v2-show-popup-reply">Reply</a>
                <input type="hidden" value="<?php echo $model['comment_id']; ?>"/>
            </div>
            <div class="v2-reply-comment">

                <!--                            <div class="v2-box-reply-comment">
                                                <div class="v2-admin-reply">
                                                    <div class="v2-admin-abbreviate"></div>
                                                    <div class="v2-name-admin-repy">mns.tv</div>   
                                                </div>  box này dành cho admin của mns.tv 
                                                <div class="v2-content-reply">
                                                    A: Unzip the fownloaded template package and install monstroid2.zip from "theme" folder via Appearance/Themes in WordPress admin panel
                                                </div>
                                            </div>-->

                <!-- end v2-box-reply-comment -->
                <?php for ($i = 0; $i < count($model['answers']); $i++) { ?>
                    <div class="v2-box-reply-comment">
                        <div class="v2-user-reply">
                            <?php if (strlen($model['answers'][$i]['avatar']) <= 1) { ?>
                                <div class="v2-user-abbreviate"><?php echo $model['answers'][$i]['full_name'][0]; ?></div>
                                <?php
                            } else {
                                $user_path = $model['answers'][$i]['user_path'];
                                $avatar = $path . "/" . $user_path . "/" . $model['answers'][$i]['avatar'];
                                ?>
                                <div class="v2-admin-abbreviate"><img src="<?php echo $avatar; ?>"></div>
                                <?php
                            }
                            ?>

                            <div class="v2-full-name-ques">
                                <span><?php echo $model['answers'][$i]['full_name']; ?></span>
                                <span></span>

                            </div>   
                        </div>
                        <div class="v2-content-reply">
                            A: <?php echo $model['answers'][$i]['content']; ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div> <!-- end v2-reply-comment -->
        </div> <!-- end v2-margin-ques-ct-comment -->
    </div> <!-- end v2-ques-ct-comment -->
    <div class="v2-circle-end-comment"></div>
</div> <!-- end v2-ct-comment -->