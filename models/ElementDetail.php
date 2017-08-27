<?php
namespace app\models;

use yii\db\ActiveRecord;

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
class ElementDetail extends ActiveRecord {


    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return 'tbl_element_detail';
    }


}
