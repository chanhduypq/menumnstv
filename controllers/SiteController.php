<?php

namespace app\controllers;

use Yii;
use app\models\Comment;
use app\models\Common;
use app\models\ContactForm;
use app\models\Element;
use app\models\ElementDetail;
use app\models\ElementDetailShow;
use app\models\EmailService;
use app\models\LayoutService;
use app\models\LoginForm;
use app\models\MultiLang;
use app\models\Option;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderSession;
use app\models\Page;
use app\models\PageDetail;
use app\models\ProfileForm;
use app\models\ProfileFormPassword;
use app\models\Service;
use app\models\Template;
use app\models\TemplateImage;
use app\models\TemplateShow;
use app\models\TemplateStore;
use app\models\User;
use yii\data\Pagination;
use yii\data\SqlDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'oAuthSuccess'],
            ],
        ];
    }
    

    //Xử lý các vấn đề trước khi thực thi các action
    public function beforeAction($action) {


        if (!parent::beforeAction($action)) {
            return false;
        }
        Yii::$app->session['language_id'] = "en";
        
        Common::setGlobalParameters();
        
		
        if (!isset(Yii::$app->session['session_id'])|| strlen(Yii::$app->session['session_id'])==0) {
            Yii::$app->session['session_id'] = time();
        }
        
        if(Yii::$app->controller->action->id=='detail'){
            Template::setViewCountForTemplate();           
        }

        
        //Chỉ login user mới vào được các trang Profile, Bought, Download, Shopping Cart
        $view_id = Yii::$app->controller->action->id;
        if ((($view_id == 'profile') || ($view_id == 'showconfirmcart')) && (strlen(Yii::$app->session['user_id']) <= 0)) {
            $this->redirect('/');
            return false;
        } else {
            return true;
        }
		
		
		
        return true;
    }

    public function actionTest(){
        echo "ABC";
        return true;
    }
    
    public function actionBought() {
        $this->layout = 'bought';
        
        $data = Yii::$app->request->get();
        $key = "";
        if (isset($data['q']))
            $key = $data['q'];

        $params['key']=$key;

        if (isset($data['catalogue_id'])) {
            $params['catalogue_id']=$data['catalogue_id'];            
        }
        else{
            $params['catalogue_id']='-1';
        }
        if (isset($data['view'])) {
            $params['view']=$data['view'];            
        }
        else{
            $params['view']='grid';
        }
        if (isset($data['resolution'])&& ($data['resolution']=='1'||$data['resolution']=='2')) {            
            $params['resolution']=$data['resolution'];
        }
        else{
            $params['resolution']='1';
        }
        if (isset($data['filter'])) {
            $params['filter']=$data['filter'];            
        }
        else{
            $params['filter']='new_post';
        }

        $bought_dataProvider= OrderDetail::getListBoughtTemplate(Yii::$app->session['user_id'],$params);
        
        $catalogues=Yii::$app->db->createCommand("SELECT * FROM tbl_catalogue WHERE active=1 AND language_id='".Yii::$app->session['language_id']."' ORDER BY order_num DESC")->queryAll();
        
        if($params['view']=='grid'){
            return $this->render('bought_grid', array('catalogues'=>$catalogues,'bought_dataProvider'=>$bought_dataProvider,'params'=>$params, 'key' => $key));
        }
        else{
            return $this->render('bought_list', array('catalogues'=>$catalogues,'bought_dataProvider'=>$bought_dataProvider,'params'=>$params, 'key' => $key));
        }
        
        
    }
    
    public function actionComment() {
        $data = Yii::$app->request->post();

        
        Comment::setCmtByUser(Yii::$app->session['user_id'], $data['template_id'], $data['content'], $data['parent_id']);
        $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionRating() {
        $data = Yii::$app->request->post();

        Comment::setVoteByUser(Yii::$app->session['user_id'], $data['template_id'], $data['value'], $data['content']);
        $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {

        // collect user input data
        if (Yii::$app->request->post()) {

            $data = Yii::$app->request->post();
            $email = str_replace("'", "\'", $data['email']);
            $email = trim($email);
            $password = md5($data['password']);
            //nhập email không đúng
            $row = Yii::$app->db->createCommand()->setSql("SELECT * FROM tbl_user WHERE email='$email'")->queryOne();
            if (!is_array($row) || count($row) == 0) {
                echo 'email';
                Yii::$app->end();
            }
            //nhập password không đúng
            $row = Yii::$app->db->createCommand()->setSql("SELECT * FROM tbl_user WHERE email='$email' AND password='$password'")->queryOne();
            if (!is_array($row) || count($row) == 0) {
                echo 'password';
                Yii::$app->end();
            }
            //đã nhập đúng
            $model = new User();
            $model->email = $data['email'];
            $model->password = $data['password'];
            $model->login();
            //nếu user đang đứng tại giỏ hàng và đã chọn hàng, click nút thanh toán, rồi mới login
            if(isset($_GET['check_out'])&&$_GET['check_out']=='1'){
                Yii::$app->session['check_out']=true;
            }

            $model->firstSetting(Yii::$app->session['user_id']);
            //
            Yii::$app->db->createCommand("UPDATE tbl_order_session SET user_id=" . Yii::$app->session['user_id'] . " WHERE session_id=" . Yii::$app->session['session_id'])->execute();            
            /*
             * khi user login vào, giả sử trước đó người ta đã từng add template hay dịch vụ nào đó vào shoppingcart, 
             * mà trong số các template hay dịch vụ nào đó đã tồn tại trong một đơn hàng trước nào đó 
             * thi phai remove chúng ra khỏi shoppingcart trong session_id hiện hành
             */
            //lấy thông tin trùng nhau giữa các đơn hàng cũ và giỏ hàng mới được add vào
            $rows=Yii::$app->db->createCommand("SELECT tbl_order_session.order_session_id,tbl_order_session.main_service,tbl_order_session.out_id FROM tbl_order_session JOIN tbl_order_detail ON tbl_order_detail.out_id=tbl_order_session.out_id AND tbl_order_detail.service_id=tbl_order_session.service_id AND tbl_order_detail.user_id=tbl_order_session.user_id WHERE tbl_order_session.user_id=".Yii::$app->session['user_id'])->queryAll();
            $order_session_ids=array();
            $out_ids=array();//chứa danh sách template_id của các template đã chọn trước khi login, và các template này chắc chắn đã mua rồi.
            if(is_array($rows)&&count($rows)>0){
                foreach ($rows as $row){
                    $order_session_ids[]=$row['order_session_id'];
                    if($row['main_service']=='1'){
                        $out_ids[]=$row['out_id'];
                    }
                }
            }            
            //
            if(count($order_session_ids)>0){//đã có sự trùng nhau giữa các đơn hàng cũ và giỏ hàng mới được add vào
                Yii::$app->db->createCommand("DELETE FROM tbl_order_session WHERE order_session_id IN (". implode(",", $order_session_ids).")")->execute();
                /**
                 * bật 1 session add_item_bought lên, 
                 * rồi trong static_footer, kiểm tra có session này thi báo lên cho user biết là đã có sự trùng lặp 
                 * tất ca layout đều gọi file static_footer này vào, nên khi user login vào, bất kể đang đứng tại page nào thi cũng báo lên cả
                 * báo lên xong thi session này được hủy
                 */
                Yii::$app->session['add_item_bought']=true;
            }  
            /**
             * nếu trước khi login
             * chọn >=1 template vào giỏ hàng
             * bây giờ kiểm tra xem các template đó đã mua đầy đủ các dịch vụ hay chưa 
             *    dĩ nhiên ở đây chỉ là các template đã mua rồi (với xử lý ở bước trên thi array $out_ids chỉ chứa các template đã mua)
             *    còn các template chưa mua thi vẫn còn nằm trong tbl_order_session, cho nên vẫn hiển thị ra tại trang confirmcart
             * nếu chưa thi add template_id vào array $not_full_out_ids
             * cuối cùng, gán array $not_full_out_ids vào session Yii::$app->session['out_ids']
             * mục đích của session Yii::$app->session['out_ids'] là 
             *        để hiển thị ra các template (đã add vào giỏ hàng trước khi login + đã mua + mua chưa đủ các dịch vụ con) 
             *        tại page confirmcart 
             *        mặc dù sau khi login chưa add 1 template hay 1 dịch vụ nào có liên quan đến 1 trong những template đó
             */
            if(count($out_ids)>0){
                $rows=Yii::$app->db->createCommand("SELECT tbl_order_detail.out_id,count(*) AS count FROM tbl_order_detail WHERE tbl_order_detail.user_id=".Yii::$app->session['user_id']." AND out_id IN (". implode(",", $out_ids).") GROUP BY out_id")->queryAll();
                $not_full_out_ids=array();
                foreach ($rows as $row){
                    if($row['count']<4){
                        $not_full_out_ids[]=$row['out_id'];
                    }                    
                }
                Yii::$app->session['out_ids']=$not_full_out_ids;
            }
        }
        Yii::$app->end();
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        User::logout();

        $this->redirect("/");
    }

    //Show trang home
    public function actionIndex() {
        
        $this->layout = 'home';
        
        $data = Yii::$app->request->get();
        $key = "";
        if (isset($data['q']))
            $key = $data['q'];
        
        $catalogues=Yii::$app->db->createCommand("SELECT * FROM tbl_catalogue WHERE active=1 AND language_id='".Yii::$app->session['language_id']."' ORDER BY order_num DESC")->queryAll();
        
        if(Yii::$app->request->isAjax){
            $data = Yii::$app->request->get();
            $key = "";
            if (isset($data['q']))
                $key = $data['q'];
            if(isset($data['resolution'])){
                $resolution=$data['resolution'];
                $dataProvider = Template::getListTemplateForHome($resolution,$key);
                if($resolution=='1'){
                    return $this->renderPartial('index_landscape', array('key'=>$key,'dataProvider' => $dataProvider,'catalogues'=>$catalogues));
                }
                else{
                    return $this->renderPartial('index_portrait', array('key'=>$key,'dataProvider' => $dataProvider,'catalogues'=>$catalogues));
                }
            }            
        }
        
        if(is_array($data)&&count($data)>0){
            if(isset($data['resolution'])){
                $resolution=$data['resolution'];
                $dataProvider = Template::getListTemplateForHome($resolution,$key);
                if($resolution=='1'){
                    return $this->render('index_landscape', array('key'=>$key,'dataProvider' => $dataProvider,'catalogues'=>$catalogues));
                }
                else{
                    return $this->render('index_portrait', array('key'=>$key,'dataProvider' => $dataProvider,'catalogues'=>$catalogues));
                }
            }       
        }
        
        
        
        $dataProvider = Template::getListTemplateForHome($resolution=1,$key);
        return $this->render('index_landscape', array('key'=>$key,'dataProvider' => $dataProvider,'catalogues'=>$catalogues));

    }

    public function actionDownload() {

        $data = Yii::$app->request->get();
        if (!isset($data['id']) || !ctype_digit($data['id'])) {
            $this->redirect("/");
        } else {
            $template_id = $data['id'];  
            /**
             * chống hacker
             * hoặc k phai hacker nhưng user vô tình/tò mò gõ url xxx/download?id=yyy
             *      cho dù là vô tình/tò mò gõ url nhưng họ đã login và ?id=yyy chính là template họ đã mua đồng thời đã thanh toán thi họ vẫn download được bình thường
             */
            //nếu user chưa đăng nhập
//            if (!isset(Yii::$app->session['user_id'])||strlen(Yii::$app->session['user_id']) <= 0) {
//                $this->redirect("/");
//                return;
//            }
            //nếu user chưa mua template này
//            $count = Yii::$app->db->createCommand()->setSql("SELECT COUNT(*) FROM tbl_order_detail JOIN tbl_order ON tbl_order.order_id=tbl_order_detail.order_id AND tbl_order.order_status=0 WHERE tbl_order.user_id=" . Yii::$app->session['user_id'] . " AND tbl_order_detail.out_id=$template_id")->queryScalar();
//            if ($count == 0) {
//                $this->redirect("/");
//                return;
//            }
            
            $archive_name = Yii::$app->db->createCommand()->setSql("SELECT archive_name FROM tbl_template WHERE template_id=$template_id")->queryScalar();
            $file_name = $GLOBALS['options']["archive_folder"] . "/" . $archive_name;
            $fp = fopen($file_name, 'rb');

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $archive_name . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_name));
            fpassthru($fp);
            exit;
        }
    }

    

    

    /*
      Dùng hiển thị các template theo Catalogue
     */

    public function actionPlaza() {
        $this->layout = 'plaza';
        
        $params=array();
        
        $data = Yii::$app->request->get();
        $key = "";
        if (isset($data['q']))
            $key = $data['q'];

        $params['key']=$key;

        if (isset($data['catalogue_id'])) {
            $params['catalogue_id']=$data['catalogue_id'];            
        }
        else{
            $params['catalogue_id']='-1';
        }
        if (isset($data['view'])) {
            $params['view']=$data['view'];            
        }
        else{
            $params['view']='grid';
        }
        if (isset($data['resolution'])&& ($data['resolution']=='1'||$data['resolution']=='2')) {            
            $params['resolution']=$data['resolution'];
        }
        else{
            $params['resolution']='1';
        }
        if (isset($data['filter'])) {
            $params['filter']=$data['filter'];            
        }
        else{
            $params['filter']='new_post';
        }
        
        
        
        $page=0;        
        if(count($data)>0){
            if(isset($data['page'])){
                $page = $data['page'];
                $page--;
            }     
        }
         
        if($params['resolution']=='1'&&$params['view']=='list'){
            $limit=5;
        }
        else if($params['resolution']=='1'&&$params['view']=='grid'){
            $limit=15;
        }
        else if($params['resolution']=='2'&&$params['view']=='list'){
            $limit=10;
        }
        else if($params['resolution']=='2'&&$params['view']=='grid'){
            $limit=12;
        }
        
        
        
        $dataProvider = Template::getListTemplate($limit, $params, $page);

        $catalogues=Yii::$app->db->createCommand("SELECT * FROM tbl_catalogue WHERE active=1 AND language_id='".Yii::$app->session['language_id']."' ORDER BY order_num DESC")->queryAll();
        
        if($params['view']=='grid'){
            $item_view='_template';
        }
        else{
            $item_view='_template_list';
        }
        
        return $this->render('plaza', array('item_view'=>$item_view,'params'=>$params,'catalogues'=>$catalogues,'dataProvider' => $dataProvider, 'havePaging' => true, 'key' => $key));
        
    }    
    /**
     * date 2016.12.22
     * khi user nhấn vào icon giỏ hàng ở phía dưới mỗi template để add nó vào giỏ hàng thi chạy đến action này
     */
    public function actionOrdersession() {
        $this->layout = 'blank'; 
        $data = Yii::$app->request->get();
        

        $row = Yii::$app->db->createCommand("SELECT tbl_template_detail.title,tbl_template_detail.num FROM tbl_template JOIN tbl_template_detail ON tbl_template.template_id = tbl_template_detail.template_id AND tbl_template_detail.language_id = '".Yii::$app->session['language_id']."' AND tbl_template_detail.type=1 WHERE tbl_template.template_id=" . $data['tid'])->queryOne();
        $uid = "0";
        if (isset(Yii::$app->session['user_id'])&& strlen(Yii::$app->session['user_id'])>0)
            $uid = Yii::$app->session['user_id']; //Lấy user_id
        $model = new OrderSession();
        $model->session_id = Yii::$app->session['session_id'];
        $model->user_id = $uid;
        $model->out_id = $data['tid'];
        $model->out_name = $row['title'];
        $model->price = $row['num'];
        $model->service_id = $model->service_group_id = $model->service_type = 1;
        $model->have_support = 0;
        $model->main_service = 1;
        $model->save(FALSE);
        
        if(isset($data['service_ids'])){
            $service_ids= rtrim($data['service_ids'], ',');
            $temp= explode(",", $service_ids);
            for($i=0;$i<count($temp);$i++){
                $row = Yii::$app->db->createCommand("SELECT * FROM tbl_service WHERE service_id=" . $temp[$i]." AND tbl_service.language_id='".Yii::$app->session['language_id']."'")->queryOne();
                
                $model = new OrderSession();
                $model->session_id = Yii::$app->session['session_id'];
                $model->user_id = $uid;
                $model->out_id = $data['tid'];
                $model->out_name = $row['service_name'];
                $model->price = $row['price'];
                $model->service_id = $row['service_id'];
                $model->service_group_id = $model->service_type = 1;
                $model->have_support = 0;
                $model->main_service = 2;
                $model->save(FALSE);
            }
        }
        //
        $order_sessions= OrderSession::getOrderSessionsForPopupCart();               
        /**
         * lấy danh sách các template đã mua đưa vào $order_out_ids
         * array $order_out_ids này có chức năng 
         *      để biet được 1 template nào đó đã được mua hay chưa
         */
        $rows = Yii::$app->db->createCommand("SELECT out_id FROM tbl_order_detail WHERE user_id='" . Yii::$app->session['user_id'] . "' AND main_service=1")->queryAll();
        $order_out_ids=array();
        foreach ($rows as $row){
            $order_out_ids[]=$row['out_id'];
        }
        
        $carts= OrderSession::buildCartForShowPopup($order_sessions, $order_out_ids, $sum_all);
        
        return $this->render('showshoppingcart', array('carts' => $carts,'sum_all'=>$sum_all));
    }

    /**
     * date 2016.12.22
     * trong trang giỏ hàng hoặc trong popup cart, khi user nhấn vào dấu x để remove 1 template đã chọn 
     * thi chạy đến action này
     */
    public function actionDeleteordersession() {
        $data = Yii::$app->request->get();
        Yii::$app->db->createCommand("DELETE FROM tbl_order_session WHERE out_id=" . $data['out_id'] . " AND session_id=" . Yii::$app->session['session_id'])->execute();
        $count_cart_template = Yii::$app->db->createCommand("SELECT COUNT(*) FROM tbl_order_session WHERE main_service=1 AND session_id=".Yii::$app->session['session_id'])->queryScalar();
        $count_cart_option = Yii::$app->db->createCommand("SELECT COUNT(*) FROM tbl_order_session WHERE main_service=2 AND session_id=".Yii::$app->session['session_id'])->queryScalar();
        $sql="SELECT tbl_template.template_id,tbl_template_detail.num,tbl_template_detail.num2,tbl_template_detail.title,tbl_template_detail.slug  							
						FROM 
							tbl_template 
								LEFT JOIN  tbl_template_detail ON 
									tbl_template.template_id = tbl_template_detail.template_id AND 
									tbl_template_detail.language_id = '".Yii::$app->session['language_id']."' 								
						WHERE 							
							tbl_template.template_id = ".$data['out_id'];
        
        $template=Yii::$app->db->createCommand($sql)->queryOne();
        $template['old_price']= Template::showOldPrice($template['num'], $template['num2']);
        $template['new_price']= Template::showNewPrice($template['num'], $template['num2']);
        $data=array('template'=>$template,'count_cart_template'=>$count_cart_template,'count_cart_option'=>$count_cart_option);
        echo Json::encode($data);

    }
    /**
     * date 2016.12.22
     * trong trang giỏ hàng
     * khi user check/uncheck vào các option (dịch vụ thêm) của từng template thi chạy đến action này
     * nếu user check thi insert, còn uncheck thi delete
     */
    public function actionOrdersessionoption() {
        $this->layout = 'blank';
        $data = Yii::$app->request->get();
        if ($data['action'] == 'add') {
            $row = Yii::$app->db->createCommand("SELECT * FROM tbl_service WHERE service_id=" . $data['service_id']." AND tbl_service.language_id='".Yii::$app->session['language_id']."'")->queryOne();
            $uid = "0";
            if (isset(Yii::$app->session['user_id'])&& strlen(Yii::$app->session['user_id'])>0)
                $uid = Yii::$app->session['user_id']; //Lấy user_id
            $model = new OrderSession();
            $model->session_id = Yii::$app->session['session_id'];
            $model->user_id = $uid;
            $model->out_id = $data['out_id'];
            $model->out_name = $row['service_name'];
            $model->price = $row['price'];
            $model->service_id = $data['service_id'];
            $model->service_group_id = $model->service_type = 1;
            $model->have_support = 0;
            $model->main_service = 2;
            $model->save(FALSE);
            
            
        }
        else if ($data['action'] == 'delete') {
            Yii::$app->db->createCommand("DELETE FROM tbl_order_session WHERE out_id=" . $data['out_id'] . " AND service_id=" . $data['service_id'] . " AND session_id=" . Yii::$app->session['session_id'])->execute();
        }
        /**
         * hiển thị pop-up cart
         */
        //
        $order_sessions= OrderSession::getOrderSessionsForPopupCart();             
        /**
         * lấy danh sách các template đã mua đưa vào $order_out_ids
         * array $order_out_ids này có chức năng 
         *      để biet được 1 template nào đó đã được mua hay chưa
         */
        $rows = Yii::$app->db->createCommand("SELECT out_id FROM tbl_order_detail WHERE user_id='" . Yii::$app->session['user_id'] . "' AND main_service=1")->queryAll();
        $order_out_ids=array();
        foreach ($rows as $row){
            $order_out_ids[]=$row['out_id'];
        }
        
        $carts= OrderSession::buildCartForShowPopup($order_sessions, $order_out_ids, $sum_all);
        
        return $this->render('showshoppingcart', array('carts' => $carts,'sum_all'=>$sum_all));
    }
    

    
    public function actionGetservices() {

        $services = Yii::$app->db->createCommand("SELECT * FROM tbl_service WHERE main_service<>1 AND language_id='" . Yii::$app->session['language_id'] . "' ORDER BY service_group_order ASC")->queryAll();
        for($i=0;$i<count($services);$i++){
            $services[$i]['price']= LayoutService::showServiceMoney($services[$i]['price']);
            if (7 > strlen(utf8_decode($services[$i]['service_name']))) {
                $services[$i]['service_name'] = strtolower($services[$i]['service_name']);
            } else {
                $string_cop = mb_substr($services[$i]['service_name'], 0,7,'UTF-8'); 
                $services[$i]['service_name'] =strtolower( $string_cop . "...");
            }
        }
        echo Json::encode($services);
        Yii::$app->end();
    }

    public function actionConfirmcart() {
        $this->layout="confirmcart";
        
        if(isset(Yii::$app->session['user_id'])&&strlen(Yii::$app->session['user_id'])>0){
            $user=$GLOBALS['user'];
        }
        else{
            $user=array();
        }
        
               
        
        $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM tbl_order_session WHERE session_id=".Yii::$app->session['session_id'])->queryScalar();
        /**
         * nếu trong tbl_order_session k có record nào ứng với Yii::$app->session['session_id'] hiện tại
         * và
         *   hoặc chưa login thi Yii::$app->session['out_ids'] chưa được khởi tạo
         *   hoặc đã login
         *        mà trước khi login chưa add template nào vào giỏ hàng thi count(Yii::$app->session['out_ids'])=0
         *        (hoặc) mà trước khi login, đã chọn >1 template vào giỏ hàng, trong số template này đều mua chưa đủ các dịch vụ con thi count(Yii::$app->session['out_ids'])=0
         */
        if ($count == 0&&(!isset(Yii::$app->session['out_ids'])||count(Yii::$app->session['out_ids'])==0)) {
            return $this->render('confirmcartblank');
        }
        
        $order_sessions= OrderSession::getOrderSessions();
        
        /**
         * nếu trước khi login, chọn >=1 template vào giỏ hàng, mà trong số các template có ít nhất 1 template đã mua + mua chưa đủ các dịch vụ con
         */
        if(isset(Yii::$app->session['out_ids'])&& is_array(Yii::$app->session['out_ids'])&&count(Yii::$app->session['out_ids'])>0){            
            $templates= Template::getBouthNotFullTemplate();        
            $order_sessions= array_merge($order_sessions,$templates);
        }
        
        
        $services = Yii::$app->db->createCommand("SELECT * FROM tbl_service WHERE main_service<>1 AND language_id='" . Yii::$app->session['language_id'] . "' ORDER BY service_group_order ASC")->queryAll();
        /**
         * lấy danh sách tbl_order_detail đưa vào $order_details
         * data của array $order_details kiểu như thế này
         * key(số)=>value(array). Ví dụ
         * 3=>(1,2,3) (đã từng mua template có template_id là 3 và 2 dịch vụ mang service_id là 2 và 3 của template này)
         * 7=>(1,4) (đã từng mua template có template_id là 7 và 1 dịch vụ mang service_id là 4 của template này)
         * 8=>(1) (đã từng mua template có template_id là 8 và chỉ duy nhất template này thoi, k có dịch vụ nào cho template này cả)
         * array $order_details này có chức năng 
         *      để biet được 1 dịch vụ nào đó của template nào đó đã được mua hay chưa
         *      và để biet được 1 template nào đó đã được mua hay chưa
         */
        $rows = Yii::$app->db->createCommand("SELECT * FROM tbl_order_detail WHERE user_id='" . Yii::$app->session['user_id'] . "'")->queryAll();
        $order_details=array();
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
            $order_details[$out_id]=$service_ids;
        }
               
        //
        $carts= OrderSession::buildCartForShow($order_sessions,$order_details,$sum_all);
        
        return $this->render('confirmcart', array('carts'=>$carts,'sum_all'=>$sum_all,'order_details' => $order_details, 'services' => $services,'user'=>$user));
    }
    

    /**
     * date 2016.12.22
     * khi user submit form thanh toán tại trang giỏ hàng thi sẽ chạy đến action này
     */
    public function actionRegisterpay() {
        if (Yii::$app->request->post()) {
            
            $transaction = Yii::$app->db->beginTransaction();
            $success=true;
            
            $data = Yii::$app->request->post();
            if(strlen(Yii::$app->session['user_id'])==0){
                $row = Yii::$app->db->createCommand()->setSql("SELECT * FROM tbl_user WHERE email='".$data['email']."'")->queryOne();
                if (is_array($row) && count($row) > 0) {
                    $lang_error = MultiLang::viewLang("error_message");
                    echo $lang_error['error_email_exist'];
                    Yii::$app->end();
                }
            }
            
            $paypal_cod=$data['paypal_cod'];
            
            if(strlen(Yii::$app->session['user_id'])==0){
                //insert vào tbl_user
                $model=new User();
                $model->full_name = $data['fullname'];
                $model->gender_id = $data['gender'];
                $model->email = $data['email'];
                $model->phone = $data['phone'];
                $model->address = $data['address'];
                $model->city = $data['city'];
                $password = substr(md5(rand()), 0, 7);
                $model->password=$password;
                if($model->save(FALSE)==FALSE){
                    $success=FALSE;
                }
                $user_id=$model->user_id;
                Yii::$app->session['user_id'] = $user_id;
                Yii::$app->session['email'] = $data['email'];
                Yii::$app->session['full_name'] = $data['fullname'];
                Yii::$app->session['gender'] = $data['gender'];
            }
            else{
                $model=User::findOne(array('user_id' => Yii::$app->session['user_id']));
                $model->phone = $data['phone'];
                $model->address = $data['address'];
                $model->city = $data['city'];
                if($model->save(FALSE)==FALSE){
                    $success=FALSE;
                }
                $user_id=Yii::$app->session['user_id'];
            }
            
            
            //update tbl_order_session
            Yii::$app->db->createCommand("UPDATE tbl_order_session SET user_id=$user_id WHERE session_id=" . Yii::$app->session['session_id'])->execute();
            //insert vào tbl_order
            $order_model=new Order();
            $order_model->user_id=$user_id;
            $order_model->total_price = $data['total_price'];
            if($paypal_cod=='paypal'){
                $order_model->order_status=1;
                $order_model->order_type=1;                
            }
            else{
                $order_model->order_status=10; 
                $order_model->order_type=2; 
            }
            $order_model->gender = Yii::$app->session['gender'];
            $order_model->full_name = Yii::$app->session['full_name'];
            $order_model->email = Yii::$app->session['email'];
            $order_model->phone = $data['phone'];
            $order_model->address = $data['address'];
            $order_model->city = $data['city'];
            $order_model->order_time=$order_model->create_time=date("Y-m-d H:i:s");
            if($order_model->save(FALSE)==FALSE){
                $success=FALSE;
            }
            $order_id=$order_model->order_id;
            //insert vào tbl_order_detail
            $rows=Yii::$app->db->createCommand("SELECT tbl_order_session.out_id,tbl_order_session.out_name,tbl_order_session.service_id,tbl_order_session.service_group_id,tbl_order_session.order_type,tbl_order_session.service_type,tbl_order_session.have_support,tbl_order_session.price,tbl_order_session.main_service,tbl_service.service_name FROM tbl_order_session JOIN tbl_service ON tbl_service.service_id=tbl_order_session.service_id AND tbl_service.language_id ='" . Yii::$app->session['language_id'] . "' WHERE tbl_order_session.session_id=" . Yii::$app->session['session_id'])->queryAll();
            $date=date("Y-m-d H:i:s");
            foreach ($rows as $row){
                //save tbl_order_detail
                $order_detail_model=new OrderDetail();
                $order_detail_model->order_id=$order_id;
                $order_detail_model->user_id=$user_id;
                $order_detail_model->template_id=$order_detail_model->out_id=$row['out_id'];
                $order_detail_model->out_name=$row['out_name'];
                $order_detail_model->service_id=$row['service_id'];
                $order_detail_model->service_group_id=$row['service_group_id'];
                $order_detail_model->order_type=$row['order_type'];
                $order_detail_model->service_type=$row['service_type'];
                $order_detail_model->have_support=$row['have_support'];
                $order_detail_model->price=$row['price'];
                $order_detail_model->update_time=$date;
                $order_detail_model->service_name=$row['service_name'];
                $order_detail_model->main_service=$row['main_service']; 
                
                if($order_detail_model->save(FALSE)==FALSE){
                    $success=FALSE;
                }
                
                if($row['main_service']=='1'){
                    $affected=Yii::$app->db->createCommand("UPDATE tbl_template SET bought_count=bought_count+1 WHERE template_id=".$row['out_id'])->execute();
                    if($affected!=1){
                        $success=FALSE;
                    }
                }
                //save tbl_template_store
                $template_store_model=new TemplateStore();
                $template_store_model->order_id=$order_id;
                $template_store_model->order_detail_id=$order_detail_model->order_detail_id;
                $template_store_model->order_status=$order_model->order_status;
                $template_store_model->order_type=$order_detail_model->order_type;
                $template_store_model->user_id=$order_detail_model->user_id;
                $template_store_model->template_id=$order_detail_model->out_id ;
                $template_store_model->template_name=$order_detail_model->out_name;
                $template_store_model->template_path='';
                $template_store_model->cnt=0;
                $template_store_model->store_space=0;
                $template_store_model->space_used=0;
                $template_store_model->end_date=$template_store_model->update_time=date("Y-m-d H:i:s");
                if($template_store_model->save(FALSE)==FALSE){
                    $success=FALSE;
                }
            }
            
            if ($success == true) {
                $transaction->commit();
            }
            else{
                $transaction->rollBack();
            }
            
            Yii::$app->session['session_id'] = time();
            unset(Yii::$app->session['out_ids']);
            
            
        }
    }

    /*
      Hiển thị và cập nhật thông tin cá nhân
     */

    public function actionProfile() {
        $profileForm = new ProfileForm();
        $profileFormPassword = new ProfileFormPassword();

        $user = new User();
        $user_id = Yii::$app->session['user_id'];
        $profileForm->loadFromDB(intval($user_id));

        //Trường hợp user submit form profile
        if($profileForm->load(Yii::$app->request->post()) && $profileForm->update()) {
            $this->redirect('/profile');
        }

        //Trường hợp user submit form change password
        if($profileFormPassword->load(Yii::$app->request->post()) && $profileFormPassword->changePass()) {
            $this->redirect('/profile');
        }

        return $this->render('profile', array('profileForm' => $profileForm, 'profileFormPassword' => $profileFormPassword, 'avatar_path' => $user->getUserAvatar($user_id)));
    }


    /*
      Hiển thị màn hình Contact
      User có thể nhập thông tin contact và gởi cho Quản trị viên thông qua email
     */
    public function actionContact() {
        $contactForm = new ContactForm();

        //Trường hợp user submit form contact, nếu thông tin được input đầy đủ sẽ gởi đến Khách Hàng
        if($contactForm->load(Yii::$app->request->post()) && $contactForm->contact()) {
            $contactForm->clearForm();
            return $this->render('contact', array('contactForm' => $contactForm, 'msg' => 'Thank you for contacting us – we will get back to you soon!'));
        }

        return $this->render('contact', array('contactForm' => $contactForm, 'msg' => ''));
    }

    /*
      Lấy thông tin từ DB tuong ứng vào show ra web
     */
    public function actionPage() {
        //Trường hợp có dối số URL
        if (isset(Yii::$app->request->get()['url'])) {
            $pages = PageDetail::getPageDetailContent(Yii::$app->request->get()['url'], Yii::$app->session['language_id']);

            //Trường hợp URL này không tồn tại
            if($pages == null) {
                $this->redirect('/'); 
                return;
            }

            $this->layout = $GLOBALS['options']['page_layouts'] . "/" . $pages[0]['layout'];
            $this->viewPath = $GLOBALS['options']['page_views'];
            $view = $pages[0]['view'];
            $this->view->title = $pages[0]['title'];

            return $this->render($view, array('pages' => $pages));
        }

        $this->redirect('/');
    }

    /*
      Hiển thị thông tin chi tiết của Template
     */

    public function actionDetail() {
 
        $this->layout = 'detail'; //Dùng layout plaza

        if (isset(Yii::$app->request->get()['url'])) {
            $url = Yii::$app->request->get()['url'];

            $detailData = Template::getTemplateDetailProviderByUrl($url);
            if (is_array($detailData)&&count($detailData)>0) { 
                $catalogueProvider = Template::getTemplateProviderByCatalogue($detailData['catalogue_01_id'], $detailData['template_id'],$detailData['resolution']);
                $upload_template_url = $GLOBALS['options']['upload_template_url'];                
                //cho hiển thị button [Mua] phía trên bên phải màn hình hay không thi kiểm tra xem thử user đã mua hay đã add vào giỏ hàng hay chưa
                $lock=false;
                if (isset(Yii::$app->session['user_id'])&& strlen(Yii::$app->session['user_id'])>0){
                    $count=Yii::$app->db->createCommand("SELECT COUNT(*) FROM tbl_order_detail WHERE out_id=".$detailData['template_id']." AND user_id=" . Yii::$app->session['user_id'])->queryScalar();
                    if($count!='0'){
                        $lock=true;
                    }
                }
                if($lock==FALSE){
                    $count=Yii::$app->db->createCommand("SELECT COUNT(*) FROM tbl_order_session WHERE session_id=".Yii::$app->session['session_id']." AND out_id=".$detailData['template_id'])->queryScalar();
                    if($count!='0'){
                        $lock=true;
                    }
                }
                //
                $services = Yii::$app->db->createCommand("SELECT * FROM tbl_service WHERE main_service<>1 AND language_id='" . Yii::$app->session['language_id'] . "' ORDER BY service_group_order ASC")->queryAll();
                //lấy thông tin các dịch vụ đã được mua
                $service_ids_in_order=array();
                if (isset(Yii::$app->session['user_id'])&& strlen(Yii::$app->session['user_id'])>0){
                    $rows=Yii::$app->db->createCommand("SELECT service_id FROM tbl_order_detail WHERE out_id=".$detailData['template_id']." AND user_id=" . Yii::$app->session['user_id'])->queryAll();
                    foreach ($rows as $row){
                        $service_ids_in_order[]=$row['service_id'];
                    }
                }
                //lấy thông tin các dịch vụ đã được add vào giỏ hàng
                $service_ids_in_session=array();
                $rows=Yii::$app->db->createCommand("SELECT service_id FROM tbl_order_session WHERE session_id=".Yii::$app->session['session_id']." AND out_id=" . $detailData['template_id'])->queryAll();
                foreach ($rows as $row){
                    $service_ids_in_session[]=$row['service_id'];
                }
                
                $data = Yii::$app->request->get();
                //lấy page của rating
                $page_rating=0;        
                if(count($data)>0){
                    if(isset($data['page'])&&isset($data['type'])&&$data['type']=='rating'){
                        $page_rating = $data['page'];
                        $page_rating--;
                    }     
                }
                //lấy page của comment
                $page_comment=0;        
                if(count($data)>0){
                    if(isset($data['page'])&&isset($data['type'])&&$data['type']=='comment'){
                        $page_comment = $data['page'];
                        $page_comment--;
                    }     
                } 
                //
                $params=array(
                                                                "upload_template_url" => $upload_template_url, 
                                                                "detailData" => $detailData, 
                                                                'catalogueProvider' => $catalogueProvider, 
                                                                'services' => $services,
                                                                'lock'=>$lock,
                                                                'service_ids_in_order'=>$service_ids_in_order,
                                                                'service_ids_in_session'=>$service_ids_in_session,
                                                                'ratings'=> Template::getRatings($detailData['template_id']),
                                                                'full_ratings'=> Template::getFullRatings($detailData['template_id'],$limit=5,$page_rating),
                                                                'full_comments'=> Template::getFullComments($detailData['template_id'],$limit=5,$page_comment,$count_question,$count_answer),
                                                                'count_question'=>$count_question,
                                                                'count_answer'=>$count_answer,
                        
                            );
                if(is_numeric($detailData['comment_id'])){
                    $GLOBALS['voted']=true;
                }
                else{
                    $GLOBALS['voted']=false;
                }
                //
                if ($detailData['resolution'] == '1') {
                    return $this->render('detail_landscape', $params);
                } else {
                    return $this->render('detail_portrait', $params);
                    
                }
            }
        }

        $this->redirect(array("/site/plaza"));
    }

    

    /**
     * gởi mail, nội dung mail là cấp 1 mật khẩu mới
     * date 26/10/2016
     */
    public function actionResetpassword() {
        $data = Yii::$app->request->get();
        $row = Yii::$app->db->createCommand()->setSql("SELECT * FROM tbl_user WHERE user_id=" . $data['user_id'] . " AND active_code='" . $data['ac'] . "'")->queryOne();
        $gender = $row['gender_id'];
        if ($gender == '1') {
            if(Yii::$app->session['language_id']=='vi'){
                $gender = "Anh";
            }
            else{
                $gender = "Mr";
            }

        } else {
            if(Yii::$app->session['language_id']=='vi'){
                $gender = "Chị";
            }
            else{
                $gender = "Ms";
            }
        }

        //send mail
        if (is_array($row) && count($row) > 0) {
            $new_password = substr(md5(rand()), 0, 7);
            EmailService::send_mail_reset_password($gender, $row['full_name'], $new_password, $row['email']);
            //update password
            Yii::$app->db->createCommand()->setSql("UPDATE tbl_user SET password='" . md5($new_password) . "' WHERE user_id=" . $data['user_id'])->execute();
        }


        $this->redirect(array("/site/index"));
    }

    /**
     * action này là action khi user click nào button [xác nhận thông tin] của form [quên mật khẩu]
     * date 25/10/2016
     */
    public function actionLostpassword() {
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            $email = str_replace("'", "\'", $data['email']);
            $email = trim($email);
            $row = Yii::$app->db->createCommand()->setSql("SELECT * FROM tbl_user WHERE email='$email'")->queryOne();

            $gender = $row['gender_id'];
            if ($gender == '1') {
                if(Yii::$app->session['language_id']=='vi'){
                    $gender = "Anh";
                }
                else{
                    $gender = "Mr";
                }
                
            } else {
                if(Yii::$app->session['language_id']=='vi'){
                    $gender = "Chị";
                }
                else{
                    $gender = "Ms";
                }
            }
            if (!is_array($row) || count($row) == 0) {
                $lang_error= MultiLang::viewLang('error_message');
                echo $lang_error['error_email_not_exist'];
            } else {
                $active_code = substr(md5(rand()), 0, 10);
                //update active_code
                Yii::$app->db->createCommand()->setSql("UPDATE tbl_user SET active_code='$active_code' WHERE user_id=" . $row['user_id'])->execute();
                //send mail                
                $link = $GLOBALS['options']["menu.mns.tv"] . "/resetpassword?user_id=" . $row['user_id'] . "&ac=$active_code";
                EmailService::send_mail_lost_password($gender, $row['full_name'], "<a href='$link' target='_blank' shape='rect'>".(Yii::$app->session['language_id']=='vi'?"click vào đây":"click here")."</a>", $email);
            }
        }
        Yii::$app->end();
    }

    // == FACEBOOK ================================================================
    public function oAuthSuccess($client)
    {
        // callback information
        $userAttributes = $client->getUserAttributes();
        if (is_array($userAttributes) && $userAttributes['email'])
        {
            $userAttributes['avatar'] = "http://graph.facebook.com/{$userAttributes['id']}/picture?type=large";
        }
        else
        {
            return $this->redirect("/");
        }

        // check and register
        $user = User::getUserByEmail($userAttributes['email']);
        if (!$user)
        {
            $user = User::registerFacebookUser($userAttributes);
        }

        // login
        User::loginViaFacebook($user);
        return $this->redirect("/");
    }
}
