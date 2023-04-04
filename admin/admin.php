<?php
    $email = $_POST["email"];
    $pwd = md5($_POST["pwd"]);

    if($email == '' || $pwd == ''){
        die("NO EMAIL AND PWD");
    }

    require_once(dirname(__DIR__)."\api\db.php");

    $sql = '
    SELECT id,email,pwd 
    FROM binfo_account
    WHERE id=1
    ';
    $result = mysqli_query($db,$sql);
    if($result){
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if($row['email'] == null){
            $sql = '
            INSERT INTO binfo_account
            (email,pwd)
            VALUES
            ("'.$email.'","'.$pwd.'")';
            $r = mysqli_query($db,$sql);
            if(!$r) echo '<script>alert("创建失败！")';
            else echo '<script>alert("第一次进入后台，已自动创建账号!\n请重新登录！"); open("login.php");</script>';
        }else{
            if($row['email'] == $email && $row['pwd'] == $pwd){
                echo '<script>alert("登录成功！");</script>';
            }else{
                echo '<script>alert("密码错误！");</script>';
            }
        }
    }else{
        echo '<script>alert("数据库错误！");</script>';
    }
?>