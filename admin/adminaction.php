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
    mysqli_close($connect);
    header("Location: index.php?action=index");
    exit;
}else{
    $refer = $_POST['refer'];
    // 修改消息
    if($_GET['action'] == 'modifynews'){
        // 若消息標題為空
        if(empty($_POST['newsTitle'])){
            mysqli_close($connect);
            header("Location: index.php?modifyErr=1&$refer");
            exit;
        // 若消息內容為空
        }elseif(empty($_POST['newsContent'])){
            mysqli_close($connect);
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
        $uid = $_POST['uid'];
        if(empty($_POST['newstitle'])){
            mysqli_close($connect);
            header("Location: index.php?msg=addnewserrtitle&action=article_news&type=postnewnews");
            exit;
        }elseif(empty($_POST['newscontent'])){
            mysqli_close($connect);
            header("Location: index.php?msg=addnewserrcontent&action=article_news&type=postnewnews");
            exit;
        }elseif(empty($_POST['newstype'])){
            mysqli_close($connect);
            header("Location: index.php?msg=addnewserrtype&action=article_news&type=postnewnews");
            exit;
        }elseif(empty($_POST['uid'])){
            mysqli_close($connect);
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
                mysqli_close($connect);
                header("Location: index.php?msg=addnewserruid&action=article_news&type=postnewnews");
                exit;
            }
        }
        $newstitle = $_POST['newstitle'];
        $newstype = $_POST['newstype'];
        $newscontent = inputCheck($_POST['newscontent']);
        $posttime = date('Y-m-d H:i:s');
        mysqli_query($connect, "INSERT INTO `news` (`newsType`, `newsTitle`, `newsContent`, `postTime`, `postUser`) VALUES ('$newstype', '$newstitle', '$newscontent', '$posttime', '$uid');");
        mysqli_close($connect);
        header("Location: index.php?msg=addnewssuccess&action=article_news&type=newslist");
        exit;

    // 修改討論板
    }elseif($_GET['action'] == 'modifyboard'){
        $refpage = $_POST['refpage'];
        $bid = $_POST['bid'];
        $deldoardimage = False;
        // 討論版名稱為空
        if(empty($_POST['boardname'])){
            mysqli_close($connect);
            header("Location: index.php?modifyerr=1&$refer");
            exit;
        // 討論版描述為空
        }elseif(empty($_POST['boarddescript'])){
            mysqli_close($connect);
            header("Location: index.php?modifyerr=2&$refer");
            exit;
        // 判別檔案上傳的狀態
        }else{
            // 沒有上傳檔案
            if($_FILES["boardimage"]["error"] == 4 || empty($_FILES['boardimage'])){
                $fileUpload = false;
            }else{
                // 檔案大小過大
                if($_FILES["boardimage"]["error"] == 1 || $_FILES["boardimage"]["error"] == 2){
                    mysqli_close($connect);
                    header("Location: index.php?modifyerr=3&$refer");
                    exit;
                }
                $fileextension = pathinfo($_FILES['boardimage']['name'], PATHINFO_EXTENSION);
                // 檔案類型不正確
                if( !in_array($fileextension, array('jpg', 'png', 'gif') ) ){
                    mysqli_close($connect);
                    header("Location: index.php?modifyerr=4&$refer");
                    exit;
                }
                // 儲存的檔名
                $targetfilename = "board-" . $bid . ".$fileextension";
                $fileUpload = true;
            }
            $boardname = $_POST['boardname'];
            $boarddescript = inputCheck($_POST['boarddescript']);
            if($_POST['hideboard'] == "true"){
                $hideboard = "`boardHide`=1";
            }else{
                $hideboard = "`boardHide`=0";
            }
            // 刪除討論區圖片
            if(!empty($_POST['delboardimage']) && $_POST['delboardimage'] == "true"){
                // 如果有上傳圖片可是也把刪除圖片打勾了
                if($fileUpload == true){
                    mysqli_close($connect);
                    header("Location: index.php?modifyerr=5&$refer");
                    exit;
                // 沒問題就把圖片先刪除
                }else{
                    unlink("../images/bbs/board/board-$bid.jpg");
                    $deldoardimage = True;
                }
            }
            // 有要上傳檔案
            if($fileUpload == true){
                // 把新的檔案移到正確的路徑
                move_uploaded_file($_FILES["boardimage"]["tmp_name"], "../images/bbs/board/$targetfilename");
                $sql = "UPDATE `bbsboard` SET `boardName`='$boardname', `boardImage`='$targetfilename', `boardDescript`='$boarddescript', $hideboard WHERE `boardID`=$bid;";
            // 沒有要上傳檔案
            }else{
                // 沒有要刪除圖片
                if($deldoardimage != True){
                    $sql = "UPDATE `bbsboard` SET `boardName`='$boardname', `boardDescript`='$boarddescript', $hideboard WHERE `boardID`=$bid;";
                // 要刪除圖片
                }else{
                    $sql = "UPDATE `bbsboard` SET `boardName`='$boardname', `boardImage`='default.jpg', `boardDescript`='$boarddescript', $hideboard WHERE `boardID`=$bid;";
                }
            }
            mysqli_query($connect, $sql);
            mysqli_close($connect);
            header("Location: index.php?modifyerr=5&action=board_admin&type=boardlist&p=$refpage");
            exit;
        }

    // 刪除討論板
    }elseif($_GET['action'] == 'delboard'){
        $bid = $_POST['bid'];
        $pid = $_POST['refpage'];
        $boardimage = $_POST['boardimage'];
        if($boardimage != 'default.jpg'){
            unlink("../images/bbs/board/$boardimage");
        }
        mysqli_query($connect, "DELETE FROM `bbsboard` WHERE `boardID`=$bid");
        mysqli_close($connect);
        header("Location: index.php?modifyerr=6&action=board_admin&type=boardlist&p=$pid");
        exit;

    // 新建討論板
    }elseif($_GET['action'] == 'createboard'){
        // 討論板名稱為空
        if(empty($_POST['boardname'])){
            mysqli_close($connect);
            header("Location: index.php?msg=createboarderrtitle&action=board_admin&type=createboard");
            exit;
        // 討論板描述為空
        }elseif(empty($_POST['boarddescript'])){
            mysqli_close($connect);
            header("Location: index.php?msg=createboarderrdescript&action=board_admin&type=createboard");
            exit;
        // 判別檔案上傳的狀態
        }else{
            // 沒有上傳檔案
            if(empty($_FILES['boardimage']) || $_FILES["boardimage"]["error"] == 4){
                $fileUpload = false;
            // 有上傳檔案
            }else{
                // 檔案大小過大
                if($_FILES["boardimage"]["error"] == 1 || $_FILES["boardimage"]["error"] == 2){
                    mysqli_close($connect);
                    header("Location: index.php?modifyerr=3&$refer");
                    exit;
                }
                $fileextension = pathinfo($_FILES['boardimage']['name'], PATHINFO_EXTENSION);
                // 檔案類型不正確
                if( !in_array($fileextension, array('jpg', 'png', 'gif') ) ){
                    mysqli_close($connect);
                    header("Location: index.php?modifyerr=4&$refer");
                    exit;
                }
                // 儲存的檔名
                $targetfilename = "board-" . $bid . ".$fileextension";
                $fileUpload = true;
            }
            // 找 session 儲存的暱稱
            $username = $_SESSION['uid'];
            $uid = $_POST['uid'];
            // 找出目前登入身分的暱稱
            $sql = mysqli_query($connect, "SELECT * FROM `member` WHERE `uid`=$uid;");
            $row = mysqli_fetch_array($sql, MYSQLI_BOTH);
            // 若暱稱與資料不符
            if($row['uid'] != $uid){
                mysqli_close($connect);
                header("Location: index.php?msg=createboarderruser&action=board_admin&type=createboard");
                exit;
            }
            if($_POST['hideboard'] == "true"){
                $hideboard = 1;
            }else{
                $hideboard = 0;
            }
            $boardname = $_POST['boardname'];
            $boarddescript = inputCheck($_POST['boarddescript']);
            $boardctime = date("Y-m-d H:i:s");
            // 有要上傳檔案
            if($fileUpload == true){
                // 把新的檔案移到正確的路徑
                move_uploaded_file($_FILES["boardimage"]["tmp_name"], "../images/bbs/board/$targetfilename");
                $sql = "INSERT INTO `bbsboard` (`boardName`, `boardImage`, `boardDescript`, `boardCTime`, `boardCreator`, `boardHide`) VALUES ('$boardname', '$targetfilename', '$boarddescript', '$boardctime', '$uid', $hideboard);";
            // 沒有要上傳檔案
            }else{
                $sql = "INSERT INTO `bbsboard` (`boardName`, `boardDescript`, `boardCTime`, `boardCreator`, `boardHide`) VALUES ('$boardname', '$boarddescript', '$boardctime', '$uid', $hideboard);";
            }
            mysqli_query($connect, $sql);
            mysqli_close($connect);
            header("Location: index.php?action=board_admin&type=boardlist&msg=createboardsuccess");
            exit;
        }
    // 都不符合上述條件
    }else{
        mysqli_close($connect);
        header("Location: index.php?action=index");
        exit;
    }
}

?>