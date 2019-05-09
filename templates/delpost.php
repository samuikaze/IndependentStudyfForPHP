<?php
if (empty($_GET['refpage'])) {
    $refpage = 1;
} else {
    $refpage = $_GET['refpage'];
}
// 貼文 ID 為空
if (empty($_GET['id'])) { ?>
    <h2 class="news-warn">找不到這則文章，請依正常程序刪除文章！<br /><br />
        <div class="btn-group" role="group">
            <a class="btn btn-lg btn-info" onClick="javascript:history.back();">返回上一頁</a>
            <a href="bbs.php?action=viewboard" class="btn btn-lg btn-success">返回討論板列表</a>
        </div>
    </h2>
<?php // 無法得知是貼文還是回文
}elseif(empty($_GET['type']) && $_GET['type'] != 'post' && $_GET['type'] != 'article'){ ?>
    <h2 class="news-warn">無法定義是「貼文」還是「回文」，請依正常程序刪除文章！<br /><br />
        <div class="btn-group" role="group">
            <a class="btn btn-lg btn-info" onClick="javascript:history.back();">返回上一頁</a>
            <a href="bbs.php?action=viewboard" class="btn btn-lg btn-success">返回討論板列表</a>
        </div>
    </h2>
<?php } else { 
    $targetid = $_GET['id'];
    // 判別要去哪邊取要刪除資料，SQL 語法用
    if($_GET['type'] == 'post'){
        $type = "bbspost";
        $idtype = "postID";
        $userid = "postUserID";
    }else{
        $type = "bbsarticle";
        $idtype = "articleID";
        $userid = "articleUserID";
    }
    $sql = mysqli_query($connect, "SELECT * FROM `$type` WHERE `$idtype`=$targetid;");
    $datarows = mysqli_num_rows($sql);
    // 找不到資料
    if ($datarows == 0) { ?>
        <h2 class="news-warn"><?php echo ($_GET['type'] == 'post') ? "找不到這則貼文！" : "找不到這則回文！"; ?><br /><br />
            <div class="btn-group" role="group">
                <?php echo (empty($refbid))? "<a class=\"btn btn-lg btn-info\" onClick=\"javascript:history.back();\">返回上一頁</a>" : "<a href=\"bbs.php?action=viewbbspost&bid=$refbid&pid=$refpage\" class=\"btn btn-lg btn-info\">返回討論板</a>"; ?>
                <a href="bbs.php?action=viewboard" class="btn btn-lg btn-success">返回討論板列表</a>
            </div>
        </h2>
<?php }else{ 
    $row = mysqli_fetch_array($sql, MYSQLI_ASSOC);
    $usrsql = "SELECT `userNickname` FROM `member` WHERE `userName`='" . $row[$userid] . "';";
    $usersql = mysqli_query($connect, $usrsql); 
    $userrow = mysqli_fetch_array($usersql, MYSQLI_BOTH); ?>
        <div class="container">
            <div class="row">
                <div class="col-sm-10 col-sm-push-1">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title">您確定要刪除這篇<?php echo ($_GET['type'] == 'post')? "貼文" : "回文"; ?>嗎？<?php echo ($_GET['type'] == 'post')? "其下所有回文也會一併被刪除！" : ""; ?></h3>
                        </div>
                        <div class="panel-body">
                            <form action="actions.php?action=delpostarticle" method="POST">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo ($_GET['type'] == 'post')? "貼文" : "回文"; ?>標題</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static"><?php echo ($_GET['type'] == 'post')? $row['postTitle'] : $row['articleTitle']; ?></p>
                                    </div>
                                </div>
                                <?php if($_GET['type'] == 'post'){ ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">貼文類型</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static"><?php echo $row['postType']; ?></p>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo ($_GET['type'] == 'post')? "貼文" : "回文"; ?>內容</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static"><?php echo ($_GET['type'] == 'post')? $row['postContent'] : $row['articleContent']; ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo ($_GET['type'] == 'post')? "貼文" : "回文"; ?>者</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static"><?php echo ($_GET['type'] == 'post')? $row['postUserID'] : $row['articleUserID']; ?><?php echo "&nbsp;(&nbsp;<strong>" . $userrow['userNickname'] . "</strong>&nbsp;)";?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo ($_GET['type'] == 'post')? "貼文" : "回文"; ?>時間</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static"><?php echo ($_GET['type'] == 'post')? $row['postTime'] : $row['articleTime']; ?></p>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <input type="submit" name="submit" class="btn btn-danger" value="確認刪除" />
                                    <?php echo (empty($refbid))? "<a class=\"btn btn-success\" onClick=\"javascript:history.back();\">返回上一頁</a>" : "<a href=\"bbs.php?action=viewbbspost&bid=$refbid&pid=$refpage\" class=\"btn btn-success\">返回討論板</a>"; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }
    }?>
<?php //} ?>