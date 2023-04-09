<?php
require(__DIR__.'/class.php');

$bvid = $_GET["bvid"];
$cid = $_GET["cid"];

$vus = new VideoStreamURL($bvid, $cid);
$json = $vus->getinfo();
$url = $json["data"]["durl"][0]["url"];
$file_name = $bvid . '.mp4'; // 要保存的文件名

// 初始化 CURL
$ch = curl_init();

// 设置 CURL 参数
$header = array(
    'Content-Type: application/octet-stream',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36 Edg/111.0.1661.62',
    'referer: https://www.bilibili.com'
);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HEADER, false);

// 执行 CURL 请求并获取文件内容
$file_content = curl_exec($ch);

// 关闭 CURL 句柄
curl_close($ch);

if ($file_content === false) {
    echo 'alert("Failed!");';
    return;
}

// 设置响应头，指定要下载的文件名和文件类型
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $file_name . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . strlen($file_content));

// 输出文件内容
echo $file_content;
?>
