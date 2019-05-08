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
                        }elseif(!empty($_GET['action']) && $_GET['action'] == 'viewpostcontent'){
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
                    <?php include "templates/loginbutton.php"; ?>
                </ol>
                <?php
                if(!empty($_GET['action']) && $_GET['action'] == 'viewboard'){
                    include "templates/viewboard.php";
                }elseif(!empty($_GET['action']) && $_GET['action'] == 'viewbbspost'){
                    include "templates/viewbbspost.php";
                }elseif(!empty($_GET['action']) && ($_GET['action'] == 'viewpostcontent' || $_GET['action'] == 'debug')){
                    include "templates/viewpostcontent.php";
                }elseif(!empty($_GET['action']) && $_GET['action'] == 'addnewpost'){
                    include "templates/addnewpost.php";
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