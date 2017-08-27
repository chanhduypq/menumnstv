<?php
namespace app\models;

use Yii;
use yii\base\Security;
use yii\base\Model;
use app\models\Option;
use app\models\ElementDetailShow;

class ServicesProcess  extends Model  {

    public function __construct() {
        
    }
	
	//Goi đến service,
	// URL của service được lưu trong table tbl_Option
	public function callServices($post_data, $service_name) {
		$urlService = $GLOBALS['options']["$service_name"];
		//echo $urlService; exit;
		$ch = curl_init($urlService);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

		// execute!
		$response = curl_exec($ch);
		
		return $response;
	}
	
	//Tạo dữ liệu cho hệ thống bằng cách gọi service buyTemplate
	public function buyTemplate($template_id, $template_name, $group_id, $user_id = 0) {
		//Trường hợp không có $user_id, lấy $user_id đầu tiên của group_id
		if($user_id == 0)
			$user_id = Yii::$app->db->createCommand("SELECT mns_user_id FROM tbl_user where group_id = " . $group_id )->queryScalar();
		
		$security_hash = $GLOBALS['options']["lm01_remote"];

		//Gọi buyTemplateService, thêm phần dữ liệu mua bán vào data của LM
		$post_data = [
			'template_id' => $template_id,
			'group_id' => $group_id,
			'user_id'   => $user_id,
			'security_hash' => $security_hash,
		];
		
		$result = $this->callServices($post_data, "buyTemplateService"); 
		
		
		//Gọi addLayoutToSeemnsTV, thêm layout vào Group của member đã mua layout đó
		if($result == 0) {
			$template_url = Yii::$app->db->createCommand("SELECT template_folder FROM tbl_element_detail_show where template_id = " . $template_id )->queryScalar();
			$group_key = Yii::$app->db->createCommand("SELECT group_key FROM tbl_group where group_id = " . $group_id )->queryScalar();
			$template_url = $GLOBALS['options']["client_template_url"] . "/" . $group_key . "/" . $template_url;
			$post_data = [
				'layout_name' => $template_name,
				'seemns_user_id' => $user_id,
				'layout_url'   => $template_url,
				'key' => $security_hash,
			];			
			$result = $this->callServices($post_data, "addLayoutToSeemnsTV");
                        
		}
		
		//Đổ dữ liệu mẩu đã có từ trước vào file json
		if($result == 0) {
			$result = $this->jsonUpdate($group_id, $template_id, 0);
		}
		
		return $result;
	}
	
	//Cập nhật nội dung file json
	public function jsonUpdate($path,$template_url, $template_show_id) {
		//Lấy dường dẫn thư template
		$template_path = $path . "/js/data_temp.json";		//Lấy file json mẫu (vật lý)
		$template_json_path = $path . "/js/data.json";		// file json sẽ đổ dữ liệu vào (vật lý)



		//Cập nhật dữ liệu trong file data.json		
		$result = ElementDetailShow::findAll(['template_show_id'=>$template_show_id]);
		$json_file_content = file_get_contents ($template_path);
		if($result != null) {
				foreach($result as $row) {
					$scr_data = "{" . $row->tag_name . "}";
					$tar_data = $row->tag_data;
					$json_file_content = str_replace($scr_data, $tar_data, $json_file_content);
				}
				
				file_put_contents($template_json_path, $json_file_content);
		} else { //Trường hợp không có dữ liệu
			return  false;
		}

		return true;
	}
	
	
	//Change password cho user
	public function changePassword($user_id, $password) {
		$security_hash = $GLOBALS['options']["lm01_remote"];
		$post_data = [
			'user_id' => $user_id,
			'password' => $password,
			'key' => $security_hash,
		];
		
		return $this->callServices($post_data, "changePassword");
	}
    
	//Get value from a table off ds.mnt.tv
	public function getValue($table, $field_name, $where) {
		$security_hash = $GLOBALS['options']["lm01_remote"];
		$post_data = [
			'table' => $table,
			'field_name' => $field_name,
			'where' => $where,
			'key' => $security_hash,
		];
		
		return $this->callServices($post_data, "getValue");
	}
    
	
}

