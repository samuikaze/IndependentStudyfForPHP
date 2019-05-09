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

            

            
        }
    }
?>