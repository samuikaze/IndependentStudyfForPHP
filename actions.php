<?php
    require "sessionCheck.php";
    //檢查字串
    function inputCheck($data){   //輸入字元安全性處理
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = nl2br($data);
        $data = preg_replace( "/\r|\n/", "", $data );
        $data = trim($data);
        return $data;
    }
    // 若沒有 GET 值就跳回首頁
    if(empty($_SERVER['QUERY_STRING'])){
        mysqli_close($connect);
        header("Location: ./");
        exit;
    }else{
        // 張貼新文章
        if($_GET['action'] == 'addnewpost'){
            // refer 為空
            if(empty($_POST['refer'])){
                $refer = "action=addnewpost&boardid=norefer";
            }else{
                $refer = $_POST['refer'];
            }
            // 文章標題為空
            if(empty($_POST['posttitle'])){
                mysqli_close($connect);
                header("Location: bbs.php?msg=addposterrtitle&$refer");
                exit;

            // 文章分類未選擇
            }elseif(empty($_POST['posttype'])){
                mysqli_close($connect);
                header("Location: bbs.php?msg=addposterrtype&$refer");
                exit;

            // 文章內容為空
            }elseif(empty($_POST['postcontent'])){
                mysqli_close($connect);
                header("Location: bbs.php?msg=addposterrcontent&$refer");
                exit;
            
            // 文章隸屬討論板為空
            }elseif(empty($_POST['targetboard'])){
                mysqli_close($connect);
                header("Location: bbs.php?msg=addposterrtargetboard&$refer");
                exit;

            // 使用者帳號為空
            }elseif(empty($_POST['userid'])){
                mysqli_close($connect);
                header("Location: bbs.php?msg=addposterruserid&$refer");
                exit;

            // 上述錯誤皆未發生
            }else{
                // 找 session 儲存的帳號
                $username = $_SESSION['uid'];
                // 若帳號與資料不符
                if($username != $_POST['userid']){
                    mysqli_close($connect);
                    header("Location: bbs.php?msg=addposterruid&$refer");
                    exit;
                // 資料都正確開始寫入資料
                }else{
                    $posttitle = $_POST['posttitle'];
                    $posttype = $_POST['posttype'];
                    $postcontent = $_POST['postcontent'];
                    $targetboard = $_POST['targetboard'];
                    $userid = $_POST['userid'];
                    $posttime = date("Y-m-d H:i:s");
                    $sql = "INSERT INTO `bbspost` (`postTitle`, `postType`, `postContent`, `postUserID`, `postTime`, `lastUpdateUserID`, `lastUpdateTime`, `postBoard`) VALUES ('$posttitle', '$posttype', '$postcontent', '$userid', '$posttime', '$userid', '$posttime', '$targetboard');";
                    mysqli_query($connect, $sql);
                    mysqli_close($connect);
                    header("Location: bbs.php?action=viewbbspost&bid=$targetboard&msg=addnewpostsuccess");
                    exit;
                }
            }
        // 刪除文章
        }elseif($_GET['action'] == 'delpost'){
            // refer 為空
            if(empty($_POST['refer'])){
                $refer = "action=delpost&boardid=norefer";
            }else{
                $refer = $_POST['refer'];
            }
            // 文章類型為空
            if(empty($_POST['type'])){
                mysqli_close($connect);
                header("Location: bbs.php?msg=delposterrtype&$refer");
                exit;
            // 文章 ID 為空
            }elseif(empty($_POST['id'])){
                mysqli_close($connect);
                header("Location: bbs.php?msg=delposterrpostid&$refer");
                exit;
            // 上述都沒錯誤
            }else{
                $type = ($_POST['type'] == 'post')? "bbspost" : "bbsarticle";
                $idtype = ($_POST['type'] == "post")? "postID" : "articleID";
                $targetid = $_POST['id'];
                $sql = mysqli_query($connect, "SELECT * FROM `$type` WHERE `$idtype`=$targetid;");
                $datarows = mysqli_num_rows($sql);
                // 找不到資料
                if ($datarows == 0) {
                    mysqli_close($connect);
                    header("Location: bbs.php?msg=delposterrnotfound&$refer");
                    exit;
                // 找到資料了
                }else{
                    $row = mysqli_fetch_array($sql, MYSQLI_ASSOC);
                    $username = $_SESSION['uid'];
                    $arthorID = ($_POST['type'] == 'post')? $row['postUserID'] : $row['articleUserID'];
                    $priv = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM `systemsetting` WHERE `settingName`='adminPriv';"), MYSQLI_ASSOC);
                    // 發文者與登入身份不符或權限不足
                    if($username != $arthorID && $_SESSION['priv'] < $priv['settingValue']){
                        mysqli_close($connect);
                        header("Location: bbs.php?msg=delposterrauthfail&$refer");
                        exit;
                    // 上述錯誤都沒有發生就開始刪除資料
                    }else{
                        $id = $_POST['id'];
                        // 貼文類型是主貼文
                        if($_POST['type'] == 'post'){
                            // 刪除主貼文要一併刪除回文
                            mysqli_query($connect, "DELETE FROM `bbspost` WHERE `postID`='$id';");
                            mysqli_query($connect, "DELETE FROM `bbsarticle` WHERE `articlePost`='$id';");
                        // 貼文類型是回文
                        }else{
                            mysqli_query($connect, "DELETE FROM `bbsarticle` WHERE `articleID`='$id';");
                        }
                        // 完成關閉資料庫連線
                        mysqli_close($connect);
                        // 如果是主貼文
                        if($_POST['type'] == 'post'){
                            // 判斷跳轉參數
                            if(empty($_POST['refbid'])){
                                $referpos = "bbs.php?action=viewboard&msg=delpostsuccessnobid";
                            }elseif(empty($_POST['refpage'])){
                                $referpos = "bbs.php?action=viewbbspost&bid=" . $_POST['refbid'] . "&pid=1&msg=delpostsuccess";
                            }else{
                                $referpos = "bbs.php?action=viewbbspost&bid=" . $_POST['refbid'] . "&pid=" . $_POST['refpage'] . "&msg=delpostsuccess";
                            }
                        // 如果是回文
                        }else{
                            // 主貼文識別碼為空
                            if(empty($_POST['refpostid'])){
                                if(empty($_POST['refbid'])){
                                    $referpos = "bbs.php?action=viewboard&msg=delpostsuccessnobid";
                                }elseif(empty($_POST['refpage'])){
                                    $referpos = "bbs.php?action=viewbbspost&bid=" . $_POST['refbid'] . "&pid=1&msg=delarticlesuccessnopostid";
                                }else{
                                    $referpos = "bbs.php?action=viewbbspost&bid=" . $_POST['refbid'] . "&pid=" . $_POST['refpage'] . "&msg=delarticlesuccessnopostid";
                                }
                            // 有主貼文識別碼
                            }else{
                                $referpos = "bbs.php?action=viewpostcontent&postid=" . $_POST['refpostid'] . "&refbid=1&refpage=2&msg=delarticlesuccess";
                            }
                        }
                        
                        // 執行跳轉
                        header("Location: $referpos");
                        exit;
                    }
                }
            }
        // 編輯文章
        }elseif(!empty($_GET['action']) && $_GET['action'] == 'editpost'){
            // refer 為空
            if(empty($_POST['refer'])){
                $refer = "action=editpost&boardid=norefer";
            }else{
                $refer = $_POST['refer'];
            }
            // 文章類型為空
            if(empty($_POST['type'])){
                mysqli_close($connect);
                header("Location: bbs.php?msg=editposterrtype&$refer");
                exit;
            // 文章類型是主貼文，但文章標題為空
            }elseif($_POST['type'] == 'post' && empty($_POST['title'])){
                mysqli_close($connect);
                header("Location: bbs.php?msg=editposterrtitle&$refer");
                exit;
            // 文章 ID 為空
            }elseif(empty($_POST['id'])){
                mysqli_close($connect);
                header("Location: bbs.php?msg=editposterrpostid&$refer");
                exit;
            // 上述都沒錯誤
            }else{
                $type = ($_POST['type'] == 'post')? "bbspost" : "bbsarticle";
                $idtype = ($_POST['type'] == "post")? "postID" : "articleID";
                $targetid = $_POST['id'];
                $sql = mysqli_query($connect, "SELECT * FROM `$type` WHERE `$idtype`=$targetid;");
                $datarows = mysqli_num_rows($sql);
                // 找不到資料
                if ($datarows == 0) {
                    mysqli_close($connect);
                    header("Location: bbs.php?msg=editposterrnotfound&$refer");
                    exit;
                // 找到資料了
                }else{
                    $row = mysqli_fetch_array($sql, MYSQLI_ASSOC);
                    $username = $_SESSION['uid'];
                    $arthorID = ($_POST['type'] == 'post')? $row['postUserID'] : $row['articleUserID'];
                    $priv = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM `systemsetting` WHERE `settingName`='adminPriv';"), MYSQLI_ASSOC);
                    // 發文者與登入身份不符或權限不足
                    if($username != $arthorID && $_SESSION['priv'] < $priv['settingValue']){
                        mysqli_close($connect);
                        header("Location: bbs.php?msg=editposterrauthfail&$refer");
                        exit;
                    // 上述錯誤都沒有發生就開始更新資料
                    }else{
                        $id = $_POST['id'];
                        $title = $_POST['title'];
                        $content = $_POST['content'];
                        $updateuser = $_SESSION['uid'];
                        $updateTime = date("Y-m-d H:i:s");
                        $refbid = (empty($_POST['refbid']))? "" : $_POST['refbid'];
                        $refpage = (empty($_POST['refpage']))? "" : $_POST['refpage'];
                        if(empty($_POST['refpostid'])){
                            mysqli_close($connect);
                            header("Location: bbs.php?msg=editposterrrefpid&$refer");
                            exit;
                        }else{
                            $refpostid = $_POST['refpostid'];
                        }
                        // 貼文類型是主貼文
                        if($_POST['type'] == 'post'){
                            $posttype = $_POST['posttype'];
                            $refmsg = "post";
                            // 更新主表格
                            mysqli_query($connect, "UPDATE `$type` SET `postTitle`='$title', `postType`='$posttype', `postContent`='$content', `lastUpdateUserID`='$updateuser', `lastUpdateTime`='$updateTime', `postStatus`=1, `postEdittime`='$updateTime' WHERE `$idtype`=$targetid");
                        // 貼文類型是回文
                        }elseif($_POST['type'] == 'article'){
                            $refmsg = "article";
                            // 更新回文表格
                            mysqli_query($connect, "UPDATE `$type` SET `articleTitle`='$title', `articleContent`='$content', `articleStatus`=1, `articleEdittime`='$updateTime' WHERE `$idtype`=$targetid");
                            // 更新最後操作資訊
                            mysqli_query($connect, "UPDATE `bbspost` SET `lastUpdateUserID`='$updateuser', `lastUpdateTime`='$updateTime' WHERE `postID`=$refpostid");
                        }
                        mysqli_close($connect);
                        // 完成更新跳回原貼文
                        header("Location: bbs.php?msg=edit" . $refmsg . "success&action=viewpostcontent&postid=$refpostid&refbid=$refbid&refpage=$refpage");
                    }
                }
            }
        }// 張貼新回文
        elseif(!empty($_GET['action']) && $_GET['action'] == 'addnewreply'){
            // refer 為空
            if(empty($_POST['refer'])){
                $refer = "action=addnewreply&refer=norefer";
            }else{
                $refer = $_POST['refer'];
            }

            // 文章內容為空
            if(empty($_POST['articlecontent'])){
                mysqli_close($connect);
                header("Location: bbs.php?msg=addarticleerrcontent&$refer");
                exit;
            
            // 回文隸屬主貼文識別碼為空
            }elseif(empty($_POST['postid'])){
                mysqli_close($connect);
                header("Location: bbs.php?msg=addarticleerrpostid&$refer");
                exit;

            // 上述錯誤皆未發生
            }else{
                // 找 session 儲存的帳號
                $username = $_SESSION['uid'];
                $articlecontent = $_POST['articlecontent'];
                $postid = $_POST['postid'];
                $posttime = date("Y-m-d H:i:s");
                // 若沒有輸入標題
                if(empty($_POST['articletitle'])){
                    $sql = "INSERT INTO `bbsarticle` (`articleContent`, `articleUserID`, `articleTime`, `articlePost`) VALUES ('$articlecontent', '$username', '$posttime', '$postid');";
                // 有輸入標題
                }else{
                    $articletitle = $_POST['articletitle'];
                    $sql = "INSERT INTO `bbsarticle` (`articleTitle`, `articleContent`, `articleUserID`, `articleTime`, `articlePost`) VALUES ('$articletitle', '$articlecontent', '$username', '$posttime', '$postid');";
                }
                // 寫入資料庫
                mysqli_query($connect, $sql);
                // 寫入最後操作時間
                mysqli_query($connect, "UPDATE `bbspost` SET `lastUpdateUserID`='$username', `lastUpdateTime`='$posttime' WHERE `postID`=$postid");
                // 推送通知
                $postData = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM `bbspost` WHERE `postID`=$postid;"), MYSQLI_ASSOC);
                $postuser = $postData['postUserID'];
                $notifytime = date("Y-m-d H:i:s");
                mysqli_query($connect, "INSERT INTO `notifications`(`notifyContent`, `notifyTitle`, `notifySource`, `notifyTarget`, `notifyURL`, `notifyTime`) VALUES ('有人回覆您的文章！', '文章有新回覆', '系統', '$postuser', 'bbs.php?action=viewpostcontent&postid=$postid', '$notifytime');");
                mysqli_close($connect);
                $refbid = (empty($_POST['refbid']))? "" : "&refbid=" . $_POST['refbid'];
                header("Location: bbs.php?msg=addreplysuccess&action=viewpostcontent&postid=$postid" . $refbid);
                exit;
            }
        }// 刪除登入階段
        elseif(!empty($_GET['action']) && $_GET['action'] == 'delsession'){
            $refer = "user.php?action=sessioncontrol";
            // 若 SESSION ID 為空
            if(empty($_GET['sid'])){
                mysqli_close($connect);
                header("Location: $refer&msg=delsessionerrsid");
                exit;
            }else{
                $sid = $_GET['sid'];
                $chksql = mysqli_query($connect, "SELECT `userName` FROM `sessions` WHERE `sID`='$sid';");
                $chksqlNums = mysqli_num_rows($chksql);
                // 若找不到資料
                if($chksqlNums == 0){
                    mysqli_close($connect);
                    header("Location: $refer&msg=delsessionerrnodata");
                    exit;
                }else{
                    $chkrow = mysqli_fetch_array($chksql, MYSQLI_ASSOC);
                    // 操作者非本人 
                    if($_SESSION['uid'] != $chkrow['userName']){
                        mysqli_close($connect);
                        header("Location: $refer&msg=delsessionerroperator");
                        exit;
                    // 都正確就把那條紀錄刪除
                    }else{
                        mysqli_query($connect, "DELETE FROM `sessions` WHERE `sID`=$sid;");
                        mysqli_close($connect);
                        header("Location: $refer&msg=delsessionsuccess");
                        exit;
                    }
                }
            }
        }// 修改使用者資料
        elseif(!empty($_GET['action']) && $_GET['action'] == 'edituserdata'){
            $refer = "user.php?action=usersetting";
            // 若要修改密碼但密碼欄位為空
            if(!empty($_POST['password']) && empty($_POST['passwordConfirm'])){
                mysqli_close($connect);
                header("Location: $refer&msg=usrseterremptypwdcnfrm");
                exit;
            // 密碼與確認密碼欄位值不一
            }elseif($_POST['password'] != $_POST['passwordConfirm']){
                mysqli_close($connect);
                header("Location: $refer&msg=usersettingerrpwdcnfrm");
                exit;
            // 暱稱為空及電子郵件為空已經用三元運算子解決
            // 都沒問題開始準備更新資料
            }else{
                // 先取得帳號對應的識別碼
                $username = $_SESSION['uid'];
                $usrsql = mysqli_query($connect, "SELECT * FROM `member` WHERE `userName`='$username';");
                $usrRs = mysqli_fetch_array($usrsql, MYSQLI_ASSOC);
                $uid = $usrRs['uid'];
                // 判斷檔案上傳的狀態
                // 沒有上傳檔案
                if($_FILES["avatorimage"]["error"] == 4 || empty($_FILES['avatorimage'])){
                    $fileUpload = false;
                //有上傳檔案則判斷檔案相關資料
                }else{
                    // 檔案大小過大
                    if($_FILES["avatorimage"]["error"] == 1 || $_FILES["avatorimage"]["error"] == 2){
                        mysqli_close($connect);
                        header("Location: $refer&msg=usersettingerrfilesize");
                        exit;
                    }
                    $fileextension = pathinfo($_FILES['avatorimage']['name'], PATHINFO_EXTENSION);
                    // 檔案類型不正確
                    if( !in_array($fileextension, array('jpg', 'png', 'gif') ) ){
                        mysqli_close($connect);
                        header("Location: $refer&msg=usersettingerrfiletype");
                        exit;
                    }
                    // 儲存的檔名為「user-<使用者ID>.<副檔名>」
                    $targetfilename = "user-$uid.$fileextension";
                    $fileUpload = true;
                }
                // 如不修改密碼
                if(empty($_POST['password'])){
                    $passwdSql = "";
                    $changePW = false;
                }else{
                    $passwdSql = "`userPW`='" . hash("sha512", $_POST['password']) . "', ";
                    $changePW = true;
                }
                $usernickname = (empty($_POST['usernickname']))? $usrRs['userNickname'] : $_POST['usernickname'];
                $email = (empty($_POST['useremail']))? $usrRs['userEmail'] : $_POST['useremail'];
                $userrealname = (empty($_POST['userrealname']))? "NULL" : "'" . $_POST['userrealname'] . "'";
                $userphone = (empty($_POST['userphone']))? "NULL" : "'" . $_POST['userphone'] . "'";
                $useraddress = (empty($_POST['useraddress']))? "NULL" : "'" . $_POST['useraddress'] . "'";
                // 如果要刪除虛擬形象
                if(!empty($_POST['delavatorimage']) && $_POST['delavatorimage'] == "true"){
                    // 如果有上傳圖片可是也把刪除圖片打勾了
                    if($fileUpload == true){
                        mysqli_close($connect);
                        header("Location: $refer&msg=usrseterravatorupdel");
                        exit;
                    // 本來就還沒上傳虛擬形象
                    }elseif($usrRs['userAvator'] == "exampleAvator.jpg"){
                        mysqli_close($connect);
                        header("Location: $refer&msg=usrseterravatornodel");
                        exit;
                    // 沒問題就把圖片先刪除
                    }else{
                        if($usrRs['userAvator'] != "exampleAvator.jpg"){
                            $deletefilename = "images/userAvator/" . $usrRs['userAvator'];
                            unlink($deletefilename);
                        }
                        $deluseravator = true;
                    }
                }else{
                    $deluseravator = false;
                }
                // 如果不上傳虛擬形象
                if($fileUpload == false){
                    if($deluseravator != True){
                        $result = mysqli_query($connect, "UPDATE `member` SET $passwdSql`userNickname`='$usernickname', `userEmail`='$email', `userRealName`=$userrealname, `userPhone`=$userphone, `userAddress`=$useraddress WHERE `uid`=$uid");
                    }else{
                        mysqli_query($connect, "UPDATE `member` SET $passwdSql`userAvator`='exampleAvator.jpg', `userNickname`='$usernickname', `userEmail`='$email', `userRealName`=$userrealname, `userPhone`=$userphone, `userAddress`=$useraddress WHERE `uid`=$uid");
                    }
                // 如果有上傳虛擬形象
                }else{
                    move_uploaded_file($_FILES["avatorimage"]["tmp_name"], "images/userAvator/$targetfilename");
                    mysqli_query($connect, "UPDATE `member` SET $passwdSql`userAvator`='$targetfilename', `userNickname`='$usernickname', `userEmail`='$email', `userRealName`=$userrealname, `userPhone`=$userphone, `userAddress`=$useraddress WHERE `uid`=$uid");
                }
                mysqli_close($connect);
                // 如果沒有修改密碼就直接導回原頁面
                if($changePW == false){
                    header("Location: $refer&msg=usersettingsuccess");
                // 如果有修改密碼則導向登入頁面
                }else{
                    header("Location: authentication.php?action=logout&type=updatepwd");
                }
                exit;
            }
        // ECPay 結帳
        /**
         * 只要換空間，下面兩個變數值一定要去用 PHP echo 一下看會不會有多餘的斜線
         * $order->Send['ReturnURL']
         * $order->Send['ClientBackURL']
         */
        }elseif(!empty($_GET['action']) && $_GET['action'] == 'checkout'){
            require_once 'api/ECPay.Payment.Integration.php';
            try {
                $order = new ECPay_ALLInOne();
                // 服務參數
                $order->ServiceURL = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";
                $order->HashKey = $cHashKey;
                $order->HashIV = $cHashIV;
                $order->MerchantID = $cMerchantID;
                $order->EncryptType = $cEncryptType;

                // 客戶資料（先存 SESSION，結完帳再寫入資料庫）
                /**
                 * Json 中文會變成 unicode 的代碼，所以先用 urlencode 處理掉
                 * 轉換完後再用 urldecode 解碼就好
                 */
                $_SESSION['cart']['cName'] = urlencode($_POST['clientname']);
                $_SESSION['cart']['cPhone'] = $_POST['clientphone'];
                $_SESSION['cart']['cAddress'] = urlencode($_POST['clientaddress']);
                $_SESSION['cart']['fPattern'] = urlencode($_POST['fPattern']);
                $_SESSION['cart']['uid'] = urlencode($_SESSION['uid']);
                // 先把資料寫進資料庫中
                $data = urldecode(json_encode($_SESSION['cart']));
                $tradeID = $_SESSION['cart']['tradeID'];
                mysqli_query($connect, "INSERT INTO `ordertemp`(`tradeID`, `contents`) VALUES ('$tradeID', '$data');");

                // 基本參數 (請依系統規劃自行調整)
                $MerchantTradeNo = "Test".time();
                // 付款完成通知回傳的網址
                $order->Send['ReturnURL'] = "http://" . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname($_SERVER['PHP_SELF'])) . "actions.php?action=order_process";
                // 訂單編號
                $order->Send['MerchantTradeNo'] = $tradeID;
                // 交易時間
                $order->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');
                // 交易金額
                $order->Send['TotalAmount'] = $_SESSION['cartTotal'] + 70;
                // 交易描述
                $order->Send['TradeDesc'] = "test";
                // 付款方式:全功能
                $order->Send['ChoosePayment'] = ECPay_PaymentMethod::ALL;
                // 客戶完成付款後返回網站的按鈕網址
                $order->Send['ClientBackURL'] = "http://" . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname($_SERVER['PHP_SELF'])) . "userorder.php?action=checkstatus&casher=ecpay";

                // 訂單的商品資料
                // 處理商品資訊
                $inCar = $_SESSION['cart'][0];
                $qty = $_SESSION['cart'][0];
                // 處理取資料 SQL
                foreach ($inCar as $i => $inCarVal) {
                    if ($i == 0) {
                        $gdSql = "`goodsOrder`=$inCarVal";
                        $od = "ORDER BY CASE `goodsOrder` WHEN $inCarVal THEN " . ($i + 1);
                    } else {
                        $gdSql .= " OR `goodsOrder`=$inCarVal";
                        $od .= " WHEN $inCarVal THEN " . ($i + 1);
                    }
                }
                $od .= " END";
                // 取資料顯示
                $perfSql = mysqli_query($connect, "SELECT * FROM `goodslist` WHERE $gdSql $od;");
                $j = 0;
                while ($goodsData = mysqli_fetch_array($perfSql, MYSQLI_ASSOC)) {
                    array_push($order->Send['Items'], array(
                        'Name' => $goodsData['goodsName'],
                        'Price' => (int) $goodsData['goodsPrice'],
                        'Currency' => "元",
                        'Quantity' => (int) $_SESSION['cart'][1][$j]
                    ));
                    $j += 1;
                }
                
                // 關閉資料庫連線並產生訂單 (自動送至綠界)
                mysqli_close($connect);
                $order->CheckOut();

            } catch (Exception $e) {
                echo $e->getMessage();
            }

        // 處理綠界資料
        /**
         * 不需額外檢查各項參數，$feedback = $AL->CheckOutFeedback() 這項只要 MAC 值不同就會拋出錯誤。
         * 這邊其實是綠界 POST 資料回來，所以 SESSION 值在這邊無作用
         * 要帶自訂值到這個頁面要使用綠界的 custom_field
         */
        }elseif(!empty($_GET['action']) && $_GET['action'] == 'order_process'){
            // 付款結果通知
            require 'api/ECPay.Payment.Integration.php';
            try {
                // 收到綠界科技的付款結果訊息，並判斷檢查碼是否相符
                $AL = new ECPay_AllInOne();
                $AL->MerchantID = $cMerchantID;
                $AL->HashKey = $cHashKey;
                $AL->HashIV = $cHashIV;
                // 加密方式
                /**「ECPay_EncryptType::ENC_MD5」為 MD5
                 * 「ECPay_EncryptType::ENC_SHA256」為 SHA256 */
                $AL->EncryptType = ECPay_EncryptType::ENC_SHA256;
                $feedback = $AL->CheckOutFeedback();
                // 以付款結果訊息進行相對應的處理
                /**
                 * 回傳的綠界科技的付款結果訊息如下（$feedback）:
                 * Array(
                 *     [MerchantID] =>
                 *     [MerchantTradeNo] =>
                 *     [StoreID] =>
                 *     [RtnCode] =>
                 *     [RtnMsg] =>
                 *     [TradeNo] =>
                 *     [TradeAmt] =>
                 *     [PaymentDate] =>
                 *     [PaymentType] =>
                 *     [PaymentTypeChargeFee] =>
                 *     [TradeDate] =>
                 *     [SimulatePaid] =>
                 *     [CustomField1] =>
                 *     [CustomField2] =>
                 *     [CustomField3] =>
                 *     [CustomField4] =>
                 *     [CheckMacValue] =>
                 * )
                 * 
                 * 綠界測試信用卡卡號
                 * 4311-9522-2222-2222
                 * 末三碼
                 * 222
                 */
                // 要處理的程式放在這裡，例如將線上服務啟用、更新訂單資料庫付款資訊等
                $orderNo = $feedback['MerchantTradeNo'];
                // 取出使用者的資料，正式寫進訂單表中，然後刪除那筆暫存資料
                $datas = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM `ordertemp` WHERE `tradeID` = '$orderNo'"));
                $cf1 = json_decode($datas['contents'], true);

                $userrealname = $cf1['cName'];
                $userphone = $cf1['cPhone'];
                $useraddress = $cf1['cAddress'];
                $username = $cf1['uid'];
                $orderprice = $feedback['TradeAmt'];
                $orderdate = $feedback['TradeDate'];
                $orderpattern = $feedback['PaymentType'];
                $status = ($feedback['RtnMsg'] == "交易成功") ? "等待出貨" : "等待付款";
                $freight = "70";
                $ordercasher = $feedback['PaymentType'];
                //$ordercasher = ($_SESSION['cart']['cashType'] == "cash") ? $_SESSION['cart']['clientcasher'] : "取貨付款";
                // 處理 SQL 的 orderContent 字串
                // 每個項目用 , 隔開，其中品項與數量以 : 隔開
                $ordercontent = "";
                foreach ($cf1[0] as $i => $val) {
                    // 內容不為空才執行
                    if (!empty($val)) {
                        // 第一次跑不需要加逗號
                        if ($i == 0) {
                            $ordercontent .= "$val:" . $cf1[1][$i];
                        } else {
                            $ordercontent .= ",$val:" . $cf1[1][$i];
                        }
                        // 否則就跳過
                    } else {
                        continue;
                    }
                }
                // 寫入訂單資料
                mysqli_query($connect, "INSERT INTO `orders` (`tradeID`, `orderMember`, `orderContent`, `orderRealName`, `orderPhone`, `orderAddress`, `orderPrice`, `orderDate`, `orderCasher`, `orderPattern`, `orderFreight`, `orderStatus`) VALUES ('$orderNo', '$username', '$ordercontent', '$userrealname', '$userphone', '$useraddress', '$orderprice', '$orderdate', '$ordercasher', '$orderpattern', '$freight', '$status');");
                mysqli_query($connect, "DELETE FROM `ordertemp` WHERE `tradeID`='$orderNo';");
                mysqli_close($connect);
                // 在網頁端回應 1|OK
                echo '1|OK';
            } catch(Exception $e) {
                echo '0|' . $e->getMessage();
            }
        // 通知已付款
        }elseif(!empty($_GET['action']) && $_GET['action'] == 'notifypaid'){
            // 若訂單編號為空
            if(empty($_GET['oid'])){
                mysqli_close($connect);
                header("Location: user.php?action=orderlist&msg=notifyerrnooid");
            }else{
                $oid = $_GET['oid'];
                mysqli_query($connect, "UPDATE `orders` SET `orderStatus`='已通知付款' WHERE `orderID`=$oid;");
                mysqli_close($connect);
                header("Location: user.php?action=orderlist&msg=notifysuccess");
                exit;
            }
        // 通知已取貨
        }elseif(!empty($_GET['action']) && $_GET['action'] == 'notifytaked'){
            // 若訂單編號為空
            if(empty($_GET['oid'])){
                mysqli_close($connect);
                header("Location: user.php?action=orderlist&msg=notifyerrnooid");
            }else{
                $oid = $_GET['oid'];
                mysqli_query($connect, "UPDATE `orders` SET `orderStatus`='已取貨' WHERE `orderID`=$oid;");
                mysqli_close($connect);
                header("Location: user.php?action=orderlist&msg=notifysuccess");
                exit;
            }
        // 申請取消訂單
        }elseif(!empty($_GET['action']) && $_GET['action'] == 'removeorder'){
            // 如果訂單編號為空
            if(empty($_POST['oid'])){
                mysqli_close($connect);
                header("Location: user.php?action=orderlist&msg=notifyerrnooid");
                exit;
            // 如果申請原因為空
            }elseif(empty($_POST['removereason'])){
                mysqli_close($connect);
                header("Location: user.php?action=removeorder&oid=" . $_POST['oid'] . "&msg=removeerrnoremovereason");
                exit;
            }elseif(empty($_POST['orderstatus'])){
                mysqli_close($connect);
                header("Location: user.php?action=removeorder&oid=" . $_POST['oid'] . "&msg=removeerrnoorderstatus");
                exit;
            }else{
                $oid = $_POST['oid'];
                $orderstatus = $_POST['orderstatus'];
                $reason = $_POST['removereason'];
                $removedate = date("Y-m-d H:i:s");
                mysqli_query($connect, "INSERT INTO `removeorder` (`targetOrder`, `removeReason`, `removeDate`) VALUES ('$oid', '$reason', '$removedate');");
                mysqli_query($connect, "UPDATE `orders` SET `orderStatus`='已申請取消訂單', `removeApplied`=1, `orderApplyStatus`='$orderstatus' WHERE `orderID`=$oid;");
                mysqli_close($connect);
                header("Location: user.php?action=orderlist&msg=removesuccess");
                exit;
            }
        // 取消結帳
        }elseif(!empty($_GET['action']) && $_GET['action'] == 'cancelorder'){
            // 如果不在結帳狀態
            if(empty($_SESSION['cart']['checkoutstatus']) || $_SESSION['cart']['checkoutstatus'] != 'notcomplete'){
                mysqli_close($connect);
                header("Location: userorder.php?action=viewcart&msg=notinorder");
                exit;
            }else{
                unset($_SESSION['cart']);
                mysqli_close($connect);
                header("Location: userorder.php?action=viewcart&msg=cancelsuccess");
                exit;
            }
        }
    }
?>