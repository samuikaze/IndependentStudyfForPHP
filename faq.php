<?php
require_once "sessionCheck.php";
$self = basename(__FILE__);
if (empty($_SERVER['QUERY_STRING']) != True) {
    $self .= "?" . $_SERVER['QUERY_STRING'];
    $self = str_replace("&", "+", $self);
}
?>
<html lang="zh-TW">

<head>
    <title>常見問題 | 洛嬉遊戲 L.S. Games</title>
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
            <div id="content-wrap" class="container">
                <!-- 麵包屑 -->
                <ol class="breadcrumb">
                    <li><a href="./"><i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;洛嬉遊戲</a></li>
                    <li class="thisPosition">常見問題</li>
                    <?php include "templates/loginbutton.php"; ?>
                </ol>
                <!-- FAQ 內容 -->
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">常見問題１</h3>
                    </div>
                    <div class="panel-body">解答內容。</div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">常見問題２</h3>
                    </div>
                    <div class="panel-body">解答內容。</div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">常見問題３</h3>
                    </div>
                    <div class="panel-body">解答內容。</div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">常見問題４</h3>
                    </div>
                    <div class="panel-body">解答內容。</div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">常見問題５</h3>
                    </div>
                    <div class="panel-body">解答內容。</div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">常見問題６</h3>
                    </div>
                    <div class="panel-body">解答內容。</div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">常見問題７</h3>
                    </div>
                    <div class="panel-body">解答內容。</div>
                </div>
                <div class="col-md-12 text-center">
                    <a href="contact.php" class="btn btn-default btn-lg">找不到問題的解答嗎？</a>
                </div>
            </div>
        </div>
        <?php include_once "templates/footer.php"; ?>
    </div>
</body>

</html>
<?php
mysqli_close($connect);
?>