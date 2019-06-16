<?php
if (empty($_GET['refpage'])) {
    $refpage = 1;
} else {
    $refpage = $_GET['refpage'];
}
if (!empty($_GET['refbid'])){
    $refbid = $_GET['refbid'];
}else{
    $refbid = "";
}
if (!empty($_GET['refpage'])){
    $refpage = $_GET['refpage'];
}else{
    $refpage = "";
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
    $usrsql = "SELECT * FROM `member` WHERE `userName`='" . $row[$userid] . "';";
    $usersql = mysqli_query($connect, $usrsql); 
    $userrow = mysqli_fetch_array($usersql, MYSQLI_BOTH); 
    $priv = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM `systemsetting` WHERE `settingName`='adminPriv';"), MYSQLI_ASSOC);
    // 若刪除者非本人
    if($userrow['userName'] != $_SESSION['uid'] && $_SESSION['priv'] < $priv['settingValue']){ ?>
        <div class="col-sm-10 col-sm-push-1">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title">錯誤</h3>
                </div>
                <div class="panel-body">
                    <h2 class="news-warn">請勿越權操作，此篇文章的發文者與您現在的登入身份不符！<br /><br />
                        <div class="btn-group" role="group">
                            <?php echo (empty($refbid) || empty($_GET['refpostid']))? "<a class=\"btn btn-lg btn-info\" onClick=\"javascript:history.back();\">返回上一頁</a>" : "<a href=\"bbs.php?action=viewpostcontent&postid=" . $_GET['refpostid'] . "&refbid=$refbid&refpage=$refpage\" class=\"btn btn-lg btn-info\">返回文章</a>"; ?>
                            <a href="bbs.php?action=viewboard" class="btn btn-lg btn-success">返回討論板列表</a>
                        </div>
                    </h2>
                </div>
            </div>
        </div>
    <?php }else{ ?>
        <div class="container">
            <div class="row">
                <div class="col-sm-10 col-sm-push-1">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title">您確定要刪除這篇<?php echo ($_GET['type'] == 'post')? "貼文" : "回文"; ?>嗎？<?php echo ($_GET['type'] == 'post')? "其下所有回文也會一併被刪除！" : ""; ?></h3>
                        </div>
                        <div class="panel-body">
                            <form action="actions.php?action=delpost" method="POST">
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
                                <input type="hidden" name="author" value="<?php echo ($_GET['type'] == 'post')? $row['postUserID'] : $row['articleUserID']; ?>" />
                                <input type="hidden" name="type" value="<?php echo $_GET['type']; ?>" />
                                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
                                <input type="hidden" name="refbid" value="<?php echo (empty($_GET['refbid'])) ? "" : $_GET['refbid']; ?>" />
                                <input type="hidden" name="refpage" value="<?php echo (empty($_GET['refpage']))? "" : $_GET['refpage']; ?>" />
                                <?php echo ($_GET['type'] == 'article')? "<input type=\"hidden\" name=\"refpostid\" value=\"" . $_GET['refpostid'] . "\" />" : ""; ?>
                                <input type="hidden" name="refer" value="action=delpost&type=<?php echo $_GET['type']; ?>&id=<?php echo $_GET['id']; ?><?php echo (!empty($_GET['refbid']))? "&refbid=" . $_GET['refbid'] : ""; ?><?php echo (!empty($_GET['refpage']))? "&refpage=" . $_GET['refpage'] : ""; ?><?php echo (!empty($_GET['refpostid']))? "&refpostid=" . $_GET['refpostid'] : ""; ?>" />
                                <div class="col-md-12 text-center">
                                    <input type="submit" name="submit" class="btn btn-danger" value="確認刪除" />
                                    <?php echo (empty($refbid) || empty($_GET['refpostid']))? "<a class=\"btn btn-success\" onClick=\"javascript:history.back();\">返回上一頁</a>" : "<a href=\"bbs.php?action=viewpostcontent&postid=" . $_GET['refpostid'] . "&refbid=$refbid&refpage=$refpage\" class=\"btn btn-success\">返回討論板</a>"; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }
    }
    }?>
<?php //} ?>