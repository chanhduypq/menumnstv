<?php
namespace app\models;

use app\models\User1;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $password;
    public $re_password;
    public $phone;
    public $address;
    public $firstname;
    public $lastname;
    public $mobile;
    public $city_id;
    public $contact_person;
    public $company;
    public $user_id;



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => "Vui lòng nhập email."],
            ['email', 'email', 'message' => "Email không đúng, vui lòng kiểm tra lại."],


            ['password', 'required', 'message' => "Vui lòng nhập password."],            
            ['re_password', 'required', 'message' => "Vui lòng nhập Repassword."],            
            ['firstname', 'required', 'message' => "Vui lòng nhập họ."],            
            ['lastname', 'required', 'message' => "Vui lòng nhập tên."],            
            ['mobile', 'required', 'message' => "Vui lòng nhập mobile."],            
            ['address', 'required', 'message' => "Vui lòng nhập địa chỉ."],            
            ['city_id', 'required', 'message' => "Vui lòng chọn thành phố."],            
            ['re_password', 'required', 'message' => "Vui lòng nhập email."],            
            ['company', 'string','max'=>1000], 
            ['contact_person', 'string','max'=>100], 
            
            array(
                'phone',
                'match', 'not' => true, 'pattern' => '/[^0-9_ ]/',
                'message' => '{attribute} chỉ được nhập bằng chữ số và khoảng trắng.',
            ),
            array(
                'mobile',
                'match', 'not' => true, 'pattern' => '/[^0-9_ ]/',
                'message' => '{attribute} chỉ được nhập bằng chữ số và khoảng trắng.',
            ),
            array('re_password', 'compare', 'compareAttribute' => 'password', 'message' => 'password không trùng nhau.'),
            array('email', 'checkEmail'),
        ];
    }
    public function checkEmail($attribute, $params) { 
        if($this->user_id==""){
            $count=Yii::$app->db->createCommand()->setSql("select count(*) from tbl_user where email='".$this->$attribute."'")->queryScalar();
        }
        else{            
            $count=Yii::$app->db->createCommand()->setSql("select count(*) from tbl_user where email='".$this->$attribute."' and user_id <> ".$this->user_id)->queryScalar();
        }
        if($count=='1'){
            $this->addError('email', "Đã tồn tại email này rồi."); 
        }
        
        
        
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
