<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;
use app\models\Order;
use app\models\OrderDetail;
use app\models\User;
use app\models\Template;
use app\models\TemplateStore;
use app\models\MultiLang;

class LayoutService extends Model {

    public static function iff_in_array($str, $val, $arr01, $arr02 = array()) {
        $strReturn = '';

        if (in_array($val, $arr01) || in_array($val, $arr02))
            $strReturn = $str;

        return $strReturn;
    }

    /**
     * hiển thị giá tiền bao gồm: số + đơn vị tiền tệ như là 100 k hay $ 20
     * @param float|string $price
     * @param type $betwwen_string một chuỗi nằm giữa giá và đơn vị tiền. 100 k thi chuỗi trong trường hợp này là " "
     * @return string
     */
    public static function showMoney($price, $betwwen_string = " ") {
        $language_id = Yii::$app->session['language_id'];
        $money_unit = $GLOBALS['options']['money-unit-en'];

        if ($language_id == 'vi') {
            $money = number_format($price, 0, ",", ".") . $betwwen_string . $money_unit;
        } else {
            $betwwen_string = str_replace(" ", "", $betwwen_string);
            $betwwen_string = str_replace("&nbsp;", "", $betwwen_string);
            $money = $money_unit . $betwwen_string . number_format($price, 2, ".", ",");
        }

        return $money;
    }

    /**
     * hiển thị giá tiền của dịch vụ con
     *     nếu >0 thi hiển thị: số + đơn vị tiền tệ như là 100 k hay $ 20
     *     còn k thi hiển thị một chuỗi đã được lưu trong db như là: miễn phí lần đầu,...
     * @param float|string $price
     * @param type $betwwen_string một chuỗi nằm giữa giá và đơn vị tiền. 100 k thi chuỗi trong trường hợp này là " "
     * @return string
     */
    public static function showServiceMoney($price, $betwwen_string = " ") {
        if ($price > 0) {
            return self::showMoney($price, $betwwen_string);
        } else {
            $lang_cart_service_option = MultiLang::viewLang("cart_service_option");
            return $lang_cart_service_option[$price] . $betwwen_string;
        }
    }

    /**
     * lấy giá của dịch vụ con để tính toán vào các đơn hàng,...
     * @param float|string $price
     * @return float|string
     */
    public static function getRealPrice($price) {
        if ($price < 0) {
            return 0;
        }

        return $price;
    }

    /**
     * trong folder video sẽ có nhiều file image và video
     * mỗi file image tương ứng sẽ có 2 file video tương ứng
     * ví dụ: abc.jpg se có 2 file video tương ứng là abc_s.mp4 và abc_m.mp4      
     * lấy file xxx_s.mp4
     * @param string $upload_template_folder
     * @param string $upload_template_url
     * @param string $template_path
     * @param string $filename
     * @return string
     */
    public static function getSmallVideo($upload_template_folder, $upload_template_url, $template_path, $filename) {
        if (file_exists($upload_template_folder . "video/" . substr($filename, 0, strlen($filename) - 4) . "_s.mp4"))
            $video_file = $upload_template_url . "/$template_path/video/" . substr($filename, 0, strlen($filename) - 4) . "_s.mp4";
        else
            $video_file = $upload_template_url . "/$template_path/video/" . substr($filename, 0, strlen($filename) - 4) . "_s.MP4";

        return $video_file;
    }

    /**
     * trong folder video sẽ có nhiều file image và video
     * mỗi file image tương ứng sẽ có 2 file video tương ứng
     * ví dụ: abc.jpg se có 2 file video tương ứng là abc_s.mp4 và abc_m.mp4      
     * lấy file xxx_m.mp4
     * @param string $upload_template_folder
     * @param string $upload_template_url
     * @param string $template_path
     * @param string $filename
     * @return string
     */
    public static function getMediumVideo($upload_template_folder, $upload_template_url, $template_path, $filename) {
        if (file_exists($upload_template_folder . "video/" . substr($filename, 0, strlen($filename) - 4) . "_m.mp4"))
            $video_file = $upload_template_url . "/$template_path/video/" . substr($filename, 0, strlen($filename) - 4) . "_m.mp4";
        else
            $video_file = $upload_template_url . "/$template_path/video/" . substr($filename, 0, strlen($filename) - 4) . "_m.MP4";

        return $video_file;
    }

    /**
     * hiển thị chuỗi template_id, luôn là 4 kí tự XXXX. Vi dụ template có id là 70 thi hiển thị 0070, template có id là 5 thi hiển thị 0005
     * @param string $template_id
     * @return string
     */
    public static function showTemplateId($template_id) {
        $NUMBER_OF_DIGIT = 4;

        $zero_string = "";
        for ($i = 0; $i < $NUMBER_OF_DIGIT - strlen($template_id); $i++) {
            $zero_string .= "0";
        }
        return $zero_string . $template_id;
    }

}
