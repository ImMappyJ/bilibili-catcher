<?php
    define("_DB_HOST","127.0.0.1");
    define("_DB_USER","root");
    define("_DB_PWD","mayukang233.");
    define("_DB_NAME","b_info");

    define("_DB_TABLE_ACCOUNT","binfo_account");

    $db = mysqli_connect(_DB_HOST,_DB_USER,_DB_PWD);

    if(!$db){
        die('MySQL is wrong!Please check your configuration!');
    }

    if(!mysqli_select_db($db,_DB_NAME)){
        $sql = 'CREATE DATABASE '._DB_NAME;
        mysqli_query($db,$sql);
        mysqli_select_db($db,_DB_NAME);
    }

    mysqli_query($db , "set names utf8"); //设置编码防止乱码

    $sql = '
    CREATE TABLE IF NOT EXISTS `'._DB_TABLE_ACCOUNT.'`(
        `id` INT UNSIGNED AUTO_INCREMENT,
        `email` VARCHAR(100) NOT NULL,
        `pwd` VARCHAR(40) NOT NULL,
        PRIMARY KEY ( `id` )
     )ENGINE=InnoDB DEFAULT CHARSET=utf8;
     ';
     mysqli_query($db,$sql); //创建账号表格
?>