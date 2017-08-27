<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Option;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class ProfileFormPassword extends Model
{
	
    public $curr_password;
	public $new_password;
	public $re_password;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        $lang_error = MultiLang::viewLang("error_message");
        return [
			
            [['curr_password'], 'required','message'=>str_replace("'", "\'", $lang_error['error_password_empty'])],
			
            
			
			// password is validated by validateCurrPassword()
            ['curr_password', 'validateCurrPassword'],
			
			// password is validated by validateNewPassword()
            ['new_password', 'validateNewPassword'],
			
			// password is validated by validateNewPassword()
            ['re_password', 'validateNewPassword'],
        ];
    }
	
	
	
	
	
	
	
	
	//Validate cho item Curent Password
	public function validateCurrPassword($attribute, $params)
    { 
            $lang_error = MultiLang::viewLang("error_message");
		
		//Kiểm tra xem password hiện tại có đúng kg ?
		$user_id = intval(Yii::$app->session['user_id']);
		$db_pass =$GLOBALS['user']['password'];
		if(md5($this->$attribute) != $db_pass) {
			$this->addError($attribute, str_replace("'", "\'", $lang_error['error_password_not_exist']));
		}
		
		//Trường hợp không nhập new_password
		if(strlen($this->new_password) == 0) {
			$this->addError($attribute,str_replace("'", "\'", $lang_error['error_new_password_empty']));
		}
    }
	
	//Validate cho item New Password
	public function validateNewPassword($attribute, $params)
    { 
            $lang_error = MultiLang::viewLang("error_message");
	
		if ((strlen($this->$attribute) > 0) && (strlen($this->$attribute) <= 6)){
			$this->addError($attribute, str_replace("'", "\'", $lang_error['error_new_password_illegal']));
		}
		
		//Trường hợp có nhập new_password và rePassword nhưng cả hai không trùng nhau
		if ((strlen($this->new_password) > 0) && (strlen($this->re_password) > 0) && ($this->new_password != $this->re_password)){
			$this->addError($attribute, str_replace("'", "\'", $lang_error['error_new_password_not_match']));
		}
		
		//Trường hợp có nhập new_password nhưng không nhập rePassword 
		if ((strlen($this->new_password) > 0) && (strlen($this->re_password) == 0)){
			$this->addError('re_password', str_replace("'", "\'", $lang_error['error_new_re_password_empty']));
		}
    }

	
	
	/*
	Validate dữ liệu nhập, nếu OK thì change password
	Nếu No Good thì trả về false và các thông báo lỗi
	*/
	public function changePass() {
		//Validate dữ liệu nhập
		if ($this->validate()&&$this->curr_password!="") {
			$user_id = intval(Yii::$app->session['user_id']);
			$user = User::findOne(['user_id' => $user_id]);
			$user->password = md5($this->new_password);
			$user->save();								//Trường hợp người nhập thành công, save password mới
			return true;
		}
		
		return false;
	}
        
}
