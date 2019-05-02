<div class="row content-body">
    <ol class="breadcrumb">
        <li><a href="?action=index"><i class="fas fa-map-marker-alt"></i> 首頁</a></li>
        <?php echo ($_GET['action'] == 'article_news') ? "<li class=\"active\">" : "<li><a href=\"?action=article_news\">"; ?>最新消息<?php echo ($_GET['action'] == 'article_news') ? "" : "</a>" ?></li>
        <?php echo ($_GET['action'] == 'modifynews') ? "<li class=\"active\">修改消息內容</li>" : ""; ?>
    </ol>
</div>
<div class="col-md-12">
    <?php if($_GET['action'] == 'article_news'){ ?>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#adminNews" aria-controls="adminNews" role="tab" data-toggle="tab">管理消息</a></li>
        <li role="presentation"><a href="#postNews" aria-controls="postNews" role="tab" data-toggle="tab">張貼新消息</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="adminNews">
                <table class="table table-hover">
                    <thead>
                        <tr class="warning">
                            <td class="news-order">序</td>
                            <td class="news-title">消息標題</td>
                            <td class="news-admin">消息管理</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php }
                if ($_GET['action'] == 'article_news') {
                    $connect = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DBNAME, $DB_PORT);
                    if (mysqli_connect_errno()) {
                        die('無法連線到資料庫: ' . mysqli_connect_error());
                    }
                    mysqli_query($connect, "SET NAMES 'utf8'");
                    mysqli_query($connect, "SET CHARACTER_SET_CLIENT=utf8");
                    mysqli_query($connect, "SET CHARACTER_SET_RESULTS=utf8");
                    // 目前頁數
                    if (empty($_GET['p'])) {
                        $page = 1;
                    } else {
                        $page = $_GET['p'];
                    }
                    //一頁顯示幾項
                    $npp = 10;
                    $tlimit = ($page - 1) * $npp;   //SQL 語法用，LIMIT 第一項
                    $blimit = $page * $npp - 1;     //SQL 語法用，LIMIT 第二項
                    $sql = mysqli_query($connect, "SELECT * FROM `news` ORDER BY `newsOrder` DESC LIMIT $tlimit, $blimit;");
                    while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) { ?>
                            <tr>
                                <td class="news-order"><?php echo $row['newsOrder']; ?></td>
                                <td class="news-title"><?php echo $row['newsTitle']; ?></td>
                                <td class="news-admin"><a href="?action=modifynews&nid=<?php echo $row['newsOrder']; ?>&refer=<?php echo $page; ?>" class="btn btn-info">編輯</a><a href="?action=delnews&nid=<?php echo $row['newsOrder']; ?>&refer=<?php echo $page; ?>" class="btn btn-danger">刪除</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
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
                            <?php echo ($page == 1) ? "<li class=\"disabled\">" : "<li>"; ?><a <?php echo ($page == 1) ? "" : "href=\"?action=article_news&p=" . ($page - 1) . "\""; ?> aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                            <?php
                            // 目前頁數
                            $i = 1;
                            // WHILE 運算不要改到原值
                            $pg = $tpg;
                            while ($pg > 0) { ?>
                                <?php /* 如果這頁就是這顆按鈕就變顏色 */ echo ($page == $i) ? "<li class=\"active\">" : "<li>"; ?><a <?php /* 如果是這頁就不印出連結 */ echo ($page == $i) ? "" : "href=\"?action=article_news&p=$i\""; ?>><?php echo $i; ?> <?php echo ($page == $i) ? "<span class=\"sr-only\">(current)</span>" : ""; ?></a></li>
                                <?php
                                $i += 1;
                                $pg -= 1;
                            }
                            ?>
                            <?php echo ($page == $tpg) ? "<li class=\"disabled\">" : "<li>"; ?><a <?php echo ($page == $tpg) ? "" : "href=\"?action=article_news&p=" . ($page + 1) . "\""; ?> aria-label="Next"><span aria-hidden="true">»</span></a></li>
                        </ul>
                    </div>
                    <!-- 頁數按鈕結束 -->
                <?php } ?>
            <?php // 修改消息
        } elseif ($_GET['action'] == 'modifynews') {
            // 沒有消息ID
            if (empty($_GET['nid'])) {
                if (empty($_GET['refer'])) {
                    $refer = 1;
                } else {
                    $refer = $_GET['refer'];
                } ?>
                    <h2 class="news-warn">找不到這則公告！<br /><a href="?action=article_news&p=<?php echo $refer; ?>" class="btn btn-lg btn-info">按此返回列表</a></h2>
                <?php } else {
                $connect = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DBNAME, $DB_PORT);
                if (mysqli_connect_errno()) {
                    die('無法連線到資料庫: ' . mysqli_connect_error());
                }
                mysqli_query($connect, "SET NAMES 'utf8'");
                mysqli_query($connect, "SET CHARACTER_SET_CLIENT=utf8");
                mysqli_query($connect, "SET CHARACTER_SET_RESULTS=utf8");
                $nid = $_GET['nid'];
                $sql = mysqli_query($connect, "SELECT * FROM `news` WHERE `newsOrder`='$nid';");
                $datarows = mysqli_num_rows($sql);  // 取得資料筆數
                if ($datarows == 0) { ?>
                        <h2 class="news-warn">找不到這則公告！<br /><a href="?action=article_news&p=<?php echo $refer; ?>" class="btn btn-lg btn-info">按此返回消息列表</a></h2>
                    <?php } else {
                    $row = mysqli_fetch_array($sql);
                } ?>
                        <form method="POST" action="adminaction.php?action=modifynews">
                            <div class="form-group">
                                <label for="newsType">消息類型</label>
                                <select name="newsType" class="form-control" id="newsType">
                                    <option value="一般">一般</option>
                                    <option value="資訊">資訊</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="newsTitle">消息標題</label>
                                <input type="text" name="newsTitle" class="form-control" id="newsTitle" value="<?php echo $row['newsTitle']; ?>" />
                            </div>
                            <div class="form-group">
                                <label for="newsContent">消息內容</label>
                                <textarea name="newsContent" class="form-control noResize" rows="3"><?php echo $row['newsContent']; ?></textarea>
                            </div>
                            <input type="hidden" name="refer" value="action=modifynews&nid=<?php echo $nid; ?>&refer=<?php echo $_GET['refer']; ?>" />
                            <!-- 還有一些隱藏欄位要加進來 -->
                            <div class="form-group text-center">
                                <input type="submit" name="submit" value="送出" class="btn btn-success" />
                                <a href="?action=article_news&p=<?php echo $_GET['refer']; ?>" class="btn btn-info">取消</a>
                            </div>
                        </form>
                <?php } ?>

            <?php } ?>
            <div role="tabpanel" class="tab-pane" id="postnews">

            </div>
        </div>
    </div>
</div>