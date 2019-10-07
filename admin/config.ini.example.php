<?php
    // 資料庫帳號
    $DB_USERNAME = '';
    // 資料庫密碼
    $DB_PASSWORD = '';
    // 資料庫伺服器位址
    $DB_HOST = 'localhost';
    // 資料庫連接埠（預設為 3306）
    $DB_PORT = '3306';
    // 資料庫名稱
    $DB_DBNAME = '108-1-1';

    # 以下為綠界金流相關設定，目前設定值皆為測試環境。
    $cHashKey = '5294y06JbISpM5x9';
    $cHashIV = 'v77hoKGq4kWxNNIS';
    $cMerchantID = '2000132';
    $cEncryptType = '1';

    #以下為 MySQL 資料庫連線的基本設定，包括 PHP 自身的時區設定
    $connect = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DBNAME, $DB_PORT);
    if (mysqli_connect_errno()) {
        die('無法連線到資料庫: ' . mysqli_connect_error());
    }
    mysqli_query($connect, "SET NAMES 'utf8'");
    mysqli_query($connect, "SET CHARACTER_SET_CLIENT=utf8");
    mysqli_query($connect, "SET CHARACTER_SET_RESULTS=utf8");
    date_default_timezone_set("Asia/Taipei");
?>