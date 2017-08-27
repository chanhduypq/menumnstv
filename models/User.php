<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * 
 */
class User extends ActiveRecord {

    public $re_password;

    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return 'tbl_user';
    }

    public static function getUserByEmail($email)
    {
        $user = User::find()->where(["email" => $email])->one();
        
        return $user;
    }

    public static function registerFacebookUser($fb)
    {
        $avatarName = "av.jpg";

        // save user
        $user = new User();
        $user->full_name = $fb['first_name'] . " " . $fb['last_name'];
        $user->gender_id = $fb['gender'] == "male" ? 1 : 2;
        $user->email = $fb['email'];
        $user->password = substr(md5(rand()), 0, 7);
        $user->avatar = $avatarName;

        $user->save();
        $user->firstSetting($user->user_id);

        // save user profile picture
        $user = self::getUserByEmail($fb['email']);
        $root = $GLOBALS['options']["user_template_folder"] . "/";
        $userPath = $user->user_path;

        Common::saveRemoteData($fb['avatar'], $root . $userPath, $avatarName);
        Option::resizeImg(Yii::$app->params['avatar_size'], 
                            $root . $userPath . "/" . $avatarName);

        return $user;
    }

    public static function loginViaFacebook($user){
        Yii::$app->session['user_id'] = $user['user_id'];
        Yii::$app->session['email'] = $user['email'];
        Yii::$app->session['full_name'] = $user['full_name'];
        Yii::$app->session['gender'] = $user['gender_id'];
        Yii::$app->session['login_key'] = $user['password'];
        Yii::$app->session['login_user_type'] = '';

        return $user;
    }

    public function getUserAvatar($user_id) {
        $path = Yii::$app->db->createCommand("SELECT option_value FROM tbl_option where option_name='user_template_url'")->queryScalar();
        $user = User::findOne(['user_id' => Yii::$app->session['user_id']]);
        $user_path = $user->user_path;
        $path = $path . "/" . $user_path;
        $avatar = $path . "/" . $user->avatar;
        if (strlen($user->avatar) <= 1)
            $avatar = $GLOBALS['options']["default_avatar"]; 

        return $avatar;
    }

    

    /*
      Dùng cho user của layout manager login vào hệ thống
     */

    public function login() {
        $this->email = str_replace("'", "\'", $this->email);

        $row = Yii::$app->db->createCommand()->setSql("select * from tbl_user where email='" . $this->email . "' and password='" . md5($this->password) . "' and status=1")->queryOne();

        if (is_array($row) && count($row) > 0) {

            Yii::$app->session['user_id'] = $row['user_id'];
            Yii::$app->session['email'] = $row['email'];
            Yii::$app->session['full_name'] = $row['full_name'];
            Yii::$app->session['gender'] = $row['gender_id'];
            Yii::$app->session['login_key'] = $row['password'];
            Yii::$app->session['login_user_type'] = $row['user_type'];
            return true;
        }
        return FALSE;
    }

    /*
      Dùng cho user của layout manager logout khỏi hệ thống
     */

    public function logout() {

        Yii::$app->session['user_id'] = "";
        Yii::$app->session['email'] = "";
        Yii::$app->session['full_name'] = "";
        Yii::$app->session['gender'] = "";
        Yii::$app->session['login_key'] = "";
        Yii::$app->session['login_user_type'] = "";
        Yii::$app->session['session_id'] = time();
        unset(Yii::$app->session['out_ids']);
        unset(Yii::$app->session['transaction_history_sort_field_name']);
        unset(Yii::$app->session['transaction_history_sort_desc_asc']);
    }

    /*
      Kiễm tra xem user này đã login chưa
     */

    public function is_login() {
        $user_id = Yii::$app->session['user_id'];
        $key = Yii::$app->session['login_key'];
        $user_type = Yii::$app->session['login_user_type'];

        $row = Yii::$app->db->createCommand()->setSql("select * from tbl_user where user_id='" . $user_id . "' and password= '" . $key . "' and status=1 and user_type='1'")->queryOne();
        if (is_array($row) && count($row) > 0) {

            Yii::$app->session['user_id'] = $row['user_id'];
            Yii::$app->session['email'] = $row['email'];
            Yii::$app->session['login_key'] = $row['password'];
            Yii::$app->session['login_user_type'] = $row['user_type'];
            return true;
        }
        return FALSE;
    }
    /**
     * Khi user đăng nhập lần đầu tiên, tạo 1 folder dành cho user đó như sau :
     * Option[user_template_folder] + "/" + yyyyMM + "/" + md5(time())
     */
    public function firstSetting($user_id){
        $user_path=Yii::$app->db->createCommand("SELECT user_path FROM tbl_user WHERE user_id=$user_id")->queryScalar();
        if(strlen($user_path) > 1){
            return;
        }
        $user_template_folder=$GLOBALS['options']["user_template_folder"];
		$YM = date("Ym");
        $user_path=$YM."/".md5(time());
        
        Yii::$app->db->createCommand("UPDATE tbl_user SET user_path='$user_path' WHERE user_id=$user_id")->execute();
        
        if(!file_exists($user_template_folder)){
            mkdir($user_template_folder);
        }
        if(!file_exists($user_template_folder."/".$YM)){
            mkdir($user_template_folder."/".$YM);
        }
        mkdir($user_template_folder."/".$user_path);
    }

    

}
