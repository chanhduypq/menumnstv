<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * 
 */
class Catalogue extends ActiveRecord {

    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return 'tbl_catalogue';
    }

    

    /*
	Lấy danh sách Catalogue theo ngôn ngữ hiện tại mà user đang chọn
	*/

	public static function getCatalogueList($ln) {
		return Catalogue::findAll(['language_id'=>$ln]);
	}
    

}
