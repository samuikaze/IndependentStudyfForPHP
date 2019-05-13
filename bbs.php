<?php
    require_once "sessionCheck.php";
    $self = basename(__FILE__);
    if(empty($_SERVER['QUERY_STRING']) != True){
        $self .= "?" . $_SERVER['QUERY_STRING'];
        $self = str_replace("&", "+", $self);
    }else{
        header("Location: $self?action=viewboard");
        exit;
    }
    // 張貼新文章、刪除文章必須登入後才可使用
    if(($_GET['action'] == 'addnewpost' || $_GET['action'] == 'delpost' || $_GET['action'] == 'replypost' || $_GET['action'] == 'editpost') && empty($_SESSION['uid'])){
        header("Location: member.php?action=login&loginErrType=5&refer=" . urlencode($self) );
        exit;
    }
    if (empty($_GET['refpage'])) {
        $refpage = 1;
    } else {
        $refpage = $_GET['refpage'];
    }
    if(!empty($_GET['refbid'])){
        $refbid = $_GET['refbid'];
    }else{
        $refbid = "";
    }
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
	<title>討論專區 | 洛嬉遊戲 L.S. Games</title>
	<?php include_once "templates/metas.php"; ?>
</head>
<body onload="loadProgress()">
    <!-- 要加入載入動畫這邊請加上 onload="loadProgress()" -->
    <?php include_once "templates/loadscr.php"; ?>
    <div class="pageWrap">
        <?php
            if (isset($_COOKIE['sid']) == False) {
                include_once "templates/loginform.php";
            }
            include_once "templates/header.php";
        ?>
        <div class="courses">
            <div class="container">
                <!-- 麵包屑 -->
                <ol class="breadcrumb">
                    <li><a href="index.html"><i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;洛嬉遊戲</a></li>
                    <li <?php echo (!empty($_GET['action']) && $_GET['action'] == 'viewboard')? "class=\"thisPosition\"" : ""; ?>><?php echo (!empty($_GET['action']) && $_GET['action'] != 'viewboard')? "<a href=\"?action=viewboard\">" : ""; ?>討論區首頁<?php echo (!empty($_GET['action']) && $_GET['action'] != 'viewboard')? "</a>" : ""; ?></li>
                    <?php
                        if(!empty($_GET['action']) && $_GET['action'] == 'viewbbspost'){
                            echo "<li class=\"thisPosition\">檢視討論板</li>";
                        }elseif(!empty($_GET['action']) && ($_GET['action'] == 'viewpostcontent' || $_GET['action'] == 'delpost')){
                            if(empty($_GET['refbid'])){
                                echo "<li><a style=\"cursor: not-allowed;\" title=\"無法取得您最後瀏覽的討論板識別碼\">檢視討論板</a></li>";
                            }else{
                                $refbid = $_GET['refbid'];
                                if(empty($_GET['refpage'])){
                                    $refpage = 1;
                                }else{
                                    $refpage = $_GET['refpage'];
                                }
                                echo "<li><a href=\"bbs.php?action=viewbbspost&bid=$refbid&pid=$refpage\">檢視討論板</a></li>";
                            }
                        }
                    ?>
                    <?php echo (!empty($_GET['action']) && $_GET['action'] == 'viewpostcontent')? "<li class=\"thisPosition\">檢視討論板文章</li>" : ""; ?>
                    <?php if (!empty($_GET['action']) && $_GET['action'] == 'delpost'){
                        if(empty($_GET['refpostid'])){
                            echo "<li><a style=\"cursor: not-allowed;\" title=\"無法取得您最後瀏覽的討論板文章識別碼\">檢視討論板文章</a></li><li class=\"thisPosition\">刪除貼文／回文</li>";
                        }else{
                            echo "<li><a href=\"?action=viewpostcontent&postid=" . $_GET['refpostid'] . "&refbid=$refbid&refpage=$refpage\">檢視討論板文章</a></li><li class=\"thisPosition\">刪除貼文／回文</li>";
                        }
                    } ?>
                    <?php include "templates/loginbutton.php"; ?>
                </ol>
                <?php
                if(!empty($_GET['action']) && $_GET['action'] == 'viewboard'){
                    include "templates/bbs/viewboard.php";
                }elseif(!empty($_GET['action']) && $_GET['action'] == 'viewbbspost'){
                    include "templates/bbs/viewbbspost.php";
                }elseif(!empty($_GET['action']) && ($_GET['action'] == 'viewpostcontent')){
                    include "templates/bbs/viewpostcontent.php";
                }elseif(!empty($_GET['action']) && $_GET['action'] == 'addnewpost'){
                    include "templates/bbs/addnewpost.php";
                }elseif(!empty($_GET['action']) && $_GET['action'] == 'editpost'){
                    include "templates/bbs/editpost.php";
                }elseif(!empty($_GET['action']) && $_GET['action'] == 'delpost'){
                    include "templates/bbs/delpost.php";
                }elseif(!empty($_GET['action']) && $_GET['action'] == 'replypost'){
                    include "templates/bbs/addnewreply.php";
                }else{
                    header("Location: $self?action=viewboard");
                    exit;
                }
                ?>
            </div>
        </div>
        <?php include_once "templates/footer.php"; ?>
    </div>
</body>
</html>
<?php mysqli_close($connect); ?>