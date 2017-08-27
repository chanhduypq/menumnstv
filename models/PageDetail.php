<?php
namespace app\models;

use Yii;
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
class PageDetail extends ActiveRecord {


    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return 'tbl_page_detail';
    }
	
	public static function getPageDetailContent($slug, $language_id) {
		$sql="SELECT 
					tbl_page_detail.page_id,
					tbl_page_detail.page_detail_id,
					tbl_page_detail.slug,
					tbl_page_detail.title,
					tbl_page_detail.content,
					tbl_page.layout,
					tbl_page.view
			FROM
				tbl_page_detail LEFT JOIN tbl_page tbl_page ON
					tbl_page.page_id = tbl_page_detail.page_id
			WHERE 
				tbl_page_detail.language_id = '" . $language_id . "' AND 
				tbl_page_detail.slug = '" . $slug . "'";

        $rows=Yii::$app->db->createCommand($sql)->queryAll();; 

        //Trường hợp URL này không tồn tại, trả về [null]
        if(sizeof($rows) <= 0)
        	return null;

        //Trường hợp URL tồn tại, trả về dòng đầu tiên của array (array này cũng chỉ có 1 dòng)
        return $rows;
	}

}
