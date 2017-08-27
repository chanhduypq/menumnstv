<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\SQLDataProvider;

/**
 * 
 */
class Option extends ActiveRecord {

    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return 'tbl_option';
    }

    /*
      Lấy data từ Option dựa vào option_name
     */

    public function getOptionValue($option_name) {
        $option_value = Yii::$app->db->createCommand("SELECT option_value FROM tbl_option where option_name = '" . $option_name . "'")->queryScalar();

        return $option_value;
    }

    /*
      Lấy data từ table Option và chuyển vào array,  theo dạng
      array['name'] => value
     */

    public static function getOptionList() {
        $options = Option::findAll(array('status' => 1));
        $arr = array();
        foreach ($options as $option) {
            $arr[$option->option_name] = $option->option_value;
        }
        return $arr;
    }

    /*
      scale image theo kích thước mới
     */

    public static function resizeImg($sizeStr, $source_file) {
        if (strlen(trim($sizeStr)) <= 1)
            return;
        $resizeArr = explode(",", $sizeStr);
        $max_width = $resizeArr[0];
        Option::resize_crop_image($resizeArr[0], $resizeArr[1], $source_file, $source_file, 80);
    }

    /*
      scale image theo kích thước mới
     */

    public static function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 100) {
        $imgsize = getimagesize($source_file);
        $width = $imgsize[0];
        $height = $imgsize[1];
        $mime = $imgsize['mime'];

        switch ($mime) {
            case 'image/gif':
                $image_create = "imagecreatefromgif";
                $image = "imagegif";
                break;

            case 'image/png':
                $image_create = "imagecreatefrompng";
                $image = "imagepng";
                $quality = 7;
                break;

            case 'image/jpeg':
                $image_create = "imagecreatefromjpeg";
                $image = "imagejpeg";
                $quality = 100;
                break;

            default:
                return false;
                break;
        }

        $dst_img = imagecreatetruecolor($max_width, $max_height);
        $src_img = $image_create($source_file);

        $width_new = $height * $max_width / $max_height;
        $height_new = $width * $max_height / $max_width;
        //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
        if ($width_new > $width) {
            //cut point by height
            $h_point = (($height - $height_new) / 2);
            //copy image
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
        } else {
            //cut point by width
            $w_point = (($width - $width_new) / 2);
            imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
        }

        $image($dst_img, $dst_dir, $quality);

        if ($dst_img)
            imagedestroy($dst_img);
        if ($src_img)
            imagedestroy($src_img);
    }

    /*
      Kiễm tra xem IP có phải của VN,
      - nếu là của VN thì trả về: [VN]
      - Nếu IP nước ngoài, trả về [NOT VN]
     */

    public static function checkVNIP() {
        // Lấy IP của client
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        

        $ip_arr = explode('.', $ip);
        
        $ip_num = 16777216 * $ip_arr[0] + 65536 * $ip_arr[1] + 256 * $ip_arr[2] + $ip_arr[3];

        $local_name = "NOT VN";

        if (($ip_num >= 20185088 ) && ($ip_num <= 20447231 ))
            $local_name = "VN";
        if (($ip_num >= 234885120 ) && ($ip_num <= 234889215 ))
            $local_name = "VN";
        if (($ip_num >= 245366784 ) && ($ip_num <= 247463935 ))
            $local_name = "VN";
        if (($ip_num >= 249561088 ) && ($ip_num <= 251658239 ))
            $local_name = "VN";
        if (($ip_num >= 452987904 ) && ($ip_num <= 452988927 ))
            $local_name = "VN";
        if (($ip_num >= 453115904 ) && ($ip_num <= 453246975 ))
            $local_name = "VN";
        if (($ip_num >= 457179136 ) && ($ip_num <= 458227711 ))
            $local_name = "VN";
        if (($ip_num >= 460722176 ) && ($ip_num <= 460726271 ))
            $local_name = "VN";
        if (($ip_num >= 635434240 ) && ($ip_num <= 635434495 ))
            $local_name = "VN";
        if (($ip_num >= 704724992 ) && ($ip_num <= 704741375 ))
            $local_name = "VN";
        if (($ip_num >= 710934528 ) && ($ip_num <= 710950911 ))
            $local_name = "VN";
        if (($ip_num >= 711983104 ) && ($ip_num <= 712507391 ))
            $local_name = "VN";
        if (($ip_num >= 832320512 ) && ($ip_num <= 832321535 ))
            $local_name = "VN";
        if (($ip_num >= 836059136 ) && ($ip_num <= 836075519 ))
            $local_name = "VN";
        if (($ip_num >= 837603328 ) && ($ip_num <= 837604351 ))
            $local_name = "VN";
        if (($ip_num >= 838238208 ) && ($ip_num <= 838262783 ))
            $local_name = "VN";
        if (($ip_num >= 962416640 ) && ($ip_num <= 962420735 ))
            $local_name = "VN";
        if (($ip_num >= 985268224 ) && ($ip_num <= 985399295 ))
            $local_name = "VN";
        if (($ip_num >= 1024188416 ) && ($ip_num <= 1024196607 ))
            $local_name = "VN";
        if (($ip_num >= 1025302528 ) && ($ip_num <= 1025310719 ))
            $local_name = "VN";
        if (($ip_num >= 1697972224 ) && ($ip_num <= 1697988607 ))
            $local_name = "VN";
        if (($ip_num >= 1700793344 ) && ($ip_num <= 1700794367 ))
            $local_name = "VN";
        if (($ip_num >= 1700806656 ) && ($ip_num <= 1700823039 ))
            $local_name = "VN";
        if (($ip_num >= 1700986880 ) && ($ip_num <= 1701003263 ))
            $local_name = "VN";
        if (($ip_num >= 1728169984 ) && ($ip_num <= 1728171007 ))
            $local_name = "VN";
        if (($ip_num >= 1728172032 ) && ($ip_num <= 1728173055 ))
            $local_name = "VN";
        if (($ip_num >= 1728179200 ) && ($ip_num <= 1728180223 ))
            $local_name = "VN";
        if (($ip_num >= 1728240640 ) && ($ip_num <= 1728243711 ))
            $local_name = "VN";
        if (($ip_num >= 1728312320 ) && ($ip_num <= 1728315391 ))
            $local_name = "VN";
        if (($ip_num >= 1728348160 ) && ($ip_num <= 1728349183 ))
            $local_name = "VN";
        if (($ip_num >= 1728388608 ) && ($ip_num <= 1728389119 ))
            $local_name = "VN";
        if (($ip_num >= 1728433152 ) && ($ip_num <= 1728435199 ))
            $local_name = "VN";
        if (($ip_num >= 1728521216 ) && ($ip_num <= 1728523263 ))
            $local_name = "VN";
        if (($ip_num >= 1728556032 ) && ($ip_num <= 1728556287 ))
            $local_name = "VN";
        if (($ip_num >= 1728556544 ) && ($ip_num <= 1728557055 ))
            $local_name = "VN";
        if (($ip_num >= 1728557312 ) && ($ip_num <= 1728557567 ))
            $local_name = "VN";
        if (($ip_num >= 1728562176 ) && ($ip_num <= 1728562431 ))
            $local_name = "VN";
        if (($ip_num >= 1728580864 ) && ($ip_num <= 1728581119 ))
            $local_name = "VN";
        if (($ip_num >= 1728643072 ) && ($ip_num <= 1728645119 ))
            $local_name = "VN";
        if (($ip_num >= 1728662528 ) && ($ip_num <= 1728665599 ))
            $local_name = "VN";
        if (($ip_num >= 1728693248 ) && ($ip_num <= 1728698367 ))
            $local_name = "VN";
        if (($ip_num >= 1728731136 ) && ($ip_num <= 1728732159 ))
            $local_name = "VN";
        if (($ip_num >= 1728762880 ) && ($ip_num <= 1728763903 ))
            $local_name = "VN";
        if (($ip_num >= 1728818176 ) && ($ip_num <= 1728819199 ))
            $local_name = "VN";
        if (($ip_num >= 1728866304 ) && ($ip_num <= 1728867327 ))
            $local_name = "VN";
        if (($ip_num >= 1728924672 ) && ($ip_num <= 1728925695 ))
            $local_name = "VN";
        if (($ip_num >= 1729048576 ) && ($ip_num <= 1729049599 ))
            $local_name = "VN";
        if (($ip_num >= 1729101824 ) && ($ip_num <= 1729102847 ))
            $local_name = "VN";
        if (($ip_num >= 1729189888 ) && ($ip_num <= 1729190911 ))
            $local_name = "VN";
        if (($ip_num >= 1729227776 ) && ($ip_num <= 1729228799 ))
            $local_name = "VN";
        if (($ip_num >= 1729233920 ) && ($ip_num <= 1729234943 ))
            $local_name = "VN";
        if (($ip_num >= 1729277952 ) && ($ip_num <= 1729278975 ))
            $local_name = "VN";
        if (($ip_num >= 1729323008 ) && ($ip_num <= 1729324031 ))
            $local_name = "VN";
        if (($ip_num >= 1729340416 ) && ($ip_num <= 1729341439 ))
            $local_name = "VN";
        if (($ip_num >= 1729354752 ) && ($ip_num <= 1729355775 ))
            $local_name = "VN";
        if (($ip_num >= 1729400832 ) && ($ip_num <= 1729402879 ))
            $local_name = "VN";
        if (($ip_num >= 1729460224 ) && ($ip_num <= 1729461247 ))
            $local_name = "VN";
        if (($ip_num >= 1729467392 ) && ($ip_num <= 1729468415 ))
            $local_name = "VN";
        if (($ip_num >= 1729597440 ) && ($ip_num <= 1729598463 ))
            $local_name = "VN";
        if (($ip_num >= 1729600512 ) && ($ip_num <= 1729601535 ))
            $local_name = "VN";
        if (($ip_num >= 1729688576 ) && ($ip_num <= 1729689599 ))
            $local_name = "VN";
        if (($ip_num >= 1729821696 ) && ($ip_num <= 1729822719 ))
            $local_name = "VN";
        if (($ip_num >= 1729838080 ) && ($ip_num <= 1729840127 ))
            $local_name = "VN";
        if (($ip_num >= 1729883136 ) && ($ip_num <= 1729884159 ))
            $local_name = "VN";
        if (($ip_num >= 1729896448 ) && ($ip_num <= 1729898495 ))
            $local_name = "VN";
        if (($ip_num >= 1729923072 ) && ($ip_num <= 1729924095 ))
            $local_name = "VN";
        if (($ip_num >= 1729932288 ) && ($ip_num <= 1729933311 ))
            $local_name = "VN";
        if (($ip_num >= 1730028544 ) && ($ip_num <= 1730029567 ))
            $local_name = "VN";
        if (($ip_num >= 1730115584 ) && ($ip_num <= 1730117631 ))
            $local_name = "VN";
        if (($ip_num >= 1730485248 ) && ($ip_num <= 1730487295 ))
            $local_name = "VN";
        if (($ip_num >= 1730578432 ) && ($ip_num <= 1730579455 ))
            $local_name = "VN";
        if (($ip_num >= 1730632704 ) && ($ip_num <= 1730634751 ))
            $local_name = "VN";
        if (($ip_num >= 1730820096 ) && ($ip_num <= 1730821119 ))
            $local_name = "VN";
        if (($ip_num >= 1731060736 ) && ($ip_num <= 1731063807 ))
            $local_name = "VN";
        if (($ip_num >= 1731182592 ) && ($ip_num <= 1731183615 ))
            $local_name = "VN";
        if (($ip_num >= 1731218432 ) && ($ip_num <= 1731221503 ))
            $local_name = "VN";
        if (($ip_num >= 1731247104 ) && ($ip_num <= 1731249151 ))
            $local_name = "VN";
        if (($ip_num >= 1742776320 ) && ($ip_num <= 1742777343 ))
            $local_name = "VN";
        if (($ip_num >= 1742859264 ) && ($ip_num <= 1742860287 ))
            $local_name = "VN";
        if (($ip_num >= 1742892032 ) && ($ip_num <= 1742893055 ))
            $local_name = "VN";
        if (($ip_num >= 1742927872 ) && ($ip_num <= 1742928895 ))
            $local_name = "VN";
        if (($ip_num >= 1742958592 ) && ($ip_num <= 1742959615 ))
            $local_name = "VN";
        if (($ip_num >= 1742985216 ) && ($ip_num <= 1742986239 ))
            $local_name = "VN";
        if (($ip_num >= 1743000576 ) && ($ip_num <= 1743001599 ))
            $local_name = "VN";
        if (($ip_num >= 1743071232 ) && ($ip_num <= 1743072255 ))
            $local_name = "VN";
        if (($ip_num >= 1743110144 ) && ($ip_num <= 1743111167 ))
            $local_name = "VN";
        if (($ip_num >= 1743229952 ) && ($ip_num <= 1743230975 ))
            $local_name = "VN";
        if (($ip_num >= 1743270912 ) && ($ip_num <= 1743273983 ))
            $local_name = "VN";
        if (($ip_num >= 1743288320 ) && ($ip_num <= 1743289343 ))
            $local_name = "VN";
        if (($ip_num >= 1743335424 ) && ($ip_num <= 1743336447 ))
            $local_name = "VN";
        if (($ip_num >= 1743397888 ) && ($ip_num <= 1743398911 ))
            $local_name = "VN";
        if (($ip_num >= 1743411200 ) && ($ip_num <= 1743412223 ))
            $local_name = "VN";
        if (($ip_num >= 1743507456 ) && ($ip_num <= 1743509503 ))
            $local_name = "VN";
        if (($ip_num >= 1743600640 ) && ($ip_num <= 1743602687 ))
            $local_name = "VN";
        if (($ip_num >= 1743609856 ) && ($ip_num <= 1743610879 ))
            $local_name = "VN";
        if (($ip_num >= 1743622144 ) && ($ip_num <= 1743624191 ))
            $local_name = "VN";
        if (($ip_num >= 1743668224 ) && ($ip_num <= 1743672319 ))
            $local_name = "VN";
        if (($ip_num >= 1743704064 ) && ($ip_num <= 1743706111 ))
            $local_name = "VN";
        if (($ip_num >= 1743724544 ) && ($ip_num <= 1743725567 ))
            $local_name = "VN";
        if (($ip_num >= 1743746048 ) && ($ip_num <= 1743748095 ))
            $local_name = "VN";
        if (($ip_num >= 1743910912 ) && ($ip_num <= 1743911935 ))
            $local_name = "VN";
        if (($ip_num >= 1743926272 ) && ($ip_num <= 1743927295 ))
            $local_name = "VN";
        if (($ip_num >= 1744005120 ) && ($ip_num <= 1744006143 ))
            $local_name = "VN";
        if (($ip_num >= 1744033792 ) && ($ip_num <= 1744034815 ))
            $local_name = "VN";
        if (($ip_num >= 1744078848 ) && ($ip_num <= 1744079871 ))
            $local_name = "VN";
        if (($ip_num >= 1744147456 ) && ($ip_num <= 1744148479 ))
            $local_name = "VN";
        if (($ip_num >= 1744172032 ) && ($ip_num <= 1744175103 ))
            $local_name = "VN";
        if (($ip_num >= 1744201728 ) && ($ip_num <= 1744201983 ))
            $local_name = "VN";
        if (($ip_num >= 1744231424 ) && ($ip_num <= 1744232447 ))
            $local_name = "VN";
        if (($ip_num >= 1744347136 ) && ($ip_num <= 1744349183 ))
            $local_name = "VN";
        if (($ip_num >= 1744376832 ) && ($ip_num <= 1744377855 ))
            $local_name = "VN";
        if (($ip_num >= 1744397312 ) && ($ip_num <= 1744398335 ))
            $local_name = "VN";
        if (($ip_num >= 1744443392 ) && ($ip_num <= 1744444415 ))
            $local_name = "VN";
        if (($ip_num >= 1744568320 ) && ($ip_num <= 1744569343 ))
            $local_name = "VN";
        if (($ip_num >= 1744632832 ) && ($ip_num <= 1744633855 ))
            $local_name = "VN";
        if (($ip_num >= 1744656384 ) && ($ip_num <= 1744657407 ))
            $local_name = "VN";
        if (($ip_num >= 1744702464 ) && ($ip_num <= 1744704511 ))
            $local_name = "VN";
        if (($ip_num >= 1744709632 ) && ($ip_num <= 1744710655 ))
            $local_name = "VN";
        if (($ip_num >= 1744754688 ) && ($ip_num <= 1744755711 ))
            $local_name = "VN";
        if (($ip_num >= 1744786432 ) && ($ip_num <= 1744787455 ))
            $local_name = "VN";
        if (($ip_num >= 1744825344 ) && ($ip_num <= 1744826367 ))
            $local_name = "VN";
        if (($ip_num >= 1754225920 ) && ($ip_num <= 1754226175 ))
            $local_name = "VN";
        if (($ip_num >= 1754226432 ) && ($ip_num <= 1754226687 ))
            $local_name = "VN";
        if (($ip_num >= 1760439296 ) && ($ip_num <= 1760439551 ))
            $local_name = "VN";
        if (($ip_num >= 1847803904 ) && ($ip_num <= 1847807999 ))
            $local_name = "VN";
        if (($ip_num >= 1848424448 ) && ($ip_num <= 1848426495 ))
            $local_name = "VN";
        if (($ip_num >= 1866592256 ) && ($ip_num <= 1866596351 ))
            $local_name = "VN";
        if (($ip_num >= 1868294144 ) && ($ip_num <= 1868295167 ))
            $local_name = "VN";
        if (($ip_num >= 1883783168 ) && ($ip_num <= 1883799551 ))
            $local_name = "VN";
        if (($ip_num >= 1884160000 ) && ($ip_num <= 1884164095 ))
            $local_name = "VN";
        if (($ip_num >= 1886214144 ) && ($ip_num <= 1886216191 ))
            $local_name = "VN";
        if (($ip_num >= 1888059392 ) && ($ip_num <= 1888063487 ))
            $local_name = "VN";
        if (($ip_num >= 1891958784 ) && ($ip_num <= 1892024319 ))
            $local_name = "VN";
        if (($ip_num >= 1893027840 ) && ($ip_num <= 1893031935 ))
            $local_name = "VN";
        if (($ip_num >= 1897160704 ) && ($ip_num <= 1897168895 ))
            $local_name = "VN";
        if (($ip_num >= 1897267200 ) && ($ip_num <= 1897365503 ))
            $local_name = "VN";
        if (($ip_num >= 1899241472 ) && ($ip_num <= 1899249663 ))
            $local_name = "VN";
        if (($ip_num >= 1899850752 ) && ($ip_num <= 1899851775 ))
            $local_name = "VN";
        if (($ip_num >= 1906311168 ) && ($ip_num <= 1908408319 ))
            $local_name = "VN";
        if (($ip_num >= 1934098432 ) && ($ip_num <= 1934622719 ))
            $local_name = "VN";
        if (($ip_num >= 1934929920 ) && ($ip_num <= 1934931967 ))
            $local_name = "VN";
        if (($ip_num >= 1938978816 ) && ($ip_num <= 1938980863 ))
            $local_name = "VN";
        if (($ip_num >= 1940234240 ) && ($ip_num <= 1940236287 ))
            $local_name = "VN";
        if (($ip_num >= 1950646272 ) && ($ip_num <= 1950648319 ))
            $local_name = "VN";
        if (($ip_num >= 1952448512 ) && ($ip_num <= 1953497087 ))
            $local_name = "VN";
        if (($ip_num >= 1953890304 ) && ($ip_num <= 1953923071 ))
            $local_name = "VN";
        if (($ip_num >= 1958821888 ) && ($ip_num <= 1958825983 ))
            $local_name = "VN";
        if (($ip_num >= 1960058880 ) && ($ip_num <= 1960067071 ))
            $local_name = "VN";
        if (($ip_num >= 1962934272 ) && ($ip_num <= 1963458559 ))
            $local_name = "VN";
        if (($ip_num >= 1969733632 ) && ($ip_num <= 1969750015 ))
            $local_name = "VN";
        if (($ip_num >= 1970929664 ) && ($ip_num <= 1970962431 ))
            $local_name = "VN";
        if (($ip_num >= 1984167936 ) && ($ip_num <= 1984430079 ))
            $local_name = "VN";
        if (($ip_num >= 1986202880 ) && ($ip_num <= 1986203135 ))
            $local_name = "VN";
        if (($ip_num >= 1986396160 ) && ($ip_num <= 1986398207 ))
            $local_name = "VN";
        if (($ip_num >= 1986740224 ) && ($ip_num <= 1986756607 ))
            $local_name = "VN";
        if (($ip_num >= 1997512704 ) && ($ip_num <= 1997520895 ))
            $local_name = "VN";
        if (($ip_num >= 1997651968 ) && ($ip_num <= 1997668351 ))
            $local_name = "VN";
        if (($ip_num >= 1997701120 ) && ($ip_num <= 1997705215 ))
            $local_name = "VN";
        if (($ip_num >= 1997715456 ) && ($ip_num <= 1997717503 ))
            $local_name = "VN";
        if (($ip_num >= 2001895424 ) && ($ip_num <= 2001899519 ))
            $local_name = "VN";
        if (($ip_num >= 2016589824 ) && ($ip_num <= 2016591871 ))
            $local_name = "VN";
        if (($ip_num >= 2018004992 ) && ($ip_num <= 2018007039 ))
            $local_name = "VN";
        if (($ip_num >= 2018009088 ) && ($ip_num <= 2018017279 ))
            $local_name = "VN";
        if (($ip_num >= 2022326272 ) && ($ip_num <= 2022330367 ))
            $local_name = "VN";
        if (($ip_num >= 2053533696 ) && ($ip_num <= 2053534719 ))
            $local_name = "VN";
        if (($ip_num >= 2055274496 ) && ($ip_num <= 2055290879 ))
            $local_name = "VN";
        if (($ip_num >= 2059995136 ) && ($ip_num <= 2059997183 ))
            $local_name = "VN";
        if (($ip_num >= 2064646144 ) && ($ip_num <= 2065694719 ))
            $local_name = "VN";
        if (($ip_num >= 2090663936 ) && ($ip_num <= 2090680319 ))
            $local_name = "VN";
        if (($ip_num >= 2090729472 ) && ($ip_num <= 2090733567 ))
            $local_name = "VN";
        if (($ip_num >= 2100953088 ) && ($ip_num <= 2100969471 ))
            $local_name = "VN";
        if (($ip_num >= 2111078400 ) && ($ip_num <= 2111111167 ))
            $local_name = "VN";
        if (($ip_num >= 2111176704 ) && ($ip_num <= 2111193087 ))
            $local_name = "VN";
        if (($ip_num >= 2112487424 ) && ($ip_num <= 2112618495 ))
            $local_name = "VN";
        if (($ip_num >= 2113761280 ) && ($ip_num <= 2113765375 ))
            $local_name = "VN";
        if (($ip_num >= 2258602496 ) && ($ip_num <= 2258602751 ))
            $local_name = "VN";
        if (($ip_num >= 2883584000 ) && ($ip_num <= 2885681151 ))
            $local_name = "VN";
        if (($ip_num >= 2942779392 ) && ($ip_num <= 2942795775 ))
            $local_name = "VN";
        if (($ip_num >= 2942959616 ) && ($ip_num <= 2942960639 ))
            $local_name = "VN";
        if (($ip_num >= 3025993728 ) && ($ip_num <= 3026059263 ))
            $local_name = "VN";
        if (($ip_num >= 3029598208 ) && ($ip_num <= 3029600255 ))
            $local_name = "VN";
        if (($ip_num >= 3029630976 ) && ($ip_num <= 3029635071 ))
            $local_name = "VN";
        if (($ip_num >= 3033984000 ) && ($ip_num <= 3033985023 ))
            $local_name = "VN";
        if (($ip_num >= 3064025088 ) && ($ip_num <= 3064029183 ))
            $local_name = "VN";
        if (($ip_num >= 3064808960 ) && ($ip_num <= 3064809215 ))
            $local_name = "VN";
        if (($ip_num >= 3068948480 ) && ($ip_num <= 3068949503 ))
            $local_name = "VN";
        if (($ip_num >= 3068990464 ) && ($ip_num <= 3068991487 ))
            $local_name = "VN";
        if (($ip_num >= 3075473408 ) && ($ip_num <= 3075571711 ))
            $local_name = "VN";
        if (($ip_num >= 3076169728 ) && ($ip_num <= 3076171775 ))
            $local_name = "VN";
        if (($ip_num >= 3076194304 ) && ($ip_num <= 3076202495 ))
            $local_name = "VN";
        if (($ip_num >= 3076235264 ) && ($ip_num <= 3076243455 ))
            $local_name = "VN";
        if (($ip_num >= 3167774208 ) && ($ip_num <= 3167774463 ))
            $local_name = "VN";
        if (($ip_num >= 3237869824 ) && ($ip_num <= 3237870079 ))
            $local_name = "VN";
        if (($ip_num >= 3261322240 ) && ($ip_num <= 3261323263 ))
            $local_name = "VN";
        if (($ip_num >= 3389017856 ) && ($ip_num <= 3389018111 ))
            $local_name = "VN";
        if (($ip_num >= 3389302784 ) && ($ip_num <= 3389303039 ))
            $local_name = "VN";
        if (($ip_num >= 3389304832 ) && ($ip_num <= 3389305087 ))
            $local_name = "VN";
        if (($ip_num >= 3389391360 ) && ($ip_num <= 3389391615 ))
            $local_name = "VN";
        if (($ip_num >= 3389415424 ) && ($ip_num <= 3389415935 ))
            $local_name = "VN";
        if (($ip_num >= 3389607680 ) && ($ip_num <= 3389608191 ))
            $local_name = "VN";
        if (($ip_num >= 3389608960 ) && ($ip_num <= 3389609215 ))
            $local_name = "VN";
        if (($ip_num >= 3391444480 ) && ($ip_num <= 3391444991 ))
            $local_name = "VN";
        if (($ip_num >= 3391843328 ) && ($ip_num <= 3391844351 ))
            $local_name = "VN";
        if (($ip_num >= 3391916288 ) && ($ip_num <= 3391916543 ))
            $local_name = "VN";
        if (($ip_num >= 3392100096 ) && ($ip_num <= 3392100351 ))
            $local_name = "VN";
        if (($ip_num >= 3392114176 ) && ($ip_num <= 3392114431 ))
            $local_name = "VN";
        if (($ip_num >= 3392415488 ) && ($ip_num <= 3392415743 ))
            $local_name = "VN";
        if (($ip_num >= 3392635904 ) && ($ip_num <= 3392636927 ))
            $local_name = "VN";
        if (($ip_num >= 3392682240 ) && ($ip_num <= 3392682495 ))
            $local_name = "VN";
        if (($ip_num >= 3392861440 ) && ($ip_num <= 3392861695 ))
            $local_name = "VN";
        if (($ip_num >= 3392925184 ) && ($ip_num <= 3392925695 ))
            $local_name = "VN";
        if (($ip_num >= 3392928768 ) && ($ip_num <= 3392929279 ))
            $local_name = "VN";
        if (($ip_num >= 3392956416 ) && ($ip_num <= 3392958463 ))
            $local_name = "VN";
        if (($ip_num >= 3393861632 ) && ($ip_num <= 3393861887 ))
            $local_name = "VN";
        if (($ip_num >= 3393862144 ) && ($ip_num <= 3393862655 ))
            $local_name = "VN";
        if (($ip_num >= 3394166784 ) && ($ip_num <= 3394168831 ))
            $local_name = "VN";
        if (($ip_num >= 3394234368 ) && ($ip_num <= 3394236415 ))
            $local_name = "VN";
        if (($ip_num >= 3394753536 ) && ($ip_num <= 3394754559 ))
            $local_name = "VN";
        if (($ip_num >= 3395027968 ) && ($ip_num <= 3395028991 ))
            $local_name = "VN";
        if (($ip_num >= 3395132416 ) && ($ip_num <= 3395133439 ))
            $local_name = "VN";
        if (($ip_num >= 3395179008 ) && ($ip_num <= 3395179263 ))
            $local_name = "VN";
        if (($ip_num >= 3395180544 ) && ($ip_num <= 3395181055 ))
            $local_name = "VN";
        if (($ip_num >= 3397176320 ) && ($ip_num <= 3397176575 ))
            $local_name = "VN";
        if (($ip_num >= 3397526528 ) && ($ip_num <= 3397527039 ))
            $local_name = "VN";
        if (($ip_num >= 3397783552 ) && ($ip_num <= 3397785599 ))
            $local_name = "VN";
        if (($ip_num >= 3397793280 ) && ($ip_num <= 3397793535 ))
            $local_name = "VN";
        if (($ip_num >= 3398934528 ) && ($ip_num <= 3398938623 ))
            $local_name = "VN";
        if (($ip_num >= 3399414784 ) && ($ip_num <= 3399415807 ))
            $local_name = "VN";
        if (($ip_num >= 3399515136 ) && ($ip_num <= 3399515647 ))
            $local_name = "VN";
        if (($ip_num >= 3400270848 ) && ($ip_num <= 3400271359 ))
            $local_name = "VN";
        if (($ip_num >= 3401529344 ) && ($ip_num <= 3401530367 ))
            $local_name = "VN";
        if (($ip_num >= 3406331648 ) && ($ip_num <= 3406331903 ))
            $local_name = "VN";
        if (($ip_num >= 3406343168 ) && ($ip_num <= 3406343423 ))
            $local_name = "VN";
        if (($ip_num >= 3408039936 ) && ($ip_num <= 3408040191 ))
            $local_name = "VN";
        if (($ip_num >= 3410866688 ) && ($ip_num <= 3410866943 ))
            $local_name = "VN";
        if (($ip_num >= 3410959360 ) && ($ip_num <= 3410959615 ))
            $local_name = "VN";
        if (($ip_num >= 3411643392 ) && ($ip_num <= 3411644415 ))
            $local_name = "VN";
        if (($ip_num >= 3412326400 ) && ($ip_num <= 3412327423 ))
            $local_name = "VN";
        if (($ip_num >= 3413213184 ) && ($ip_num <= 3413229567 ))
            $local_name = "VN";
        if (($ip_num >= 3413575680 ) && ($ip_num <= 3413576703 ))
            $local_name = "VN";
        if (($ip_num >= 3413582848 ) && ($ip_num <= 3413583871 ))
            $local_name = "VN";
        if (($ip_num >= 3413584896 ) && ($ip_num <= 3413585919 ))
            $local_name = "VN";
        if (($ip_num >= 3413588480 ) && ($ip_num <= 3413593087 ))
            $local_name = "VN";
        if (($ip_num >= 3414224896 ) && ($ip_num <= 3414226943 ))
            $local_name = "VN";
        if (($ip_num >= 3416260864 ) && ($ip_num <= 3416261119 ))
            $local_name = "VN";
        if (($ip_num >= 3416285184 ) && ($ip_num <= 3416287231 ))
            $local_name = "VN";
        if (($ip_num >= 3416371712 ) && ($ip_num <= 3416371967 ))
            $local_name = "VN";
        if (($ip_num >= 3416391680 ) && ($ip_num <= 3416457215 ))
            $local_name = "VN";
        if (($ip_num >= 3416489984 ) && ($ip_num <= 3416506367 ))
            $local_name = "VN";
        if (($ip_num >= 3416922624 ) && ($ip_num <= 3416923135 ))
            $local_name = "VN";
        if (($ip_num >= 3416985600 ) && ($ip_num <= 3416989695 ))
            $local_name = "VN";
        if (($ip_num >= 3417350144 ) && ($ip_num <= 3417352191 ))
            $local_name = "VN";
        if (($ip_num >= 3418168320 ) && ($ip_num <= 3418169343 ))
            $local_name = "VN";
        if (($ip_num >= 3418267648 ) && ($ip_num <= 3418271743 ))
            $local_name = "VN";
        if (($ip_num >= 3418294272 ) && ($ip_num <= 3418296319 ))
            $local_name = "VN";
        if (($ip_num >= 3418304512 ) && ($ip_num <= 3418306559 ))
            $local_name = "VN";
        if (($ip_num >= 3418554368 ) && ($ip_num <= 3418570751 ))
            $local_name = "VN";
        if (($ip_num >= 3418961920 ) && ($ip_num <= 3418962943 ))
            $local_name = "VN";
        if (($ip_num >= 3419209728 ) && ($ip_num <= 3419226111 ))
            $local_name = "VN";
        if (($ip_num >= 3419517952 ) && ($ip_num <= 3419518975 ))
            $local_name = "VN";
        if (($ip_num >= 3419570176 ) && ($ip_num <= 3419602943 ))
            $local_name = "VN";
        if (($ip_num >= 3523362816 ) && ($ip_num <= 3523379199 ))
            $local_name = "VN";
        if (($ip_num >= 3528908800 ) && ($ip_num <= 3528912895 ))
            $local_name = "VN";
        if (($ip_num >= 3537068032 ) && ($ip_num <= 3537076223 ))
            $local_name = "VN";
        if (($ip_num >= 3539271680 ) && ($ip_num <= 3539304447 ))
            $local_name = "VN";
        if (($ip_num >= 3663989248 ) && ($ip_num <= 3663989503 ))
            $local_name = "VN";
        if (($ip_num >= 3663990272 ) && ($ip_num <= 3663990527 ))
            $local_name = "VN";
        if (($ip_num >= 3664002048 ) && ($ip_num <= 3664002303 ))
            $local_name = "VN";
        if (($ip_num >= 3706142720 ) && ($ip_num <= 3706159103 ))
            $local_name = "VN";
        if (($ip_num >= 3715694592 ) && ($ip_num <= 3715710975 ))
            $local_name = "VN";
        if (($ip_num >= 3716415488 ) && ($ip_num <= 3716431871 ))
            $local_name = "VN";
        if (($ip_num >= 3716481024 ) && ($ip_num <= 3716489215 ))
            $local_name = "VN";
        if (($ip_num >= 3741057024 ) && ($ip_num <= 3741271039 ))
            $local_name = "VN";
        if (($ip_num >= 3741271296 ) && ($ip_num <= 3741319167 ))
            $local_name = "VN";
        if (($ip_num >= 3743115264 ) && ($ip_num <= 3743117311 ))
            $local_name = "VN";

        //Trả kết quả 
        return $local_name;
    }

}
