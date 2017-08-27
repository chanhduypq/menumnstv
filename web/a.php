<?php
function chinh_hop($array,&$results){
    for($i=0;$i<count($array);$i++){
        $temp=$array[$i];
        
        return chinh_hop($array, $results);
    }
    return $results;
}
chinh_hop(array(1,2), $results);
echo "<pre>";
var_dump($results);
echo "</pre>";

