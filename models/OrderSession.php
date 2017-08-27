<?php
namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use app\models\Option;
use app\models\LayoutService;

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
class OrderSession extends ActiveRecord {
    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return 'tbl_order_session';
    }
    /**
     * 
     * lấy order_sessions để hiển thị tại page confirmcart
     * @return array
     */
    public static function getOrderSessions(){
        if(isset(Yii::$app->session['user_id'])&&strlen(Yii::$app->session['user_id'])>0){
            $or_where=" OR tbl_order_session.user_id=".Yii::$app->session['user_id'];            
        }
        else{
            $or_where="";
        }
        
        $order_sessions = Yii::$app->db->createCommand(
                        "SELECT DISTINCT "
                            . "tbl_template_detail.title,"
                            . "tbl_template.template_id,"
                            . "tbl_template_detail.num,
                                tbl_template_detail.num2,"
                            . "tbl_service.price,"
                            . "tbl_template.path,"
                            . "tbl_template.resolution,"
                            . "tbl_template.thumb,"
                            . "tbl_template.video,"
                            . "tbl_template_detail.content,"
                            . "tbl_order_session.main_service,"
                            . "tbl_order_session.service_id,"
                            . "tbl_order_session.user_id,"
                            . "tbl_order_session.order_session_id,"
                            . "tbl_order_session.session_id "
                        . "FROM tbl_template "
                        . "JOIN tbl_order_session "
                                    . "ON tbl_template.template_id=tbl_order_session.out_id "
                        . "LEFT JOIN  tbl_template_detail 
                                    ON tbl_template.template_id = tbl_template_detail.template_id 
                                    AND tbl_template_detail.language_id = '" . Yii::$app->session['language_id'] . "' "
                                    . "AND tbl_template_detail.type=1 "
                        . "JOIN tbl_service "
                        . "ON tbl_service.service_id=tbl_order_session.service_id "
                        . "AND tbl_service.language_id = '" . Yii::$app->session['language_id'] . "' "
                        . "WHERE tbl_order_session.session_id=" . Yii::$app->session['session_id'].$or_where
                        . " ORDER BY tbl_order_session.out_id,tbl_order_session.main_service ASC"
                )->queryAll();
        
        
        return $order_sessions;
    }
    /**
     * 
     * lấy order_sessions để hiển thị tại popup cart
     * @return array
     */
    public static function getOrderSessionsForPopupCart(){
                
        $order_sessions = Yii::$app->db->createCommand(
                        "SELECT DISTINCT "
                            . "tbl_template_detail.title,"
                            . "tbl_template.template_id,"
                            . "tbl_template_detail.num,
                                tbl_template_detail.num2,"
                            . "tbl_service.price,"
                            . "tbl_template.path,"
                            . "tbl_template.resolution,"
                            . "tbl_template.thumb,"
                            . "tbl_template.video,"
                            . "tbl_template_detail.content,"
                            . "tbl_order_session.service_id,"
                            . "tbl_order_session.user_id,"
                            . "tbl_order_session.order_session_id,"
                            . "tbl_order_session.main_service,"
                            . "tbl_order_session.session_id,"
                            . "tbl_order_detail.order_detail_id "
                        . "FROM tbl_template "
                        . "JOIN tbl_order_session "
                                    . "ON tbl_template.template_id=tbl_order_session.out_id "
                        . "LEFT JOIN  tbl_template_detail 
                                    ON tbl_template.template_id = tbl_template_detail.template_id 
                                    AND tbl_template_detail.language_id = '" . Yii::$app->session['language_id'] . "' "
                                    . "AND tbl_template_detail.type=1 "
                        . "LEFT JOIN tbl_order_detail "
                                . "ON tbl_order_detail.user_id=tbl_order_session.user_id "
                                . "AND tbl_order_detail.service_id=tbl_order_session.service_id "
                                . "AND tbl_order_detail.out_id=tbl_order_session.out_id "
                        . "JOIN tbl_service "
                            . "ON "
                                . "tbl_service.service_id=tbl_order_session.service_id "
                                . "AND tbl_service.language_id ='" . Yii::$app->session['language_id'] . "' "
                        . "WHERE tbl_order_session.session_id=" . Yii::$app->session['session_id']
                        . " ORDER BY tbl_order_session.out_id,tbl_order_session.main_service ASC"
                )->queryAll();    
        
        return $order_sessions;
    }
    /**
     * user sau khi add nhiều item vào giỏ hàng
     * thi sẽ có data trong tbl_order_session
     * lấy data này và build thành array để hiển thị thông tin giỏ hàng tại trang confirmcart
     */
    public static function buildCartForShow($order_sessions,$order_details,&$sum_all){
        $option=new Option();
        $upload_template_url = $option->getOptionValue('upload_template_url');
        $sum_all=0;
        /**
         * lấy danh sách template, rồi đưa vào biến $carts
         */
        $carts=array();
        for($i=0,$n=count($order_sessions);$i<$n;$i++){                                
            $thumb = $upload_template_url . "/" . $order_sessions[$i]['path'] . "/assets/image/" . $order_sessions[$i]['thumb'];
            if($order_sessions[$i]['resolution']=='1'){
                $thumb_confirm_class="thumb-confirm-l";
            }
            else{
                $thumb_confirm_class="thumb-confirm-p";
            }
            $video = $upload_template_url . "/" . $order_sessions[$i]['path'] . "/video/" . $order_sessions[$i]['video']."_s.mp4";
            $carts[$order_sessions[$i]['template_id']]=array(
                                            'price_template'=>$order_sessions[$i]['num'],
                                            'num'=>$order_sessions[$i]['num'],
                                            'num2'=>$order_sessions[$i]['num2'],
                                            'thumb'=>$thumb,
                                            'video'=>$video,
                                            'thumb_confirm_class'=>$thumb_confirm_class,
                                            'title'=>$order_sessions[$i]['title'],
                                            'resolution'=>$order_sessions[$i]['resolution'],
                                            'content'=>$order_sessions[$i]['content'],
                                                );
            //lấy tổng cộng giá tiền của tất cả các template và dịch vụ                                 
            if($order_sessions[$i]['session_id']==Yii::$app->session['session_id']){
                if($order_sessions[$i]['main_service']=='1'){
                    $new_price=$order_sessions[$i]['num']-$order_sessions[$i]['num2'];
                    if($new_price<0){
                        $new_price=0;
                    }
                    $sum_all+=$new_price;//$order_sessions[$i]['num'];
                }
                else{
                    $sum_all+= LayoutService::getRealPrice($order_sessions[$i]['price']);

                }                                
            }

        }
        /**
         * trong mỗi element của array $carts 
         * thêm array con service_ids và 1 value (price) cho mỗi element
         * service_ids chứa danh sách các service_id trong giỏ hàng đang được thêm vào 
         * price là tổng giá của 1 template đó và các dịch vụ của template đó nếu nó mới vừa được add vào tbl_order_session
         * nếu price này =0, tức là chưa có một thao tác add nào vào giỏ hàng cho template này hoặc các dịch vụ của template này, thi element template này phai được remove ra khỏi array $carts
         */
        foreach ($carts as $key=>$value){
            $temp=array();
            $sum=0;
            $has_service_price_equal_0=false;
            for($i=0,$n=count($order_sessions);$i<$n;$i++){
                if($key==$order_sessions[$i]['template_id']){
                    
                    if($order_sessions[$i]['session_id']==Yii::$app->session['session_id']){
                        $temp[]=$order_sessions[$i]['service_id'];
                    }
                    //lấy tổng giá của 1 template và các dịch vụ của template tương ứng nếu nó mới vừa được add vào tbl_order_session                                        
                    if($order_sessions[$i]['session_id']==Yii::$app->session['session_id']){
                        if($order_sessions[$i]['main_service']=='1'){
                            $new_price=$value['num']-$value['num2'];
                            
                            if($new_price<0){
                                $new_price=0;
                            }
                            $sum+=$new_price;
                        }
                        else{      
                            $sum+= LayoutService::getRealPrice($order_sessions[$i]['price']);
                            if($order_sessions[$i]['price']<0){
                                $has_service_price_equal_0=true;
                            }
                        }

                    }
                    //nếu đã tồn tại trong tbl_order_detail có nghĩa là template này đã được mua rồi
                    if(array_search($key, array_keys($order_details))!==FALSE){
                        $carts["$key"]['lock']=true;
                    }
                    else{
                        $carts["$key"]['lock']=FALSE;
                    }
                }
            }
            $carts["$key"]['service_ids']=$temp;
            $carts["$key"]['price']=$sum;
            /**
             * nếu $sum =0, tức là chưa có một thao tác add nào vào giỏ hàng cho template này hoặc các dịch vụ của template này, 
             * và
             *   hoặc chưa login thi Yii::$app->session['out_ids'] chưa được khởi tạo
             *   hoặc đã login
             *        mà trước khi login chưa add template nào vào giỏ hàng thi count(Yii::$app->session['out_ids'])=0--->in_array($key, Yii::$app->session['out_ids'])=false
             *        (hoặc) mà trước khi login, đã chọn >1 template vào giỏ hàng, trong số template này đều mua chưa đủ các dịch vụ con, nhưng template hiện tại ($key) k nằm trong array Yii::$app->session['out_ids']--->in_array($key, Yii::$app->session['out_ids'])=false
             * thi element template này phai được remove ra khỏi array $carts
             */
            if($sum==0&&$has_service_price_equal_0==FALSE&&(!isset(Yii::$app->session['out_ids'])||in_array($key, Yii::$app->session['out_ids'])==false)&&$value['num']-$value['num2']>0){
                unset($carts["$key"]);
            }
            
        }
        return $carts;
    }
    /**
     * user sau khi add nhiều item vào giỏ hàng
     * thi sẽ có data trong tbl_order_session
     * lấy data này và build thành array để hiển thị thông tin giỏ hàng tại popup cart
     */
    public static function buildCartForShowPopup($order_sessions,$order_out_ids,&$sum_all){
        $option=new Option();
        $upload_template_url = $option->getOptionValue('upload_template_url');
        
        $sum_all=0;
        $carts=array();
        for($i=0,$n=count($order_sessions);$i<$n;$i++){                                
            $thumb = $upload_template_url . "/" . $order_sessions[$i]['path'] . "/assets/image/" . $order_sessions[$i]['thumb'];
            $carts[$order_sessions[$i]['template_id']]=array(
                                            'thumb'=>$thumb,
                                            'title'=>$order_sessions[$i]['title'],
                                            'resolution'=>$order_sessions[$i]['resolution'],
                                            'price_template'=>$order_sessions[$i]['num'],
                                            'num'=>$order_sessions[$i]['num'],
                                            'num2'=>$order_sessions[$i]['num2'],
                                                );
            if($order_sessions[$i]['session_id']==Yii::$app->session['session_id']&&$order_sessions[$i]['order_detail_id']==""){  
                if($order_sessions[$i]['main_service']=='1'){
                    $new_price=$order_sessions[$i]['num']-$order_sessions[$i]['num2'];
                    if($new_price<0){
                        $new_price=0;
                    }
                    $sum_all+=$new_price;
                }
                else{
                    $sum_all+= LayoutService::getRealPrice($order_sessions[$i]['price']);                    
                }
            }

        }
        foreach ($carts as $key=>$value){
            $sum=0;
            $has_service_price_equal_0=false;
            for($i=0,$n=count($order_sessions);$i<$n;$i++){
                if($key==$order_sessions[$i]['template_id']){
                    if($order_sessions[$i]['session_id']==Yii::$app->session['session_id']&&$order_sessions[$i]['order_detail_id']==""){
                        if($order_sessions[$i]['main_service']=='1'){
                            $new_price=$value['num']-$value['num2'];
                            
                            if($new_price<0){
                                $new_price=0;
                            }
                            $sum+=$new_price;
                        }
                        else{  
                            $sum+= LayoutService::getRealPrice($order_sessions[$i]['price']);
                            if($order_sessions[$i]['price']<0){
                                $has_service_price_equal_0=true;
                            }
                        }
                    }                                        
                }
            }
            $carts["$key"]['price']=$sum;
            
            if(in_array($key, $order_out_ids)){
                $carts["$key"]['lock']=true;
            }
            else{
                $carts["$key"]['lock']=FALSE;
            }
            
            if($sum==0&&$has_service_price_equal_0==FALSE&&$value['num']-$value['num2']>0){
                unset($carts["$key"]);
            }
            
        }
        return $carts;
    }
}
?>