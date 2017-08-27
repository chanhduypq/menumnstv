<?php
namespace app\models;

use Yii;
use yii\base\Model;

class Verify extends Model{
    public static function file_verify($path){
        return true;
    }
    public static function template_verity($path){
        return true;
    }
}

