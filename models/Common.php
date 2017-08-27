<?php
namespace app\models;

use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class Common extends Model
{
    public static function setQuery(){
        $query="SELECT * FROM tbl_option WHERE status=1;";
        
        $user_id='0';
        if(isset(Yii::$app->session['user_id'])&& strlen(Yii::$app->session['user_id']) > 0){
            $user_id=Yii::$app->session['user_id'];
        }
        $query.="SELECT * FROM 	tbl_user WHERE user_id='$user_id';";

        return $query;
    }
    
    public static function setGlobalParameters(){
        $query= self::setQuery();
        
        $results=Yii::$app->db->createCommand($query)->query();
        
        $options =$results->readAll();
        foreach ($options as $option) {
            $arr[$option['option_name'] ] = $option['option_value'];
        }
        $GLOBALS['options'] =$arr;
        
        $results->nextResult();
        $GLOBALS['user'] =$results->read();
    }
    
    public static function saveRemoteData($target, $savePath, $filename)
    {
        $profile_Image = $target;
        $userImage = $filename;
        $path = $savePath;
        $data = file_get_contents($profile_Image);

        if ($http_response_header != NULL)
        {
            $file = $path . "/" . $userImage;
            file_put_contents($file, $data);
        }
    }
}
