<?php
	include('topmenu_data.php');
?>
<?php
	$menu = $arrMenu[Yii::$app->session['language_id']];
        $i=1;
	foreach($menu as $mnItem) {
            $activeClass = "";
			$action = Yii::$app->controller->action->id;
			$slug = "";
			if(isset($_GET['url'])) $slug = $_GET['url'];
            if(
                ($mnItem['name'] == 'Plaza' && $action=='plaza')
                ||($mnItem['name'] == 'Home' && $action=='index')
				||($mnItem['name'] == 'Price' && $action=='page' && $slug == "price")
				||($mnItem['name'] == 'More infomation' && $action=='page' && ($slug == "why-mnstv" || $slug == "why-mns-tv"))
				||($mnItem['name'] == 'Contact' && $action=='contact')
            ) 
                $activeClass = ' class="active"';
		//Trường hợp là menu gốc, tức kg có sub-menu
		if(count($mnItem) <= 2) {
			//Nếu menu là Plaza => thêm class Active vào
			
?>
						<li<?=$activeClass?>>
                            <a href="<?=$mnItem['url']?>"><span class="icon-menu<?php echo $i++;?>"></span><?=$mnItem['name']?></a>
                        </li>
<?php
		}
		//Trường hợp menu có phân nhánh
		else {
?>
						<li<?=$activeClass?>>                                
							<a href="<?=$mnItem['url']?>"><span class="icon-menu<?php echo $i++;?>"></span><?=$mnItem['name']?></a>
								<ul  class="sub-menu">
<?php
						foreach($mnItem['sub'] as $sub) {
?>
									<li>
                                                                            <a href="<?=$sub['url']?>">
                                                                                <?php 
                                                                                if($mnItem['name'] == 'More infomation'){?>
                                                                                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                                                                                <?php
                                                                                }
                                                                                else if($mnItem['name'] == 'Plaza'){
                                                                                    if($sub['name']=='Landscape') echo '<i class="sub-land"></i>';
                                                                                    else if($sub['name']=='Portrait') echo '<i class="sub-port"></i>';                                                                                                                                                                    }
                                                                                ?>
                                                                            <?=$sub['name']?>
                                                                            </a>
                                                                        </li>
<?php
						}
?>
								</ul>
						</li>
<?php
		}
	}
?>