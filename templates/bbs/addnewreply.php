<div class="container">
    <div class="row">
        <div class="col-sm-10 col-sm-push-1">
            <?php if (empty($_GET['postid'])) { ?>
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">錯誤</h3>
                    </div>
                    <div class="panel-body text-center">
                        <h2 class="news-warn">無法指定回文張貼的文章識別碼，請依正常程序張貼新回文！<br /><br />
                            <div class="btn-group" role="group">
                                <a class="btn btn-lg btn-info" onClick="javascript:history.back();">返回上一頁</a>
                                <?php echo (empty($_GET['refbid'])) ? "<a href=\"bbs.php?action=viewboard\" class=\"btn btn-lg btn-success\">返回討論板列表</a>" : "<a href=\"bbs.php?action=viewpostcontent&postid=" . $_GET['refbid'] . "&refbid=" . $_GET['refbid'] . "\" class=\"btn btn-lg btn-success\">返回討論文章</a>"; ?>
                            </div>
                        </h2>
                    </div>
                </div>
            <?php } else { 
                $sql = mysqli_query($connect, "SELECT `postStatus` FROM `bbspost` WHERE `postID`=" . $_GET['postid']);
                $pStatus = mysqli_fetch_array($sql, MYSQLI_ASSOC);
                if($pStatus['postStatus'] == 2 || $pStatus['postStatus'] == 3){?>
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">錯誤</h3>
                    </div>
                    <div class="panel-body text-center">
                        <h2 class="news-warn">該文章已被鎖定，不可以新增回文！<br /><br />
                            <div class="btn-group" role="group">
                                <a class="btn btn-lg btn-info" onClick="javascript:history.back();">返回上一頁</a>
                                <?php echo (empty($_GET['refbid'])) ? "<a href=\"bbs.php?action=viewboard\" class=\"btn btn-lg btn-success\">返回討論板列表</a>" : "<a href=\"bbs.php?action=viewpostcontent&postid=" . $_GET['refbid'] . "&refbid=" . $_GET['refbid'] . "\" class=\"btn btn-lg btn-success\">返回討論文章</a>"; ?>
                            </div>
                        </h2>
                    </div>
                </div>
            <?php } else {
            $refbid = (empty($_GET['refbid'])) ? "" : $_GET['refbid']; ?>
                <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'addarticleerrcontent') { ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>回文內容不可留空！</strong></h4>
                    </div>
                <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'addarticleerrpostid') { ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>回文隸屬之主貼文識別碼無法識別，請依正常程序張貼回文！</strong></h4>
                    </div>
                <?php } ?>
                <form method="POST" action="actions.php?action=addnewreply">
                    <div class="form-group">
                        <label for="articletitle">回文標題</label>
                        <input type="text" name="articletitle" class="form-control" id="articletitle" placeholder="請輸入回文標題，可以不填" />
                    </div>
                    <div class="form-group">
                        <label for="articlecontent">回文內容</label>
                        <textarea id="editor1" name="articlecontent" class="form-control noResize" rows="3" placeholder="請輸入回文內容，此為必填項"></textarea>
                        <script>CKEDITOR.replace( 'editor1' );</script>
                    </div>
                    <input type="hidden" name="refer" value="action=replypost&postid=<?php echo $_GET['postid']; ?>&refbid=<?php echo $_GET['refbid']; ?>" />
                    <input type="hidden" name="postid" value="<?php echo $_GET['postid']; ?>" />
                    <input type="hidden" name="refbid" value="<?php echo $refbid; ?>" />
                    <div class="form-group text-center">
                        <input type="submit" name="submit" value="送出" class="btn btn-success" />
                        <a href="?action=viewpostcontent&postid=<?php echo $_GET['postid']; ?>&refbid=<?php echo $refbid; ?>" class="btn btn-info">取消</a>
                    </div>
                </form>
            <?php } 
            } ?>
        </div>
    </div>
</div>