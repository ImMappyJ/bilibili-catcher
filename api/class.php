<?php
define("_WEB_URL","http://localhost"); //个人域名
define("_BILI_COOKIE",""); //bilibili 用户cookie (用于下载视频)

class GetInfoParents{
    protected $url;

    public function getinfo(){
        $util = new Util();
        $result = $util->geturl($this->url);
        return json_decode($result,True);
    }
}

abstract class Printer{
    abstract function printInfo($mid);
}

class RegionInfo extends GetInfoParents{

    public function __construct()
    {
        $this->url = 'https://api.bilibili.com/x/web-interface/online';
    }

}

class HotVideosInfo extends GetInfoParents{

    public function __construct()
    {
        $this->url = 'https://api.bilibili.com/x/web-interface/online/list';
    }
}

class UserInfo extends GetInfoParents{
    public function __construct($uid)
    {
        $this->url = "https://api.bilibili.com/x/space/wbi/acc/info?mid=".$uid."&platform=web";
    }
}

class UserVideosList extends GetInfoParents{
    public function __construct($uid)
    {
        $this->url = "https://api.bilibili.com/x/space/wbi/arc/search?mid=".$uid."&pn=1&ps=20&order=pubdate&index=1&platform=web";
    }
}

class UserVideoTag extends GetInfoParents{
    public function __construct($mid)
    {
        $this->url = "https://api.bilibili.com/x/web-interface/view/detail/tag?bvid=".$mid;
    }
}

class UserVideoTagData extends GetInfoParents{
    public function __construct($uid)
    {
        $this->url = _WEB_URL."/api/uservideosdata.php?uid=".$uid;
    }
}

class UserDynamics extends GetInfoParents{
    public function __construct($uid)
    {
        $this->url = "https://api.bilibili.com/x/polymer/web-dynamic/v1/feed/space?host_mid=".$uid;
    }
}

class UserDynamicsKey extends GetInfoParents{
    public function __construct()
    {
        $this->url = _WEB_URL."/api/userdynamicskey.php";
    }
}

class UserDynamicsData extends GetInfoParents{
    public function __construct($uid)
    {
        $this->url = _WEB_URL."/api/userdynamicsdata.php?uid=".$uid;
    }
}

class VideoInfo extends GetInfoParents{
    public function __construct($bvid)
    {
        $this->url = "https://api.bilibili.com/x/web-interface/view?bvid=".$bvid;
    }
}

class VideoStreamURL extends GetInfoParents{
    public function __construct($bvid,$cid,$qn)
    {
        $this->url = "https://api.bilibili.com/x/player/playurl?fnval=1&bvid=".$bvid."&cid=".$cid."&qn=".$qn;
    }
}

class Util{

    public static function geturl($url){
        $handle = curl_init($url);
        $header = array(
        'Content-type: application/json;charset=UTF-8',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36 Edg/111.0.1661.62',
        'Cookie: '._BILI_COOKIE
        );
        curl_setopt($handle,CURLOPT_SSL_VERIFYHOST,False);
        curl_setopt($handle,CURLOPT_SSL_VERIFYPEER,False);
        curl_setopt($handle,CURLOPT_RETURNTRANSFER,True);
        curl_setopt($handle,CURLOPT_POST,False);
        curl_setopt($handle,CURLOPT_HEADER,False);
        curl_setopt($handle,CURLOPT_HTTPHEADER,$header);
        $output = curl_exec($handle);
        return $output;
    }
}

/*
    UID存在则在搜索框中加载UID
*/
function printSearchBoxWithUID($uid){
    echo '
        <form action="user.php" id="form-searchbox" method="GET">
            <label>请输入查询UID：</label>
            <br/><br/>
            <input type="text" style="width:50%;height:40px;font-size:larger;" oninput="value=value.replace(/[^\d]/g,\'\')" name="uid" value="'.$uid.'">
            <br/><br/>
            <input type="submit" style="font-size:40px;color:white;background-color:cornflowerblue;border:none;padding:0 20 0 20;" value="查询">
        </form>';
}

/*
    UID为空则搜索框留空
*/
function printSearchBox(){
    echo '
        <form action="user.php" id="form-searchbox" method="GET">
            <label>请输入查询UID：</label>
            <br/><br/>
            <input type="text" style="width:50%;height:40px;font-size:larger;" oninput="value=value.replace(/[^\d]/g,\'\')" name="uid">
            <br/><br/>
            <input type="submit" style="font-size:40px;color:white;background-color:cornflowerblue;border:none;padding:0 20 0 20;" value="查询">
        </form>';
}

/*
    BVID存在则在搜索框中加载BVID
*/
function printVSearchBoxWithBVID($bvid){
    echo '
        <form action="video.php" id="form-searchbox" method="GET">
            <label>请输入查询BVID(示例:bv1xxxxxxd)：</label>
            <br/><br/>
            <input type="text" style="width:50%;height:40px;font-size:larger;" name="bvid" value="'.$bvid.'">
            <br/><br/>
            <input type="submit" style="font-size:40px;color:white;background-color:cornflowerblue;border:none;padding:0 20 0 20;" value="查询">
        </form>';
}

/*
    BVID为空则搜索框留空
*/
function printVSearchBox(){
    echo '
        <form action="video.php" id="form-searchbox" method="GET">
            <label>请输入查询BVID(示例:bv1xxxxxxd)：</label>
            <br/><br/>
            <input type="text" style="width:50%;height:40px;font-size:larger;" name="bvid">
            <br/><br/>
            <input type="submit" style="font-size:40px;color:white;background-color:cornflowerblue;border:none;padding:0 20 0 20;" value="查询">
        </form>';
}

/*
    热门视频
*/
function printOnlineHotVideo(){
    echo '<h1 style="text-align: center;">当前观看人数最多</h1>';
    echo '
    <div class="container-hotvideos">';
    $hotvideo = new HotVideosInfo();
    $info = $hotvideo->getinfo();
    $list = $info["data"];
    foreach($list as $single_video){
        echo 
        '
        <a target="_blank" href="'.$single_video["short_link_v2"].'">
        <div class="hotvideos">
        <img style="margin:0 auto;" title="'.$single_video["desc"].'" src="'.$single_video["pic"].'" referrerPolicy="no-referrer" width="286.5" height="179">
        <h6 style="height:48px;overflow:hidden;">'.$single_video["title"].'</h6>
        <h6>'.$single_video["owner"]["name"].'</h6>
        <h4>正在观看人数：'.$single_video["online_count"].'</h4>
        </div>
        </a>
        ';
    }
    echo '</div>';
}

/*
    分区信息
*/
function printRegionInfo(){
    $ri = new RegionInfo();
    $info = $ri->getinfo();
    $regionlist = $info["data"]["region_count"];
    echo
    '
    <div id="container-regioninfo" style="width:1000px;height:700px;margin:0 auto;"></div>
    <script type="text/javascript">
      var myChart = echarts.init(document.getElementById(\'container-regioninfo\'));

      var option = {
        title: {
            text: \'B站分区最新投稿统计\',
            subtext: \'New Videos!\',
            left: \'center\'
          },
          tooltip: {
            trigger: \'item\'
          },
          legend: {
            orient: \'vertical\',
            left: \'left\'
          },
        series: [
            {
              type: \'pie\',
              name: \'最新投稿\',
              data: [
                {
                    value: '.$regionlist["1"].',
                    name: \'动画\'
                },
                {
                    value: '.$regionlist["13"].',
                    name: \'番剧\'
                },
                {
                    value: '.$regionlist["167"].',
                    name: \'国创\'
                },
                {
                    value: '.$regionlist["3"].',
                    name: \'音乐\'
                },
                {
                    value: '.$regionlist["129"].',
                    name: \'舞蹈\'
                },
                {
                    value: '.$regionlist["4"].',
                    name: \'游戏\'
                },
                {
                    value: '.$regionlist["36"].',
                    name: \'知识\'
                },
                {
                    value: '.$regionlist["188"].',
                    name: \'数码\'
                },
                {
                    value: '.$regionlist["160"].',
                    name: \'生活\'
                },
                {
                    value: '.$regionlist["138"].',
                    name: \'搞笑\'
                },
                {
                    value: '.$regionlist["119"].',
                    name: \'鬼畜\'
                },
                {
                    value: '.$regionlist["155"].',
                    name: \'时尚\'
                },
                {
                    value: '.$regionlist["202"].',
                    name: \'资讯\'
                },
                {
                    value: '.$regionlist["165"].',
                    name: \'广告\'
                },
                {
                    value: '.$regionlist["5"].',
                    name: \'娱乐\'
                },
                {
                    value: '.$regionlist["181"].',
                    name: \'影视\'
                },
                {
                    value: '.$regionlist["177"].',
                    name: \'纪录片\'
                },
                {
                    value: '.$regionlist["23"].',
                    name: \'电影\'
                },
                {
                    value: '.$regionlist["11"].',
                    name: \'电视剧\'
                },
              ],
              radius: \'70%\',
              emphasis: {
                itemStyle: {
                  shadowBlur: 10,
                  shadowOffsetX: 0,
                  shadowColor: \'rgba(0, 0, 0, 0.5)\'
                }
              }
            }
          ]
      };

      myChart.setOption(option);
    </script>
    ';
}

/*
    视频信息
*/
class VideoInfoPrint extends Printer{
    function printInfo($bvid)
    {
        $vinfo = new VideoInfo($bvid);
        $json = $vinfo->getinfo();
        if($json["code"] != 0){
            echo '
            <div class="message-warn">
                <h1>Code:'.$json["code"].'</h1>
                <h1>'.$json["message"].'</h1>
            </div>
            ';
        }else{
            $data = $json["data"];
            $stat = $data["stat"];
            $page = $data["pages"];
            echo '
            <div class="container-videoinfo-basic">
                <h1 style="display: inline; background-color:gray; margin:0px auto 0px auto; border-color: gray; border-radius: 5px;color: white;">'.$data["tname"].'</h1><h1 style="display: inline;">'.$data["title"].'</h1>
                <br />
                <img style="border-radius: 2px; display: box;"  height="200" width="320" src="'.$data["pic"].'" referrerPolicy="no-referrer">
                <br />
                <span title="总播放数" class="view item"><img style="vertical-align: middle;" src="//s1.hdslb.com/bfs/static/jinkela/video/asserts/view.svg" alt="">'.$stat["view"].'</span>
                <span title="历史累计弹幕数" class="danmaku"><img style="vertical-align: middle;" src="//s1.hdslb.com/bfs/static/jinkela/video/asserts/dm.svg" alt="">'.$stat["danmaku"].'</span>
                <span title="发布日期" class="pudate"><img height="20px" width="20px" style="vertical-align: middle;" src="data:image/svg+xml;base64,PHN2ZyB0PSIxNjQyNTg4MTEzODk5IiBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHAtaWQ9IjY4MjciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PGRlZnM+PC9kZWZzPjxwYXRoIGQ9Ik0xNjcuMDI0IDUxMmEzNDQuOTc2IDM0NC45NzYgMCAxIDEgNjg5Ljk1MiAwIDM0NC45NzYgMzQ0Ljk3NiAwIDAgMS02OTAgMHpNNTEyIDEwNi45NzZhNDA1LjAyNCA0MDUuMDI0IDAgMSAwIDAgODEwLjA0OCA0MDUuMDI0IDQwNS4wMjQgMCAwIDAgMC04MTB6IG0zMCAyMzUuMDA4YTMwIDMwIDAgMSAwLTYwIDBWNTEyYzAgNy45NjggMy4xNjggMTUuNiA4Ljc4NCAyMS4yMTZsMTIwIDEyMGEzMCAzMCAwIDEgMCA0Mi40MzItNDIuNDMyTDU0MiA0OTkuNTJWMzQxLjk4NHoiIGZpbGw9IiM5NDk5QTAiIHAtaWQ9IjY4MjgiPjwvcGF0aD48L3N2Zz4=" alt=""><span class="pudate-text">'.date("Y-m-d H:i",$data["pubdate"]).'</span></span>
                <h5>'.'作者:'.$data["owner"]["name"].'</h5>
                <h5>'.'简介:'.$data["desc"].'</h5>
                <svg style="vertical-align: middle;" width="36" height="36" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg" class="icon"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.77234 30.8573V11.7471H7.54573C5.50932 11.7471 3.85742 13.3931 3.85742 15.425V27.1794C3.85742 29.2112 5.50932 30.8573 7.54573 30.8573H9.77234ZM11.9902 30.8573V11.7054C14.9897 10.627 16.6942 7.8853 17.1055 3.33591C17.2666 1.55463 18.9633 0.814421 20.5803 1.59505C22.1847 2.36964 23.243 4.32583 23.243 6.93947C23.243 8.50265 23.0478 10.1054 22.6582 11.7471H29.7324C31.7739 11.7471 33.4289 13.402 33.4289 15.4435C33.4289 15.7416 33.3928 16.0386 33.3215 16.328L30.9883 25.7957C30.2558 28.7683 27.5894 30.8573 24.528 30.8573H11.9911H11.9902Z"></path></svg>
                '.$stat["like"].'
                <svg style="vertical-align: middle;" width="28" height="28" viewBox="0 0 28 28" xmlns="http://www.w3.org/2000/svg" class="icon" style="fill:;"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.045 25.5454C7.69377 25.5454 2.54504 20.3967 2.54504 14.0454C2.54504 7.69413 7.69377 2.54541 14.045 2.54541C20.3963 2.54541 25.545 7.69413 25.545 14.0454C25.545 17.0954 24.3334 20.0205 22.1768 22.1771C20.0201 24.3338 17.095 25.5454 14.045 25.5454ZM9.66202 6.81624H18.2761C18.825 6.81624 19.27 7.22183 19.27 7.72216C19.27 8.22248 18.825 8.62807 18.2761 8.62807H14.95V10.2903C17.989 10.4444 20.3766 12.9487 20.3855 15.9916V17.1995C20.3854 17.6997 19.9799 18.1052 19.4796 18.1052C18.9793 18.1052 18.5738 17.6997 18.5737 17.1995V15.9916C18.5667 13.9478 16.9882 12.2535 14.95 12.1022V20.5574C14.95 21.0577 14.5444 21.4633 14.0441 21.4633C13.5437 21.4633 13.1382 21.0577 13.1382 20.5574V12.1022C11.1 12.2535 9.52148 13.9478 9.51448 15.9916V17.1995C9.5144 17.6997 9.10883 18.1052 8.60856 18.1052C8.1083 18.1052 7.70273 17.6997 7.70265 17.1995V15.9916C7.71158 12.9487 10.0992 10.4444 13.1382 10.2903V8.62807H9.66202C9.11309 8.62807 8.66809 8.22248 8.66809 7.72216C8.66809 7.22183 9.11309 6.81624 9.66202 6.81624Z"></path></svg>
                '.$stat["coin"].'
                <svg style="vertical-align: middle;" width="28" height="28" viewBox="0 0 28 28" xmlns="http://www.w3.org/2000/svg" class="icon" style="fill:;"><path fill-rule="evenodd" clip-rule="evenodd" d="M19.8071 9.26152C18.7438 9.09915 17.7624 8.36846 17.3534 7.39421L15.4723 3.4972C14.8998 2.1982 13.1004 2.1982 12.4461 3.4972L10.6468 7.39421C10.1561 8.36846 9.25639 9.09915 8.19315 9.26152L3.94016 9.91102C2.63155 10.0734 2.05904 11.6972 3.04049 12.6714L6.23023 15.9189C6.96632 16.6496 7.29348 17.705 7.1299 18.7605L6.39381 23.307C6.14844 24.6872 7.62063 25.6614 8.84745 25.0119L12.4461 23.0634C13.4276 22.4951 14.6544 22.4951 15.6359 23.0634L19.2345 25.0119C20.4614 25.6614 21.8518 24.6872 21.6882 23.307L20.8703 18.7605C20.7051 17.705 21.0339 16.6496 21.77 15.9189L24.9597 12.6714C25.9412 11.6972 25.3687 10.0734 24.06 9.91102L19.8071 9.26152Z"></path></svg>
                '.$stat["favorite"].'
                <svg style="vertical-align: middle;" width="28" height="28" viewBox="0 0 28 28" xmlns="http://www.w3.org/2000/svg" class="icon"><path d="M12.6058 10.3326V5.44359C12.6058 4.64632 13.2718 4 14.0934 4C14.4423 4 14.78 4.11895 15.0476 4.33606L25.3847 12.7221C26.112 13.3121 26.2087 14.3626 25.6007 15.0684C25.5352 15.1443 25.463 15.2144 25.3847 15.2779L15.0476 23.6639C14.4173 24.1753 13.4791 24.094 12.9521 23.4823C12.7283 23.2226 12.6058 22.8949 12.6058 22.5564V18.053C7.59502 18.053 5.37116 19.9116 2.57197 23.5251C2.47607 23.6489 2.00031 23.7769 2.00031 23.2122C2.00031 16.2165 3.90102 10.3326 12.6058 10.3326Z"></path></svg>
                '.$stat["share"].'
            </div>
            <div class="container-videoinfo-download">
                <h1>下载视频</h1>
                <select id="videoSelect" style="font-size: 20px;">';
            for($i = 0;$i < sizeof($page);$i++){
                echo "<option value=\"{$i}\">".$page[$i]["part"]."</option>";
            }
            echo '
                </select>
                <select id="quality" name="quality" style="font-size: 20px;">
                    <option value="80">1080p</option>
                    <option value="74">720p 60fps</option>
                    <option value="64">720p</option>
                    <option value="32">480p</option>
                </select>
                <button id="download" style="font-size: 20px;">下载</button>
                <label id="progress"></label>
            </div>
            <div class="container-temp" style="display:none;">';
            for($i = 0;$i < sizeof($page);$i++){
                echo "<h id=\"{$i}-cid\">".$page[$i]["cid"]."</h>";
                echo "<h id=\"{$i}-bvid\">".$data["bvid"]."</h>";
            }
            echo '
            </div>';
            echo '
            <script>
                var download_btn = document.getElementById("download");
                download_btn.addEventListener("click",function(){
                    var select = document.getElementById("videoSelect");
                    var quality = document.getElementById("quality");
                    var q_index = quality.selectedIndex;
                    var qn = quality.options[q_index].value;
                    var v_index = select.selectedIndex;
                    var bvid = document.getElementById(v_index + "-bvid").innerHTML;
                    var cid = document.getElementById(v_index + "-cid").innerHTML;
                    alert("正在下载，请勿关闭窗口！");

                    // 创建一个 XMLHttpRequest 对象
                    var xhr = new XMLHttpRequest();
                  
                    // 设置该对象的属性，包括请求方法、文件 URL、响应类型
                    xhr.open("GET", "api/download.php?bvid="+bvid+"&cid="+cid+"&qn="+qn, true);
                    xhr.responseType = "blob";

                    // 获取下载进度
                    var progress = document.getElementById("progress");
                    progress.innerHTML = "正在获取资源...";
                    xhr.onprogress = function(e) {
                        if (e.lengthComputable) {
                          var percentComplete = (e.loaded / e.total) * 100;
                          progress.innerHTML = percentComplete + "% downloaded";
                        }
                    };

                    // 注册回调函数，处理文件下载完成后的操作
                    xhr.onload = function() {
                      // 检查状态码，如果请求成功，则进行下载操作
                      if (xhr.status === 200) {
                        // 创建reader
                        var blob = new Blob([this.response], {type: "video/mp4"});
                        // 创建一个 <a> 元素
                        var link = document.createElement("a");
                  
                        // 设置该元素的属性，包括文件名和 URL
                        link.href = window.URL.createObjectURL(blob);
                        link.download = bvid+".mp4";
                  
                        // 将该元素添加到页面中并模拟点击
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                      }
                    };
                    
                    // 发送请求
                    xhr.send();
                });
            </script>
            ';
        }
    }
}

/*
    个人信息
*/
class UserInfoPrint extends Printer{
    function printInfo($uid){
        {
            $uinfo = new UserInfo($uid);
            $json = $uinfo->getinfo();
            if($json["code"] != 0){
                echo '
                <div class="message-warn">
                    <h1>Code:'.$json["code"].'</h1>
                    <h1>'.$json["message"].'</h1>
                </div>
                ';
            }else{
                $data = $json["data"];
                echo '
                <div class="container-userinfo">
                    <div class="container-userinfo-basic">
                        <h1>基本信息</h1>
                        <img style="border-radius: 100px;" height="200" width="200" src="'.$data["face"].'" referrerPolicy="no-referrer">
                        <br/>';
        
                if(!empty($data["school"]["name"])){
                    echo '<h2 style="display:inline; background-color: #6c6c67; border: 2px solid #484845; color: #121211;">'.$data["school"]["name"].'</h2>';
                }
                echo '
                        <h2 style="display:inline;">'.$data["name"].'</h2>
                        <svg style="display:inline;" width="25" height="15" viewBox="0 0 1901 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" width="200" height="200" class="icon">'.$this->getLevelModelData($data["level"]).'</path></svg>
                        <img style="display:inline;" width="18" height="18" src="'.$this->getSexIconData($data["sex"]).'">';
                
                if($data["fans_medal"]["show"] && $data["fans_medal"]["wear"]){
                    $medal = $data["fans_medal"]["medal"];
                    $this->printFansMedal($medal["medal_color_start"],$medal["medal_color_end"],$medal["medal_color_border"],$medal["medal_name"],$medal["level"]);
                }
        
                $this->printVIPIcon($data["vip"]["label"]);
                $this->printOfficialRank($data["official"]);
                echo '
                        <h5>'.$data["sign"].'</h5>
                    </div>
                    <div class="container-userinfo-item">
                        <h1>动态画像(仅供参考)</h1> 
                        ';
                $this->printDynamicsCloudWords($uid);
                echo '
                    </div>
                    <div class="container-userinfo-item">
                        <h1>最近投稿云图(20条)</h1> 
                        ';
                $this->printUploadedVideosCloudWords($uid);
                echo '
                    </div>
                </div>
                ';
        } // 基础信息
    }
}

/*
    等级图标打印
*/
function getLevelModelData($level){
    $data = "";
    switch($level){
        case 0:
            $data = '<path d="M146.285714 170.349714h1609.142857V877.714286H146.285714z" fill="#FFFFFF" p-id="2674"></path><path d="M1763.766857 73.142857c24.868571 0 44.544 18.285714 43.593143 49.444572v747.52a48.859429 48.859429 0 0 1-49.737143 49.298285H122.88a48.859429 48.859429 0 0 1-49.737143-49.371428V208.969143c0-24.649143 18.651429-49.371429 49.737143-49.371429H1148.342857v-37.010285c0-24.722286 18.651429-49.444571 49.737143-49.444572h565.613714zM265.801143 264.630857h-49.737143c-18.578286 0-37.302857 18.578286-37.302857 37.010286v488.082286c0 18.578286 18.724571 37.010286 37.302857 37.010285h298.422857c18.651429 0 37.302857-18.432 37.302857-37.010285v-49.371429c0-18.651429-18.651429-37.156571-37.302857-37.156571h-211.382857v-401.554286c0-18.432-18.651429-37.010286-37.302857-37.010286z m447.634286 0h-49.737143c-18.651429 0-37.302857 18.578286-37.302857 37.010286v302.811428c-0.365714 12.653714 0 30.427429 6.217142 36.937143l174.08 172.982857c6.875429 6.875429 19.529143 9.947429 29.403429 11.264l10.020571 0.950858h8.045715l10.020571-0.950858c9.874286-1.316571 22.601143-4.388571 29.476572-11.264l174.08-172.982857c6.144-6.070857 18.578286-18.432 18.578285-30.866285V307.931429c0-18.505143-18.651429-37.083429-37.302857-37.083429h-49.590857c-18.724571 0-37.376 18.578286-37.376 37.083429V585.874286l-105.691429 104.96-105.618285-104.96V301.641143c0-18.432-18.651429-37.010286-37.302857-37.010286zM1658.148571 178.102857h-354.304a49.005714 49.005714 0 0 0-49.737142 49.444572v543.670857c0 24.649143 18.651429 49.371429 49.737142 49.371428h354.304c24.868571 0 49.737143-18.505143 49.737143-49.371428V227.474286a48.932571 48.932571 0 0 0-49.737143-49.444572zM1552.457143 295.497143c16.603429 0 33.206857 14.628571 36.717714 30.866286l0.658286 6.144v333.677714c0 16.457143-14.774857 32.914286-31.158857 36.352l-6.217143 0.658286h-142.921143c-16.603429 0-33.133714-14.628571-36.571429-30.866286l-0.731428-6.144V332.580571c0-16.457143 14.701714-32.914286 31.085714-36.352l6.217143-0.658285h142.921143z" fill="#C0C0C0"></path>';
            break;
        case 1:
            $data = '<path d="M146.285714 169.984h1609.142857v707.364571H146.285714z" fill="#FFFFFF" p-id="2546"></path><path d="M1779.565714 73.142857c24.795429 0 49.590857 24.722286 43.446857 49.444572v747.446857a48.859429 48.859429 0 0 1-49.664 49.371428H144.603429a48.786286 48.786286 0 0 1-49.517715-49.371428V209.042286c0-24.722286 18.505143-49.444571 49.517715-49.444572h1021.805714v-37.010285c0-24.722286 18.651429-49.444571 49.590857-49.444572h563.638857z m-272.530285 117.467429h-123.830858c-18.505143 0-37.083429 18.505143-37.083428 37.010285v49.444572c0 18.505143 18.578286 37.083429 37.083428 37.083428h37.229715v389.12h-49.590857c-18.651429 0-37.156571 18.505143-37.156572 37.083429v49.371429c0 18.578286 18.505143 37.083429 37.156572 37.083428h223.378285c18.578286 0 38.326857-18.505143 38.326857-37.083428v-49.152c0-18.578286-19.748571-37.522286-38.326857-37.522286h-49.956571v-475.428572c0-18.505143-18.651429-37.010286-37.229714-37.010285zM732.964571 264.630857h-49.517714c-18.578286 0-37.156571 18.578286-37.156571 37.083429v308.443428c0.146286 12.214857 0.950857 27.940571 6.217143 31.305143l173.348571 172.909714c12.434286 12.434286 43.446857 12.434286 43.446857 12.434286h1.024c5.632-0.219429 31.305143-1.462857 42.276572-12.434286l173.421714-172.909714c1.389714-1.462857 3.072-2.852571 4.900571-4.388571 6.217143-5.339429 13.604571-11.702857 13.604572-20.260572V307.858286c0-18.505143-18.505143-37.010286-37.083429-37.010286h-49.517714c-18.651429 0-37.302857 18.505143-37.302857 37.010286v277.942857L875.52 690.907429 770.194286 585.874286V301.714286c0-18.505143-18.505143-37.083429-37.156572-37.083429z m-445.878857 0h-49.590857c-18.505143 0-37.083429 18.578286-37.083428 37.083429v488.009143c0 18.505143 18.578286 37.010286 37.083428 37.010285h297.325714c18.505143 0 37.156571-18.505143 37.156572-37.010285v-49.444572c0-18.578286-18.578286-37.083429-37.156572-37.083428H324.242286v-401.554286c0-18.432-18.578286-37.010286-37.156572-37.010286z" fill="#C0C0C0"></path>';
            break;
        case 2:
            $data = '<path d="M146.285714 171.739429h1609.142857v707.364571H146.285714z" fill="#FFFFFF" p-id="2546"></path><path d="M1779.565714 73.142857c24.795429 0 49.590857 24.722286 43.446857 49.444572v747.52a48.786286 48.786286 0 0 1-49.590857 49.298285H144.603429a48.713143 48.713143 0 0 1-49.590858-49.371428V208.969143c0-24.649143 18.578286-49.371429 49.590858-49.371429h1021.805714v-37.010285c0-24.722286 18.578286-49.444571 49.517714-49.444572h563.565714zM733.037714 264.630857h-49.590857c-18.578286 0-37.156571 18.578286-37.156571 37.010286v302.811428c0 13.897143 0 33.060571 6.217143 36.937143l173.348571 172.982857c12.434286 12.434286 43.373714 12.434286 43.373714 12.434286s31.012571 0 43.373715-12.434286l185.782857-172.982857c6.217143-6.070857 6.217143-18.432 6.217143-30.866285V307.931429c0-18.505143-18.578286-37.083429-37.156572-37.083429h-49.517714c-18.651429 0-37.156571 18.578286-37.156572 37.083429V585.874286l-105.325714 104.96-105.325714-104.96V301.641143c0-18.432-18.505143-37.010286-37.083429-37.010286z m-445.952 0h-49.517714c-18.578286 0-37.156571 18.578286-37.156571 37.010286v488.082286c0 18.578286 18.578286 37.010286 37.156571 37.010285h297.252571c18.578286 0 37.156571-18.432 37.156572-37.010285v-49.371429c0-18.651429-18.578286-37.156571-37.156572-37.156571H324.242286v-401.554286c0-18.432-18.578286-37.010286-37.156572-37.010286z m1399.661715-92.672h-384c-18.578286 0-37.083429 18.505143-37.083429 37.010286v49.444571c0 18.505143 18.505143 37.083429 37.083429 37.083429h297.252571v142.043428h-297.252571c-18.578286 0-37.083429 18.505143-37.083429 37.083429v315.099429c0 18.505143 18.505143 37.010286 37.083429 37.010285h384c18.578286 0 37.156571-18.505143 37.156571-37.010285v-49.444572c0-18.578286-18.578286-37.156571-37.156571-37.156571h-297.179429V561.152h297.179429c18.578286 0 37.156571-18.578286 37.156571-37.083429V208.969143c0-18.505143-18.578286-37.010286-37.156571-37.010286z" fill="#8BD29B"></path>';
            break;
        case 3:
            $data = '<path d="M146.285714 174.811429h1609.142857v707.364571H146.285714z" fill="#FFFFFF" p-id="2802"></path><path d="M1757.622857 73.142857c24.795429 0 49.517714 24.722286 43.373714 49.444572v747.446857a48.859429 48.859429 0 0 1-49.590857 49.371428H122.660571A48.786286 48.786286 0 0 1 73.142857 870.034286V209.042286c0-24.722286 18.578286-49.444571 49.517714-49.444572h1021.805715v-37.010285c0-24.722286 18.651429-49.444571 49.590857-49.444572h563.565714zM710.948571 264.630857h-49.517714c-18.578286 0-37.156571 18.578286-37.156571 37.083429v302.665143c0 13.165714 0 32.987429 6.217143 37.083428l173.348571 172.909714c11.044571 10.971429 36.717714 12.214857 42.349714 12.434286h1.024s31.012571 0 43.373715-12.434286l185.782857-172.909714c6.217143-6.217143 6.217143-18.505143 6.217143-30.866286V307.858286c0-18.505143-18.578286-37.010286-37.156572-37.010286h-49.517714c-18.651429 0-37.229714 18.505143-37.229714 37.010286v277.942857l-105.325715 105.033143L748.251429 585.874286V301.714286c0-18.505143-18.651429-37.083429-37.156572-37.083429z m-445.878857 0h-49.590857c-18.578286 0-37.156571 18.578286-37.156571 37.083429v488.009143c0 18.505143 18.578286 37.010286 37.156571 37.010285h297.325714c18.578286 0 37.156571-18.505143 37.156572-37.010285v-49.444572c0-18.578286-18.578286-37.083429-37.156572-37.083428h-210.651428v-401.554286c0-18.432-18.505143-37.010286-37.083429-37.010286zM1664.731429 171.958857h-384c-18.578286 0-37.083429 18.505143-37.083429 37.010286v49.444571c0 18.505143 18.505143 37.083429 37.083429 37.083429h297.252571v142.043428h-297.252571c-18.578286 0-37.083429 18.505143-37.083429 37.083429v49.444571c0 18.505143 18.505143 37.010286 37.083429 37.010286h297.252571v142.116572h-297.252571c-18.578286 0-37.083429 18.432-37.083429 37.010285v49.444572c0 18.505143 18.505143 37.083429 37.083429 37.083428h384c18.578286 0 37.156571-18.578286 37.156571-37.083428V208.969143c0-18.505143-18.578286-37.010286-37.156571-37.010286z" fill="#7BCDEF"></path>';
            break;
        case 4:
            $data = '<path d="M154.916571 159.890286h1609.142858v707.364571h-1609.142858z" fill="#FFFFFF" p-id="2930"></path><path d="M1757.622857 73.142857c24.795429 0 49.517714 24.722286 43.373714 49.444572v747.446857a48.859429 48.859429 0 0 1-49.590857 49.371428H122.660571A48.786286 48.786286 0 0 1 73.142857 870.034286V209.042286c0-24.722286 18.578286-49.444571 49.517714-49.444572h1021.805715v-37.010285c0-24.722286 18.651429-49.444571 49.590857-49.444572h563.565714zM710.948571 264.630857h-49.517714c-18.578286 0-37.156571 18.578286-37.156571 37.083429v309.248c0.146286 13.238857 0.877714 26.770286 6.217143 30.500571l173.348571 172.909714c12.434286 12.434286 43.373714 12.434286 43.373714 12.434286h1.097143c5.558857-0.219429 31.232-1.462857 42.276572-12.434286l185.782857-172.909714c6.217143-6.217143 6.217143-18.505143 6.217143-30.866286V307.858286c0-18.505143-18.578286-37.010286-37.156572-37.010286h-49.517714c-18.651429 0-37.229714 18.505143-37.229714 37.010286v277.942857l-105.325715 105.033143L748.251429 585.874286V301.714286c0-18.505143-18.651429-37.083429-37.156572-37.083429z m-445.878857 0h-49.590857c-18.578286 0-37.156571 18.578286-37.156571 37.083429v488.009143c0 18.505143 18.578286 37.010286 37.156571 37.010285h297.325714c18.578286 0 37.156571-18.505143 37.156572-37.010285v-49.444572c0-18.578286-18.578286-37.083429-37.156572-37.083428h-210.651428v-401.554286c0-18.432-18.505143-37.010286-37.083429-37.010286z m1065.179429-92.672h-49.517714c-18.578286 0-37.083429 18.505143-37.083429 37.010286v315.026286c0 18.578286 18.505143 37.083429 37.083429 37.083428h297.252571v228.571429c0 18.505143 18.578286 37.083429 37.229714 37.083428h49.517715c18.578286 0 37.156571-18.578286 37.156571-37.083428V208.969143c0-18.505143-18.578286-37.010286-37.156571-37.010286h-49.517715c-18.651429 0-37.229714 18.505143-37.229714 37.010286v228.571428h-210.505143V208.969143c0-18.505143-18.651429-37.010286-37.229714-37.010286z" fill="#FEBB8B"></path>';
            break;
        case 5:
            $data = '<path d="M154.916571 169.691429h1609.142858v707.364571h-1609.142858z" fill="#FFFFFF" p-id="3058"></path><path d="M1779.565714 73.142857c24.795429 0 49.590857 24.722286 43.446857 49.444572v747.446857a48.859429 48.859429 0 0 1-49.590857 49.371428H144.603429a48.786286 48.786286 0 0 1-49.590858-49.371428V209.042286c0-24.722286 18.578286-49.444571 49.590858-49.444572h1021.805714v-37.010285c0-24.722286 18.578286-49.444571 49.517714-49.444572h563.565714zM733.037714 264.630857h-49.590857c-18.578286 0-37.156571 18.578286-37.156571 37.083429v302.665143c0 12.507429 0 31.232 6.217143 37.083428l173.348571 172.909714c11.044571 10.971429 36.790857 12.214857 42.349714 12.434286h1.024s31.012571 0 43.373715-12.434286l185.782857-172.909714c6.217143-6.217143 6.217143-18.505143 6.217143-30.866286V307.858286c0-18.505143-18.578286-37.010286-37.156572-37.010286h-49.517714c-18.651429 0-37.156571 18.505143-37.156572 37.010286v277.942857l-105.325714 105.033143-105.325714-104.96V301.714286c0-18.505143-18.505143-37.083429-37.083429-37.083429z m-445.952 0h-49.517714c-18.578286 0-37.156571 18.578286-37.156571 37.083429v488.009143c0 18.505143 18.578286 37.010286 37.156571 37.010285h297.252571c18.578286 0 37.156571-18.505143 37.156572-37.010285v-49.444572c0-18.578286-18.578286-37.083429-37.156572-37.083428H324.242286v-401.554286c0-18.432-18.578286-37.010286-37.156572-37.010286z m1399.661715-92.672h-384c-18.578286 0-37.083429 18.505143-37.083429 37.010286v315.026286c0 18.578286 18.505143 37.083429 37.083429 37.083428h297.252571v142.116572h-297.252571c-18.578286 0-37.083429 18.432-37.083429 37.010285v49.444572c0 18.505143 18.505143 37.083429 37.083429 37.083428h384c18.578286 0 37.156571-18.578286 37.156571-37.083428V474.624c0-18.505143-18.578286-37.083429-37.156571-37.083429h-297.179429V301.641143h297.179429c18.578286 0 37.156571-18.505143 37.156571-37.083429v-55.588571c0-18.505143-18.578286-37.010286-37.156571-37.010286z" fill="#EE672A"></path>';
            break;
        case 6:
            $data = '<path d="M154.916571 184.758857h1609.142858v707.364572h-1609.142858z" fill="#FFFFFF" p-id="2674"></path><path d="M1779.565714 93.037714c22.674286 0 45.421714 20.772571 44.324572 43.300572l-0.877715 6.144v747.446857c0 22.454857-15.36 44.909714-41.472 48.859428l-8.118857 0.585143H144.603429a48.859429 48.859429 0 0 1-49.005715-41.325714l-0.585143-8.118857V228.937143c0-22.454857 15.36-44.909714 41.398858-48.786286l8.192-0.585143h1021.805714v-37.083428c0-22.454857 15.36-44.909714 41.398857-48.786286l8.118857-0.658286h563.565714z m-92.891428 105.033143h-383.926857c-16.457143 0-32.914286 14.628571-36.425143 30.939429l-0.658286 6.144v574.464c0 16.457143 14.628571 32.914286 30.939429 36.425143l6.144 0.658285h384c16.530286 0 32.987429-14.628571 36.498285-30.939428l0.658286-6.144V494.592c0-16.530286-14.628571-32.914286-31.012571-36.425143l-6.144-0.658286h-297.179429V321.609143h297.179429c16.530286 0 32.987429-14.628571 36.498285-30.939429l0.658286-6.144v-49.371428c0-18.578286-18.578286-37.083429-37.156571-37.083429zM733.110857 284.598857h-49.590857c-18.578286 0-37.156571 18.505143-37.156571 37.010286v302.738286c0 16.749714 0 31.817143 6.217142 37.010285l173.348572 172.909715c7.899429 7.899429 23.259429 10.752 33.426286 11.849142l9.947428 0.585143 8.557714-0.438857c10.093714-0.950857 26.550857-3.657143 34.816-11.995428l185.782858-172.909715c4.973714-4.900571 5.997714-13.824 6.144-23.478857V327.68c0-18.505143-18.505143-37.010286-37.083429-37.010286h-49.517714c-18.651429 0-37.156571 18.505143-37.156572 37.083429v277.942857l-105.325714 104.96-105.325714-104.96V321.609143c0-18.505143-18.505143-37.010286-37.083429-37.010286z m-445.952 0h-49.517714c-16.530286 0-33.060571 14.628571-36.571429 30.866286l-0.585143 6.144v488.009143c0 16.530286 14.628571 32.914286 30.939429 36.425143l6.217143 0.658285h297.252571c16.530286 0 32.987429-14.628571 36.498286-30.939428l0.658286-6.144v-49.371429c0-16.603429-14.628571-32.987429-31.012572-36.425143l-6.144-0.658285H324.242286v-401.554286c0-18.505143-18.578286-37.010286-37.156572-37.010286z m1312.914286 296.448v142.116572h-210.505143V581.046857h210.505143z" fill="#FF0000"></path>';
            break;
    }
    return $data;
}

/*
    性别图标打印
*/
function getSexIconData($sex){
    $url = '';
    switch($sex){
        case '男':
            $url = 'css/img/sex/male.png';
            break;
        case '女':
            $url = 'css/img/sex/female.png';
            break;
        default:
            $url = 'css/img/sex/unknown.png';
            break;
    }
    return $url;
}

/*
    打印粉丝勋章
*/
function printFansMedal($start,$end,$border,$name,$level){
    $hex_start = dechex($start);
    $hex_end = dechex($end);
    $hex_border = dechex($border);

    echo '
    <div class="space-fans-medal" style="display:inline;">
    <div class="medal-box fans-medal" style="position: relative;vertical-align: middle;margin-left: 3px;margin-bottom: 5px;height: 20px;line-height: 15px;display: -ms-inline-flexbox;display: inline-flex;font-size: 10px;color: #f25d8e;border-radius: 1px;">
    <div class="medal-true-love" style="width: auto; padding-left: 8px;border-right-width: 0;border-bottom-left-radius: 1px;border-top-left-radius: 1px;padding-left: 2px;padding-right: 2px;white-space: nowrap;border-color: #'.$hex_border.'; color: rgb(255, 255, 255); background-image: linear-gradient(90deg, #'.$hex_start.', #'.$hex_end.');">
    <div class="tiny" style="transform: scale(.5);width: 200%;height: 200%;font-weight: 400;transform-origin: left 30%;font-size: 20px;">'.$name.'</div>
    </div>
    <div class="medal-level" style="border-color: #'.$hex_border.'; color: #'.$hex_start.';border-left: .5px solid #f25d8e;border-bottom-left-radius: 0;border-top-left-radius: 0;border-bottom-right-radius: 1px;border-top-right-radius: 1px;width: 18px;background: #fff;"><div class="tiny" style="transform: scale(.5);width: 200%;height: 200%;font-weight: 400;transform-origin: left 30%;font-size: 20px;">'.$level.'</div></div></div><!----></div>
    ';
}

/*
    打印会员勋章
*/
function printVIPIcon($label){
    echo '<br />';
    echo '<img style="display:inline; transform: scale(0.5,0.5); vertical-align: middle;" referrerPolicy="no-referrer" src="'.$label["img_label_uri_hans_static"].'" title="'.$label["text"].'">';
}

/*
    打印认证徽章
*/
function printOfficialRank($official){
    $type = $official["type"];
    $svg = '';
    switch($type){
        case -1:
            return;
        case 0:
            $svg = 'https://s1.hdslb.com/bfs/seed/jinkela/short/user-avatar/personal.svg';
            break;
        case 1:
            $svg = 'https://s1.hdslb.com/bfs/seed/jinkela/short/user-avatar/business.svg';
            break;
    }
    $title = $official["title"];
    $desc = $official["desc"];
    echo '
    <div class="official-box" style="display: inline;">
        <object style="transform: scale(0.4); vertical-align: middle;" data="'.$svg.'" type="image/svg+xml"></object>
        <h3 style="vertical-align: middle;display: inline;">'.$desc.$title.'</h3>
    </div>
    ';
}

/*
    打印投稿云图
*/
function printUploadedVideosCloudWords($uid){
    $uvtd = new UserVideoTagData($uid);
    $json = $uvtd->getinfo();
    if($json["code"] != 200){
        echo '<div id="video-cloudpic" style="width: 500px; height: 500px; margin: 0 auto; vertical-align:center"><h1>该用户未上传视频</h1></div>';
        return ;
    }
    echo '
    <div id="video-cloudpic" style="width: 500px; height: 500px; margin: 0 auto;"></div>
    <script>
            var vchart = echarts.init(document.getElementById("video-cloudpic"));

            var voption = {
                tooltip: {},
                series: [ {
                    type: "wordCloud",
                    gridSize: 2,
                    sizeRange: [12, 50],
                    rotationRange: [-90, 90],
                    shape: "square",
                    drawOutOfBound: false,
                    width: 500,
                    height: 500,
                    shrinkToFit: true,
                    textStyle: {
                        color: function () {
                            return "rgb(" + [
                                Math.round(Math.random() * 160),
                                Math.round(Math.random() * 160),
                                Math.round(Math.random() * 160)
                            ].join(",") + ")";
                        }
                    },
                    emphasis: {
                        focus: "self",
                        
                        textStyle: {
                            shadowBlur: 10,
                            shadowColor: "#333"
                        }
                    },
                    data: [';
    foreach($json["data"]["fluency"] as $id => $info_arr){
        echo '
                    {
                        name: "'.$info_arr["name"].'",
                        value: '.$info_arr["value"].'
                    },
                    ';
    }
    echo '          {
                        name: "",
                        value: 0
                    }
                    ]
                } ]
            };

            vchart.setOption(voption);

            window.onresize = vchart.resize;
        </script>
    ';
}

/*
    打印成分云图
*/
function printDynamicsCloudWords($uid){
    $uvtd = new UserDynamicsData($uid);
    $json = $uvtd->getinfo();
    if($json["code"] != 200){
        echo '<div id="dynamics-cloudpic" style="width: 500px; height: 500px; margin: 0 auto; vertical-align:center"><h1>该用户没有动态</h1></div>';
        return ;
    }
    echo '
    <div id="dynamics-cloudpic" style="width: 500px; height: 500px; margin: 0 auto;"></div>
    <script>
            var dchart = echarts.init(document.getElementById("dynamics-cloudpic"));

            var doption = {
                tooltip: {
                    formatter:function (params) {
                        return "你知道我是什么成分吧";
                    }
                },
                series: [ {
                    type: "wordCloud",
                    gridSize: 2,
                    sizeRange: [50, 50],
                    shape: "square",
                    drawOutOfBound: false,
                    width: 500,
                    height: 500,
                    shrinkToFit: true,
                    textStyle: {
                        color: function () {
                            return "rgb(" + [
                                Math.round(Math.random() * 160),
                                Math.round(Math.random() * 160),
                                Math.round(Math.random() * 160)
                            ].join(",") + ")";
                        }
                    },
                    emphasis: {
                        focus: "self",
                        
                        textStyle: {
                            shadowBlur: 10,
                            shadowColor: "#333"
                        }
                    },
                    data: [';
    foreach($json["data"] as $ele){
    echo '
                    {
                        name: "'.$ele["nick"].'",
                        value: 1
                    },
                    ';
    }
    echo '          {
                        name: "",
                        value: 0
                    }
                    ]
                } ]
            };

            dchart.setOption(doption);

            window.onresize = dchart.resize;
        </script>
    ';
}
}
?>