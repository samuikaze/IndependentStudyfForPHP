<?php
require_once 'sessionCheck.php';
require_once 'templates/functions.php';
$self = basename(__FILE__);
// 沒有 GET 值重導回檢視通知頁面
if (empty($_SERVER['QUERY_STRING'])) {
    header("Location: ?action=viewnotifies");
    exit;
}
// 沒有登入就導回登入頁面
if (empty($_SESSION['auth'])) {
    header("Location: member.php?action=login&loginErrType=5&refer=" . urlencode($self));
    exit;
} else { ?>
    <!DOCTYPE html>
    <html lang="zh-TW">

    <head>
        <title>通知管理 | 洛嬉遊戲 L.S. Games</title>
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
            <div class="container">
                <!-- 麵包屑 -->
                <ol class="breadcrumb">
                    <li><a href="index.html"><i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;洛嬉遊戲</a></li>
                    <li class="thisPosition">通知管理</li>
                    <?php include "templates/loginbutton.php"; ?>
                </ol>
                <div class="row">
                    <div class="col-md-10 col-md-push-1">
                        <?php if (!empty($_GET['action']) && $_GET['action'] == 'viewnotifies') {
                            // 若沒有通知可顯示
                            if ($notifynums == 0) { ?>
                                <div class="panel panel-info" style="margin-top: 1em;">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">資訊</h3>
                                    </div>
                                    <div class="panel-body">
                                        <h2 class="info-warn">目前沒有通知！<br /><br /></h2>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <table class="table table-hover">
                                    <thead>
                                        <tr class="info">
                                            <th colspan="2">通知一覽</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($notifyData = mysqli_fetch_array($notifySql, MYSQLI_ASSOC)){ ?>
                                        <!-- 一則通知 -->
                                        <tr>
                                            <td <?php echo ($notifyData['notifyStatus'] == 'u')? "" : "colspan=\"2\""; ?>><a href="#" class="notify-link <?php echo ($notifyData['notifyStatus'] == 'u')? "notify-unread" : "notify-read"; ?>">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <span class="pull-left"><?php echo $notifyData['notifySource']; ?>・<?php echo $notifyData['notifyTime']; ?></span>
                                                            
                                                            <div class="clearfix"></div>
                                                            <div class="notify-content">
                                                                <?php echo (empty($notifyData['notifyTitle']))? "" : "<h3>" . $notifyData['notifyTitle'] . "</h3>"; ?>
                                                                <span><?php echo $notifyData['notifyContent']; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a></td>
                                            <?php echo ($notifyData['notifyStatus'] == 'u')? "<td valign=\"middle\"><span class=\"pull-right\">標示為已讀</span></td>" : ""; ?>
                                        </tr>
                                        <!-- /一則通知 -->
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="panel panel-danger" style="margin-top: 1em;">
                                <div class="panel-heading">
                                    <h3 class="panel-title">錯誤</h3>
                                </div>
                                <div class="panel-body">
                                    <h2 class="news-warn">無此功能，請依正常程序操作！<br /><br />
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-lg btn-success" href="notification.php">返回通知一覽</a>
                                        </div>
                                    </h2>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php include_once "templates/footer.php"; ?>
        </div>
    </body>

    </html>
<?php } ?>