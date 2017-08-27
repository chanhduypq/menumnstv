<?php
namespace app\models;

use yii\db\ActiveRecord;
use Yii;

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
class Service extends ActiveRecord {
    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return 'tbl_service';
    }
    /**
     * check services and see whether a service has been added to the cart or was purchased
     * @param array $services
     * @param array $order_details, contains the services and templates were purchased
     * @param int|string $outid, template id
     * @param array $service_ids, container services have been added to the cart
     * @return void
     */
    public static function setServices(&$services,$order_details,$outid,$service_ids){
        foreach ($services as $key=>$service){
            //kiểm tra dịch vụ này ứng với template hiện tại đã được mua hay chưa
            $lock=FALSE;
            if(array_search($outid, array_keys($order_details))!==FALSE){//nếu trong tbl_order_detail đã có 1 out_id là template này
                if(in_array($service['service_id'], $order_details[$outid])){//nếu trong tbl_order_detail trong có service_id trùng với dịch vụ này ứng với template này
                    $lock=true;
                }
            }

            if($lock==true){
                $services[$key]['disabled']=' disabled="disabled"';
            }
            else{
                $services[$key]['disabled']='';
            }
            /**
             * nếu dịch vụ này mới được thêm vào giỏ hàng hoặc đã từng được mua thi check vào checkbox của dịch vụ đó
             */
            if(
                in_array($service['service_id'],$service_ids)//dịch vụ này mới được add vào giỏ hàng
                ||(array_search($outid, array_keys($order_details))!==FALSE&&in_array($service['service_id'], $order_details[$outid]))//dịch vụ này đã được mua
             ){
                $services[$key]['checked']=' checked="checked"';
            }
            else{
                $services[$key]['checked']='';
            }
            
            $services[$key]['lock']=$lock;
        }
    }
}
?>