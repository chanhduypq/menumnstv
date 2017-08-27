<?php
namespace app\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * Template model
 *
 * @property integer $template_id
 * @property string $template_name
 * @property integer $designer_id
 * @property string $path
 * @property string $update_time
 * @property integer $status
 */
class Comment extends ActiveRecord {
    const COMMENT='1';
    const VOTE='2';

    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return 'tbl_comment';
    }
    /**
     * 
     * @param int|string $user_id
     * @param int|string $template_id
     * @param int|string $vote_value
     * @return void
     */
    public static function setVoteByUser($user_id, $template_id, $vote_value,$content){
        $content= str_replace("'", "\'", $content);
        $content= htmlentities($content);
        //insert tbl_comment
        $sql="INSERT INTO tbl_comment ("
                                    . "user_id,"
                                    . "comment_type,"
                                    . "template_id,"
                                    . "value,"
                                    . "content,"
                                    . "parent_id"
                                    . ")"
                            . "VALUES ("
                                    . "$user_id,"
                                    . Comment::VOTE.","
                                    . "$template_id,"
                                    . "$vote_value,"
                                    . "'$content',"
                                    . "0"
                                    . ")";
        Yii::$app->db->createCommand($sql)->execute();
        //update tbl_template.ranking
        self::updateRanking($template_id);
        
    }
    /**
     * 
     * @param int|string $user_id
     * @param int|string $template_id
     * @param int|string $vote_value
     * @return void
     */
    public static function updateVoteByUser($user_id, $template_id, $vote_value){
        //update tbl_comment
        $sql="UPDATE tbl_comment SET "
                                    . "tbl_comment.value=$vote_value "
                                . "WHERE "
                                    . "tbl_comment.template_id=$template_id"
                                    . " AND tbl_comment.comment_type=". Comment::VOTE
                                    . " AND tbl_comment.user_id=$user_id";
        Yii::$app->db->createCommand($sql)->execute();
        //update tbl_template.ranking
        self::updateRanking($template_id);
        
    }
    /**
     * 
     * @param int|string $template_id
     * @return void
     */
    public static function updateRanking($template_id){
        //get avg of vote
        $sql="SELECT AVG(tbl_comment.value) "
                . "FROM tbl_comment "
                . "WHERE "
                    . "tbl_comment.template_id=$template_id "
                    . "AND tbl_comment.comment_type=".Comment::VOTE;
        $avg=Yii::$app->db->createCommand($sql)->queryScalar();
        $avg= round(floatval($avg),1);
        //update tbl_template.ranking
        $sql="UPDATE tbl_template SET "
                                    . "tbl_template.ranking=$avg "
                                . "WHERE "
                                    . "tbl_template.template_id=$template_id";
        Yii::$app->db->createCommand($sql)->execute();
    }
    /**
     * 
     * @param int|string $user_id
     * @param int|string $template_id
     * @param string $cmt
     * @return void
     */
    public static function setCmtByUser($user_id, $template_id, $cmt,$parent_id){
        $cmt= str_replace("'", "\'", $cmt);
        $cmt= htmlentities($cmt);
        //insert tbl_comment
        $sql="INSERT INTO tbl_comment ("
                                    . "user_id,"
                                    . "comment_type,"
                                    . "template_id,"
                                    . "value,"
                                    . "content,"
                                    . "parent_id"
                                    . ")"
                            . "VALUES ("
                                    . "$user_id,"
                                    . Comment::COMMENT.","
                                    . "$template_id,"
                                    . "0,"
                                    . "'$cmt',"
                                    . "$parent_id"
                                    . ")";
        $affected=Yii::$app->db->createCommand($sql)->execute();   
        
        if($affected==1){
            self::increaseCommentCount($template_id);
        }        
    }
    /**
     * 
     * @param int|string $user_id
     * @param int|string $template_id
     * @param int|string $cmt_id
     * @return void
     */
    public static function deleteCmtByUser($user_id, $template_id, $cmt_id){
        $sql="DELETE FROM tbl_comment "
                                . "WHERE "
                                    . "template_id=$template_id "
                                    . "AND user_id=$user_id "
                                    . "AND comment_id=$cmt_id "
                                    . "AND comment_type=".Comment::COMMENT;
        $affected=Yii::$app->db->createCommand($sql)->execute();
        
        if($affected==1){
            self::decreaseCommentCount($template_id);
        }
        
    }
    /**
     * 
     * @param int|string $template_id
     * @return void
     */
    public static function increaseCommentCount($template_id){
        $sql="UPDATE tbl_template SET "
                                    . "cmt_count=cmt_count+1 "
                                . "WHERE "
                                    . "tbl_template.template_id=$template_id";
        Yii::$app->db->createCommand($sql)->execute();
    }
    /**
     * 
     * @param int|string $template_id
     * @return void
     */
    public static function decreaseCommentCount($template_id){
        $sql="UPDATE tbl_template SET "
                                    . "cmt_count=cmt_count-1 "
                                . "WHERE "
                                    . "tbl_template.template_id=$template_id";
        Yii::$app->db->createCommand($sql)->execute();
    }
    /**
     * 
     * @param int|string $user_id
     * @param int|string $template_id
     * @param string $cmt
     * @return void
     */
    public static function updateCmtByUser($user_id, $template_id, $cmt){
        $cmt= str_replace("'", "\'", $cmt);
        //update tbl_comment
        $sql="UPDATE tbl_comment SET "
                                    . "tbl_comment.content='$cmt' "
                                . "WHERE "
                                    . "tbl_comment.template_id=$template_id"
                                    . " AND tbl_comment.comment_type=". Comment::COMMENT
                                    . " AND tbl_comment.user_id=$user_id";
        Yii::$app->db->createCommand($sql)->execute();        
    }
}
?>