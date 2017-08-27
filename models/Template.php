<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use app\models\Template_Show;
use app\models\TemplateStore;
use app\models\Catalogue;
use yii\data\SqlDataProvider;
use yii\data\Pagination;
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
class Template extends ActiveRecord {

    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return 'tbl_template';
    }

    //Mã hóa file
    public function createDemoFile($path, $path_target, $order_url) {
        $data = file_get_contents($path);
        file_put_contents($path_target, $data);
    }

    //Hàm này mã hóa utf-8 charater thành mã đặc biệt có thể đọc bằng Javascript
    public function escape($str) {
        $specialChars = array(
            " " => "%20",
            "!" => "%21",
            "@" => "@",
            "#" => "%23",
            "$" => "%24",
            //"%"=>"%25",
            "^" => "%5E",
            "&" => "%26",
            "*" => "*",
            "(" => "%28",
            ")" => "%29",
            "-" => "-",
            "_" => "_",
            "=" => "%3D",
            "+" => "+",
            ":" => "%3A",
            ";" => "%3B",
            "." => ".",
            "\"" => "%22",
            "'" => "%27",
            "\\" => "%5C",
            "/" => "%2F",
            "?" => "%3F",
            "<" => "%3C",
            ">" => "%3E",
            "~" => "%7E",
            "[" => "%5B",
            "]" => "%5D",
            "{" => "%7B",
            "}" => "%7D",
            "`" => "%60",
            chr(10) => "",
            chr(13) => "",
        );


        foreach ($specialChars as $theChar => $code) {
            $str = str_replace($theChar, $code, $str);
        }

        return $str;
    }

    function javascript_escape($str) {
        $new_str = '';

        $str_len = strlen($str);
        for ($i = 0; $i < $str_len; $i++) {
            $new_str .= '\\x' . dechex(ord(substr($str, $i, 1)));
        }

        return $new_str;
    }

    /**
     * session Yii::$app->session['templates'] luôn được update liên tục mỗi lần user vào hệ thống
     *     vi nếu k được update liên tục thi session này sẽ k chứa những template mới được upload từ admin
     *     như vậy, thi user vào trang detail của template mới được upload, tbl_template.view_count của template này sẽ k tăng lên 1 đơn vị mỗi khi user viếng thăm template này
     * @param array $new_template_ids, chứa tất cả các template cho đến thời điểm hiện tại
     * @return void
     */
    public static function setTemplateSession($new_template_ids) {
        //lấy thông tin session Yii::$app->session['templates'] cũ
        if (!isset(Yii::$app->session['templates']) || !is_array(Yii::$app->session['templates']) || count(Yii::$app->session['templates']) == 0) {
            $old_template_session = array();
        } else {
            $old_template_session = Yii::$app->session['templates'];
        }

        /**
         * thời gian viếng thăm mới nhất  mỗi template (các template đã có trong session cũ) sẽ được lấy từ session cũ đưa vào $new_template_ids
         * sau đó mới update session Yii::$app->session['templates'] bằng $new_template_ids
         * 
         * data cua array $new_template_ids, $old_template_session là như thế này ($key1=>$value1,$key2=>$value2,...)
         *       $key1, $key2,... là các template_id
         *       $value1, $value2,... là thời gian viếng thăm mới nhất của template tương ứng 
         *       nếu $value='' tức là template tương ứng chưa được viếng thăm lần nào
         */
        foreach ($new_template_ids as $key => $value) {

            if (in_array($key, array_keys($old_template_session))) {
                $new_template_ids[$key] = $old_template_session[$key];
            }
        }

        Yii::$app->session['templates'] = $new_template_ids;
    }

    /**
     * lấy tất cả id của các template cho đến thời điểm hiện tại
     * @return array, data cua array này là như thế này ($key1=>$value1,$key2=>$value2,...)
     *       $key1, $key2,... là các template_id
     *       $value1, $value2,... là thời gian viếng thăm mới nhất của template tương ứng 
     *       nếu $value='' tức là template tương ứng chưa được viếng thăm lần nào
     *       với function này thi $value luôn là ''
     */
    public static function getTemplateIdsForSession() {
        $template_ids = Yii::$app->db->createCommand("SELECT template_id FROM tbl_template")->queryAll();

        $template_session = array();
        foreach ($template_ids as $template_id) {
            $template_session[$template_id['template_id']] = "";
        }

        return $template_session;
    }

    /**
     * Khi user vào xem trang detail của bất kỳ template nào, tăng tbl_template.view_count lên thêm 1 đơn vị.
     * Lượt view có giá trị 24h, tức trong vòng 24h sau nếu user có quay lại cũng không làm tăng lượt view.
     * @param int $time_out, là số giây, mặc định ở đây là 24h tức là 24*3600=86400
     */
    public static function setViewCountForTemplate($time_out = 86400) {

        $template_ids = self::getTemplateIdsForSession();

        self::setTemplateSession($template_ids);

        if (strlen(Yii::$app->session['language_id']) <= 0) {
            $language_id = "en";
        } else {
            $language_id = Yii::$app->session['language_id'];
        }

        $template_id = Yii::$app->db->createCommand("
			SELECT template_id "
                        . "FROM tbl_template_detail "
                        . "WHERE "
                        . "language_id='$language_id' "
                        . "AND type=1 "
                        . "AND slug='" . $_GET['url'] . "'"
                )->queryScalar();

        $template_session = Yii::$app->session['templates'];

        foreach ($template_session as $key => $value) {
            if ($key == $template_id) {

                $time = time();

                if (
                        $value == ""//mới viếng thăm lần đầu
                        || (($time - $value) > $time_out)//viếng thăm lại sau một khoảng thời gian nào đó (khoảng thời gian $time_out giây
                ) {
                    self::increaseTemplateViewCount($template_id);
                }

                $template_session[$key] = $time; //update thời gian viếng thăm mới nhất cho template này ($template_id)

                Yii::$app->session['templates'] = $template_session;

                break;
            }
        }
    }

    /**
     * hiển thị giá tiền bao gồm: số + đơn vị tiền tệ như là 100 k hay $20
     * @param float|string $price
     * @param float|string $discount
     * @return string
     */
    public static function showNewPrice($price, $discount) {
        if ($price == 0) {
            return 'FREE';
        }

        $language_id = Yii::$app->session['language_id'];
        $money_unit = $GLOBALS['options']['money-unit-en'];

        if ($language_id == 'en') {
            $money = $money_unit . number_format($price - $discount, 2, ".", ",");
        } else {
            $money = number_format($price - $discount, 0, ",", ".") . " " . $money_unit;
        }

        return $money;
    }

    /**
     * hiển thị discount bao gồm: số + đơn vị tiền tệ như là 100 k hay $20
     * @param float|string $discount
     * @return string
     */
    public static function showOldPrice($price, $discount) {
        if ($discount == 0) {
            return '&nbsp;&nbsp;';
        }

        $language_id = Yii::$app->session['language_id'];
        $money_unit = $GLOBALS['options']['money-unit-en'];

        if ($language_id == 'en') {
            $money = $money_unit . number_format($price, 2, ".", ",");
        } else {
            $money = number_format($price, 0, ",", ".") . " " . $money_unit;
        }

        return $money;
    }

    /**
     * hiển thị các 5 dấu sao, trong đó bao nhiêu dấu được đánh màu vàng thi tùy vào value của $ranking
     * @param float|string $ranking
     * @return string
     */
    public static function showRanking($ranking) {
        $html = '';

        $temp = explode(".", $ranking);
        $int = $temp[0];
        if (count($temp) == 1) {
            $decimal = 0;
        } else {
            if('0.'.$temp[1]>=0.75){
                $decimal=0;
                $int++;
            }
            else if('0.'.$temp[1]>=0.25){
                $decimal=0.5;
            }
            else{
                $decimal=0;
            }
        }

        for ($i = 0; $i < $int; $i++) {
            $html .= '<span class="one-star"></span>';
        }

        if ($decimal == 0.5) {
            $html .= '<span class="half-star"></span>';
            $int++;
        }

        for ($i = 0; $i < 5 - $int; $i++) {
            $html .= '<span class="no-star"></span>';
        }

        return $html;
    }
    /**
     * hiển thị các 5 dấu sao, trong đó bao nhiêu dấu được đánh màu vàng thi tùy vào value của $ranking
     * @param float|string $ranking
     * @return string
     */
    public static function showRankingAtDetail($ranking) {
        $html = '';

        for ($i = 0; $i < $ranking; $i++) {
            $html .= '<span class="v2-yelow-star"></span>';
        }

        for ($i = 0; $i < 5 - $ranking; $i++) {
            $html .= '<span class="v2-no-star"></span>';
        }

        return $html;
    }
    
    /**
     * lấy rating của từng loại sao (1 sao, 2 sao,...)
     * @param int|string $template_id
     * @return array
     */
    public static function getRatings($template_id) {
        $sql="SELECT value,COUNT(value) AS count FROM tbl_comment "
                . "WHERE "
                    . "template_id=$template_id "
                    . "AND comment_type=".Comment::VOTE." "
                . "GROUP BY value";
        
        $rows=Yii::$app->db->createCommand($sql)->queryAll();        
        
        for($i=5;$i>=1;$i--){
            $count='0';
            foreach ($rows as $row){
                if($row['value']==$i){
                    $count=$row['count'];
                }
            }
            $ratings["$i"]=$count;
        }

        return $ratings;
        
    }
    
    /**
     * lấy thông tin rating mà các user đã rate, thông tin đầy đủ bao gồm value, content
     * @param int|string $template_id
     * @param int|string $limit
     * @param int|string $page
     * @return SqlDataProvider
     */
    public static function getFullRatings($template_id,$limit,$page) {
        $totalCount = Yii::$app->db->createCommand("SELECT COUNT(*) "
                    
                                                            . "FROM tbl_comment "
                                                            . "JOIN tbl_user ON tbl_user.user_id=tbl_comment.user_id "
                                                            . "WHERE "
                                                                . "template_id=$template_id "
                                                                . "AND comment_type=".Comment::VOTE
                )->queryScalar();



        $dataProvider = new SqlDataProvider([
            'sql' => "SELECT "
                    . "tbl_user.avatar,"
                    . "tbl_user.user_path,"
                    . "tbl_user.full_name,"
                    . "tbl_comment.value,"
                    . "tbl_comment.content "
                . "FROM tbl_comment "
                . "JOIN tbl_user ON tbl_user.user_id=tbl_comment.user_id "
                . "WHERE "
                    . "template_id=$template_id "
                    . "AND comment_type=".Comment::VOTE
                ." ORDER BY tbl_comment.update_time DESC"     
                ,
            
            'totalCount' => $totalCount,
            
            'pagination' => [
                'pageSize' => $limit,
                'page' => $page,
            ],
        ]);
        
        $models = $dataProvider->models;

        for ($i = 0; $i < count($models); $i++) {
           $models[$i]['html_ranking']=self::showRankingAtDetail($models[$i]['value']);
        }

        $dataProvider->setModels($models);
        
        return $dataProvider;        
        
        
    }
    /**
     * lấy thông tin câu hỏi và câu trả lời
     * @param int|string $template_id
     * @param int|string $limit
     * @param int|string $page
     * @return SqlDataProvider
     */
    public static function getFullComments($template_id,$limit,$page,&$count_question,&$count_answer) {
        
        $count_question=$totalCount = Yii::$app->db->createCommand("SELECT COUNT(*) "                    
                                                            . "FROM tbl_comment "
                                                            . "JOIN tbl_user ON tbl_user.user_id=tbl_comment.user_id "
                                                            . "WHERE "
                                                                . "template_id=$template_id "
                                                                . "AND tbl_comment.parent_id=0 "
                                                                . "AND comment_type=".Comment::COMMENT
                )->queryScalar();
        
        $count_answer = Yii::$app->db->createCommand("SELECT COUNT(*) "                    
                                                            . "FROM tbl_comment "
                                                            . "JOIN tbl_user ON tbl_user.user_id=tbl_comment.user_id "
                                                            . "WHERE "
                                                                . "template_id=$template_id "
                                                                . "AND tbl_comment.parent_id<>0 "
                                                                . "AND comment_type=".Comment::COMMENT
                )->queryScalar();



        $dataProvider = new SqlDataProvider([
            'sql' => "SELECT "
                        . "tbl_user.avatar,"
                        . "tbl_user.user_path,"
                        . "tbl_user.full_name,"
                        . "tbl_comment.value,"
                        . "tbl_comment.comment_id,"
                        . "tbl_comment.parent_id,"
                        . "tbl_comment.content "
                    . "FROM tbl_comment "
                    . "JOIN tbl_user ON tbl_user.user_id=tbl_comment.user_id "
                    . "WHERE "
                        . "template_id=$template_id "
                        . "AND tbl_comment.parent_id=0 "
                        . "AND comment_type=".Comment::COMMENT
                    ." ORDER BY tbl_comment.update_time DESC"     
                ,
            
            'totalCount' => $totalCount,
            
            'pagination' => [
                'pageSize' => $limit,
                'page' => $page,
            ],
        ]);
        
        $models = $dataProvider->models;
        
        $sql="SELECT "
                    . "tbl_user.avatar,"
                    . "tbl_user.user_path,"
                    . "tbl_user.full_name,"
                    . "tbl_comment.value,"
                    . "tbl_comment.comment_id,"
                    . "tbl_comment.parent_id,"
                    . "tbl_comment.content "
                . "FROM tbl_comment "
                . "JOIN tbl_user ON tbl_user.user_id=tbl_comment.user_id "
                . "WHERE "
                    . "template_id=$template_id "
                    . "AND tbl_comment.parent_id<>0 "
                    . "AND comment_type=".Comment::COMMENT
                ." ORDER BY tbl_comment.update_time DESC" 
                ;
             
        $rows=Yii::$app->db->createCommand($sql)->queryAll();            

        for ($i = 0; $i < count($models); $i++) {
            $answers=array();
            for($j=0;$j<count($rows);$j++){
                if($rows[$j]['parent_id']==$models[$i]['comment_id']){
                    $answers[]=$rows[$j];                    
                }
            }
            $models[$i]['answers']=$answers;            
            
        }

        $dataProvider->setModels($models);
        
        return $dataProvider;  
        
    }

    /**
     * tăng view_count lên một đơn vị cho một template
     * @param int|string $template_id
     * @return void
     */
    public static function increaseTemplateViewCount($template_id) {
        if (!ctype_digit($template_id)) {
            return;
        }


        $sql = "UPDATE tbl_template SET view_count=view_count+1 WHERE template_id='$template_id'";

        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * trước khi login, chọn >=1 template vào giỏ hàng, mà trong số các template có ít nhất 1 template đã mua + mua chưa đủ các dịch vụ con
     * lấy các template này
     */
    public static function getBouthNotFullTemplate() {
        if (!isset(Yii::$app->session['out_ids']) || !is_array(Yii::$app->session['out_ids']) || count(Yii::$app->session['out_ids']) == 0) {
            return array();
        }

        $templates = Yii::$app->db->createCommand(
                        "SELECT DISTINCT "
                        . "tbl_template_detail.title,"
                        . "tbl_template.template_id,"
                        . "tbl_template_detail.num,
                                tbl_template_detail.num2,"
                        . "0 AS price,"
                        . "tbl_template.path,"
                        . "tbl_template.resolution,"
                        . "tbl_template.thumb,"
                        . "tbl_template.video,
                                tbl_template.view_count,
                                tbl_template.cmt_count,
                                tbl_template.ranking,
                                tbl_template.bought_count,"
                        . "tbl_template_detail.content,"
                        . "0 AS main_service,"
                        . "'' AS service_id,"
                        . "'' AS user_id,"
                        . "'' AS order_session_id,"
                        . "'' AS session_id "
                        . "FROM tbl_template "
                        . "LEFT JOIN  tbl_template_detail 
                                    ON tbl_template.template_id = tbl_template_detail.template_id 
                                    AND tbl_template_detail.language_id = '" . Yii::$app->session['language_id'] . "' "
                        . "AND tbl_template_detail.type=1 "
                        . "WHERE tbl_template.template_id IN (" . implode(",", Yii::$app->session['out_ids']) . ")"
                )->queryAll();

        return $templates;
    }

    public function getTemplateDetailProvider($id) {
        $ln = Yii::$app->session['language_id'];

        $uid = "";
        if (strlen(Yii::$app->session['user_id']) > 0)
            $uid = Yii::$app->session['user_id'];

        $dataProvider = new SqlDataProvider([
            'sql' => "SELECT DISTINCT 
							tbl_template.template_id, 
							tbl_template.template_name,
							tbl_template.catalogue_01_id,
							FORMAT(tbl_template.price,0) AS 'price',
							tbl_template.path,
							tbl_template.resolution,
							tbl_template.thumb,
							tbl_template.thumb2,
							tbl_template.video,
                                                        tbl_template.view_count,
                                                        tbl_template.cmt_count,
                                                        tbl_template.ranking,
                                                        tbl_template.bought_count,
							tbl_template.video2,
							tbl_template_detail.title, 
							tbl_template_detail.description, 
							tbl_template_img.img_name,
							tbl_template_img.img_path ,
							tbl_order_detail.template_id AS 'is_bought'
						FROM 
							tbl_template 
								LEFT JOIN  tbl_template_detail ON
									tbl_template.template_id = tbl_template_detail.template_id AND
									tbl_template_detail.language_id = :language_id
								LEFT JOIN tbl_template_img ON
									tbl_template_img.template_id = tbl_template.template_id
								LEFT JOIN tbl_order_detail ON
									tbl_template.template_id = tbl_order_detail.template_id AND
									(tbl_order_detail.user_id = :user_id)
						WHERE
							tbl_template.status = 0 AND 
							tbl_template.template_id = :template_id
						ORDER BY 	
							tbl_template.update_time 
							DESC	
								",
            'params' => [':template_id' => $id,
                ':language_id' => $ln,
                ':user_id' => $uid
            ],
            'sort' => [
                'attributes' => [
                    'tbl_template.update_time',
                ],
            ],
        ]);

        return $dataProvider;
    }

    public static function getTemplateDetailProviderByUrl($url) {
        $ln = Yii::$app->session['language_id'];

        $uid = "";
        if (strlen(Yii::$app->session['user_id']) > 0)
            $uid = Yii::$app->session['user_id'];
        
        $url= str_replace("'", "\'", $url);

        
        
        $sql="SELECT DISTINCT 
							tbl_template.template_id, 
							tbl_template.template_name,
							tbl_template.catalogue_01_id,
                                                        tbl_catalogue.catalogue_name,
							tbl_template_detail.num,
                                                        tbl_template_detail.num2,
							tbl_template.path,
							tbl_template.resolution,
							tbl_template.thumb,
							tbl_template.thumb2,
							tbl_template.video,
                                                        tbl_template.view_count,
                                                        tbl_template.cmt_count,
                                                        tbl_template.ranking,
                                                        tbl_template.bought_count,
							tbl_template.video2,
							tbl_template_detail.title, 
							tbl_template_detail.description,
                                                        tbl_template_detail.content,
							tbl_template_img.img_name,
							tbl_template_img.img_path ,
                                                        tbl_comment.comment_id,
							tbl_order_detail.template_id AS 'is_bought'
						FROM 
							tbl_template 
								LEFT JOIN  tbl_template_detail ON
									tbl_template.template_id = tbl_template_detail.template_id AND
									tbl_template_detail.language_id = '$ln'
								LEFT JOIN tbl_template_img ON
									tbl_template_img.template_id = tbl_template.template_id
								LEFT JOIN tbl_order_detail ON
									tbl_template.template_id = tbl_order_detail.template_id AND
									(tbl_order_detail.user_id = '$uid') 
                                                                LEFT JOIN tbl_catalogue ON
									tbl_catalogue.catalogue_id = tbl_template.catalogue_01_id 
                                                                LEFT JOIN tbl_comment ON
                                                                    tbl_comment.template_id = tbl_template.template_id AND tbl_comment.user_id = '$uid' AND tbl_comment.comment_type=".Comment::VOTE."                                                                    
						WHERE
							tbl_template.status = 0 AND 
							tbl_template_detail.slug = '$url'";
        
        $template= Yii::$app->db->createCommand($sql)->queryOne();
        
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
        $bought_template_rows = Yii::$app->db->createCommand("SELECT DISTINCT tbl_order_detail.out_id,tbl_order.order_status FROM tbl_order_detail JOIN tbl_order ON tbl_order.order_id=tbl_order_detail.order_id AND tbl_order_detail.main_service=1 WHERE tbl_order.user_id='$uid'")->queryAll();
        $bought_template_ids = array();
        $bought_template_order_statuss = array();
        if (is_array($bought_template_rows) && count($bought_template_rows) > 0) {
            foreach ($bought_template_rows as $bought_template_row) {
                $bought_template_ids[] = $bought_template_row['out_id'];
                $bought_template_order_statuss[] = $bought_template_row['order_status'];
            }
            //lấy danh sách chi tiết đơn hàng
            $sql = "SELECT service_id,out_id FROM tbl_order_detail WHERE tbl_order_detail.user_id='$uid' AND tbl_order_detail.out_id IN (" . implode(",", $bought_template_ids) . ") ";
            $order_details = Yii::$app->db->createCommand($sql)->queryAll();
        } else {
            $order_details = array();
        }
        /**
         * lấy danh sách tbl_order_session đưa vào $order_sessions
         * data của array $order_sessions kiểu như thế này
         * key(số)=>value(array). Ví dụ
         * 3=>(2,3) (trong giỏ hàng có 2 dịch vụ mang service_id là 2 và 3, của template có template_id là 3
         * 7=>(4) (trong giỏ hàng có 1 dịch vụ mang service_id là 4, của template có template_id là 7)
         * array $order_sessions này có chức năng để biet được 1 dịch vụ nào đó của template nào đó đã được add vào giỏ hàng chưa, nghĩa là icon của dich vụ đó phai hiển thị là uncheck_icon_path hay checked_icon_path
         */
        $rows = Yii::$app->db->createCommand("SELECT * FROM tbl_order_session WHERE session_id='" . Yii::$app->session['session_id'] . "' ORDER BY service_group_order ASC")->queryAll();
        $order_sessions = array();
        $out_ids = array();
        foreach ($rows as $row) {
            $out_ids[] = $row['out_id'];
        }
        foreach ($out_ids as $out_id) {
            $service_ids = array();
            foreach ($rows as $row) {
                if ($row['out_id'] == $out_id) {
                    $service_ids[] = $row['service_id'];
                }
            }
            $order_sessions[$out_id] = $service_ids;
        }
        
        $template['download']=false;
        /**
         * kiểm tra template này đã được mua hay chưa
         * nếu mua rồi tức là template_id của nó có trong array $bought_template_ids
         * search template_id của nó có trong array $bought_template_ids
         *       nếu có thi xem nó nằm ở vị trí nào trong array, rồi gán vị trí đó vào $index
         *       dựa vào $index này, biet được giá trị của element vị trí $index trong array $bought_template_order_statuss
         *       để biet được đơn hàng đó đã thanh toán hay chưa
         *       vì các element của 2 array này đã nằm đúng thứ tự với nhau
         */
        $index = array_search($template['template_id'], $bought_template_ids);
        if ($index !== false//template này đã được mua
                || in_array($template['template_id'], array_keys($order_sessions))//template này vừa dc thêm vào giỏ hàng     
        ) {
            if (
                ($index !== false && ($bought_template_order_statuss[$index] == '0'||$template['num']=='0'))//đã mua và đã thanh toán thi hiển thị icon download
            ) 
            {
                
                $template['download']=true;
            }
        }
        
        return $template;

        
    }

    /**
     * 
     * @param string|int $catalogue_id
     * @param string|int $template_id , lấy các template cùng dịch vụ nhưng phải khác chính nó (tại trang detail/bo-tai-chanh thi phía dưới hiển thị những template cùng dịch vụ, phai loại trừ template bo-tai-chanh)
     * @return SqlDataProvider
     */
    public function getTemplateProviderByCatalogue($catalogue_id, $template_id,$resolution='1') {
        $ln = Yii::$app->session['language_id'];
        $uid = "";
        if (strlen(Yii::$app->session['user_id']) > 0)
            $uid = Yii::$app->session['user_id'];

        $dataProvider = new SqlDataProvider([
            'sql' => "SELECT DISTINCT
							tbl_template.template_id, 
							tbl_template.template_name,
							tbl_template_detail.num,
                                                        tbl_template_detail.num2,
							tbl_template.path,
							tbl_template.thumb,
							tbl_template.thumb2,
							tbl_template.video,
                                                        tbl_template.view_count,
                                                        tbl_template.cmt_count,
                                                        tbl_template.ranking,
                                                        tbl_template.bought_count,
							tbl_template.resolution,
							tbl_template_detail.title, 
							tbl_template_detail.description,
                                                        tbl_template_detail.slug 
						FROM 
							tbl_template 
								LEFT JOIN  tbl_template_detail ON
									tbl_template.template_id = tbl_template_detail.template_id AND
									tbl_template_detail.language_id = :language_id 								
						WHERE
							tbl_template.status = 0 AND tbl_template.template_id<>$template_id AND tbl_template.resolution=$resolution AND (
								tbl_template.catalogue_01_id = :catalogue_id OR
								tbl_template.catalogue_02_id = :catalogue_id OR
								tbl_template.catalogue_03_id = :catalogue_id ) AND tbl_template_detail.slug<>'' AND tbl_template_detail.slug is not null                                                   
						ORDER BY 	
							tbl_template.update_time 
							DESC	
								",
            'params' => [':catalogue_id' => $catalogue_id,
                ':language_id' => $ln,
            ],
            'sort' => [
                'attributes' => [
                    'tbl_template.update_time',
                ],
            ],
            'pagination' => [
                'pageSize' => 5,
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
        $bought_template_rows = Yii::$app->db->createCommand("SELECT DISTINCT tbl_order_detail.out_id,tbl_order.order_status FROM tbl_order_detail JOIN tbl_order ON tbl_order.order_id=tbl_order_detail.order_id AND tbl_order_detail.main_service=1 WHERE tbl_order.user_id='$uid'")->queryAll();
        $bought_template_ids = array();
        $bought_template_order_statuss = array();
        if (is_array($bought_template_rows) && count($bought_template_rows) > 0) {
            foreach ($bought_template_rows as $bought_template_row) {
                $bought_template_ids[] = $bought_template_row['out_id'];
                $bought_template_order_statuss[] = $bought_template_row['order_status'];
            }
            //lấy danh sách chi tiết đơn hàng
            $sql = "SELECT service_id,out_id FROM tbl_order_detail WHERE tbl_order_detail.user_id='$uid' AND tbl_order_detail.out_id IN (" . implode(",", $bought_template_ids) . ") ";
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
        $rows = Yii::$app->db->createCommand("SELECT * FROM tbl_order_session WHERE session_id='" . Yii::$app->session['session_id'] . "' ORDER BY service_group_order ASC")->queryAll();
        $order_sessions = array();
        $out_ids = array();
        foreach ($rows as $row) {
            $out_ids[] = $row['out_id'];
        }
        foreach ($out_ids as $out_id) {
            $service_ids = array();
            foreach ($rows as $row) {
                if ($row['out_id'] == $out_id) {
                    $service_ids[] = $row['service_id'];
                }
            }
            $order_sessions[$out_id] = $service_ids;
        }
        //
        $models = $dataProvider->models;
        //mỗi model của $dataProvider, thêm một key=>value ('bottom_icon'=>'các icon ví, đã thanh toán, dịch vụ nếu đã mua hoặc chỉ duy nhất icon add-cart nếu chưa mua')
        $lang = MultiLang::viewLang("confirmcart");
        for ($i = 0; $i < count($models); $i++) {
            self::setBottomIcon($models[$i], $bought_template_ids, $bought_template_order_statuss, $services, $order_details, $order_sessions, $lang['cost']);
        }

        $dataProvider->setModels($models);

        return $dataProvider;
    }

    /**
     * thêm một key=>value cho $model ('bottom_icon'=>'các icon ví, đã thanh toán, dịch vụ nếu đã mua hoặc chỉ duy nhất icon add-cart nếu chưa mua')
     * value đó là một đoạn html kiểu như thế này:
     *     nếu đã mua thi kiểu như thế này
     *               <div class="dowload-template"><div class="service-mns_"><a href="javascript:void(0);" class="mns-payment"><img src="/images/mns-payment.png" class="img-responsive"></a></div><div class="service-mns_"><a href="javascript:void(0);" class="mns-service"><img src="/images/mns-service.png" class="img-responsive"></a></div><div class="service-mns_"><a onclick="addOption(11,3);" href="javascript:void(0);" class="mns-service"><img src="/images/icon-edit-mns.png" class="img-responsive"></a></div><div class="service-mns_"><a onclick="addOption(11,4);" href="javascript:void(0);" class="mns-service"><img src="/images/icon-setting-mns.png" class="img-responsive"></a></div></div>
     *     nếu chưa mua thi kiểu như thế này
     *               <div class="add-to-cart"><a onclick="changeValueCart(1);" href="javascript:void(0);" class="add-to-cart1"><img src="/images/icon-cart.png" class="img-responsive"></a></div>
     * 
     * @param array $model (chứa thông tin của một template)
     * @param array $bought_template_ids (chứa danh sách các template_id của các template mà user đã mua, nếu chưa đăng nhập hoặc đăng nhập vào rồi nhưng user này chưa mua template nào thi array này rỗng)
     * @param array $bought_template_order_statuss (chứa danh sách các order_status của các template mà user đã mua, nếu chưa đăng nhập hoặc đăng nhập vào rồi nhưng user này chưa mua template nào thi array này rỗng)
     * @param array $services (chứa danh sách các dịch vụ mà k phai main_service, lấy từ table tbl_service)
     * @param array $order_details (chứa danh sách chi tiết đơn hàng mà user đã mua, nếu chưa đăng nhập hoặc đăng nhập vào rồi nhưng user này chưa mua template nào thi array này rỗng)
     * @param array $order_sessions
     *        data của array $order_sessions kiểu như thế này
     *        key(số)=>value(array). Ví dụ
     *        3=>(2,3) (trong giỏ hàng có 2 dịch vụ mang service_id là 2 và 3, của template có template_id là 3
     *        7=>(4) (trong giỏ hàng có 1 dịch vụ mang service_id là 4, của template có template_id là 7)
     *        array $order_sessions này có chức năng để biet được 1 dịch vụ nào đó của template nào đó đã được add vào giỏ hàng chưa, nghĩa là icon của dich vụ đó phai hiển thị là uncheck_icon_path hay checked_icon_path
     * 
     * các element của 2 array $bought_template_ids, $bought_template_order_statuss  phai nằm đúng thứ tự với nhau
     *       để biet được chính xác template đó nằm ở đơn hàng nào và đơn hàng đó đã được thanh toán hay chưa

     */
    public static function setBottomIcon(&$model, $bought_template_ids, $bought_template_order_statuss, $services, $order_details, $order_sessions, $lang_cost) {
        $model['download']=false;
        $payment_download='payment';
        /**
         * kiểm tra template này đã được mua hay chưa
         * nếu mua rồi tức là template_id của nó có trong array $bought_template_ids
         * search template_id của nó có trong array $bought_template_ids
         *       nếu có thi xem nó nằm ở vị trí nào trong array, rồi gán vị trí đó vào $index
         *       dựa vào $index này, biet được giá trị của element vị trí $index trong array $bought_template_order_statuss
         *       để biet được đơn hàng đó đã thanh toán hay chưa
         *       vì các element của 2 array này đã nằm đúng thứ tự với nhau
         */
        $index = array_search($model['template_id'], $bought_template_ids);
        if ($index !== false//template này đã được mua
                || in_array($model['template_id'], array_keys($order_sessions))//template này vừa dc thêm vào giỏ hàng     
        ) {
            if (
                ($index !== false && ($bought_template_order_statuss[$index] == '0'||$model['num']=='0'))//đã mua và đã thanh toán thi hiển thị icon download
            ) 
            {
                $link = "/download?id=" . $model['template_id'];
                $image = "/images/allow_download.png";
                $class = "mns-dowload-tl";
                $model['download']=true;
                $payment_download='download';
            } else {//chưa thanh toán thi hiển thị icon cái ví
                $link = "javascript:void(0);";
                $image = "/images/mns-payment.png";
                $class = "mns-payment";
            }
            $class = '';
            $bottom_icon = '<div class="dowload-template' . ($model['resolution'] == '2' ? '-portrait' : '') . '">'
                    . '<div class="v2-service-mns' . ($model['resolution'] == '2' ? '-portrait' : '') . '">'
                    . '<a href="' . $link . '" class="' . $class . '"><img src="' . $image . '" class="img-responsive">'.(((Yii::$app->controller->action->id=='detail'&&$model['resolution'] == '2')||(Yii::$app->controller->action->id=='plaza'&&$model['resolution'] == '2'))?'<i>('.$payment_download.')</i>':'').'</a>';

            foreach ($services as $service) {
                /**
                 * dựa vào danh sách chi tiết đơn hàng ($order_details), 
                 * xác định dịch vụ này ứng với template hiện tại ($models[$i]['template_id']) 
                 * đã được mua hay chưa
                 */
                $has_service = FALSE;
                foreach ($order_details as $order_detail) {
                    if (
                            $order_detail['service_id'] == $service['service_id']//nếu đúng dịch vụ 
                            && $order_detail['out_id'] == $model['template_id']//và là thuộc template này
                    ) {
                        $has_service = true;
                        break;
                    }
                }
                /**
                 * nếu đã mua thi hiển thị icon đã mua (bought_icon_path) và k bật sự kiện click cho dịch vụ này
                 * còn chưa mua thi hiển thị icon chưa mua (uncheck_icon_path) và bật sự kiện click cho dịch vụ này (click thi được thêm vào giỏ hàng)
                 */
                if ($has_service) {//dịch vụ này đã mua
                    $icon = "/" . $service['bought_icon_path'];
                    $onclick = "";
                    $check_service_class = "";
                } else {//dịch vụ này chưa mua
                    $icon = "/" . $service['uncheck_icon_path'];
                    $check_service_class = "";
                    /**
                     * nếu dịch vụ này đã được add vào giỏ hàng rồi thi icon của nó phải được check (là checked_icon_path chứ k phai là uncheck_icon_path)
                     */
                    if (array_search($model['template_id'], array_keys($order_sessions)) !== FALSE) {//nếu trong tbl_order_session đã có 1 out_id là template này
                        if (in_array($service['service_id'], $order_sessions[$model['template_id']])) {//nếu trong tbl_order_session trong có service_id trùng với dịch vụ này ứng với template này
                            $icon = "/" . $service['checked_icon_path'];
                            $check_service_class = " check-mns-service";
                        }
                    }

                    $onclick = " onclick=addOption(" . $model['template_id'] . "," . $service['service_id'] . ",'/" . $service['checked_icon_path'] . "','/" . $service['uncheck_icon_path'] . "',this);";
                }
                /**
                 * khi rê chuột vào các icon dịch vụ nằm dưới mỗi template
                 * thi phai hiển thị nội dung chi tiết của dịch vụ này (title)
                 * bay giờ lấy thông tin đó gán vào $data_tipso để đưa vào attr data-tipso cho mỗi icon
                 */
                $data_tipso = '<div>
                                    <strong>' . $service['service_name'] . '</strong>
                                    <p>' . $service['description'] . '</p>                                                            
                                    <strong>' . $lang_cost . '&nbsp;' . LayoutService::showServiceMoney($service['price']) . '</strong>
                                </div>';

                if (7 > strlen(utf8_decode($service['service_name']))) {
                    $string = strtolower($service['service_name']);
                } else {
                    $string_cop = mb_substr($service['service_name'], 0,7,'UTF-8'); 
                    $string = strtolower($string_cop . "...");
                }
                
                $bottom_icon .= '<a class="bottom tipso_style" data-tipso="' . $data_tipso . '"' . $onclick . ' href="javascript:void(0);"><img src="' . $icon . '" class="img-responsive">'.(((Yii::$app->controller->action->id=='detail'&&$model['resolution'] == '2')||(Yii::$app->controller->action->id=='plaza'&&$model['resolution'] == '2'))?'<i>('.$string.')</i>':'').'</a>';
            }
            $bottom_icon .= '</div>'
                    . '</div>';
            $model['bottom_icon'] = $bottom_icon;
        } else {//template này chưa được mua
            if ($model['resolution'] == '1') {
                $model['bottom_icon'] = '<div class="add-to-cart"><a onclick="changeValueCart(' . $model['template_id'] . ',this);" href="javascript:void(0);" class="add-to-cart1"><img src="/images/v2-add-cart.png" class="img-responsive"></i></a></div>';
            } else {
                $model['bottom_icon'] = '<div class="v2-name-price v2-margin-home-p">
                                                <div class="v2-right-n-p-p">
                                                    <span' . (Template::showOldPrice($model['num'], $model['num2']) != '&nbsp;&nbsp;' ? ' class="v2-cost-p"' : '') . '>' . Template::showOldPrice($model['num'], $model['num2']) . '</span>
                                                    <span class="v2-cost-sale-p">' . Template::showNewPrice($model['num'], $model['num2']) . '</span>
                                                </div>
                                            </div>'
                        . '<div class="add-to-cart-p"><a onclick="changeValueCart(' . $model['template_id'] . ',this);" href="javascript:void(0);" class="add-to-cart1"><img src="/images/v2-add-cart.png" class="img-responsive"></i></a></div>';
            }
        }
    }

    /**
     * thêm một key=>value cho $model ('bottom_icon'=>'các icon ví, đã thanh toán, dịch vụ nếu đã mua hoặc chỉ duy nhất icon add-cart nếu chưa mua')
     * value đó là một đoạn html kiểu như thế này:
     *     nếu đã mua thi kiểu như thế này
     *               <div class="dowload-template"><div class="service-mns_"><a href="javascript:void(0);" class="mns-payment"><img src="/images/mns-payment.png" class="img-responsive"></a></div><div class="service-mns_"><a href="javascript:void(0);" class="mns-service"><img src="/images/mns-service.png" class="img-responsive"></a></div><div class="service-mns_"><a onclick="addOption(11,3);" href="javascript:void(0);" class="mns-service"><img src="/images/icon-edit-mns.png" class="img-responsive"></a></div><div class="service-mns_"><a onclick="addOption(11,4);" href="javascript:void(0);" class="mns-service"><img src="/images/icon-setting-mns.png" class="img-responsive"></a></div></div>
     *     nếu chưa mua thi kiểu như thế này
     *               <div class="add-to-cart"><a onclick="changeValueCart(1);" href="javascript:void(0);" class="add-to-cart1"><img src="/images/icon-cart.png" class="img-responsive"></a></div>
     * 
     * @param array $model (chứa thông tin của một template)
     * @param array $bought_template_ids (chứa danh sách các template_id của các template mà user đã mua, nếu chưa đăng nhập hoặc đăng nhập vào rồi nhưng user này chưa mua template nào thi array này rỗng)
     * @param array $bought_template_order_statuss (chứa danh sách các order_status của các template mà user đã mua, nếu chưa đăng nhập hoặc đăng nhập vào rồi nhưng user này chưa mua template nào thi array này rỗng)
     * @param array $services (chứa danh sách các dịch vụ mà k phai main_service, lấy từ table tbl_service)
     * @param array $order_details (chứa danh sách chi tiết đơn hàng mà user đã mua, nếu chưa đăng nhập hoặc đăng nhập vào rồi nhưng user này chưa mua template nào thi array này rỗng)
     * @param array $order_sessions
     *        data của array $order_sessions kiểu như thế này
     *        key(số)=>value(array). Ví dụ
     *        3=>(2,3) (trong giỏ hàng có 2 dịch vụ mang service_id là 2 và 3, của template có template_id là 3
     *        7=>(4) (trong giỏ hàng có 1 dịch vụ mang service_id là 4, của template có template_id là 7)
     *        array $order_sessions này có chức năng để biet được 1 dịch vụ nào đó của template nào đó đã được add vào giỏ hàng chưa, nghĩa là icon của dich vụ đó phai hiển thị là uncheck_icon_path hay checked_icon_path
     * 
     * các element của 2 array $bought_template_ids, $bought_template_order_statuss  phai nằm đúng thứ tự với nhau
     *       để biet được chính xác template đó nằm ở đơn hàng nào và đơn hàng đó đã được thanh toán hay chưa

     */
    public static function setBottomIconForListMode(&$model, $bought_template_ids, $bought_template_order_statuss, $services, $order_details, $order_sessions, $lang_cost) {
        $model['download']=false;
        /**
         * kiểm tra template này đã được mua hay chưa
         * nếu mua rồi tức là template_id của nó có trong array $bought_template_ids
         * search template_id của nó có trong array $bought_template_ids
         *       nếu có thi xem nó nằm ở vị trí nào trong array, rồi gán vị trí đó vào $index
         *       dựa vào $index này, biet được giá trị của element vị trí $index trong array $bought_template_order_statuss
         *       để biet được đơn hàng đó đã thanh toán hay chưa
         *       vì các element của 2 array này đã nằm đúng thứ tự với nhau
         */
        $index = array_search($model['template_id'], $bought_template_ids);
        if ($index !== false//template này đã được mua
                || in_array($model['template_id'], array_keys($order_sessions))//template này vừa dc thêm vào giỏ hàng     
        ) {
            if (
                ($index !== false && ($bought_template_order_statuss[$index] == '0'||$model['num']=='0'))//đã mua và đã thanh toán thi hiển thị icon download
            ) 
            {
                $link = "/download?id=" . $model['template_id'];                
                $image = "/images/allow_download.png";
                $class = "mns-dowload-tl";
                $model['download']=true;
            } else {//chưa thanh toán thi hiển thị icon cái ví
                $link = "javascript:void(0);";
                $image = "/images/mns-payment.png";
                $class = "mns-payment";
            }
            $class = '';
            $bottom_icon = '<div class="v2-service-mns">'
                    . '<a href="' . $link . '"><img src="' . $image . '" class="img-responsive"></a>';

            foreach ($services as $service) {
                /**
                 * dựa vào danh sách chi tiết đơn hàng ($order_details), 
                 * xác định dịch vụ này ứng với template hiện tại ($models[$i]['template_id']) 
                 * đã được mua hay chưa
                 */
                $has_service = FALSE;
                foreach ($order_details as $order_detail) {
                    if (
                            $order_detail['service_id'] == $service['service_id']//nếu đúng dịch vụ 
                            && $order_detail['out_id'] == $model['template_id']//và là thuộc template này
                    ) {
                        $has_service = true;
                        break;
                    }
                }
                /**
                 * nếu đã mua thi hiển thị icon đã mua (bought_icon_path) và k bật sự kiện click cho dịch vụ này
                 * còn chưa mua thi hiển thị icon chưa mua (uncheck_icon_path) và bật sự kiện click cho dịch vụ này (click thi được thêm vào giỏ hàng)
                 */
                if ($has_service) {//dịch vụ này đã mua
                    $icon = "/" . $service['bought_icon_path'];
                    $onclick = "";
                    $check_service_class = "";
                } else {//dịch vụ này chưa mua
                    $icon = "/" . $service['uncheck_icon_path'];
                    $check_service_class = "";
                    /**
                     * nếu dịch vụ này đã được add vào giỏ hàng rồi thi icon của nó phải được check (là checked_icon_path chứ k phai là uncheck_icon_path)
                     */
                    if (array_search($model['template_id'], array_keys($order_sessions)) !== FALSE) {//nếu trong tbl_order_session đã có 1 out_id là template này
                        if (in_array($service['service_id'], $order_sessions[$model['template_id']])) {//nếu trong tbl_order_session trong có service_id trùng với dịch vụ này ứng với template này
                            $icon = "/" . $service['checked_icon_path'];
                            $check_service_class = " check-mns-service";
                        }
                    }

                    $onclick = " onclick=addOption(" . $model['template_id'] . "," . $service['service_id'] . ",'/" . $service['checked_icon_path'] . "','/" . $service['uncheck_icon_path'] . "',this);";
                }
                /**
                 * khi rê chuột vào các icon dịch vụ nằm dưới mỗi template
                 * thi phai hiển thị nội dung chi tiết của dịch vụ này (title)
                 * bay giờ lấy thông tin đó gán vào $data_tipso để đưa vào attr data-tipso cho mỗi icon
                 */
                $data_tipso = '<div>
                                    <strong>' . $service['service_name'] . '</strong>
                                    <p>' . $service['description'] . '</p>                                                            
                                    <strong>' . $lang_cost . '&nbsp;' . LayoutService::showServiceMoney($service['price']) . '</strong>
                                </div>';

                $bottom_icon .= '<a class="bottom tipso_style" data-tipso="' . $data_tipso . '"' . $onclick . ' href="javascript:void(0);"><img src="' . $icon . '" class="img-responsive"></a>';
            }
            $bottom_icon .= '</div>';

            $model['bottom_icon'] = $bottom_icon;
        } else {//template này chưa được mua
            $model['bottom_icon'] = '<a href="/detail/' . $model['slug'] . '"><img src="/images/v2-icon-readmore.png" class="img-responsive"></a>'
                    . '<a onclick="changeValueCart1(' . $model['template_id'] . ',this);" href="javascript:void(0);" class="add-to-cart1"><img src="/images/v2-add-cart.png" class="img-responsive"></a>';
        }
    }

    //Tạo danh sách các template dự theo các Catalogue
    //Trả về một array là tập các provider
    public static function getListTemplate($limit, $params, $page) {
        /**
         * $params là một array rỗng hoặc có data, nếu có thi bao gồm một hoặc nhiều cặp key=>vaue như thế này:
         *      'catalogue_id'=>'1','view'=>'grid', 'filter'=>'new_post',...
         *      data có được như vay là do user click 1 link, sau đó click thêm link khác, rồi link khác nữa,... tại vùng search của trang plaza
         * dựa vào data như vay thi sẽ set where và ORDER BY cho câu sql
         */
        $where = '1=1';
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



        $ln = Yii::$app->session['language_id'];

        $uid = "";
        if (strlen(Yii::$app->session['user_id']) > 0)
            $uid = Yii::$app->session['user_id'];


        //Lấy Show-all catalogue
        $key = $params['key'];
        $key1 = str_replace("'", "\'", $key);
        $totalCount = Yii::$app->db->createCommand("
					SELECT 
						COUNT(DISTINCT tbl_template.template_id) 
					FROM 
						tbl_template JOIN  tbl_template_detail ON
                                                    tbl_template.template_id = tbl_template_detail.template_id AND
                                                    tbl_template_detail.language_id = '$ln'
					WHERE
						tbl_template.status = 0 AND $where AND tbl_template_detail.slug<>'' AND tbl_template_detail.slug is not null 
                                                AND (
                                                        tbl_template_detail.title like '%$key1%' OR  
                                                        tbl_template_detail.keywork like '%$key1%'"
                        . "
                                                )    
                                        "
                )->queryScalar();



        $dataProvider = new SqlDataProvider([
            'sql' => "SELECT DISTINCT 
							tbl_template.template_id, 
							tbl_template.template_name,
							tbl_template_detail.num,
                                                        tbl_template_detail.num2,
							tbl_template.resolution,
							tbl_template.path,
							tbl_template.thumb,
							tbl_template.thumb2,
							tbl_template.video,
                                                        tbl_template.view_count,
                                                        tbl_template.cmt_count,
                                                        tbl_template.ranking,
                                                        tbl_template.bought_count,
							tbl_template_detail.title, 
							tbl_template_detail.content, 
                                                        tbl_template_detail.slug,
                                                        tbl_template.catalogue_01_id,
                                                        tbl_template.catalogue_02_id,
                                                        tbl_template.catalogue_03_id 
						FROM 
							tbl_template JOIN  tbl_template_detail ON
								tbl_template.template_id = tbl_template_detail.template_id AND
								tbl_template_detail.language_id = :language_id 
						WHERE
							tbl_template.status = 0 AND $where AND tbl_template_detail.slug<>'' AND tbl_template_detail.slug is not null 
                                                            AND 
                                                                (
                                                                        tbl_template_detail.title like :key OR  
                                                                        tbl_template_detail.keywork like :key OR                                                                           
                                                                        :key = ''" . "
                                                                ) 
						$order	
								",
            'params' => [':language_id' => $ln,
                ':user_id' => $uid,
                ':key' => "%" . $key . "%"
            ],
            'totalCount' => $totalCount,
            'sort' => [
                'attributes' => [
                    'tbl_template.update_time',
                ],
            ],
            'pagination' => [
                'pageSize' => $limit,
                'page' => ($params['catalogue_id'] == '-1' ? $page : 0),
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
        $bought_template_rows = Yii::$app->db->createCommand("SELECT DISTINCT tbl_order_detail.out_id,tbl_order.order_status FROM tbl_order_detail JOIN tbl_order ON tbl_order.order_id=tbl_order_detail.order_id AND tbl_order_detail.main_service=1 WHERE tbl_order.user_id='$uid'")->queryAll();
        $bought_template_ids = array();
        $bought_template_order_statuss = array();
        if (is_array($bought_template_rows) && count($bought_template_rows) > 0) {
            foreach ($bought_template_rows as $bought_template_row) {
                $bought_template_ids[] = $bought_template_row['out_id'];
                $bought_template_order_statuss[] = $bought_template_row['order_status'];
            }
            //lấy danh sách chi tiết đơn hàng
            $sql = "SELECT service_id,out_id FROM tbl_order_detail WHERE tbl_order_detail.user_id='$uid' AND tbl_order_detail.out_id IN (" . implode(",", $bought_template_ids) . ") ";
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
        $rows = Yii::$app->db->createCommand("SELECT * FROM tbl_order_session WHERE session_id='" . Yii::$app->session['session_id'] . "' ORDER BY service_group_order ASC")->queryAll();
        $order_sessions = array();
        $out_ids = array();
        foreach ($rows as $row) {
            $out_ids[] = $row['out_id'];
        }
        foreach ($out_ids as $out_id) {
            $service_ids = array();
            foreach ($rows as $row) {
                if ($row['out_id'] == $out_id) {
                    $service_ids[] = $row['service_id'];
                }
            }
            $order_sessions[$out_id] = $service_ids;
        }
        //mỗi model của $dataProvider trong array $listCatalogueProvider, thêm một key=>value ('bottom_icon'=>'các icon ví, đã thanh toán, dịch vụ nếu đã mua hoặc chỉ duy nhất icon add-cart nếu chưa mua')


        $lang = MultiLang::viewLang("confirmcart");
        $models = $dataProvider->models;

        for ($i = 0; $i < count($models); $i++) {
            if ($params['view'] == 'grid')
                Template::setBottomIcon($models[$i], $bought_template_ids, $bought_template_order_statuss, $services, $order_details, $order_sessions, $lang['cost']);
            else
                Template::setBottomIconForListMode($models[$i], $bought_template_ids, $bought_template_order_statuss, $services, $order_details, $order_sessions, $lang['cost']);
        }

        $dataProvider->setModels($models);
        return $dataProvider;
    }

    public static function getListTemplateForHome($resolution, $key, $limit = 9) {

        $ln = Yii::$app->session['language_id'];

        $key1 = str_replace("'", "\'", $key);
        $totalCount = Yii::$app->db->createCommand("
					SELECT 
						COUNT(DISTINCT tbl_template.template_id) 
					FROM 
						tbl_template JOIN  tbl_template_detail ON
                                                    tbl_template.template_id = tbl_template_detail.template_id AND
                                                    tbl_template_detail.language_id = '$ln'
					WHERE
						tbl_template.resolution=$resolution 
                                                    AND tbl_template.status = 0 
                                                    AND tbl_template_detail.slug<>'' 
                                                    AND tbl_template_detail.slug is not null 
                                                    AND (
                                                        tbl_template_detail.title like '%$key1%' OR  
                                                        tbl_template_detail.keywork like '%$key1%'                                                       
                                                    )    
                                        "
                )->queryScalar();


        $dataProvider = new SqlDataProvider([
            'sql' => "SELECT DISTINCT 
							tbl_template.template_id, 
							tbl_template.template_name,
							tbl_template_detail.num,
                                                        tbl_template_detail.num2,
							tbl_template.resolution,
                                                        tbl_template.catalogue_01_id,
                                                        tbl_template.catalogue_02_id,
                                                        tbl_template.catalogue_03_id,
							tbl_template.path,
							tbl_template.thumb,
							tbl_template.thumb2,
							tbl_template.video,
                                                        tbl_template.view_count,
                                                        tbl_template.cmt_count,
                                                        tbl_template.ranking,
                                                        tbl_template.bought_count,
							tbl_template_detail.title, 
							tbl_template_detail.description, 
                                                        tbl_template_detail.slug,
							'' AS short_name 
						FROM 
							tbl_template JOIN  tbl_template_detail ON
								tbl_template.template_id = tbl_template_detail.template_id AND
								tbl_template_detail.language_id = :language_id 
						WHERE
							tbl_template.resolution=$resolution 
                                                        AND tbl_template.status = 0 
                                                        AND tbl_template_detail.slug<>'' 
                                                        AND tbl_template_detail.slug is not null 
                                                        AND 
                                                            (
                                                                    tbl_template_detail.title like :key OR  
                                                                    tbl_template_detail.keywork like :key OR                                                                           
                                                                    :key = ''" . "
                                                            ) 
                                                            
						ORDER BY 	
							tbl_template.update_time 
							DESC	
								",
            'params' => [':language_id' => $ln,
                ':key' => "%" . $key . "%"
            ],
            'totalCount' => $totalCount,
            'sort' => [
                'attributes' => [
                    'tbl_template.update_time',
                ],
            ],
            'pagination' => [
                'pageSize' => $limit,
                'page' => 0,
            ],
        ]);

        $uid = "";
        if (strlen(Yii::$app->session['user_id']) > 0)
            $uid = Yii::$app->session['user_id'];

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
        $bought_template_rows = Yii::$app->db->createCommand("SELECT DISTINCT tbl_order_detail.out_id,tbl_order.order_status FROM tbl_order_detail JOIN tbl_order ON tbl_order.order_id=tbl_order_detail.order_id AND tbl_order_detail.main_service=1 WHERE tbl_order.user_id='$uid'")->queryAll();
        $bought_template_ids = array();
        $bought_template_order_statuss = array();
        if (is_array($bought_template_rows) && count($bought_template_rows) > 0) {
            foreach ($bought_template_rows as $bought_template_row) {
                $bought_template_ids[] = $bought_template_row['out_id'];
                $bought_template_order_statuss[] = $bought_template_row['order_status'];
            }
            //lấy danh sách chi tiết đơn hàng
            $sql = "SELECT service_id,out_id FROM tbl_order_detail WHERE tbl_order_detail.user_id='$uid' AND tbl_order_detail.out_id IN (" . implode(",", $bought_template_ids) . ") ";
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
        $rows = Yii::$app->db->createCommand("SELECT * FROM tbl_order_session WHERE session_id='" . Yii::$app->session['session_id'] . "' ORDER BY service_group_order ASC")->queryAll();
        $order_sessions = array();
        $out_ids = array();
        foreach ($rows as $row) {
            $out_ids[] = $row['out_id'];
        }
        foreach ($out_ids as $out_id) {
            $service_ids = array();
            foreach ($rows as $row) {
                if ($row['out_id'] == $out_id) {
                    $service_ids[] = $row['service_id'];
                }
            }
            $order_sessions[$out_id] = $service_ids;
        }
        //mỗi model của $dataProvider trong array $listCatalogueProvider, thêm một key=>value ('bottom_icon'=>'các icon ví, đã thanh toán, dịch vụ nếu đã mua hoặc chỉ duy nhất icon add-cart nếu chưa mua')


        $lang = MultiLang::viewLang("confirmcart");
        $models = $dataProvider->models;

        for ($j = 0; $j < count($models); $j++) {
            Template::setBottomIcon($models[$j], $bought_template_ids, $bought_template_order_statuss, $services, $order_details, $order_sessions, $lang['cost']);
        }
        $dataProvider->setModels($models);

        return $dataProvider;
    }

    public static function getListTemplateFilterRanking($limit, $where, $key, $page, $catalogue_id) {
        $lang = MultiLang::viewLang("plaza");
        $label_all = $lang['label_all'];

        $ln = Yii::$app->session['language_id'];

        $uid = "";
        if (strlen(Yii::$app->session['user_id']) > 0)
            $uid = Yii::$app->session['user_id'];

        $listCatalogue = Catalogue::getCatalogueList($ln);

        $l = sizeOf($listCatalogue);
        $listCatalogueProvider = array();
        $j = 0;

        //Lấy Show-all catalogue
        $key1 = str_replace("'", "\'", $key);
        $totalCount = Yii::$app->db->createCommand("
                                    SELECT 
                                            COUNT(DISTINCT tbl_template.template_id) 
                                    FROM 
                                            tbl_template JOIN  tbl_template_detail ON
                                                tbl_template.template_id = tbl_template_detail.template_id AND
                                                tbl_template_detail.language_id = '$ln'
                                    WHERE
                                            tbl_template.status = 0 AND $where AND tbl_template_detail.slug<>'' AND tbl_template_detail.slug is not null 
                                            AND (
                                                    tbl_template_detail.title like '%$key1%' OR  
                                                    tbl_template_detail.keywork like '%$key1%'"
                        . "
                                            )    
                                    "
                )->queryScalar();


        $dataProvider = new SqlDataProvider([
            'sql' => "SELECT DISTINCT 
                                                    tbl_template.template_id, 
                                                    tbl_template.template_name,
                                                    tbl_template_detail.num,
                                                    tbl_template_detail.num2,
                                                    tbl_template.resolution,
                                                    tbl_template.path,
                                                    tbl_template.thumb,
                                                    tbl_template.thumb2,
                                                    tbl_template.video,
                                                        tbl_template.view_count,
                                                        tbl_template.cmt_count,
                                                        tbl_template.ranking,
                                                        tbl_template.bought_count,
                                                    tbl_template_detail.title, 
                                                    tbl_template_detail.description, 
                                                    tbl_template_detail.slug,
                                                    '$label_all' AS short_name 
                                            FROM 
                                                    tbl_template JOIN  tbl_template_detail ON
                                                            tbl_template.template_id = tbl_template_detail.template_id AND
                                                            tbl_template_detail.language_id = :language_id 
                                            WHERE
                                                    tbl_template.status = 0 AND $where AND tbl_template_detail.slug<>'' AND tbl_template_detail.slug is not null 
                                                        AND 
                                                            (
                                                                    tbl_template_detail.title like :key OR  
                                                                    tbl_template_detail.keywork like :key OR                                                                           
                                                                    :key = ''" . "
                                                            )
                                            ORDER BY 	
                                                    tbl_template.ranking 
                                                    DESC	
                                                            ",
            'params' => [':language_id' => $ln,
                ':user_id' => $uid,
                ':key' => "%" . $key . "%"
            ],
            'totalCount' => $totalCount,
            'sort' => [
                'attributes' => [
                    'tbl_template.ranking',
                ],
            ],
            'pagination' => [
                'pageSize' => $limit,
                'page' => ($catalogue_id == null ? $page : 0),
            ],
        ]);

        $listCatalogueProvider[0] = $dataProvider;

        $j = 1;
        //Lấy thông tin template theo từng catalogue
        foreach ($listCatalogue as $catalogue) {
            $totalCount = Yii::$app->db->createCommand("
                                            SELECT 
                                                    COUNT(DISTINCT tbl_template.template_id) 
                                            FROM 
                                                    tbl_template JOIN  tbl_template_detail ON
                                                        tbl_template.template_id = tbl_template_detail.template_id AND
                                                        tbl_template_detail.language_id = '$ln'
                                            WHERE
                                                    tbl_template.status = 0 AND $where AND tbl_template_detail.slug<>'' AND tbl_template_detail.slug is not null 
                                                    AND (
                                                            tbl_template_detail.title like '%$key1%' OR  
                                                            tbl_template_detail.keywork like '%$key1%'"
                            . "
                                                    ) 
                                                    AND (
                                                    catalogue_01_id=" . $catalogue['catalogue_id'] . " OR 
                                                    catalogue_02_id=" . $catalogue['catalogue_id'] . " OR 
                                                    catalogue_03_id=" . $catalogue['catalogue_id'] . ")"
                    )->queryScalar();


            $dataProvider = new SqlDataProvider([
                'sql' => "SELECT  DISTINCT
                                                            tbl_template.template_id, 
                                                            tbl_template.template_name,
                                                            tbl_template_detail.num AS 'price',
                                                            tbl_template.resolution,
                                                            tbl_template.path,
                                                            tbl_template.thumb,
                                                            tbl_template.thumb2,
                                                            tbl_template.video,
                                                        tbl_template.view_count,
                                                        tbl_template.cmt_count,
                                                        tbl_template.ranking,
                                                        tbl_template.bought_count,
                                                            tbl_template_detail.title, 
                                                            tbl_template_detail.description,
                                                            tbl_template_detail.slug,
                                                            tbl_order_detail.template_id AS 'is_bought', 
                                                            '" . $catalogue['catalogue_name'] . "' AS short_name,
                                                             " . $catalogue['catalogue_id'] . " AS catalogue_id" . "    
                                                    FROM 
                                                            tbl_template join  tbl_template_detail on
                                                                    tbl_template.template_id = tbl_template_detail.template_id AND
                                                                    tbl_template_detail.language_id = :language_id
                                                            LEFT JOIN tbl_order_detail ON
                                                                    tbl_template.template_id = tbl_order_detail.template_id AND
                                                                    (tbl_order_detail.user_id = :user_id OR :user_id = '')
                                                    WHERE
                                                            tbl_template.status = 0 AND $where AND (
                                                            tbl_template.catalogue_01_id = :catalogue_id OR
                                                            tbl_template.catalogue_02_id = :catalogue_id OR
                                                            tbl_template.catalogue_03_id = :catalogue_id ) AND tbl_template_detail.slug<>'' AND tbl_template_detail.slug is not null 
                                                            AND 
                                                                (
                                                                        tbl_template_detail.title like :key OR  
                                                                        tbl_template_detail.keywork like :key OR                                                                             
                                                                        :key = ''" . "
                                                                )
                                                    ORDER BY 	
                                                            tbl_template.ranking 
                                                            DESC	
                                                                    ",
                'params' => [':catalogue_id' => $catalogue['catalogue_id'],
                    ':language_id' => $ln,
                    ':user_id' => $uid,
                    ':key' => "%" . $key . "%"
                ],
                'totalCount' => $totalCount,
                'sort' => [
                    'attributes' => [
                        'tbl_template.ranking',
                    ],
                ],
                'pagination' => [
                    'pageSize' => $limit,
                    'page' => ($catalogue_id == $catalogue['catalogue_id'] ? $page : 0),
                ],
            ]);

            $listCatalogueProvider[$j] = $dataProvider;
            $j++;
        }
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
        $bought_template_rows = Yii::$app->db->createCommand("SELECT DISTINCT tbl_order_detail.out_id,tbl_order.order_status FROM tbl_order_detail JOIN tbl_order ON tbl_order.order_id=tbl_order_detail.order_id AND tbl_order_detail.main_service=1 WHERE tbl_order.user_id='$uid'")->queryAll();
        $bought_template_ids = array();
        $bought_template_order_statuss = array();
        if (is_array($bought_template_rows) && count($bought_template_rows) > 0) {
            foreach ($bought_template_rows as $bought_template_row) {
                $bought_template_ids[] = $bought_template_row['out_id'];
                $bought_template_order_statuss[] = $bought_template_row['order_status'];
            }
            //lấy danh sách chi tiết đơn hàng
            $sql = "SELECT service_id,out_id FROM tbl_order_detail WHERE tbl_order_detail.user_id='$uid' AND tbl_order_detail.out_id IN (" . implode(",", $bought_template_ids) . ") ";
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
        $rows = Yii::$app->db->createCommand("SELECT * FROM tbl_order_session WHERE session_id='" . Yii::$app->session['session_id'] . "' ORDER BY service_group_order ASC")->queryAll();
        $order_sessions = array();
        $out_ids = array();
        foreach ($rows as $row) {
            $out_ids[] = $row['out_id'];
        }
        foreach ($out_ids as $out_id) {
            $service_ids = array();
            foreach ($rows as $row) {
                if ($row['out_id'] == $out_id) {
                    $service_ids[] = $row['service_id'];
                }
            }
            $order_sessions[$out_id] = $service_ids;
        }
        //mỗi model của $dataProvider trong array $listCatalogueProvider, thêm một key=>value ('bottom_icon'=>'các icon ví, đã thanh toán, dịch vụ nếu đã mua hoặc chỉ duy nhất icon add-cart nếu chưa mua')


        $lang = MultiLang::viewLang("confirmcart");
        for ($i = 0; $i < count($listCatalogueProvider); $i++) {
            $models = $listCatalogueProvider[$i]->models;
            for ($j = 0; $j < count($models); $j++) {
                Template::setBottomIcon($models[$j], $bought_template_ids, $bought_template_order_statuss, $services, $order_details, $order_sessions, $lang['cost']);
            }
            $listCatalogueProvider[$i]->setModels($models);
        }

        return $listCatalogueProvider;
    }

    //Nén toàn bộ folder lại thành 1 file Zip
    public static function Zip($source, $destination) {
        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }
        $zip = new \ZipArchive();
        if (!$zip->open($destination, \ZIPARCHIVE::CREATE)) {
            return false;
        }
        $source = str_replace('\\', '/', $source);
        if (is_dir($source) === true) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);
            foreach ($files as $file) {
                $file = str_replace('\\', '/', $file);
                // Ignore "." and ".." folders
                if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..')))
                    continue;
                if (is_dir($file) === true) {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                } else if (is_file($file) === true) {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        } else if (is_file($source) === true) {
            $zip->addFromString($source, file_get_contents($source));
        }
        return $zip->close();
    }

}
