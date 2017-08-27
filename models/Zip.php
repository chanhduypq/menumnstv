<?php

use Yii;
use yii\base\Model;
use app\models\Zip;

class Zip{
    public function __construct() {
        
    }
    public function extract1($file_name,$path){
        $zip = new ZipArchive();
        $res = $zip->open($file_name);
        if ($res === TRUE) {
          $zip->extractTo($path);
          $zip->close();
          
        } 
    }
}

