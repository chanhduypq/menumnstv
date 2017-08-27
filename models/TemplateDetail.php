<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\SimpleHtmlDom;
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
class TemplateDetail extends ActiveRecord {


    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return 'tbl_template_detail';
    }

	public static function getTemplateDetail($url) {
		$ln = Yii::$app->session['language_id'];
		return TemplateDetail::findOne(array('slug'=>$url, "language_id" => $ln));
	}
	
}
