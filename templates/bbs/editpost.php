<?php
// 沒有參考頁數
if (empty($_GET['refpage'])) {
    $refpage = 1;
} else {
    $refpage = $_GET['refpage'];
}
// 沒有參考討論板
if (!empty($_GET['refbid'])) {
    $refbid = $_GET['refbid'];
} else {
    $refbid = "";
}
// 貼文 ID 為空
if (empty($_GET['id'])) { ?>
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title">錯誤</h3>
        </div>
        <div class="panel-body text-center">
            <h2 class="news-warn">找不到這則文章，請依正常程序刪除文章！<br /><br />
                <div class="btn-group" role="group">
                    <a class="btn btn-lg btn-info" onClick="javascript:history.back();">返回上一頁</a>
                    <a href="bbs.php?action=viewboard" class="btn btn-lg btn-success">返回討論板列表</a>
                </div>
            </h2>
        </div>
    </div>
<?php // 無法得知是貼文還是回文
} elseif (empty($_GET['type']) || ($_GET['type'] != 'post' && $_GET['type'] != 'article')) { ?>
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title">錯誤</h3>
        </div>
        <div class="panel-body text-center">
            <h2 class="news-warn">無法定義是「貼文」還是「回文」，請依正常程序刪除文章！<br /><br />
                <div class="btn-group" role="group">
                    <a class="btn btn-lg btn-info" onClick="javascript:history.back();">返回上一頁</a>
                    <?php echo (empty($_GET['refpostid']))? "<a class=\"btn btn-success\" onClick=\"javascript:history.back();\">返回上一頁</a>" : "<a href=\"bbs.php?action=viewpostcontent&postid=" . $_GET['refpostid'] . "&refbid=$refbid&refpage=$refpage\" class=\"btn btn-lg btn-success\">返回文章</a>"; ?>
                </div>
            </h2>
        </div>
    </div>
<?php }elseif($_GET['type'] == 'action' && empty($_GET['refpostid'])){ ?>
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title">錯誤</h3>
        </div>
        <div class="panel-body text-center">
            <h2 class="news-warn">無法找到主貼文識別碼，請依正常程序刪除文章！<br /><br />
                <div class="btn-group" role="group">
                    <a class="btn btn-lg btn-info" onClick="javascript:history.back();">返回上一頁</a>
                    <?php echo (empty($_GET['refpostid']))? "<a class=\"btn btn-success\" onClick=\"javascript:history.back();\">返回上一頁</a>" : "<a href=\"bbs.php?action=viewpostcontent&postid=" . $_GET['refpostid'] . "&refbid=$refbid&refpage=$refpage\" class=\"btn btn-lg btn-success\">返回文章</a>"; ?>
                </div>
            </h2>
        </div>
    </div>
<?php // 資訊齊全
} else {
    $id = $_GET['id'];
    // 判別文章類型決定要用哪個資料表及哪個欄位
    if ($_GET['type'] == 'post') {
        $type = "bbspost";
        $idtype = "postID";
        $userid = "postUserID";
    } else {
        $type = "bbsarticle";
        $idtype = "articleID";
        $userid = "articleUserID";
    }
    $sql = mysqli_query($connect, "SELECT * FROM `$type` WHERE `$idtype`=$id;");
    $datarows = mysqli_num_rows($sql);
    // 找不到資料
    if ($datarows == 0) { ?>
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">錯誤</h3>
            </div>
            <div class="panel-body text-center">
                <h2 class="news-warn"><?php echo ($_GET['type'] == 'post') ? "找不到這則貼文！" : "找不到這則回文！"; ?><br /><br />
                    <div class="btn-group" role="group">
                        <?php echo (empty($refbid)) ? "<a class=\"btn btn-lg btn-info\" onClick=\"javascript:history.back();\">返回上一頁</a>" : "<a href=\"bbs.php?action=viewbbspost&bid=$refbid&pid=$refpage\" class=\"btn btn-lg btn-info\">返回討論板</a>"; ?>
                        <a href="bbs.php?action=viewboard" class="btn btn-lg btn-success">返回討論板列表</a>
                    </div>
                </h2>
            </div>
        </div>
    <?php } else {
    $row = mysqli_fetch_array($sql, MYSQLI_ASSOC);
    $usrsql = "SELECT * FROM `member` WHERE `userName`='" . $row[$userid] . "';";
    $usersql = mysqli_query($connect, $usrsql);
    $userrow = mysqli_fetch_array($usersql, MYSQLI_BOTH);
    // 若編輯者非本人
    if ($userrow['userName'] != $_SESSION['uid']) { ?>
            <div class="col-sm-10 col-sm-push-1">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">錯誤</h3>
                    </div>
                    <div class="panel-body">
                        <h2 class="news-warn">請勿越權操作，此篇文章的發文者與您現在的登入身份不符！<br /><br />
                            <div class="btn-group" role="group">
                                <?php echo (empty($refbid) || empty($_GET['refpostid'])) ? "<a class=\"btn btn-lg btn-info\" onClick=\"javascript:history.back();\">返回上一頁</a>" : "<a href=\"bbs.php?action=viewpostcontent&postid=" . $_GET['refpostid'] . "&refbid=$refbid&refpage=$refpage\" class=\"btn btn-lg btn-info\">返回文章</a>"; ?>
                                <a href="bbs.php?action=viewboard" class="btn btn-lg btn-success">返回討論板列表</a>
                            </div>
                        </h2>
                    </div>
                </div>
            </div>
            <?php } else { ?>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-10 col-sm-push-1">
                        <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'editposterrtype') { ?>
                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4><strong>無法得知文章是主貼文還是回文，請依正常程序修改文章！</strong></h4>
                            </div>
                        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editposterrpostid') { ?>
                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4><strong>無法取得文章的識別碼，請依正常程序修改文章！</strong></h4>
                            </div>
                        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editposterrnotfound') { ?>
                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4><strong>找不到此文章，請依正常程序修改文章！</strong></h4>
                            </div>
                        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editposterrauthfail') { ?>
                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4><strong>發文者與您登入的身分不符，請依正常程序修改文章！</strong></h4>
                            </div>
                        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editposterrrefpid') { ?>
                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4><strong>無法取得參考貼文識別碼，請依正常程序修改文章！</strong></h4>
                            </div>
                        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editposterrtitle') { ?>
                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4><strong>主貼文標題不可留空！</strong></h4>
                            </div>
                        <?php } ?>
                        <form action="actions.php?action=editpost" method="POST">
                            <div class="form-group">
                                <label for="title"><?php echo ($_GET['type'] == 'post') ? "主貼文標題" : "回文標題"; ?></label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo ($_GET['type'] == 'post') ? $row['postTitle'] : $row['articleTitle']; ?>" placeholder="請輸入<?php echo ($_GET['type'] == 'post') ? "主貼文標題，此為必填項目" : "回文標題，可不填"; ?>">
                            </div>
                            <?php if ($_GET['type'] == 'post') { ?>
                                <div class="form-group">
                                    <label for="posttype">主貼文類型</label>
                                    <select class="form-control" name="posttype">
                                        <option value="綜合討論"<?php echo ($row['postType'] == '綜合討論')? " selected" : ""; ?>>綜合討論</option>
                                        <option value="板務公告"<?php echo ($row['postType'] == '板務公告')? " selected" : ""; ?>>板務公告</option>
                                        <option value="攻略心得"<?php echo ($row['postType'] == '攻略心得')? " selected" : ""; ?>>攻略心得</option>
                                        <option value="同人創作"<?php echo ($row['postType'] == '同人創作')? " selected" : ""; ?>>同人創作</option>
                                    </select>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="content"><?php echo ($_GET['type'] == 'post') ? "主貼文內容" : "回文內容"; ?></label>
                                <textarea id="editor1" name="content" class="form-control" rows="3" placeholder="請輸入<?php echo ($_GET['type'] == 'post') ? "主貼文內容" : "回文內容"; ?>，此為必填項"><?php echo ($_GET['type'] == 'post') ? $row['postContent'] : $row['articleContent']; ?></textarea>
                                <script>CKEDITOR.replace( 'editor1' );</script>
                            </div>
                            <input type="hidden" name="type" value="<?php echo $_GET['type']; ?>" />
                            <input type="hidden" name="author" value="<?php echo ($_GET['type'] == 'post')? $row['postUserID'] : $row['articleUserID']; ?>" />
                            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
                            <input type="hidden" name="refbid" value="<?php echo (empty($_GET['refbid'])) ? "" : $_GET['refbid']; ?>" />
                            <input type="hidden" name="refpage" value="<?php echo (empty($_GET['refpage']))? "" : $_GET['refpage']; ?>" />
                            <input type="hidden" name="refpostid" value="<?php echo $_GET['refpostid']; ?>" />
                            <input type="hidden" name="refer" value="action=editpost&type=<?php echo $_GET['type']; ?>&id=<?php echo $_GET['id']; ?><?php echo (!empty($_GET['refbid']))? "&refbid=" . $_GET['refbid'] : ""; ?><?php echo (!empty($_GET['refpage']))? "&refpage=" . $_GET['refpage'] : ""; ?><?php echo (!empty($_GET['refpostid']))? "&refpostid=" . $_GET['refpostid'] : ""; ?>" />
                            <div class="col-md-12 text-center">
                                <input type="submit" name="submit" class="btn btn-success" value="確認修改" />
                                <?php echo (empty($_GET['refpostid']))? "<a class=\"btn btn-info\" onClick=\"javascript:history.back();\">返回上一頁</a>" : "<a href=\"bbs.php?action=viewpostcontent&postid=" . $_GET['refpostid'] . "&refbid=$refbid&refpage=$refpage\" class=\"btn btn-info\">返回文章</a>"; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php }
}
} ?>