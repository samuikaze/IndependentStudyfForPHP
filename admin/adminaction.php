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
    if(!empty($_POST['refer'])){
        $refer = $_POST['refer'];
    }
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
            $newsContent = $_POST['newsContent'];
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
        $newscontent = $_POST['newscontent'];
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
            if($_FILES["boardimage"]["error"] == 4 || empty($_FILES["boardimage"])){
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
            if(!empty($_POST['hideboard'])){
                $hideboard = 1;
            }else{
                $hideboard = 0;
            }
            $boardname = $_POST['boardname'];
            $boarddescript = inputCheck($_POST['boarddescript']);
            $boardctime = date("Y-m-d H:i:s");
            // 不論有沒有要上傳檔案都先建立討論板
            $sql = "INSERT INTO `bbsboard` (`boardName`, `boardDescript`, `boardCTime`, `boardCreator`, `boardHide`) VALUES ('$boardname', '$boarddescript', '$boardctime', '$uid', $hideboard);";
            mysqli_query($connect, $sql);
            // 若有要上傳檔案就取得討論板ID當作檔名
            if($fileUpload == true){
                $idsql = mysqli_query($connect, "SELECT * FROM `bbsboard` ORDER BY `boardID` DESC LIMIT 0, 1;");
                $idRow = mysqli_fetch_array($idsql, MYSQLI_ASSOC);
                $targetfilename = "board-" . $idRow['boardID'] . ".$fileextension";
                // 把新的檔案移到正確的路徑
                move_uploaded_file($_FILES["boardimage"]["tmp_name"], "../images/bbs/board/$targetfilename");
                mysqli_query($connect, "UPDATE `bbsboard` SET `boardImage`='$targetfilename' WHERE `boardID`=" . $idRow['boardID']);
            }
            mysqli_close($connect);
            header("Location: index.php?action=board_admin&type=boardlist&msg=createboardsuccess");
            exit;
        }

    // 上架商品
    }elseif($_GET['action'] == 'addgoods'){
        $refer = "index.php?action=goods_admin&type=addgoods";
        // 商品名稱為空
        if(empty($_POST['goodname'])){
            mysqli_close($connect);
            header("Location: $refer&msg=addgoodserrname");
            exit;
        // 商品價格為空或為零
        }elseif(empty($_POST['goodprice'])){
            mysqli_close($connect);
            header("Location: $refer&msg=addgoodserrprice");
            exit;
        // 商品數量為空或為零
        }elseif(empty($_POST['goodquantity']) || $_POST['goodquantity'] == 0){
            mysqli_close($connect);
            header("Location: $refer&msg=addgoodserrquantity");
            exit;
        // 商品描述為空
        }elseif(empty($_POST['gooddescript'])){
            mysqli_close($connect);
            header("Location: $refer&msg=addgoodserrdescript");
            exit;
        // 判別檔案上傳的狀態
        }else{
            // 沒有上傳檔案
            if($_FILES["goodimage"]["error"] == 4 || empty($_FILES["goodimage"])){
                $fileUpload = false;
            // 有上傳檔案
            }else{
                // 檔案大小過大
                if($_FILES["goodimage"]["error"] == 1 || $_FILES["goodimage"]["error"] == 2){
                    mysqli_close($connect);
                    header("Location: $refer&msg=addgoodserrfilesize");
                    exit;
                }
                $fileextension = pathinfo($_FILES['goodimage']['name'], PATHINFO_EXTENSION);
                // 檔案類型不正確
                if( !in_array($fileextension, array('jpg', 'png', 'gif') ) ){
                    mysqli_close($connect);
                    header("Location: $refer&msg=addgoodserrfiletype");
                    exit;
                }
                $fileUpload = true;
            }
            // 找 session 儲存的暱稱
            $username = $_SESSION['uid'];
            $goodsname = $_POST['goodname'];
            $goodprice = $_POST['goodprice'];
            $goodqty = $_POST['goodquantity'];
            $gooddescript = $_POST['gooddescript'];
            $postdate = date('Y-m-d H:i:s');
            $postuser = $_SESSION['uid'];
            // 不論如何先執行新增
            mysqli_query($connect, "INSERT INTO `goodslist` (`goodsName`, `goodsDescript`, `goodsPrice`, `goodsQty`, `goodsPostDate`, `goodsUp`) VALUES ('$goodsname', '$gooddescript', '$goodprice', '$goodqty', '$postdate', '$postuser');");
            if($fileUpload == true){
                //取得最後一筆資料更新檔案名稱
                $fnameIndex = mysqli_fetch_array(mysqli_query($connect, "SELECT `goodsOrder` FROM `goodslist` ORDER BY `goodsOrder` DESC LIMIT 0, 1;"), MYSQLI_ASSOC);
                $filename = "goods-" . $fnameIndex['goodsOrder'] . ".$fileextension";
                // 把新的檔案移到正確的路徑
                move_uploaded_file($_FILES["goodimage"]["tmp_name"], "../images/goods/$filename");
                mysqli_query($connect, "UPDATE `goodslist` SET `goodsImgUrl`='$filename' WHERE `goodsOrder`=" . $fnameIndex['goodsOrder'] . ";");
            }
            mysqli_close($connect);
            header("Location: index.php?action=goods_admin&type=goodslist&msg=addgoodsuccess");
            exit;
        }
    // 編輯商品
    }elseif($_GET['action'] == 'editgoods'){
        // 若商品識別碼為空
        if(empty($_POST['gid'])){
            mysqli_close($connect);
            header("Location: action=goods_admin&type=goodslist&msg=editgoodserrgid");
            exit;
        }else{
            $gid = $_POST['gid'];
            $refer = "index.php?action=modifygoods&goodid=$gid" . ((empty($_POST['refpage']))? "" : "&refpage=" . $_POST['refpage']);
        }
        // 商品名稱為空
        if(empty($_POST['goodname'])){
            mysqli_close($connect);
            header("Location: $refer&msg=editgoodserrname");
            exit;
        // 商品價格為空
        }elseif(empty($_POST['goodprice'])){
            mysqli_close($connect);
            header("Location: $refer&msg=editgoodserrprice");
            exit;
        // 商品數量為空或為零
        }elseif(empty($_POST['goodquantity']) || $_POST['goodquantity'] == 0){
            mysqli_close($connect);
            header("Location: $refer&msg=editgoodserrquantity");
            exit;
        // 商品描述為空
        }elseif(empty($_POST['gooddescript'])){
            mysqli_close($connect);
            header("Location: $refer&msg=editgoodserrdescript");
            exit;
        // 判別檔案上傳的狀態
        }else{
            $fnameIndex = mysqli_query($connect, "SELECT * FROM `goodslist` WHERE `goodsOrder`=$gid;");
            $fnameRows = mysqli_num_rows($fnameIndex);
            // 若找不到該商品
            if($fnameRows == 0){
                mysqli_close($connect);
                header("Location: $refer&msg=editgoodserrnodata");
                exit;
            }else{
                $fnameDatas = mysqli_fetch_array($fnameIndex, MYSQLI_ASSOC);
            }
            // 沒有上傳檔案
            if($_FILES["goodimage"]["error"] == 4 || empty($_FILES["goodimage"])){
                $fileUpload = false;
            // 有上傳檔案
            }else{
                // 檔案大小過大
                if($_FILES["goodimage"]["error"] == 1 || $_FILES["goodimage"]["error"] == 2){
                    mysqli_close($connect);
                    header("Location: $refer&msg=editgoodserrfilesize");
                    exit;
                }
                $fileextension = pathinfo($_FILES['goodimage']['name'], PATHINFO_EXTENSION);
                // 檔案類型不正確
                if( !in_array($fileextension, array('jpg', 'png', 'gif') ) ){
                    mysqli_close($connect);
                    header("Location: $refer&msg=editgoodserrfiletype");
                    exit;
                }
                $fileUpload = true;
            }
            // 刪除商品圖片
            $deldoardimage = false;
            if(!empty($_POST['delgoodimage']) && $_POST['delgoodimage'] == "true"){
                // 如果有上傳圖片可是也把刪除圖片打勾了
                if($fileUpload == true){
                    mysqli_close($connect);
                    header("Location: $refer&msg=editgoodserrupdel");
                    exit;
                // 沒問題就把圖片先刪除
                }else{
                    $filename = $fnameDatas['goodsImgUrl'];
                    // 若圖片是系統預設圖片
                    if($filename == "default.jpg"){
                        mysqli_close($connect);
                        header("Location: $refer&msg=editgoodserrnodel");
                        exit;
                    }else{
                        unlink("../images/goods/$filename");
                        $delgoodsimage = true;
                    }
                }
            }
            $goodsname = $_POST['goodname'];
            $goodprice = $_POST['goodprice'];
            $goodqty = $_POST['goodquantity'];
            $gooddescript = $_POST['gooddescript'];
            // 若有上傳檔案
            if($fileUpload == true){
                $filename = "goods-$gid.$fileextension";
                move_uploaded_file($_FILES["goodimage"]["tmp_name"], "../images/goods/$filename");
                mysqli_query($connect, "UPDATE `goodslist` SET `goodsName`='$goodsname', `goodsImgUrl`='$filename', `goodsDescript`='$gooddescript', `goodsPrice`='$goodprice', `goodsQty`='$goodqty' WHERE `goodsOrder`=$gid;");
            // 沒有上傳檔案
            }else{
                // 若要刪除商品圖
                if($delgoodsimage == true){
                    mysqli_query($connect, "UPDATE `goodslist` SET `goodsName`='$goodsname', `goodsImgUrl`='default.jpg', `goodsDescript`='$gooddescript', `goodsPrice`='$goodprice', `goodsQty`='$goodqty' WHERE `goodsOrder`=$gid;");
                // 不刪除商品圖
                }else{
                    mysqli_query($connect, "UPDATE `goodslist` SET `goodsName`='$goodsname', `goodsDescript`='$gooddescript', `goodsPrice`='$goodprice', `goodsQty`='$goodqty' WHERE `goodsOrder`=$gid;");
                }
            }
            mysqli_close($connect);
            header("Location: index.php?action=goods_admin&type=goodslist&msg=editgoodsuccess" . ((empty($_POST['refpage']))? "" : "&pid=" . $_POST['refpage']));
            exit;
        }
    // 下架商品
    }elseif($_GET['action'] == 'delgoods'){
        // 若商品識別碼為空
        if(empty($_POST['gid'])){
            mysqli_close($connect);
            header("Location: action=goods_admin&type=goodslist&msg=delgoodserrgid");
            exit;
        }else{
            $gid = $_POST['gid'];
            // 沒有refpage值
            if(empty($_POST['refpage'])){
                $refer = "?action=delgoods&goodid=13";
            }else{
                $refer = "?action=delgoods&goodid=13&refpage=" . $_POST['refpage'];
            }
            // 先判斷有沒有上傳商品圖片
            $goodsdata = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM `goodslist` WHERE `goodsOrder`='$gid';"), MYSQLI_ASSOC);
            // 若有上傳圖片
            if($goodsdata['goodsImgUrl'] != "default.jpg"){
                $fname = $goodsdata['goodsImgUrl'];
                unlink("../images/goods/$fname");
            }
            // 執行下架
            mysqli_query($connect, "DELETE FROM `goodslist` WHERE `goodsOrder`=$gid;");
            mysqli_close($connect);
            $successRefer = "index.php?action=goods_admin&type=goodslist" . ((empty($_POST['refpage']))? "" : "&pid=" . $_POST['refpage']) . "&msg=delgoodsuccess";
            header("Location: $successRefer");
            exit;
        }

    // 通知已出貨
    }elseif($_GET['action'] == 'notifysend'){
        // 若訂單編號為空
        if(empty($_GET['oid'])){
            mysqli_close($connect);
            header("Location: index.php?action=order_admin&type=vieworderlist&msg=nooid");
            exit;
        // 如果各商品數量資料為空
        }elseif(empty($_POST['goodsqty'])){
            $oid = $_GET['oid'];
            mysqli_close($connect);
            header("Location: index.php?action=vieworderdetail&oid=$oid&msg=nogoodsqty");
            exit;
        }else{
            $oid = $_GET['oid'];
            $goodsqty = $_POST['goodsqty'];
            // 先處理訂單裡商品的資料
            // 先拆品項
            $goods = explode(",", $goodsqty);
            // 再把商品ID($goodsinfo[$i][0])和價格拆開($goodsinfo[$i][1])
            $goodsinfo = array();
            foreach ($goods as $i => $val) {
                $goodsinfo[$i] = explode(":", $goods[$i]);
                // 處理 SQL 條件語法
                if ($i == 0) {
                    $condition = $goodsinfo[$i][0];
                    $gOrder = "WHEN " . $goodsinfo[$i][0] . " THEN `goodsQty`-" . $goodsinfo[$i][1];
                } else {
                    $condition .= "," . $goodsinfo[$i][0];
                    $gOrder .= " WHEN " . $goodsinfo[$i][0] . " THEN `goodsQty`-" . $goodsinfo[$i][1];
                }
            }
            $gOrder .= " END";
            // 更新商品出貨後數量
            $goodsdata = mysqli_query($connect, "UPDATE `goodslist` SET `goodsQty`=CASE `goodsOrder` $gOrder WHERE `goodsOrder` IN ($condition);");
            mysqli_query($connect, "UPDATE `orders` SET `orderStatus`='已出貨' WHERE `orderID`=$oid;");
            mysqli_close($connect);
            header("Location: index.php?action=order_admin&type=vieworderlist&msg=sendupdatesuccess");
            exit;
        }
    // 審核取消訂單
    }elseif($_GET['action'] == 'applyremoveorder'){
        // 若訂單編號為空
        if(empty($_POST['oid'])){
            mysqli_close($connect);
            header("Location: index.php?action=order_admin&type=vieworderlist&msg=nooid");
            exit;
        }else{
            // 若審核選項沒有選，通常是透過開發工具改的
            if(empty($_POST['reviewResult'])){
                mysqli_close($connect);
                header("Location: index.php?action=order_admin&type=vieworderlist&msg=noreviewresult");
                exit;
            }else{
                $oid = $_POST['oid'];
                // 選是選擇通過審核
                if($_POST['reviewResult'] == 'true'){
                    // 更新主訂單資料
                    mysqli_query($connect, "UPDATE `orders` SET `orderStatus`='訂單已取消' WHERE `orderID`=$oid;");
                    // 更新取消訂單記錄
                    mysqli_query($connect, "UPDATE `removeorder` SET `removeStatus`='passed' WHERE `targetOrder`=$oid;");
                }else{
                    // 先取得原訂單狀態
                    $orderStatusSQL = mysqli_fetch_array(mysqli_query($connect, "SELECT `orderApplyStatus` FROM `orders` WHERE `orderID`=$oid;"), MYSQLI_ASSOC);
                    $orderStatus = $orderStatusSQL['orderApplyStatus'];
                    // 更新主訂單資料
                    mysqli_query($connect, "UPDATE `orders` SET `orderStatus`='$orderStatus', `removeApplied`=0, `orderApplyStatus`=NULL WHERE `orderID`=$oid;");
                    // 移除取消訂單的紀錄
                    mysqli_query($connect, "DELETE FROM `removeorder` WHERE `targetOrder`=$oid;");
                }
                mysqli_close($connect);
                header("Location: index.php?action=order_admin&type=vieworderlist");
                exit;
            }
        }
    }else{
        mysqli_close($connect);
        header("Location: index.php?action=index");
        exit;
    }
}

?>