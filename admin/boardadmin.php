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
        <?php echo ($_GET['action'] == 'board_admin') ? "<li class=\"active\">" : "<li><a href=\"?action=board_admin&type=boardlist&p=$refpage\">"; ?>討論板管理<?php echo ($_GET['action'] == 'article_news') ? "" : "</a>" ?></li>
        <?php echo ($_GET['action'] == 'editboard') ? "<li class=\"active\">修改討論板</li>" : ""; ?>
        <?php echo ($_GET['action'] == 'delboard') ? "<li class=\"active\">確認刪除消息</li>" : ""; ?>
    </ol>
</div>
<div class="col-md-12">
    <?php if (!empty($_GET['action']) && $_GET['action'] == 'board_admin') { /* 討論板列表 */ ?>
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
        <?php } elseif (!empty($_GET['action']) && $_GET['action'] == 'editboard') {
        if (empty($_GET['bid'])) { ?>
                <h2 class="news-warn">找不到這個討論板！<br /><a href="?action=board_admin&type=boardlist&p=<?php echo $refpage; ?>" class="btn btn-lg btn-info">按此返回討論板管理列表</a></h2>
            <?php } else {
            $bid = $_GET['bid'];
            $sql = mysqli_query($connect, "SELECT * FROM `bbsboard` WHERE `boardID`=$bid;");
            $datarows = mysqli_num_rows($sql);
            if ($datarows == 0) { ?>
                    <h2 class="news-warn">找不到這個討論板！<br /><a href="?action=board_admin&type=boardlist&p=<?php echo $refpage; ?>" class="btn btn-lg btn-info">按此返回討論板管理列表</a></h2>
                <?php } else {
                $row = mysqli_fetch_array($sql);
                if (!empty($_GET['modifyerr']) && $_GET['modifyerr'] == 1) { ?>
                        <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4><strong>討論板標題欄位不能為空！</strong></h4>
                        </div>
                    <?php }elseif(!empty($_GET['modifyerr']) && $_GET['modifyerr'] == 2){ ?>
                        <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4><strong>討論板描述欄位不能為空！</strong></h4>
                        </div>
                    <?php }elseif(!empty($_GET['modifyerr']) && $_GET['modifyerr'] == 3){ ?>
                        <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4><strong>討論區圖片檔案過大！</strong></h4>
                        </div>
                    <?php }elseif(!empty($_GET['modifyerr']) && $_GET['modifyerr'] == 4){ ?>
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
                            <textarea type="text" class="form-control noResize" id="boarddescript" name="boarddescript"><?php echo $row['boardDescript']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="boardimage">討論版圖片</label>
                            <input type="file" id="boardimage" name="boardimage" />
                            <p class="help-block">請選擇圖片欲上傳的圖片</p>
                        </div>
                        <div class="form-group">
                            <label for="nowimage">目前討論版圖片</label><br />
                            <?php if (empty($row['boardImage'])) { ?>
                                <p class="form-control-static text-info" id="nowimage"><strong>此討論板無圖片！</strong></p>
                            <?php } else { ?>
                                <img src="../images/bbs/board/<?php echo $row['boardImage']; ?>" id="nowimage" />
                            <?php } ?>
                        </div>
                        <input type="hidden" name="bid" value="<?php echo $_GET['bid']; ?>" />
                        <input type="hidden" name="refer" value="<?php echo "action=modifynews&nid=$nid&refer=" . $_SERVER['QUERY_STRING']; ?>" />
                        <input type="hidden" name="refpage" value="<?php echo $_GET['refpage']; ?>" />
                        <div class="form-group text-center">
                            <input type="submit" name="submit" class="btn btn-success" value="送出" />
                            <a href="?action=board_admin&type=boardlist&p=<?php echo $refpage; ?>" class="btn btn-info">取消</a>
                        </div>
                    </form>
                <?php } ?>
            <?php } ?>

        <?php } ?>
        <!-- 新建討論板 -->
        <div role="tabpanel" class="tab-pane fade<?php echo (!empty($_GET['type']) && $_GET['type'] == 'createboard') ? " in active" : ""; ?>" id="profile">
            2
        </div>
    </div>
</div>