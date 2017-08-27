<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $contact_gender;
    public $contact_full_name;
    public $contact_email;
    public $contact_receive_email;
    public $contact_body;
    


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['contact_gender', 'contact_full_name', 'contact_email',  'contact_body'], 'required'],
            // email has to be a valid email address
            ['contact_email', 'email'],
        ];
    }

    public function clearForm() {
        $this->contact_gender = "";
        $this->contact_email = "";
        $this->contact_full_name = "";
        $this->contact_body = "";
    }


    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @return boolean whether the model passes validation
     */
    public function contact()
    {
        if ($this->validate()) {
            $options= Option::getOptionList();
            
            $subject = "Full name : " . $this->contact_gender . " " . $this->contact_full_name . "\n" . $this->contact_body . "\n";
            Yii::$app->mailer->compose()
                ->setTo($options['admin.email'])
                ->setFrom([$this->contact_email => $this->contact_full_name])
                ->setSubject("[Contact] - " . $this->contact_full_name)
                ->setTextBody($this->contact_body)
                ->send();

            return true;
        }
        return false;
    }
}
