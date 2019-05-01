<?php
require_once "sessionCheck.php";
$self = basename(__FILE__);
if (empty($_SERVER['QUERY_STRING']) != True) {
    $self .= "?" . $_SERVER['QUERY_STRING'];
}
$connect = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DBNAME, $DB_PORT);
if (mysqli_connect_errno()) {
    die('無法連線到資料庫: ' . mysqli_connect_error());
}
mysqli_query($connect, "SET NAMES 'utf8'");
mysqli_query($connect, "SET CHARACTER_SET_CLIENT=utf8");
mysqli_query($connect, "SET CHARACTER_SET_RESULTS=utf8");
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <title>最新消息 | 洛嬉遊戲 L.S. Games</title>
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
                    <li class="thisPosition">最新消息</li>
                    <?php include "templates/loginbutton.php"; ?>
                </ol>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-10" style="float:unset; margin: 0 auto;">
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
                                        /* 開始取資料
                                         * 其中取資料就先讓最後一筆資料先顯示
                                         * 消息如果是一周內則標題後面應該顯示 new 的 badge
                                         * 時間的計算先利用 strtotime 函數轉換後計算，單位是秒
                                         * 一頁顯示七筆資料
                                         */
                                        if(empty($_GET['p'])){
                                            $page = 1;
                                        }else{
                                            $page = $_GET['p'];
                                        }
                                        $tlimit = ($page - 1) * 7;
                                        $blimit = $page * 7 - 1;
                                        $sql = "SELECT * FROM `news` ORDER BY `newsOrder` DESC LIMIT $tlimit, $blimit;";
                                        $query = mysqli_query($connect, $sql);
                                        while ($newsRows = mysqli_fetch_array($query, MYSQLI_BOTH)) { ?>
                                            <tr>
                                                <td class="newsType"><span class="badge badge-primary"><?php echo $newsRows['newsType']; ?></span></td>
                                                <td><a href="#"><?php echo $newsRows['newsTitle']; ?></a><?php /* 一周內顯示 NEW 標籤 */ echo (strtotime("now") - strtotime($newsRows['postTime']) <= 604800) ? "&nbsp;&nbsp;<span class=\"badge badge-warning\">new!</span>" : ""; ?></td>
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
                            // 如果總筆數除以 7 筆大於 1，意即大於一頁
                            $tpg = ceil($rows['times'] / 7);
                            if($tpg > 1){ ?>
                            <!-- 頁數按鈕開始 -->
                            <div class="text-center">
                                <ul class="pagination">
                                <?php echo ($page == 1) ? "<li class=\"disabled\">" : "<li>";?><a <?php echo ($page == 1) ? "" : "href=\"?p=" . ($page - 1) . "\"";?> aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                                    <?php
                                    // 目前頁數
                                    $i = 1;
                                    // WHILE 運算不要改到原值
                                    $pg = $tpg;
                                    while($pg > 0){ ?>
                                    <?php /* 如果這頁就是這顆按鈕就變顏色 */ echo ($page == $i) ? "<li class=\"active\">" : "<li>";?><a href="news.php?p=<?php /* 印出頁數 */ echo $i;?>"><?php echo $i;?> <?php echo ($page == $i) ? "<span class=\"sr-only\">(current)</span>" : "";?></a></li>
                                    <?php 
                                    $i += 1;
                                    $pg -= 1;
                                    }
                                    ?>
                                    <?php echo ($page == $tpg) ? "<li class=\"disabled\">" : "<li>";?><a <?php echo ($page == $tpg) ? "" : "href=\"?p=". ($page + 1) . "\""; ?> aria-label="Next"><span aria-hidden="true">»</span></a></li>
                                </ul>
                            </div>
                            <!-- 頁數按鈕結束 -->
                            <?php } ?>
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