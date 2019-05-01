<?php
    require_once "admin/config.ini.php";
    require_once "sessionCheck.php";

    // 如果不是 POST 資料進來就重導到登入頁面
    if($_SERVER["REQUEST_METHOD"] != "POST"){
        if($_GET["action"] != "logout"){
            header("Location: member.php?action=login");
            exit;
        }
    }

    // 輸入字元安全性處理
    function inputCheck($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    /* 連線 MySQL 資料庫
     * mysql_connect 為非永久性連線，mysqli_connect 則為永久性連線
     */
    $connect = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DBNAME, $DB_PORT);
    if ( mysqli_connect_errno() ) {
        die('無法連線到資料庫: ' . mysqli_connect_error());
    }

    // 登入
    /*
     * 登入錯誤碼 1 = 帳號欄位為空，2 = 密碼欄位為空，3 = 資料有誤
     */
    if($_GET["action"] == "login"){
        if ( !empty($_POST["refer"]) ){
            $refer = $_POST["refer"];
        }else{
            $refer = "index.html";      // 全面更新 PHP 檔後請用尋找取代把.html取代為.php
        }
        // 資料判別
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            // 帳號欄位為空，重導回登入頁面
            if( empty( $_POST["username"] ) ){
                header("Location: member.php?action=login&loginErrType=1&refer=$refer");
                exit;
            }else{
                $username = inputCheck( $_POST["username"] );
            }
            // 密碼欄位為空
            if( empty( $_POST["password"] ) ){
                header("Location: member.php?action=login&loginErrType=2&refer=$refer");
                exit;
            }else{
                $password = inputCheck($_POST["password"]);
                $password = hash("sha512", $password);
            }
            // 都不為空就開始比對資料
            mysqli_query($connect, "SET NAMES 'utf8'");
            mysqli_query($connect, "SET CHARACTER_SET_CLIENT=utf8");
            mysqli_query($connect, "SET CHARACTER_SET_RESULTS=utf8");
            // 取資料
            $sql = mysqli_query($connect, "SELECT * FROM member WHERE userName = '$username'");
            $row = mysqli_fetch_array($sql, MYSQLI_BOTH);
            // 資料錯誤
            if ( $row['userName'] != $username || $row['userPW'] != $password ){
                mysqli_close($connect);
                header("Location: member.php?action=login&&loginErrType=3&refer=$refer");
                exit;
            // 資料正確
            }else{
                /* 登入區塊會有幾個參數
                 * SESSION部分有「auth」、「user」和「uid」
                 * COOKIE部分有「user」、「sid」和「auth」
                 * MySQL部分有「sID」、「userName」、「sessionID」和「loginTime」
                 */
                // PHP SESSION 寫入
                $_SESSION['auth'] = True;
                $_SESSION['user'] = $row['userNickname'];
                $_SESSION['uid'] = $row['userName'];
                // MySQL sessions 資料表寫入
                $username = $row['userName'];
                $sessionID = session_id();
                $ltime = date("Y-m-d H:i:s");
                $iprmtaddr = $_SERVER['REMOTE_ADDR'];
                if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                    $ipXFwFor = "";
                }else{
                    $ipXFwFor = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }
                if (empty($_SERVER['HTTP_VIA'])){
                    $iphttpvia = "";
                }else{
                    $iphttpvia = $_SERVER['HTTP_VIA'];
                }
                mysqli_query($connect, "INSERT INTO sessions (userName, sessionID, ipRmtAddr, ipXFwFor, ipHttpVia, loginTime) VALUES ('$username', '$sessionID', '$iprmtaddr', '$ipXFwFor', '$iphttpvia', '$ltime')");
                // Cookie 寫入 （登入後未瀏覽任一頁面則效期一個月）
                setcookie("user", $username, time() + 2592000);
                setcookie("sid", $sessionID, time() + 2592000);
                setcookie("auth", "true", time() + 2592000);
                mysqli_close($connect);
                header("Location: $refer");
                exit;
            }
        }
    }

    // 註冊
    /*
     * 註冊錯誤碼 1=帳號欄位為空 2=密碼欄為空 3=密碼確認欄為空 4=電子郵件欄位為空 5=密碼確認欄與密碼欄資料不同 6=暱稱欄位為空 7=帳號重複 8=註冊成功
     */
    if($_GET['action'] == 'register'){
        if ( !empty($_POST["refer"]) ){
            $refer = $_POST["refer"];
        }else{
            $refer = "index.html";      // 全面更新 PHP 檔後請用尋找取代把.html取代為.php
        }
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if( empty( $_POST["username"] ) ){
                header("Location: member.php?action=register&regErrType=1&refer=$refer");
                exit;
            }else{
                $username = inputCheck( $_POST["username"] );
            }
            // 密碼欄位為空
            if( empty( $_POST["password"] ) ){
                header("Location: member.php?action=register&regErrType=2&refer=$refer");
                exit;
            }else{
                // 密碼確認欄位為空
                if( empty( $_POST["passwordConfirm"] ) ){
                    header("Location: member.php?action=register&regErrType=3&refer=$refer");
                    exit;
                }else{
                    $password = inputCheck($_POST["password"]);
                    $passwordConfirm = inputCheck($_POST["passwordConfirm"]);
                    // 密碼欄位與密碼確認欄位資料不符
                    if($password != $passwordConfirm){
                        header("Location: member.php?action=register&regErrType=5&refer=$refer");
                        exit;
                    }else{
                        $password = hash("sha512", $password);
                    }
                }
            }
            // 電子郵件欄位為空
            if( empty( $_POST["email"] ) ){
                header("Location: member.php?action=register&regErrType=4&refer=$refer");
                exit;
            }else{
                $email = inputCheck($_POST["email"]);
            }
            if( empty( $_POST["usernickname"] ) ){
                header("Location: member.php?action=register&regErrType=6&refer=$refer");
                exit;
            }else{
                $usernickname = inputCheck($_POST["usernickname"]);
            }
            // 都不為空就開始寫入資料
            mysqli_query($connect, "SET NAMES 'utf8'");
            mysqli_query($connect, "SET CHARACTER_SET_CLIENT=utf8");
            mysqli_query($connect, "SET CHARACTER_SET_RESULTS=utf8");
            $regSql = "INSERT INTO `member` (`userName`, `userPW`, `userNickname`, `userEmail`, `userPriviledge`) VALUES ('$username', '$password', '$usernickname', '$email', '1')";
            mysqli_query($connect, $regSql);
            if(mysqli_errno($connect) == 1062){
                header("Location: member.php?action=register&regErrType=7&refer=$refer");
                exit;
            }else{
                header("Location: member.php?action=login&regErrType=8&refer=$refer");
                exit;
            }
        }
    }

    // 登出
    if ($_GET['action'] == 'logout'){
        // 本來就沒登入
        if($_SESSION['auth'] != True){
            mysqli_close($connect);
            header("Location: index.php");
        // 開始登出並清資料庫的 session 表
        }else{                                                      
            $_SESSION['auth'] == '';
            $_SESSION['user'] == '';
            $_SESSION['uid'] == '';
            $sid = $_COOKIE['sid'];
            // 清除資料庫中的 session 記錄
            mysqli_query($connect, "SET NAMES 'utf8'");
            mysqli_query($connect, "SET CHARACTER_SET_CLIENT=utf8");
            mysqli_query($connect, "SET CHARACTER_SET_RESULTS=utf8"); 
            mysqli_query($connect, "DELETE FROM sessions WHERE sessionID='$sid'");
            // 刪除cookie
            setcookie("user", "", time()-3600);
            setcookie("sid", "", time()-3600);
            setcookie("auth", "", time()-3600);
            mysqli_close($connect);
            session_unset();
            session_destroy();
            $refer = ( isset($_GET['refer']) == True ) ? $_GET['refer'] : "index.html";
            header("Location: $refer");
        }
   }
?>