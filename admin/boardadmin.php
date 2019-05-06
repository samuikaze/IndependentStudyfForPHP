<?php
if (empty($_GET['refpage'])) {
    $refpage = 1;
} else {
    $refpage = $_GET['refpage'];
}
?>
<div class="row content-body">
    <ol class="breadcrumb">
        <li><a href="?action=index"><i class="fas fa-map-marker-alt"></i> 首頁</a></li>
        <?php echo ($_GET['action'] == 'board_admin') ? "<li class=\"active\">" : "<li><a href=\"?action=board_admin&type=boardlist&p=$refpage\">"; ?>討論板管理<?php echo ($_GET['action'] == 'board_admin') ? "" : "</a>" ?></li>
        <?php echo ($_GET['action'] == 'editboard') ? "<li class=\"active\">修改討論板</li>" : ""; ?>
        <?php echo ($_GET['action'] == 'delboard') ? "<li class=\"active\">確認刪除討論區</li>" : ""; ?>
    </ol>
</div>
<div class="col-md-12">
    <?php /* 討論板列表 */
    if (!empty($_GET['action']) && $_GET['action'] == 'board_admin') {
        if (!empty($_GET['modifyerr']) && $_GET['modifyerr'] == 5) { ?>
            <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>討論區修改成功！</strong></h4>
            </div>
        <?php } ?>
        <!-- 分頁 -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" <?php echo (!empty($_GET['type']) && $_GET['type'] == 'boardlist') ? " class=\"active\"" : ""; ?>><a href="#home" aria-controls="home" role="tab" data-toggle="tab">管理討論板</a></li>
            <li role="presentation" <?php echo (!empty($_GET['type']) && $_GET['type'] == 'createboard') ? " class=\"active\"" : ""; ?>><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">新建討論板</a></li>
        </ul>
        <!-- 內容 -->
        <div class="tab-content">
            <!-- 管理討論板 -->
            <div role="tabpanel" class="tab-pane fade<?php echo (!empty($_GET['type']) && $_GET['type'] == 'boardlist') ? " in active" : ""; ?>" id="home">
                <table class="table table-hover">
                    <thead>
                        <tr class="warning">
                            <td class="news-order">序</td>
                            <td class="news-title">討論板標題</td>
                            <td class="news-admin">討論板管理</td>
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
                        $blimit = $page * $npp;     //SQL 語法用，LIMIT 第二項
                        $sql = mysqli_query($connect, "SELECT * FROM `bbsboard` ORDER BY `boardID` DESC LIMIT $tlimit, $blimit;");
                        $bid = 1;
                        while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) { ?>
                            <tr>
                                <td class="news-order"><?php echo ($page - 1) * $npp + $bid; ?></td>
                                <td class="news-title"><?php echo $row['boardName']; ?></td>
                                <td class="news-admin">
                                    <a href="?action=editboard&bid=<?php echo $row['boardID']; ?>&refpage=<?php echo $page; ?>" class="btn btn-info">編輯</a>
                                    <a href="?action=delboard&bid=<?php echo $row['boardID']; ?>&refpage=<?php echo $page; ?>" class="btn btn-danger">刪除</a>
                                </td>
                            </tr>
                            <?php $bid += 1;
                        } ?>
                    </tbody>
                </table>
            </div>
            <!-- 新建討論板 -->
            <div role="tabpanel" class="tab-pane fade<?php echo (!empty($_GET['type']) && $_GET['type'] == 'createboard') ? " in active" : ""; ?>" id="profile">
                2
            </div>
        <?php } elseif (!empty($_GET['action']) && $_GET['action'] == 'editboard') {
        if (empty($_GET['bid'])) { 
            mysqli_close($connect); ?>
                <h2 class="news-warn">找不到這個討論板！<br /><a href="?action=board_admin&type=boardlist&p=<?php echo $refpage; ?>" class="btn btn-lg btn-info">按此返回討論板管理列表</a></h2>
            <?php } else {
            $bid = $_GET['bid'];
            $sql = mysqli_query($connect, "SELECT * FROM `bbsboard` WHERE `boardID`=$bid;");
            $datarows = mysqli_num_rows($sql);
            if ($datarows == 0) { 
                mysqli_close($connect); ?>
                    <h2 class="news-warn">找不到這個討論板！<br /><a href="?action=board_admin&type=boardlist&p=<?php echo $refpage; ?>" class="btn btn-lg btn-info">按此返回討論板管理列表</a></h2>
                <?php } else {
                $row = mysqli_fetch_array($sql);
                if (!empty($_GET['modifyerr']) && $_GET['modifyerr'] == 1) { ?>
                        <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4><strong>討論板標題欄位不能為空！</strong></h4>
                        </div>
                    <?php } elseif (!empty($_GET['modifyerr']) && $_GET['modifyerr'] == 2) { ?>
                        <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4><strong>討論板描述欄位不能為空！</strong></h4>
                        </div>
                    <?php } elseif (!empty($_GET['modifyerr']) && $_GET['modifyerr'] == 3) { ?>
                        <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4><strong>討論區圖片檔案過大！</strong></h4>
                        </div>
                    <?php } elseif (!empty($_GET['modifyerr']) && $_GET['modifyerr'] == 4) { ?>
                        <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4><strong>討論區圖片格式為不允許的檔案類型！</strong></h4>
                        </div>
                    <?php } ?>
                    <form method="POST" action="adminaction.php?action=modifyboard" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="boardname">討論板名稱</label>
                            <input type="text" class="form-control" id="boardname" name="boardname" value="<?php echo $row['boardName'] ?>" />
                        </div>
                        <div class="form-group">
                            <label for="boarddescript">討論板描述</label>
                            <textarea type="text" class="form-control noResize" id="boarddescript" name="boarddescript"><?php echo br2nl($row['boardDescript']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="boardimage">討論版圖片</label>
                            <input type="file" id="boardimage" name="boardimage" />
                            <p class="help-block">建議解析度為 640 × 310</p>
                        </div>
                        <div class="form-group">
                            <label for="nowimage">目前討論版圖片</label><br />
                            <?php if (empty($row['boardImage'])) { ?>
                                <p class="form-control-static text-info" id="nowimage"><strong>此討論板無圖片！</strong></p>
                            <?php } else { ?>
                                <img src="../images/bbs/board/<?php echo $row['boardImage']; ?>" id="nowimage" width="100%" />
                            <?php } ?>
                        </div>
                        <input type="hidden" name="bid" value="<?php echo $_GET['bid']; ?>" />
                        <input type="hidden" name="refer" value="<?php echo $_SERVER['QUERY_STRING']; ?>" />
                        <input type="hidden" name="refpage" value="<?php echo $_GET['refpage']; ?>" />
                        <div class="form-group text-center">
                            <input type="submit" name="submit" class="btn btn-success" value="送出" />
                            <a href="?action=board_admin&type=boardlist&p=<?php echo $refpage; ?>" class="btn btn-info">取消</a>
                        </div>
                    </form>
                <?php } ?>
            <?php } ?>
        <?php } elseif (!empty($_GET['action']) && $_GET['action'] == 'delboard') {
        $page = $_GET['refpage'];
        // 沒有 bid
        if (empty($_GET['bid'])) { 
            mysqli_close($connect); ?>
                <h2 class="news-warn">找不到這個討論板！<br /><a href="?action=board_admin&type=boardlist&p=1" class="btn btn-lg btn-info">按此返回討論板管理列表</a></h2>
                <?php exit;
            } else {
                $bid = $_GET['bid'];
                $sql = mysqli_query($connect, "SELECT * FROM `bbsboard` WHERE `boardID`=$bid;");
                // 取得資料筆數
                $datarows = mysqli_num_rows($sql);
                // 沒有取得半筆資料，意即找不到公告
                if ($datarows == 0) { 
                    mysqli_close($connect); ?>
                    <h2 class="news-warn">找不到這個討論板！<br /><a href="?action=board_admin&type=boardlist&p=1" class="btn btn-lg btn-info">按此返回討論板管理列表</a></h2>
                    <?php exit;
                    // 找到公告開始印確認刪除的資料內容
                } else {
                    $row = mysqli_fetch_array($sql, MYSQLI_BOTH);
                    $sqluser = mysqli_query($connect, "SELECT `userNickname` FROM `member` WHERE `uid`=" . $row['boardCreator']);
                    $userrow = mysqli_fetch_array($sqluser, MYSQLI_BOTH); ?>
                    <form method="POST" action="adminaction.php?action=delboard" class="form-horizontal">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>您確定要刪除這個討論區嗎？其下所有文章也會被刪除，且這個動作無法復原！</strong></h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">討論區名稱</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static"><?php echo $row['boardName']; ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">討論區描述</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static"><?php echo $row['boardDescript']; ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">討論區建立時間</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static"><?php echo $row['boardCTime']; ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">討論區建立者</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static"><?php echo $userrow['userNickname']; ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">討論區圖片</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static"><img src="../images/bbs/board/<?php echo $row['boardImage']; ?>" width="100%" /></p>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <input type="submit" name="submit" class="btn btn-danger" value="確認刪除" />
                                    <a href="?action=board_admin&type=boardlist&p=<?php echo $page; ?>" class="btn btn-success">返回列表</a>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php }
        }
    } ?>
    </div>
</div>