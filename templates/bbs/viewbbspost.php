<?php
if (empty($_GET['bid'])) { ?>
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title">錯誤</h3>
        </div>
        <div class="panel-body text-center">
            <h2 class="news-warn">找不到此討論板！<br /><br />
                <div class="btn-group" role="group">
                    <a href="?action=viewboard" class="btn btn-lg btn-success">返回討論板一覽</a>
                </div>
            </h2>
        </div>
    </div>
<?php } else {
    $bid = $_GET['bid'];

if (empty($_GET['pid'])) {
    $pid = 1;
} else {
    $pid = $_GET['pid'];
}
// 設定一頁顯示多少筆文章
$postPerPage = 9;                       // SQL 語法用，LIMIT 第二項
$tlimit = ($pid - 1) * $postPerPage;    // SQL 語法用，LIMIT 第一項
$sql = mysqli_query($connect, "SELECT * FROM `bbspost` WHERE `postBoard`=$bid ORDER BY `lastUpdateTime` DESC LIMIT $tlimit, $postPerPage;");
$datarows = mysqli_num_rows($sql);
// 設定當文章數大於多少時強調該數字
$hotPost = 100;
?>
<div class="container">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="container-fluid" style="margin: 5px 0;">
                <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'addnewpostsuccess') { ?>
                    <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>張貼新文章成功！</strong></h4>
                    </div>
                <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'delposterrtype') { ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>無法判別貼文的屬性，請依正常程序刪除貼文！</strong></h4>
                    </div>
                <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'delposterrpostid') { ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>無法判別貼文的識別碼，請依正常程序刪除貼文！</strong></h4>
                    </div>
                <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'delposterrnotfound') { ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>找不到這篇文章，請依正常程序刪除貼文！</strong></h4>
                    </div>
                <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'delposterrauthfail') { ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>欲刪除的文章發文者與您的登入身份不符，請依正常程序刪除貼文！</strong></h4>
                    </div>
                <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'delpostsuccess') { ?>
                    <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>刪除文章成功！</strong></h4>
                    </div>
                <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'delpostsuccessnopostid') { ?>
                    <div class="alert alert-warning alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>刪除文章成功，但因為無法識別文章 ID ，故跳轉至本頁面。</strong></h4>
                    </div>
                <?php } ?>
                <div class="dropdown pull-right">
                    <?php if ($datarows != 0) { ?>
                        <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">全部主題 <span class="caret"></span></button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li><a href="#">綜合討論</a></li>
                            <li><a href="#">板務公告</a></li>
                            <li><a href="#">攻略心得</a></li>
                            <li><a href="#">同人創作</a></li>
                        </ul>
                    <?php } ?>
                    <a href="?action=addnewpost&boardid=<?php echo $bid; ?>&refpage=<?php echo $pid; ?>" class="btn btn-success">張貼文章</a>
                </div>
            </div>
            <?php if ($datarows != 0) { ?>
                <table class="table table-hover" style="vertical-align: middle;">
                    <thead>
                        <tr class="info">
                            <th class="post-nums">文章數</th>
                            <th class="post-title">文章標題</th>
                            <th class="post-time">貼文時間</th>
                            <th class="post-time last-operatime">最後操作時間</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
                            $refer = urlencode("&bid=$bid&pid=$pid");
                            $articlesql = mysqli_query($connect, "SELECT `bbspost`.*, `bbsarticle`.* FROM `bbspost` LEFT OUTER JOIN `bbsarticle` ON `bbsarticle`.`articlePost`=`bbspost`.`postID` WHERE `bbspost`.`postID`=" . $row['postID'] . ";");
                            $articlerows = mysqli_num_rows($articlesql);
                            ?>
                            <tr>
                                <td class="post-nums text-left"><span class="<?php echo ($articlerows >= $hotPost) ? "text-danger" : "text-info"; ?>"><?php echo ($articlerows >= $hotPost) ? "<strong>" : ""; ?><?php echo $articlerows; ?><?php echo ($articlerows >= $hotPost) ? "</strong>" : ""; ?></span></td>
                                <td class="post-title"><a href="?action=viewpostcontent&postid=<?php echo $row['postID']; ?>&refbid=<?php echo $bid; ?>&refpage=<?php echo $pid; ?>"><span class="badge badge-warning"><?php echo $row['postType']; ?></span> <?php echo $row['postTitle']; ?></a></td>
                                <td class="post-time"><?php echo $row['postUserID']; ?><br /><?php echo $row['postTime']; ?></td>
                                <td class="post-time last-operatime"><?php echo (!empty($row['lastUpdateUserID'])) ? $row['lastUpdateUserID'] . "<br />" . $row['lastUpdateTime'] : "<span style=\"color: gray;\">目前尚無回覆</span>"; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="dropdown pull-right">
                    <?php if ($datarows != 0) { ?>
                        <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">全部主題 <span class="caret"></span></button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li><a href="#">綜合討論</a></li>
                            <li><a href="#">板務公告</a></li>
                            <li><a href="#">攻略心得</a></li>
                            <li><a href="#">同人創作</a></li>
                        </ul>
                    <?php } ?>
                    <a href="?action=addnewpost&boardid=<?php echo $bid; ?>&refpage=<?php echo $pid; ?>" class="btn btn-success">張貼文章</a>
                </div>
            </div>
            <?php
            //判斷所有資料筆數「$rows['筆數']」
            $sql = mysqli_query($connect, "SELECT COUNT(*) AS `times` FROM `bbspost` WHERE `postBoard`=$bid;");
            $rows = mysqli_fetch_array($sql, MYSQLI_BOTH);
            // 如果總筆數除以 $npp 筆大於 1，意即大於一頁
            $tpg = ceil($rows['times'] / $postPerPage);
            if ($tpg > 1) { ?>
            
                <div class="clearfix"></div>
                <!-- 頁數按鈕開始 -->
                <div class="text-center">
                    <ul class="pagination">
                        <?php echo ($pid == 1) ? "<li class=\"disabled\">" : "<li>"; ?><a <?php echo ($pid == 1) ? "" : "href=\"?action=viewbbspost&bid=$bid&pid=" . ($pid - 1) . "\""; ?> aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                        <?php
                        // 目前頁數
                        $i = 1;
                        // WHILE 運算不要改到原值
                        $pg = $tpg;
                        while ($pg > 0) { ?>
                            <?php /* 如果這頁就是這顆按鈕就變顏色 */ echo ($pid == $i) ? "<li class=\"active\">" : "<li>"; ?><a <?php /* 如果是這頁就不印出連結 */ echo ($pid == $i) ? "" : "href=\"?action=viewbbspost&bid=$bid&pid=$i\""; ?>><?php echo $i; ?> <?php echo ($pid == $i) ? "<span class=\"sr-only\">(current)</span>" : ""; ?></a></li>
                            <?php
                            $i += 1;
                            $pg -= 1;
                        }
                        ?>
                        <?php echo ($pid == $tpg) ? "<li class=\"disabled\">" : "<li>"; ?><a <?php echo ($pid == $tpg) ? "" : "href=\"?action=viewbbspost&bid=$bid&pid=" . ($pid + 1) . "\""; ?> aria-label="Next"><span aria-hidden="true">»</span></a></li>
                    </ul>
                </div>
                <!-- 頁數按鈕結束 -->
            <?php } ?>
        <?php } else {
        $chkbrd = mysqli_query($connect, "SELECT * FROM `bbsboard` WHERE `boardID`=$bid");
        $chkbrdRows = mysqli_num_rows($chkbrd);
        if ($chkbrdRows != 0) { ?>
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">警告</h3>
                    </div>
                    <div class="panel-body text-center">
                        <h2 class="news-warn" style="color: #8a6d3b !important;">討論板目前無文章<br /><br />
                            <div class="btn-group" role="group">
                                <a href="?action=viewboard" class="btn btn-lg btn-info">返回討論板一覽</a>
                                <a href="?action=addnewpost&boardid=<?php echo $bid; ?>" class="btn btn-lg btn-success">按此張貼新文章</a>
                            </div>
                        </h2>
                    </div>
                </div>
            <?php } else { ?>
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">錯誤</h3>
                    </div>
                    <div class="panel-body text-center">
                        <h2 class="news-warn">找不到此討論板！<br /><br />
                            <div class="btn-group" role="group">
                                <a href="?action=viewboard" class="btn btn-lg btn-success">返回討論板一覽</a>
                            </div>
                        </h2>
                    </div>
                </div>
            <?php } ?>
        <?php } 
        } ?>
    </div>
</div>