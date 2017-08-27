<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\Option;
use yii\data\SqlDataProvider;
use yii\data\Pagination;

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
class MultiLang extends ActiveRecord {

    const TYPE_VIEW = '1';
    const TYPE_LAYOUT = '2';

    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return 'tbl_multilang';
    }

    /*
      Trả về một array là nội dung ngôn ngữ dành dựa trên page và type
     */

    public static function loadData($page, $type) {
        $lang = array();


        $arr = Yii::$app->db->createCommand("
					SELECT  
						name, 
						value 
					FROM 
						tbl_multilang 
					WHERE 
						page='" . $page . "' AND  
						type='" . $type . "' AND 
						language_id='" . Yii::$app->session['language_id'] . "'"
                )->queryAll();
        $l = count($arr);

        for ($i = 0; $i < $l; $i++) {
            $lang[$arr[$i]['name']] = $arr[$i]['value'];
        }

        return $lang;
    }

    /*
      Trả về một array là nội dung ngôn ngữ dành cho phần layout
     */

    public static function layoutLang($page) {
        return MultiLang::loadData($page, MultiLang::TYPE_LAYOUT);
    }

    /*
      Trả về một array là nội dung ngôn ngữ dành cho phần view
     */

    public static function viewLang($page) {
        return MultiLang::loadData($page, MultiLang::TYPE_VIEW);
    }

}
