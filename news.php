<?php
require_once "sessionCheck.php";
$self = basename(__FILE__);
if (empty($_SERVER['QUERY_STRING']) != True) {
    $self .= "?" . $_SERVER['QUERY_STRING'];
    $self = str_replace("&", "+", $self);
} else {
    mysqli_close($connect);
    header("Location: news.php?action=viewnews");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <title>最新消息 | 洛嬉遊戲 L.S. Games</title>
    <?php include_once "templates/metas.php"; ?>
</head>

<body onload="loadProgress()">
    <!-- 要加入載入動畫這邊請加上 onload="loadProgress()" -->
    <?php include_once "templates/loadscr.php";?>
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
                    <li><a href="./"><i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;洛嬉遊戲</a></li>
                    <?php echo ($_GET['action'] == 'viewnews') ? "<li class=\"thisPosition\">" : "<li><a href=\"news.php?action=viewnews&p=" . ((empty($_GET['refpage'])) ? 1 : $_GET['refpage']) . "\">"; ?>最新消息<?php echo ($_GET['action'] == 'viewnews') ? "" : "</a>"; ?></li>
                    <?php echo ($_GET['action'] == 'viewcontent') ? "<li class=\"thisPosition\">檢視消息</li>" : ""; ?>
                    <?php include "templates/loginbutton.php"; ?>
                </ol>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-10" style="float:unset; margin: 0 auto;">
                            <?php if ($_GET['action'] == 'viewnews') {
                                /* 開始取資料
                                    * 其中取資料就先讓最後一筆資料先顯示
                                    * 消息如果是一周內則標題後面應該顯示 new 的 badge
                                    * 時間的計算先利用 strtotime 函數轉換後計算，單位是秒
                                    * 一頁顯示七筆資料
                                    */
                                if (empty($_GET['p'])) {
                                    $page = 1;
                                } else {
                                    $page = $_GET['p'];
                                }
                                $npp = 9;   //每頁消息數，SQL 語法用，LIMIT 第二項
                                $tlimit = ($page - 1) * $npp;   //SQL 語法用，LIMIT 第一項      
                                $sql = "SELECT * FROM `news` ORDER BY `newsOrder` DESC LIMIT $tlimit, $npp;";
                                $query = mysqli_query($connect, $sql);
                                if (mysqli_num_rows($query) == 0) { ?>
                                    <div class="panel panel-danger">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">錯誤</h3>
                                        </div>
                                        <div class="panel-body text-center">
                                            <h2 class="news-warn">此頁目前沒有可以顯示的消息。<br /><br />
                                                <div class="btn-group" role="group">
                                                    <a href="news.php" class="btn btn-success">返回第一頁</a>
                                                </div>
                                            </h2>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <!-- 消息面版v2 -->
                                    <div class="tab-content">
                                        <!-- 全部公告 -->
                                        <table id="news" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>類型</th>
                                                    <th>標題</th>
                                                    <th>發佈時間</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                while ($newsRows = mysqli_fetch_array($query, MYSQLI_BOTH)) { ?>
                                                    <tr>
                                                        <td class="newsType"><span class="badge <?php echo ($newsRows['newsType'] == "一般") ? "badge-primary" : "badge-success" ?>"><?php echo $newsRows['newsType']; ?></span></td>
                                                        <td><a href="?action=viewcontent&nid=<?php echo $newsRows['newsOrder']; ?>&refpage=<?php echo $page; ?>"><?php echo $newsRows['newsTitle']; ?></a><?php /* 一周內顯示 NEW 標籤 */ echo (strtotime("now") - strtotime($newsRows['postTime']) <= 604800) ? "&nbsp;&nbsp;<span class=\"badge badge-warning\">NEW!</span>" : ""; ?></td>
                                                        <td class="releaseTime"><?php echo $newsRows['postTime']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <?php
                                    //判斷所有資料筆數「$rows['筆數']」
                                    $sql = mysqli_query($connect, "SELECT COUNT(*) AS `times` FROM `news`;");
                                    $rows = mysqli_fetch_array($sql, MYSQLI_BOTH);
                                    // 如果總筆數除以 $npp 筆大於 1，意即大於一頁
                                    $tpg = ceil($rows['times'] / $npp);
                                    if ($tpg > 1) { ?>
                                        <!-- 頁數按鈕開始 -->
                                        <div class="text-center">
                                            <ul class="pagination">
                                                <?php echo ($page == 1) ? "<li class=\"disabled\">" : "<li>"; ?><a <?php echo ($page == 1) ? "" : "href=\"?action=viewnews&p=" . ($page - 1) . "\""; ?> aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                                                <?php
                                                // 目前頁數
                                                $i = 1;
                                                // WHILE 運算不要改到原值
                                                $pg = $tpg;
                                                while ($pg > 0) { ?>
                                                    <?php /* 如果這頁就是這顆按鈕就變顏色 */ echo ($page == $i) ? "<li class=\"active\">" : "<li>"; ?><a <?php /* 如果是這頁就不印出連結 */ echo ($page == $i) ? "" : "href=\"?action=viewnews&p=$i\""; ?>><?php echo $i; ?> <?php echo ($page == $i) ? "<span class=\"sr-only\">(current)</span>" : ""; ?></a></li>
                                                    <?php
                                                    $i += 1;
                                                    $pg -= 1;
                                                }
                                                ?>
                                                <?php echo ($page == $tpg) ? "<li class=\"disabled\">" : "<li>"; ?><a <?php echo ($page == $tpg) ? "" : "href=\"?action=viewnews&p=" . ($page + 1) . "\""; ?> aria-label="Next"><span aria-hidden="true">»</span></a></li>
                                            </ul>
                                        </div>
                                        <!-- 頁數按鈕結束 -->
                                    <?php }
                            } ?>
                            <?php } elseif ($_GET['action'] == 'viewcontent') {
                            if (!empty($_GET['nid'])) {
                                $nid = $_GET['nid'];
                                $sql = mysqli_query($connect, "SELECT * FROM `news` WHERE `newsOrder`='$nid';");
                                $datarows = mysqli_num_rows($sql);
                                if ($datarows == 0) { ?>
                                        <div class="panel panel-danger">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">錯誤</h3>
                                            </div>
                                            <div class="panel-body text-center">
                                                <h2 class="news-warn">找不到該則公告！<br /><br />
                                                    <div class="btn-group" role="group">
                                                        <a href="news.php?action=viewnews<?php echo (!empty($_GET['refpage']))? "&p=" . $_GET['refpage'] : "";?>" class="btn btn-success">返回消息列表</a>
                                                    </div>
                                                </h2>
                                            </div>
                                        </div>
                                    <?php } else {
                                    $row = mysqli_fetch_array($sql, MYSQLI_BOTH); ?>
                                        <div class="news-view">
                                            <div class="news-time"><?php echo $row['postTime']; ?>&nbsp;・&nbsp;<span class="badge <?php echo ($row['newsType'] == "一般") ? "badge-primary" : "badge-success" ?>"><?php echo $row['newsType']; ?></span></div>
                                            <h2 class="text-info news-title"><?php echo $row['newsTitle']; ?>
                                        </div>
                                        <hr />
                                        <div class="news-content"><?php echo $row['newsContent']; ?></div>
                                        <div class="container-fluid text-center" style="margin: 3em 0 0 0;"><a href="?action=viewnews&p=<?php echo (empty($_GET['refpage'])) ? "1" : urlencode($_GET['refpage']); ?>" class="btn btn-lg btn-success">返回消息列表</a></div>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <div class="panel panel-danger">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">錯誤</h3>
                                    </div>
                                    <div class="panel-body text-center">
                                        <h2 class="news-warn">找不到該則公告！<br /><br />
                                            <div class="btn-group" role="group">
                                                <a href="news.php?action=viewnews<?php echo (!empty($_GET['refpage']))? "&p=" . $_GET['refpage'] : "";?>" class="btn btn-success">返回消息列表</a>
                                            </div>
                                        </h2>
                                    </div>
                                </div>
                            <?php } ?>
                            <div>
                            <?php } else {
                            mysqli_close($connect);
                            header("Location: news.php?action=viewnews");
                            exit;
                        } ?>
                        </div>
                    </div>
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