<?php
define("_CODE_NOT_FOUND",404); //未找到动态
define("_CODE_SAFE",200); //找到动态
define("_PARAM_MISS",401); //字域缺少
header("Content-Type: application/json; charset=utf-8",True); //设置响应头
require(__DIR__.'/class.php');


if(isset($_GET["uid"])){
    $uid = $_GET["uid"];
    $udk = new UserDynamicsKey();
    $k_v = $udk->getinfo();
    $ud = new UserDynamics($uid);
    $d = $ud->getinfo();
    $dynamics = $d["data"]["items"];
    if(count($dynamics) == 0){
        $array = ["code"=>_CODE_NOT_FOUND,"data"=>null];
        echo json_encode($array);
    }else{
        $str = json_encode($dynamics,JSON_UNESCAPED_UNICODE);
        $cf_list = array();
        foreach($k_v as $nick => $key){
            if(strstr($str,$key)){
                $ele = ["nick"=>$nick,"name"=>$key];
                array_push($cf_list,$ele);
            }
        }
        if(count($cf_list) == 0){
            $ele = ["nick"=>"纯良","name"=>"无"];
                array_push($cf_list,$ele);
        }
        $array = ["code"=>_CODE_SAFE,"data"=>$cf_list];
        echo json_encode($array,JSON_UNESCAPED_UNICODE);
    }
}else{
    $array = ["code"=>_PARAM_MISS,"data"=>null];
    echo json_encode($array);
}
?>