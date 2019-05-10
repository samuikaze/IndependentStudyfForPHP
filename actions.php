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
                    $postcontent = inputCheck($_POST['postcontent']);
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
        }
    }
?>