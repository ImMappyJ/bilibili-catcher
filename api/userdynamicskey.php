<?php
    $k_v = [
        "原" => "原神",
        "崩" => "崩坏",
        "农" => "王者荣耀",
        "粥" => "明日方舟",
        "撸" => "英雄联盟",
        "方块" => "我的世界",
        "CSGO" => "CSGO",
        "CF" => "穿越火线",
        "东方" => "东方Project",
        "屁股肉" => "Phigros",
        "嘉心糖" => "嘉然",
        "顶碗人" => "向晚",
        "奶淇琳" => "乃琳",
        "贝极星" => "贝拉",
        "雏草姬" => "塔菲",
        "杰尼" => "七海",
        "小星星" => "星瞳",
        "小孩梓" => "梓",
        "棺材板" => "東雪蓮"
    ];
    header("Content-Type: application/json; charset=utf-8",True); //设置响应头
    echo json_encode($k_v,JSON_UNESCAPED_UNICODE);
?>