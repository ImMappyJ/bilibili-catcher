<?php
    if(isset($_COOKIE["email"])){
        $email = $_COOKIE["email"];
        $pwd = $_COOKIE["pwd"];
    }else{
        if(!isset($_POST["email"]) || !isset($_POST["pwd"])){
            echo '<script>open("login.php");</script>';
        }else{
            $email = $_POST["email"];
            $pwd = md5($_POST["pwd"]);
        }
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
                $expire = time()+60*60*24*30;
                setcookie("email",$email,$expire); //cookie 30天过期
                setcookie("pwd",$pwd,$expire); // cookie 30天过期
                printMainPage();
            }else{
                echo '<script>alert("密码错误！"); open("login.php");</script>';
            }
        }
    }else{
        echo '<script>alert("数据库错误！");</script>';
    }


    function printMainPage(){
        echo '
        <head>
            <title>管理中心</title>
            <meta charset="utf-8" />
            <link rel="stylesheet" type="text/css" href="css/admin.css">
        </head>
        <body>
            <div id="container-main">
                <h1>管理中心</h1>
            </div>
        </body>
        ';
    }
?>