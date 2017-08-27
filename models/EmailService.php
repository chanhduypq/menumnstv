<?php
namespace app\models;

use yii\db\ActiveRecord;
use app\models\Option;
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
class EmailService extends ActiveRecord {


    /**
     * date 2016.12.05
     */
    public static function send_mail_register($gender,$fullname,$password,$email){
        $options= Option::getOptionList();
        $html_body=$options['email_register_body-'.Yii::$app->session['language_id']];
                
        $html_body= str_replace("[danh_xung]", $gender, $html_body);
        $html_body= str_replace("[full_name]", $fullname, $html_body);
        $html_body= str_replace("[password]", $password, $html_body);
        $html_body= str_replace("[email]", $email, $html_body);

        Yii::$app->mailer->compose()
            ->setFrom($options['admin.email'])
            ->setTo($email)
            ->setSubject($options['email_register_subject-'.Yii::$app->session['language_id']])    
            ->setHtmlBody($html_body)    
            ->send(); 
    }
    /**
     * date 2016.12.05
     */
    public static function send_mail_lost_password($gender,$fullname,$link,$email){
        $options= Option::getOptionList();   
        $html_body=$options['email_lost_password_step1_body-'.Yii::$app->session['language_id']];
                
        $html_body= str_replace("[danh_xung]", $gender, $html_body);
        $html_body= str_replace("[full_name]", $fullname, $html_body);
        $html_body= str_replace("[click vào đây]", $link, $html_body);
        $html_body= str_replace("[email]", $email, $html_body);

        Yii::$app->mailer->compose()
            ->setFrom($options['admin.email'])
            ->setTo($email)
            ->setSubject($options['email_lost_password_step1_subject-'.Yii::$app->session['language_id']])    
            ->setHtmlBody($html_body)    
            ->send(); 
    }
    /**
     * date 2016.12.05
     */
    public static function send_mail_reset_password($gender,$fullname,$new_password,$email){
        $options= Option::getOptionList();        
        $html_body=$options['email_lost_password_step2_body-'.Yii::$app->session['language_id']];
                
        $html_body= str_replace("[danh_xung]", $gender, $html_body);
        $html_body= str_replace("[full_name]", $fullname, $html_body);
        $html_body= str_replace("[password]", $new_password, $html_body);
        $html_body= str_replace("[email]", $email, $html_body);

        Yii::$app->mailer->compose()
            ->setFrom($options['admin.email'])
            ->setTo($email)
            ->setSubject($options['email_lost_password_step2_subject-'.Yii::$app->session['language_id']])    
            ->setHtmlBody($html_body)    
            ->send(); 
    }

}
