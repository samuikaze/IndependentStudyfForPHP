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
                    // 發文者與登入身份不符
                    if($username != $arthorID){
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
                    // 發文者與登入身份不符
                    if($username != $arthorID){
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
        }
    }
?>