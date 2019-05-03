<?php
$type = "important";
require "../sessionCheck.php";
//檢查字串
function inputCheck($data){   //輸入字元安全性處理
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = nl2br($data);
    $data = preg_replace( "/\r|\n/", "", $data );
    $data = trim($data);
    return $data;
}
// 若沒有 GET 值就跳回後台首頁
if(empty($_SERVER['QUERY_STRING'])){
    header("Location: index.php?action=index");
    exit;
}else{
    $refer = $_POST['refer'];
    $connect = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DBNAME, $DB_PORT);
    if (mysqli_connect_errno()) {
        die('無法連線到資料庫: ' . mysqli_connect_error());
    }
    mysqli_query($connect, "SET NAMES 'utf8'");
    mysqli_query($connect, "SET CHARACTER_SET_CLIENT=utf8");
    mysqli_query($connect, "SET CHARACTER_SET_RESULTS=utf8");
    date_default_timezone_set("Asia/Taipei");

    // 修改消息
    if($_GET['action'] == 'modifynews'){
        // 若消息標題為空
        if(empty($_POST['newsTitle'])){
            header("Location: index.php?modifyErr=1&$refer");
            exit;
        // 若消息內容為空
        }elseif(empty($_POST['newsContent'])){
            header("Location: index.php?modifyErr=2&$refer");
            exit;
        // 都沒問題開始寫入資料
        }else{
            $pid = $_POST['refpage'];
            $newsID = $_POST['newsID'];
            $newsType = $_POST['newsType'];
            $newsTitle = $_POST['newsTitle'];
            $newsContent = inputCheck($_POST['newsContent']);
            mysqli_query($connect, "UPDATE `news` SET `newsType`='$newsType', `newsTitle`='$newsTitle', `newsContent`='$newsContent' WHERE `newsOrder`=$newsID;");
            mysqli_close($connect);
            header("Location: index.php?msg=modifynewssuccess&action=article_news&type=newslist&p=$pid");
            exit;
        }

    // 刪除消息
    }elseif($_GET['action'] == 'delnews'){
        $nid = $_POST['nid'];
        $pid = $_POST['refpage'];
        mysqli_query($connect, "DELETE FROM `news` WHERE `newsOrder`=$nid");
        mysqli_close($connect);
        header("Location: index.php?msg=delnewssuccess&action=article_news&type=newslist&p=$pid");
        exit;

    // 新增消息
    }elseif($_GET['action'] == 'addnews'){
        if(empty($_POST['newstitle'])){
            header("Location: index.php?msg=addnewserrtitle&action=article_news&type=postnewnews");
            exit;
        }elseif(empty($_POST['newscontent'])){
            header("Location: index.php?msg=addnewserrcontent&action=article_news&type=postnewnews");
            exit;
        }elseif(empty($_POST['newstype'])){
            header("Location: index.php?msg=addnewserrtype&action=article_news&type=postnewnews");
            exit;
        }elseif(empty($_POST['uid'])){
            header("Location: index.php?msg=addnewserruid&action=article_news&type=postnewnews");
            exit;
        }else{
            // 找 session 儲存的暱稱
            $username = $_SESSION['uid'];
            // 找出目前登入身分的暱稱
            $sql = mysqli_query($connect, "SELECT * FROM `member` WHERE `uid`=$uid;");
            $row = mysqli_fetch_array($sql, MYSQLI_BOTH);
            // 若暱稱與資料不符
            if($row['uid'] != $uid){
                header("Location: index.php?msg=addnewserruid&action=article_news&type=postnewnews");
                exit;
            }
        }
        $newstitle = $_POST['newstitle'];
        $newstype = $_POST['newstype'];
        $newscontent = inputCheck($_POST['newscontent']);
        $uid = $_POST['uid'];
        date_default_timezone_set("Asia/Taipei");
        $posttime = date('Y-m-d H:i:s');
        mysqli_query($connect, "INSERT INTO `news` (`newsType`, `newsTitle`, `newsContent`, `postTime`, `postUser`) VALUES ('$newstype', '$newstitle', '$newscontent', '$posttime', '$uid');");
        header("Location: index.php?msg=addnewssuccess&action=article_news&type=newslist");
        exit;
    }elseif($_POST[''] == ''){

    // 如果上述條件都不符合跳回後台首頁
    }else{
        header("Location: index.php?action=index");
        exit;
    }
}

?>