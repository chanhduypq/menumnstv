<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\data\SqlDataProvider;
use yii\data\Pagination;
use app\models\Template;
use Yii;
use app\models\MultiLang;  //Gói đa ngôn ngữ
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
class OrderDetail extends ActiveRecord {

    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return 'tbl_order_detail';
    }

    /**
     * lấy lịch sử giao dịch
     */
    public static function getTransactionHistory($user_id, &$sum_all, $page) {

        $order = " ORDER BY ".Yii::$app->session['transaction_history_sort_field_name']." ".Yii::$app->session['transaction_history_sort_desc_asc'];
        
        $totalCount = Yii::$app->db->createCommand('SELECT COUNT(*) FROM tbl_order WHERE user_id=' . $user_id)->queryScalar();
        $dataProvider = new SqlDataProvider([
            'sql' => "SELECT order_id,DATE_FORMAT(order_time,'%Y.%m.%d') AS order_time,order_status,total_price,order_type
						FROM 
							tbl_order 
						WHERE 
							tbl_order.user_id = :user_id
						$order",
            'params' => [
                ':user_id' => $user_id,
            ],
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => 5,
                'page' => $page,
            ],
        ]);

        //lấy tất cả các order_id đưa vào array $order_ids
        $order_ids = array();
        foreach ($dataProvider->models as $model) {
            $order_ids[] = $model['order_id'];
        }
        //build WHERE
        if (count($order_ids) > 0) {
            $where_order_id = " WHERE tbl_order_detail.order_id IN (" . implode(",", $order_ids) . ")";
        } else {
            $where_order_id = "";
        }

        //lấy tất cả tbl_order_detail của những order_id trong array $order_ids
        $sql = "SELECT "
                    . "tbl_order_detail.order_id,"
                    . "tbl_order_detail.main_service,"
                    . "tbl_order_detail.out_id,"
                    . "tbl_service.service_name,"
                    . "tbl_template_detail.num,"
                    . "tbl_service.price,"
                    . "tbl_template_detail.slug,"
                    . "tbl_template_detail.title "
                . "FROM tbl_order_detail "
                . "LEFT JOIN tbl_template_detail "
                    . "ON "
                        . "tbl_template_detail.template_id=tbl_order_detail.out_id "
                        . "AND tbl_template_detail.type=1 "
                        . "AND tbl_template_detail.language_id ='" . Yii::$app->session['language_id'] . "' "
                . "JOIN tbl_service "
                    . "ON "
                        . "tbl_service.service_id=tbl_order_detail.service_id "
                        . "AND tbl_service.language_id ='" . Yii::$app->session['language_id'] . "' "
                . "$where_order_id";
        $rows = Yii::$app->db->createCommand($sql)->queryAll();

        $models = $dataProvider->models;
        //lấy tổng số tiền của tất cả các đơn hàng đưa vào $sum_all (hiển thị dưới cùng ở web)
        $sum_all = 0;
        //mỗi model của $dataProvider, thêm key=>value ('name'=>'thông tin sản phẩm')
        for ($i = 0; $i < count($models); $i++) {
            $name = "";
            $sum=0;
            foreach ($rows as $row) {
                /**
                 * nếu chi tiết đơn hàng có order_id chính là order_id của model hiện tại ($model[$i]
                 * thi thêm vào một nội dung html kiểu như thế này
                 *        <div class="history-service"><p><span>+ </span>DS11 - bò tái chanh</p><p>12.312<span> k</span></p></div>
                 */
                if ($row['order_id'] == $models[$i]['order_id']) {
                    //nếu là main_service thi cho font chữ màu xanh (thêm class font_blue)
                    if ($row['main_service'] == '1') {
                        $class_font_blue = " class='font_blue'";
                    } else {
                        $class_font_blue = "";
                    }
                    //
                    $name .= '<div class="history-service"><p' . $class_font_blue . '>'.($row['main_service'] == '1'?'<a href="/detail/'.$row['slug'].'">':'').'<span>+ </span>';
                    if ($row['main_service'] == '1') {//nếu là main_service
                        $name .= $row['title'];
                    } else {//nếu k phải main_service
                        $name .= $row['service_name']." (".$row['title'].")";
                    }
                    $name .= ($row['main_service'] == '1'?'</a>':'')."</p><p>";
                    if ($row['main_service'] == '1') {
                        $name .= LayoutService::showMoney($row['num'],'<span> ').'</span></p>';
                        $sum+=$row['num'];
                    }
                    else{
                        $name .= LayoutService::showServiceMoney($row['price'],'<span> ').'</span></p>';
                        $sum+= LayoutService::getRealPrice($row['price']);
                        
                        
                    }
                    
                    $name .= "</div>";
                }
            }
            //thêm key=>value ('name'=>'thông tin sản phẩm')
            $models[$i]['name'] = $name;
            //
            $models[$i]['total_price']=$sum;
            //lấy tổng giá của mỗi đơn hàng cộng dồn vào tổng số tiền của tất cả các đơn hàng (hiển thị dưới cùng ở web)
            $sum_all += $models[$i]['total_price'];
        }
        //lấy $models vừa build (thêm key1=>value1, key2=>value2) ở trên nhúng trở lại $dataProvider
        $dataProvider->setModels($models);

        return $dataProvider;
    }

    /**
     * lấy danh sách template đã mua
     */
    public static function getListBoughtTemplate($user_id,$params) {     
        
        $where = "tbl_order_detail.user_id = $user_id";
        $order = ' ';
        foreach ($params as $key => $value) {
            if ($key == 'catalogue_id') {
                if ($value != '-1') {//không phải là show all
                    $where .= " AND "
                            . "("
                            . "tbl_template.catalogue_01_id=$value "
                            . "OR tbl_template.catalogue_02_id=$value "
                            . "OR tbl_template.catalogue_03_id=$value"
                            . ")";
                }
            } else if ($key == 'resolution') {
                $where .= " AND tbl_template.resolution=$value";
            } else if ($key == 'filter') {
                if ($value == 'light') {
                    $where .= " AND tbl_template.is_light=1";
                } else if ($value == 'dark') {
                    $where .= " AND tbl_template.is_dark=1";
                } else if ($value == 'new_post') {
                    $order = ' ORDER BY tbl_template.update_time DESC';
                } else if ($value == 'best_sellers') {
//                                $order=' ORDER BY tbl_template.update_time DESC';
                    $order = ' ';
                } else if ($value == 'most_view') {
                    $order = ' ORDER BY tbl_template.view_count DESC';
                } else if ($value == 'like') {
//                                $order=' ORDER BY tbl_template.update_time DESC';
                    $order = ' ';
                } else if ($value == 'download') {
                    $order = ' ORDER BY tbl_template.bought_count DESC';
                }
            }
        }

        if (trim($order) == "") {
            $order = ' ORDER BY tbl_template.update_time DESC';
        }
        
        $key = $params['key'];        

        $totalCount = Yii::$app->db->createCommand("SELECT COUNT(DISTINCT tbl_order_detail.out_id) FROM tbl_order_detail JOIN tbl_order ON tbl_order.order_id=tbl_order_detail.order_id AND tbl_order_detail.main_service=1 WHERE tbl_order.user_id='$user_id'")->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => "SELECT DISTINCT 
							tbl_order_detail.order_detail_id,
							tbl_order_detail.order_id,
							tbl_order_detail.user_id, 
							tbl_order_detail.out_id,
							tbl_order_detail.out_name,
							tbl_order_detail.service_id,
							tbl_order_detail.service_name,
							tbl_order_detail.main_service,
                                                        tbl_template_detail.num,
                                                        tbl_template_detail.num2,
                                                        tbl_order.order_status,
							tbl_template.thumb,
                                                        tbl_template.template_id,
							tbl_template.video,
							tbl_template.path,
							tbl_template.resolution,
                                                        tbl_template.template_name,
                                                        tbl_template.view_count,
                                                        tbl_template.cmt_count,
                                                        tbl_template.ranking,
                                                        tbl_template.bought_count,
							tbl_template_detail.title,
							tbl_template_detail.content,
                                                        tbl_template_detail.slug  
						FROM 
							tbl_order_detail 
                                                                                        JOIN 
                                                                                                        tbl_order ON tbl_order.order_id=tbl_order_detail.order_id AND tbl_order_detail.main_service=1
											JOIN 
													tbl_template ON
														tbl_template.template_id=tbl_order_detail.out_id                                                                                                                 
											JOIN 
													tbl_template_detail ON
														tbl_template_detail.template_id=tbl_template.template_id AND 
                                                                                                                tbl_template_detail.type=1 AND 
														tbl_template_detail.language_id ='" . Yii::$app->session['language_id'] . "' 
                                                                                                                     AND 
                                                                                                                    (
                                                                                                                            tbl_template_detail.title like :key OR  
                                                                                                                            tbl_template_detail.keywork like :key OR                                                                           
                                                                                                                            :key = ''" . "
                                                                                                                    ) 
											
						WHERE 
							$where "
                                                . "$order",
            
            'totalCount' => $totalCount,
            'params' => [
                ':key' => "%" . $key . "%"
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        /**
         * lấy danh sách các template đã mua (k bao gồm các dịch vụ con)
         * thông tin được lấy bao gồm: 
         *       out_id 
         *            cũng chính là template_id, 
         *            đưa vào array $bought_template_ids
         *            nếu mỗi item của $dataProvider (tức là mỗi template), có template_id nằm trong array này thi tức là template này đã mua, còn k thi chưa mua
         *            nếu đã mua thi chỉ biết là đã mua, chưa biet đã thanh toán hay chưa, muốn biet đã thanh toán hay chưa thi phai dựa vào order_status
         *       order_status
         *            để biet được template này đã được thanh toán hay chưa, 
         *            đưa vào array $bought_template_order_statuss)
         *            nếu đã thanh toán thi hiển thị icon download, còn chưa thi hiển thị icon add-cart
         * các element của 2 array này phai nằm đúng thứ tự với nhau
         *       để biet được chính xác template đó nằm ở đơn hàng nào và đơn hàng đó đã được thanh toán hay chưa
         */
        $bought_template_rows = Yii::$app->db->createCommand("SELECT DISTINCT tbl_order_detail.out_id,tbl_order.order_status FROM tbl_order_detail JOIN tbl_order ON tbl_order.order_id=tbl_order_detail.order_id AND tbl_order_detail.main_service=1 WHERE tbl_order.user_id='$user_id'")->queryAll();
        $bought_template_ids = array();
        $bought_template_order_statuss = array();
        if (is_array($bought_template_rows) && count($bought_template_rows) > 0) {
            foreach ($bought_template_rows as $bought_template_row) {
                $bought_template_ids[] = $bought_template_row['out_id'];
                $bought_template_order_statuss[] = $bought_template_row['order_status'];
            }
            //lấy danh sách chi tiết đơn hàng
            $sql = "SELECT service_id,out_id FROM tbl_order_detail WHERE tbl_order_detail.user_id='$user_id' AND tbl_order_detail.out_id IN (" . implode(",", $bought_template_ids) . ") ";
            $order_details = Yii::$app->db->createCommand($sql)->queryAll();
        } else {
            $order_details = array();
        }
        //lấy danh sách service
        $services = Yii::$app->db->createCommand("SELECT * FROM tbl_service WHERE main_service<>1 AND language_id='" . Yii::$app->session['language_id'] . "' ORDER BY service_group_order ASC")->queryAll();
        /**
         * lấy danh sách tbl_order_session đưa vào $order_sessions
         * data của array $order_sessions kiểu như thế này
         * key(số)=>value(array). Ví dụ
         * 3=>(2,3) (trong giỏ hàng có 2 dịch vụ mang service_id là 2 và 3, của template có template_id là 3
         * 7=>(4) (trong giỏ hàng có 1 dịch vụ mang service_id là 4, của template có template_id là 7)
         * array $order_sessions này có chức năng để biet được 1 dịch vụ nào đó của template nào đó đã được add vào giỏ hàng chưa, nghĩa là icon của dich vụ đó phai hiển thị là uncheck_icon_path hay checked_icon_path
         */
        $rows = Yii::$app->db->createCommand("SELECT * FROM tbl_order_session WHERE main_service<>1 AND session_id='" . Yii::$app->session['session_id'] . "' ORDER BY service_group_order ASC")->queryAll();
        $order_sessions=array();
        $out_ids=array();
        foreach ($rows as $row){
            $out_ids[]=$row['out_id'];
        }
        foreach ($out_ids as $out_id){
            $service_ids=array();
            foreach ($rows as $row){
                if($row['out_id']==$out_id){
                    $service_ids[]=$row['service_id'];
                }
            }
            $order_sessions[$out_id]=$service_ids;
        } 
        //
        $models = $dataProvider->models;
        //mỗi model của $dataProvider, thêm một key=>value ('bottom_icon'=>'các icon ví, đã thanh toán, dịch vụ nếu đã mua hoặc chỉ duy nhất icon add-cart nếu chưa mua')
        $lang = MultiLang::viewLang("confirmcart");
        for ($i = 0; $i < count($models); $i++) {            
            if ($params['view'] == 'grid')
                Template::setBottomIcon($models[$i], $bought_template_ids, $bought_template_order_statuss, $services, $order_details, $order_sessions, $lang['cost']);
            else
                Template::setBottomIconForListMode($models[$i], $bought_template_ids, $bought_template_order_statuss, $services, $order_details, $order_sessions, $lang['cost']);
           
        }

        $dataProvider->setModels($models);


        return $dataProvider;
    }

}
