<?php
namespace app\models;

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
class Element extends ActiveRecord {


    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return 'tbl_element';
    }

	// Lấy danh sách các phần tử data-tag trong html dựa vào id
	static function getListElement($file_path) {
		$html = new SimpleHtmlDom();
		$html->load_file($file_path); 
		$rets = $html->findDom('*[id]'); 
		$arr = array();
		$i = 0;
		foreach ($rets as $ret) {
			$str = trim($ret->id);
			
			if(!(strpos($str, "mns_") === FALSE && strpos($str, "up_") === FALSE)) {
				$arr[$i]['id'] = $ret->id;			// id of Element
				$arr[$i]['type'] = $ret->tag;		// type of Element
				if($ret->tag == "img") {
					$arr[$i]['data'] = $ret->src;
					$arr[$i]['note'] = strval($ret->width) . ", " . strval($ret->height);
				} else {
					$arr[$i]['data'] = $ret->plaintext;
					$arr[$i]['note'] = "";
				}
					
				$i++;
			}
		}
		return $arr;
	}
	
	static function resizeImg($sizeStr, $source_file) {
		if(strlen(trim($sizeStr)) <= 1) return;
		$resizeArr = explode(",", $sizeStr);
		$max_width = $resizeArr[0];
		Element::resize_crop_image($resizeArr[0], $resizeArr[1], $source_file, $source_file);
	}
	 
	static function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
		$imgsize = getimagesize($source_file);
		$width = $imgsize[0];
		$height = $imgsize[1];
		$mime = $imgsize['mime'];
	 
		switch($mime){
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
				$quality = 80;
				break;
	 
			default:
				return false;
				break;
		}
		 
		$dst_img = imagecreatetruecolor($max_width, $max_height);
		imagealphablending($dst_img, false);
		
imagesavealpha($dst_img, true);
		$src_img = $image_create($source_file);
		 
		$width_new = $height * $max_width / $max_height;
		$height_new = $width * $max_height / $max_width;
		//if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
		if($width_new > $width){
			//cut point by height
			$h_point = (($height - $height_new) / 2);
			//copy image
			imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
		}else{
			//cut point by width
			$w_point = (($width - $width_new) / 2);
			imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
		}
		 
		$image($dst_img, $dst_dir, $quality);
	 
		if($dst_img)imagedestroy($dst_img);
		if($src_img)imagedestroy($src_img);
		
	}
}
