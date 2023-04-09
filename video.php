<?php
require_once(__DIR__."/header.php");
require(__DIR__."/api/class.php");

print '<div class="container-main">';

if(isset($_GET["bvid"])){
    $bvid = $_GET["bvid"];
    if(strcmp($bvid,'')){
        printVSearchBoxWithBVID($bvid);
        echo '<script>searchboxUp();</script>';
        $vi = new VideoInfoPrint();
        $vi->printInfo($bvid);
    }else{
        printVSearchBox();
        echo '<script>searchboxDown();</script>';
    }
}else{
    printVSearchBox();
    echo '<script>searchboxDown();</script>';
}
print '</div>';
require(__DIR__."/footer.php");
?>