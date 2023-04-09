<?php
define("_CODE_NOT_FOUND",404); //未找到视频
define("_CODE_SAFE",200); //找到视频
define("_PARAM_MISS",401); //字域缺少
header("Content-Type: application/json; charset=utf-8",True); //设置响应头
require(__DIR__.'/class.php');

if(isset($_GET["uid"])){
    $uid = $_GET["uid"];
    $bvid_arr = getvideoslist($uid);
    if($bvid_arr == null){
        $array = ["code"=>_CODE_NOT_FOUND,"data"=>null];
        echo json_encode($array);
    }else{
        $tags_arr = gettagsfluence($bvid_arr);
        $array = ["code"=>_CODE_SAFE,"data"=>["vlists"=>$bvid_arr,"fluency"=>$tags_arr]];
        echo json_encode($array,JSON_UNESCAPED_UNICODE);
    }
}else{
    $array = ["code"=>_PARAM_MISS,"data"=>null];
    echo json_encode($array);
}

function getvideoslist($uid){
    $uvl = new UserVideosList($uid);
    $json = $uvl->getinfo();
    if($json["code"] == 0){
        $vlist = $json["data"]["list"]["vlist"];
        if($vlist == null) return null;
        $bvid_arr = array();
        foreach($vlist as $video){
            array_push($bvid_arr,$video["bvid"]);
        }
        return $bvid_arr;
    }else{
        return null;
    }
}

function gettagsfluence($bvid_arr){
    $tags_arr = array();
    foreach($bvid_arr as $mid){
        $uvt = new UserVideoTag($mid);
        $json = $uvt->getinfo();
        if($json["code"] == 0){
            $tags = $json["data"];
            foreach($tags as $tag){
                $id = $tag["tag_id"];
                if($tags_arr[$id] == null){
                    $tags_arr[$id] = ["name"=>$tag["tag_name"],"value"=>1];
                }else{
                    $tags_arr[$id]["value"]++;
                }
            }
        }else{
            continue;
        }
    }
    return $tags_arr;
}
?>