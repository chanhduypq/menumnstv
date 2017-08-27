<?php 
use app\models\MultiLang;		

$lang = MultiLang::viewLang("plaza");

$url=Yii::$app->request->url;
if(strpos($url, "?")!==FALSE){
    $url.="&";
}
else{
    $url.="?";
}
?>
<div id="menu-categories">
    <a href="javascript:void(0)"><i class="fa fa-bars" aria-hidden="true"></i></a>
</div>
<div class="v2-dashboard-land">
    <div class="v2-padding-dashboard-land">
        <div class="v2-box-pdl">
            <div class="v2-title-bpdl">Search</div>
            <form action="" method="GET" id="frm-search">
                <div class="v2-box-search">
                    <input name="q" value="<?php echo $params['key'];?>" type="text" id="v2-search-bpdl" placeholder="Enter keywords">
                    <a href="javascript:void(0);" onclick="$('#frm-search').submit();" class="v2-btn-search-d"></a>
                </div>
            </form>
        </div> <!-- end v2-box-pdl -->
        <div class="v2-box-pdl">
            <div class="v2-title-bpdl">Categories</div>
            <div class="v2-content-bpdl">
                <ul class="v2-categories">
                    <li<?php if($params['catalogue_id']=='-1'){ echo ' class="active-cate"';}?>><a href="<?php echo build_url($url, "catalogue_id", "-1");?>"><i></i><?php echo $lang['label_all'];?></a></li>
                    <?php
                    
                    for($i = 0; $i <count($catalogues); $i++) {
                        if($catalogues[$i]['catalogue_id']==$params['catalogue_id']){
                            echo '<li class="active-cate"><a href="'.build_url($url, "catalogue_id", $catalogues[$i]['catalogue_id']).'"><i></i>'.$catalogues[$i]['catalogue_name'].'</a></li>';
                        }
                        else{
                            echo '<li><a href="'.build_url($url, "catalogue_id", $catalogues[$i]['catalogue_id']).'"><i></i>'.$catalogues[$i]['catalogue_name'].'</a></li>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </div> <!-- end v2-box-pdl -->
        <div class="v2-box-pdl">
            <div class="v2-title-bpdl">View</div>
            <div class="v2-content-bpdl">
                <ul class="v2-view">
                    <li<?php if($params['view']=='grid') echo' class="active-view"';?>><a href="<?php echo build_url($url, "view", "grid");?>"><i class="v2-view-grid"></i>Grid</a></li>
                    <li<?php if($params['view']=='list') echo' class="active-view"';?>><a href="<?php echo build_url($url, "view", "list");?>"><i class="v2-view-list"></i>List</a></li>
                </ul>
            </div>
        </div> <!-- end v2-box-pdl -->
        <div class="v2-box-pdl">
            <div class="v2-title-bpdl">Layouts</div>
            <div class="v2-content-bpdl">
                <ul class="v2-layouts">
                    <li<?php if($params['resolution']=='1') echo' class="active-layout"';?>><a href="<?php echo build_url($url, "resolution", "1");?>"><i class="v2-layouts-land"></i>Landscape</a></li>
                    <li<?php if($params['resolution']=='2') echo' class="active-layout"';?>><a href="<?php echo build_url($url, "resolution", "2");?>"><i class="v2-layouts-port"></i>Portrait</a></li>
                </ul>
            </div>
        </div> <!-- end v2-box-pdl -->
        <div class="v2-box-pdl">
            <div class="v2-title-bpdl">Filter</div>
            <div class="v2-content-bpdl">
                <ul class="v2-filter">
                    <li<?php if($params['filter']=='new_post') echo' class="active-filter"';?>><a href="<?php echo build_url($url, "filter", "new_post");?>"><i class="v2-filter-newpost"></i>New Post</a></li>
                    <li<?php if($params['filter']=='light') echo' class="active-filter"';?>><a href="<?php echo build_url($url, "filter", "light");?>"><i class="v2-filter-light"></i>Light</a></li>
                    <li<?php if($params['filter']=='dark') echo' class="active-filter"';?>><a href="<?php echo build_url($url, "filter", "dark");?>"><i class="v2-filter-dark"></i>Dark</a></li>
                    <li<?php if($params['filter']=='best_sellers') echo' class="active-filter"';?>><a href="<?php echo build_url($url, "filter", "best_sellers");?>"><i class="v2-filter-best"></i>Best Sellers</a></li>
                    <li<?php if($params['filter']=='most_view') echo' class="active-filter"';?>><a href="<?php echo build_url($url, "filter", "most_view");?>"><i class="v2-filter-most"></i>Most View</a></li>
                    <li<?php if($params['filter']=='like') echo' class="active-filter"';?>><a href="<?php echo build_url($url, "filter", "like");?>"><i class="v2-filter-like"></i>Like</a></li>
                    <li<?php if($params['filter']=='download') echo' class="active-filter"';?>><a href="<?php echo build_url($url, "filter", "download");?>"><i class="v2-filter-down"></i>Download</a></li>
                </ul>
            </div>
        </div> <!-- end v2-box-pdl -->
    </div> <!-- end v2-padding-dashboard-land -->
</div> <!-- end v2-dashboard-land -->

<?php
/**
 * $url ban đầu có thể là /plaza hoặc /plaza?par1=val1 hoặc /plaza?par1=val1&par2=val2
 *   lưu ý: giá trị của $url khi được truyền vào function này luôn có kí tự ? hoặc & cuối cùng
 * ứng với mỗi link trong vùng search sẽ thêm key=value như là resolution=1, view=grid
 * ở đây, xử lý 3 trường hợp:
 *          1/ $url là /plaza (tức là chưa có parameter nào trong url) thi cho nó trở thành /plaza?key=value
 *          2/ $url là /plaza?par1=val1 hoặc /plaza?par1=val1&par2=val2 (tức là đã có ít nhất một parameter trong url) thi cho nó trở thành /plaza?key=value, ở đây lại phân thành 2 trường hợp
 *             2.1/ trong số các parameter, đã có một parameter trùng với key mới. Như vậy, giữ nguyên url cũ, chỉ thay thế value mới. Ví dụ: /plaza?resolution=1 thay thành /plaza?resolution=2
 *             2.2/ trong số các parameter, chưa có một parameter trùng với key mới. Như vay thêm vào url chuỗi ?key=value hoặc &key=value
 * @param string $url
 * @param string $key
 * @param string $value
 * @return string
 */
function build_url($url,$key,$value){
    // trường hợp thuộc mục 1/ hoặc 2.2 theo chú thích ở trên
    if(strpos($url, $key)===FALSE){
        $url.="$key=$value";
        //cho page=1 trở lại
        if(strpos($url, 'page')!==FALSE&&$key!='view'){
            $temp= explode('page=', $url);
            $temp=$temp[1];
            $temp= explode('&', $temp);

            return str_replace("page=".$temp[0], "page=1", $url);
        }
        return $url;
    }
    
    /**
     * trường hợp thuộc mục 2.1 theo chú thích ở trên
     */
    //lấy giá trị như thế này đưa vào biến $temp (url: /plaza?key=val1-->giá trị lấy là =val1; url: /plaza?key=val1&par1=val2--->giá trị lấy về là =val1&par1=val2
    $temp= explode($key, $url);
    $temp=$temp[1];
    //lấy giá trị như thế này đưa vào biến $temp1 (dù $temp mang giá trị =val1 hay =val1&par1=val2 thi giá trị lấy về vẫn là =val1
    $temp1= explode("&", $temp);
    $temp1=$temp1[0];
    
    $url=rtrim(str_replace($temp1, "=$value", $url),"&");
    
    //cho page=1 trở lại
    if(strpos($url, 'page')!==FALSE&&$key!='view'){
        $temp= explode('page=', $url);
        $temp=$temp[1];
        $temp= explode('&', $temp);
        
        return str_replace("page=".$temp[0], "page=1", $url);
    }
    
    return $url;
}
?>