<?php
require_once(__DIR__."/header.php");
require(__DIR__."/class.php");

print '<div class="container-main">';

if(isset($_GET["uid"])){
    $uid = $_GET["uid"];
    if(strcmp($uid,'')){
        printSearchBoxWithUID($uid);
        echo '<script>searchboxUp();</script>';
        $printer = new UserInfoPrint();
        $printer->printInfo($uid);
    }else{
        printSearchBox();
        echo '<script>searchboxDown();</script>';
    }

}else{
    printSearchBox();
    echo '<script>searchboxDown();</script>';
}
print '</div>';
require(__DIR__."/footer.php");
?>