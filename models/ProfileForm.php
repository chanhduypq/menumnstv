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
class ProfileForm extends Model
{
	public $user_id;
	public $email;
    public $avatar;
    public $full_name;
	//public $birthday;
	public $gender_id;
	public $phone;
	public $address;
	public $city;
	public $country_id;
	public $change_pass;
	public $changepass;
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
			[['avatar'], 'file','maxFiles' => 10],
            [['avatar'], 'file','extensions' => 'gif, jpg'],
			
			// username and password are both required
			[['full_name'], 'required'],
			[['gender_id'], 'required'],
			[['phone'], 'required'],
			[['address'], 'required'],
			[['city'], 'required'],
            [['country_id'], 'required'],
            [['gender_id'], 'required','message'=>'Vui lòng chọn danh xưng'],
            [['full_name'], 'required','message'=>str_replace("'", "\'", $lang_error['error_fullname_empty'])],
			
            // password is validated by validateFullname()
            ['full_name', 'validateFullname'],

			// password is validated by validateGender()
            ['gender_id', 'validateGender'],
			
			// phone is validated by validatePhone()
            ['phone', 'validatePhone'],
			
			// address is validated by validateAddress()
            ['address', 'validateAddress'],
			
			// city is validated by validateCity()
            ['city', 'validateCity'],
			
			// password is validated by validateCountryId()
            ['country_id', 'validateCountryId'],
			
        ];
    }
	
	//Validate cho Full name
	public function validateFullname($attribute, $params)
    { 
            $lang_error = MultiLang::viewLang("error_message");
		$illegal = "@#$%^&*()+=-[]';,./{}|:<>?~"; 
		if(strpbrk($this->full_name, $illegal) != '') {
		  $this->addError($attribute, str_replace("'", "\'", $lang_error['error_fullname_illegal']));
		}
		
		if (strlen($this->full_name) > 100) {
             $this->addError($attribute, 'Full name maximum is 100 character');
        }
		
		if (strlen($this->full_name) <= 4) {
		//echo 'Full name is require'; exit;
             $this->addError($attribute, 'Full name too short');
        }
    }
	
	
	//Validate cho item Gender
	public function validateGender($attribute, $params)
    {
		if (strlen($this->$attribute) <= 0) {
             $this->addError($attribute, 'Please choice gender');
        }
    }
	
	//Validate cho Phone
	public function validatePhone($attribute, $params)
    { 
            $lang_error = MultiLang::viewLang("error_message");
		$illegal = "@#$%^&*=';,/{}|:<>?~QƯERTYUIOPASDFGHJKLZXCVBNMqưertyuiopasdfghjklzxcvbnm"; 
		if(strpbrk($this->phone, $illegal) != '') {
		  $this->addError($attribute, str_replace("'", "\'", $lang_error['error_phone_illegal']));
		}
	}
	
	//Validate cho item Country
	public function validateCountryId($attribute, $params)
    {
		if (strlen($this->$attribute) <= 0) {
             $this->addError($attribute, 'Please select contry');
        }
    }
	
	//Validate cho item Address
	public function validateAddress($attribute, $params)
    {
            $lang_error = MultiLang::viewLang("error_message");
		$illegal = "@#$%^&*+=[]';{}|:<>?~"; 
		if(strpbrk($this->city, $illegal) != '') {
		  $this->addError($attribute, str_replace("'", "\'", $lang_error['error_address_illegal']));
		}
		
		if (strlen($this->address) > 200) {
             $this->addError($attribute, 'Address maximum is 100 character');
        }
    }
	
	//Validate cho item City
	public function validateCity($attribute, $params)
    {
		$illegal = "@#$%^&*()+=-[]';,./{}|:<>?~0123456789"; 
		if(strpbrk($this->city, $illegal) != '') {
		  $this->addError($attribute, "Please don't enter special charater");
		}
		
		if (strlen($this->city) > 100) {
             $this->addError($attribute, 'City name maximum is 100 character');
        }
    }
	


    public function loadFromDB($user_id) {
    	if(intval($user_id) > 0) {
				$user = User::findOne(['user_id' => $user_id]);
				$this->full_name = $user->full_name;
				$this->gender_id = $user->gender_id;
				$this->phone = $user->phone;
				$this->address = $user->address;
				$this->city = $user->city;
				$this->country_id = $user->country_id;
				return true;
		}

		return false;
    }


	/**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function save()
    {
		$is_upload_avatar = false; // Cờ dùng nhận biết user có upload avatar hay không
		$user_id = intval(Yii::$app->session['user_id']);
        if ($this->validate()) {
		
			//Trường hợp có avatar upload
			if (isset($_FILES ["ProfileForm"]["tmp_name"])) {
				$types = array('image/jpeg', 'image/gif', 'image/png');
				if (($_FILES ["ProfileForm"]['size'] > 1) && (in_array($_FILES ["ProfileForm"]['type'] ["avatar"], $types))){
				
					//Lấy path của riêng user đó  : 
					$path =$GLOBALS['options']['user_template_folder'];
					$user_path =$GLOBALS['user']['user_path'];
					$path = $path . "/" . $user_path;
					
					move_uploaded_file($_FILES ["ProfileForm"]["tmp_name"]["avatar"], $path . "/" . $_FILES ["ProfileForm"]["name"]["avatar"]);
					Option::resizeImg(Yii::$app->params['avatar_size'], $path . "/" . $_FILES ["ProfileForm"]["name"]["avatar"]); //scale kích thước image
					$is_upload_avatar = true;
				}
			}
		
			//Trường hợp $user_id > 0, tức user đã tồn tại
			// Trường hợp này chỉ update
			if(intval($user_id) > 0) {
				$user = User::findOne(['user_id' => $user_id]);
				$user->full_name = $this->full_name;
				$user->gender_id = $this->gender_id;
				$user->phone = $this->phone;
				$user->address = $this->address;
				$user->city = $this->city;
				$user->country_id = $this->country_id;
				
				if($is_upload_avatar == true) $user->avatar = $_FILES ["ProfileForm"]["name"]["avatar"];
				
				$user->save();

				return true;
			} else { //Trường hợp $user_id > 0, tức user đã tồn tại
					// Trường hợp này là tạo mới
					
				$user = new User();
				$user->full_name = $this->full_name;
				$user->gender_id = $this->gender_id;
				$user->phone = $this->phone;
				$user->address = $this->address;
				$user->city = $this->city;
				$user->country_id = $this->country_id;
				if($is_upload_avatar == true) $user->avatar = Yii::$app->params['avatar_url_path'] . "/" . $_FILES ["ProfileForm"]["name"]["avatar"];
				/*
				//Phần change pass sẽ dùng Form riêng
				if((strlen($this->curr_password) > 6) && (strlen($this->new_password) > 6) && ($this->new_password == $this->re_password)) {
					$user->password = md5($this->new_password);
				}
				*/
				
				$user->save();
		
				return true;
			}
        }
		
		
        return false;
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
        /**
         * date 2016.12.07
         */
        public function update(){
            $avatar_path="";

            if ($this->validate()) {
            	
	            if (isset($_FILES["ProfileForm"]["name"]["avatar"]) && $_FILES["ProfileForm"]["name"]["avatar"] != "") {
	                $types = array('image/jpeg', 'image/gif', 'image/png');
	                if (in_array($_FILES["ProfileForm"]["type"]["avatar"], $types)) {

	                    //Lấy path của riêng user đó  : 	                    
                            $path =$GLOBALS['options']['user_template_folder'];
                            $user_path =$GLOBALS['user']['user_path'];
	                    $path = $path . "/" . $user_path;
	                    move_uploaded_file($_FILES["ProfileForm"]["tmp_name"]["avatar"], $path . "/" . $_FILES["ProfileForm"]["name"]["avatar"]);
	                    Option::resizeImg(Yii::$app->params['avatar_size'], $path . "/" . $_FILES["ProfileForm"]["name"]["avatar"]); //scale kích thước image
	                    $avatar_path = $_FILES["ProfileForm"]["name"]["avatar"];
	                }
	            }
	                    
	                    
	            $user_id = intval(Yii::$app->session['user_id']);
	            $user = User::findOne(['user_id' => $user_id]);
	            $user->full_name = $this->full_name;
	            $user->gender_id = $this->gender_id;
	            $user->phone = $this->phone;
	            $user->address = $this->address;
	            $user->city = $this->city;
	            $user->country_id = $this->country_id;
	            if(strlen($avatar_path) > 0){
	                $user->avatar=$avatar_path;
	            }
	            $user->save();	
	            return true;
        	}
			return false;
        }
}
