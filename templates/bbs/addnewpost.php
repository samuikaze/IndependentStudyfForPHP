<div class="container">
    <div class="row">
        <div class="col-sm-10 col-sm-push-1">
        <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'addposterrtitle') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>文章標題不可留空！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'addposterrtype') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>請確實將文章分類！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'addposterrcontent') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>文章內容不可留空！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'addposterrtargetboard') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>無法判別文章應該隸屬於哪個討論板，請依正常程序張貼新文章！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'addposterruserid') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>無法確認張貼文章者的身分，請依正常程序張貼新文章！</strong></h4>
            </div>
        <?php } elseif(!empty($_GET['msg']) && $_GET['msg'] == 'addposterruid') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>資料中的張貼者身分與您所登入的身分不符，請依正常程序張貼新文章！</strong></h4>
            </div>
        <?php } ?>
            <?php if(empty($_GET['boardid'])){ ?>
                <h2 class="news-warn">無法指定文章張貼的討論板，請依正常程序張貼新文章！<br /><br /><a href="?action=viewboard" class="btn btn-lg btn-success">按此返回討論版列表</a></h2>
            <?php }elseif($_GET['boardid'] == 'norefer'){?>
                <h2 class="news-warn">由於未指定重導路徑，故本頁僅用於顯示訊息！<br /><br /><a href="?action=viewboard" class="btn btn-lg btn-success">按此返回討論版列表</a></h2>
            <?php }else{ ?>
            <form method="POST" action="actions.php?action=addnewpost">
                <div class="form-group">
                    <label for="posttitle">文章標題</label>
                    <input type="text" name="posttitle" class="form-control" id="posttitle" placeholder="請輸入文章標題，此為必填項" />
                </div>
                <div class="form-group">
                        <label for="posttype">文章分類</label>
                        <select name="posttype" class="form-control" id="posttype">
                            <option value="" selected>請選擇分類</option>
                            <option value="綜合討論">綜合討論</option>
                            <?php if($_SESSION['priv'] == 99){ ?><option value="板務公告">板務公告</option><?php } ?>
                            <option value="攻略心得">攻略心得</option>
                            <option value="同人創作">同人創作</option>
                        </select>
                    </div>
                <div class="form-group">
                    <label for="postcontent">文章內容</label>
                    <textarea name="postcontent" class="form-control noResize" rows="3" placeholder="請輸入文章內容，此為必填項"></textarea>
                </div>
                <input type="hidden" name="refer" value="action=addnewpost&boardid=<?php echo $_GET['boardid']; ?>" />
                <input type="hidden" name="targetboard" value="<?php echo $_GET['boardid']; ?>" />
                <input type="hidden" name="userid" value="<?php echo $_SESSION['uid']; ?>" />
                <div class="form-group text-center">
                    <input type="submit" name="submit" value="送出" class="btn btn-success" />
                    <a href="?action=viewbbspost&bid=<?php echo $_GET['boardid']; ?>" class="btn btn-info">取消</a>
                </div>
            </form>
            <?php } ?>
        </div>
    </div>
</div>