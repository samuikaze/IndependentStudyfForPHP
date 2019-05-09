<div class="row content-body">
    <ol class="breadcrumb">
        <li><a href="?action=index"><i class="fas fa-map-marker-alt"></i> 首頁</a></li>
        <?php echo ($_GET['action'] == 'article_news') ? "<li class=\"active\">" : "<li><a href=\"?action=article_news&type=newslist\">"; ?>最新消息<?php echo ($_GET['action'] == 'article_news') ? "" : "</a>" ?></li>
        <?php echo ($_GET['action'] == 'modifynews') ? "<li class=\"active\">修改消息內容</li>" : ""; ?>
        <?php echo ($_GET['action'] == 'delnews') ? "<li class=\"active\">確認刪除消息</li>" : ""; ?>
    </ol>
</div>
<div class="col-md-12">
    <?php if ($_GET['action'] == 'article_news') {
        if (!empty($_GET['msg']) && $_GET['msg'] == 'modifynewssuccess') { ?>
    <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4><strong>消息編輯成功！</strong></h4>
    </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'delerror1') { ?>
    <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4><strong>找不到該則公告，請依正常程序刪除公告！</strong></h4>
    </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'delnewssuccess') { ?>
    <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4><strong>消息刪除成功！</strong></h4>
    </div>
        <?php }elseif(!empty($_GET['msg']) && $_GET['msg'] == 'addnewssuccess'){ ?>
    <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4><strong>新增消息成功！</strong></h4>
    </div>
        <?php } ?>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"<?php echo (!empty($_GET['type']) && $_GET['type'] == 'newslist') ? " class=\"active\"" : ""; ?>><a href="#adminNews" aria-controls="adminNews" role="tab" data-toggle="tab">管理消息</a></li>
        <li role="presentation"<?php echo (!empty($_GET['type']) && $_GET['type'] == 'postnewnews') ? " class=\"active\"" : ""; ?>><a href="#postNews" aria-controls="postNews" role="tab" data-toggle="tab">張貼新消息</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade<?php echo (!empty($_GET['type']) && $_GET['type'] == 'newslist') ? " active in" : ""; ?>" id="adminNews">
            <table class="table table-hover">
                <thead>
                    <tr class="warning">
                        <td class="news-order">序</td>
                        <td class="news-title">消息標題</td>
                        <td class="news-admin">消息管理</td>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // 目前頁數
                    if (empty($_GET['p'])) {
                        $page = 1;
                    } else {
                        $page = $_GET['p'];
                    }
                    //一頁顯示幾項
                    $npp = 10;
                    $tlimit = ($page - 1) * $npp;   //SQL 語法用，LIMIT 第一項
                    $blimit = $page * $npp;         //SQL 語法用，LIMIT 第二項
                    $sql = mysqli_query($connect, "SELECT * FROM `news` ORDER BY `newsOrder` DESC LIMIT $tlimit, $blimit;");
                    $newsid = 1;
                    while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) { ?>
                    <tr>
                        <td class="news-order"><?php echo ($page - 1) * $npp + $newsid; ?></td>
                        <td class="news-title"><span class="badge <?php echo ($row['newsType'] == '一般') ? "badge-primary" : "badge-success"; ?>"><?php echo $row['newsType']; ?></span>&nbsp;<?php echo $row['newsTitle']; ?></td>
                        <td class="news-admin"><a href="?action=modifynews&nid=<?php echo $row['newsOrder']; ?>&refpage=<?php echo $page; ?>" class="btn btn-info">編輯</a><a href="?action=delnews&nid=<?php echo $row['newsOrder']; ?>&refpage=<?php echo $page; ?>" class="btn btn-danger">刪除</a></td>
                    </tr>
                        <?php $newsid += 1;
                        } ?>
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
                        <?php echo ($page == 1) ? "<li class=\"disabled\">" : "<li>"; ?><a <?php echo ($page == 1) ? "" : "href=\"?action=article_news&type=newslist&p=" . ($page - 1) . "\""; ?> aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                        <?php
                        // 目前頁數
                        $i = 1;
                        // WHILE 運算不要改到原值
                        $pg = $tpg;
                        while ($pg > 0) { ?>
                            <?php /* 如果這頁就是這顆按鈕就變顏色 */ echo ($page == $i) ? "<li class=\"active\">" : "<li>"; ?><a <?php /* 如果是這頁就不印出連結 */ echo ($page == $i) ? "" : "href=\"?action=article_news&type=newslist&p=$i\""; ?>><?php echo $i; ?> <?php echo ($page == $i) ? "<span class=\"sr-only\">(current)</span>" : ""; ?></a></li>
                            <?php
                            $i += 1;
                            $pg -= 1;
                        }
                        echo ($page == $tpg) ? "<li class=\"disabled\">" : "<li>"; ?><a <?php echo ($page == $tpg) ? "" : "href=\"?action=article_news&type=newslist&p=" . ($page + 1) . "\""; ?> aria-label="Next"><span aria-hidden="true">»</span></a></li>
                </ul>
            </div>
            <!-- 頁數按鈕結束 -->
                <?php } ?>
        </div>
                <?php 
                $sid = $_SESSION['uid'];
                $usersql = mysqli_query($connect, "SELECT `uid` FROM `member` WHERE `userName`='$sid';");
                $uidrow = mysqli_fetch_array($usersql);?>
        <!-- 張貼新消息 -->
        <div role="tabpanel" class="tab-pane fade<?php echo (!empty($_GET['type']) && $_GET['type'] == 'postnewnews') ? " active in" : ""; ?>" id="postNews">
            <?php if(!empty($_GET['msg']) && $_GET['msg'] == 'addnewserrtitle'){ ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>消息標題不可留空！</strong></h4>
            </div>
            <?php }elseif(!empty($_GET['msg']) && $_GET['msg'] == 'addnewserrtype'){?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>請確實選擇一個消息類型！</strong></h4>
            </div>
            <?php }elseif(!empty($_GET['msg']) && $_GET['msg'] == 'addnewserrcontent'){ ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>消息內容不可留空！</strong></h4>
            </div>
            <?php }elseif(!empty($_GET['msg']) && $_GET['msg'] == 'addnewserruid'){?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>無法取得您的身分或身分與資料不符，請依正常程序新增消息！</strong></h4>
            </div>
            <?php } ?>
            <form method="POST" action="adminaction.php?action=addnews">
                <div class="form-group">
                    <label for="newstitle">消息標題</label>
                    <input type="text" class="form-control" name="newstitle" id="newstitle" placeholder="請輸入消息標題" />
                </div>
                <div class="form-group">
                    <label for="newstype">消息類型</label>
                    <select name="newstype" class="form-control" id="newstype">
                        <option value="" selected>請選擇類型</option>
                        <option value="一般">一般</option>
                        <option value="資訊">資訊</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="newscontent">消息內容</label>
                    <textarea name="newscontent" class="form-control noResize" rows="3" placeholder="請輸入消息內容"></textarea>
                </div>
                <input type="hidden" name="uid" value="<?php echo $uidrow['uid']; ?>" />
                <div class="form-group text-center">
                    <input type="submit" name="submit" value="送出" class="btn btn-success" />
                </div>
            </form>
        </div>
    </div>
                <?php // 修改消息
                } elseif ($_GET['action'] == 'modifynews') {
                    if (empty($_GET['refpage'])) {
                        $refer = 1;
                    } else {
                        $refer = $_GET['refpage'];
                    }
                    // 沒有消息ID
                    if (empty($_GET['nid'])) {
                         ?>
    <h2 class="news-warn">找不到這則公告！<br /><a href="?action=article_news&type=newslist&p=<?php echo $refer; ?>" class="btn btn-lg btn-info">按此返回列表</a></h2>
                <?php } else {
                    $nid = $_GET['nid'];
                    $sql = mysqli_query($connect, "SELECT * FROM `news` WHERE `newsOrder`='$nid';");
                    $datarows = mysqli_num_rows($sql);  // 取得資料筆數
                    if ($datarows == 0) { ?>
    <h2 class="news-warn">找不到這則公告！<br /><a href="?action=article_news&type=newslist&p=<?php echo $refer; ?>" class="btn btn-lg btn-info">按此返回消息列表</a></h2>
                    <?php } else {
                        $row = mysqli_fetch_array($sql);
                    if (!empty($_GET['modifyErr']) && $_GET['modifyErr'] == 1) { ?>
    <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4><strong>消息標題欄位不能為空！</strong></h4>
    </div>
                    <?php } elseif (!empty($_GET['modifyErr']) && $_GET['modifyErr'] == 2) { ?>
    <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4><strong>消息內容欄位不能為空！</strong></h4>
    </div>
                    <?php } else {
                        echo "";
                    } ?>
    <form method="POST" action="adminaction.php?action=modifynews" style="margin-top: 1em;">
        <div class="form-group">
            <label for="newsType">消息類型</label>
            <select name="newsType" class="form-control" id="newsType">
                <option value="一般" <?php echo ($row['newsType'] == '一般') ? " selected" : ""; ?>>一般</option>
                <option value="資訊" <?php echo ($row['newsType'] == '資訊') ? " selected" : ""; ?>>資訊</option>
            </select>
        </div>
        <div class="form-group">
            <label for="newsTitle">消息標題</label>
            <input type="text" name="newsTitle" class="form-control" id="newsTitle" value="<?php echo $row['newsTitle']; ?>" />
        </div>
        <div class="form-group">
            <label for="newsContent">消息內容</label>
            <textarea name="newsContent" class="form-control noResize" rows="3"><?php echo br2nl($row['newsContent']); ?></textarea>
        </div>
        <input type="hidden" name="newsID" value="<?php echo $row['newsOrder']; ?>" />
        <input type="hidden" name="refer" value="<?php echo "action=modifynews&nid=$nid&refer=" . $_SERVER['QUERY_STRING']; ?>" />
        <input type="hidden" name="refpage" value="<?php echo $_GET['refpage']; ?>" />
        <div class="form-group text-center">
            <input type="submit" name="submit" value="送出" class="btn btn-success" />
            <a href="?action=article_news&type=newslist&p=<?php echo $_GET['refpage']; ?>" class="btn btn-info">取消</a>
        </div>
    </form>
                    <?php }
                } 
            //刪除消息
            } elseif ($_GET['action'] == 'delnews') {
                $page = $_GET['refpage'];
                // 沒有 nid
                if (empty($_GET['nid'])) { ?>
                    <h2 class="news-warn">找不到這則公告！<br /><a href="?action=article_news&type=newslist&p=1" class="btn btn-lg btn-info">按此返回消息列表</a></h2>
                    <?php exit;
                } else {
                    $nid = $_GET['nid'];
                    $sql = mysqli_query($connect, "SELECT * FROM `news` WHERE `newsOrder`=$nid;");
                    // 取得資料筆數
                    $datarows = mysqli_num_rows($sql);
                    // 沒有取得半筆資料，意即找不到公告
                    if ($datarows == 0) { ?>
                        <h2 class="news-warn">找不到這則公告！<br /><a href="?action=article_news&type=newslist&p=1" class="btn btn-lg btn-info">按此返回消息列表</a></h2>
                        <?php exit;
                        // 找到公告開始印確認刪除的資料內容
                    } else { 
                        $row = mysqli_fetch_array($sql, MYSQLI_BOTH);
                        $sqluser = mysqli_query($connect, "SELECT `userNickname` FROM `member` WHERE `uid`=" . $row['postUser']);
                        $userrow = mysqli_fetch_array($sqluser, MYSQLI_BOTH); ?>
    <form method="POST" class="form-horizontal" action="adminaction.php?action=delnews" style="margin-top: 1em;">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title"><strong>您確定要刪除這則消息嗎？這個動作無法復原！</strong></h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">消息標題</label>
                    <div class="col-sm-10">
                        <p class="form-control-static"><?php echo $row['newsTitle']; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">消息類型</label>
                    <div class="col-sm-10">
                        <p class="form-control-static"><span class="badge <?php echo ($row['newsType'] == '一般') ? "badge-primary" : "badge-success"; ?>"><?php echo $row['newsType']; ?></span></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">消息張貼時間</label>
                    <div class="col-sm-10">
                        <p class="form-control-static"><?php echo $row['postTime']; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">消息張貼者</label>
                    <div class="col-sm-10">
                        <p class="form-control-static"><?php echo $userrow['userNickname']; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">消息內容</label>
                    <div class="col-sm-10">
                        <p class="form-control-static"><?php echo $row['newsContent']; ?></p>
                    </div>
                </div>
                <input type="hidden" name="nid" value="<?php echo $nid; ?>" />
                <input type="hidden" name="refpage" value="<?php echo $page; ?>" />
                <div class="col-md-12 text-center">
                    <input type="submit" name="submit" class="btn btn-danger" value="確認刪除" />
                    <a href="?action=article_news&type=newslist&p=<?php echo $page; ?>" class="btn btn-success">返回列表</a>
                </div>
            </div>
        </div>
    </form>
                    <?php }
                }
            } ?>
</div>