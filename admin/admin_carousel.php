<div class="row content-body">
    <ol class="breadcrumb">
        <li><a href="?action=index"><i class="fas fa-map-marker-alt"></i> 首頁</a></li>
        <?php echo ($_GET['action'] == 'frontcarousel') ? "<li class=\"active\">" : "<li><a href=\"?action=frontcarousel&type=carousellist\">"; ?>輪播管理<?php echo ($_GET['action'] == 'frontcarousel') ? "" : "</a>" ?></li>
        <?php echo ($_GET['action'] == 'carouseladmin') ? "<li class=\"active\">編輯輪播</li>" : ""; ?>
        <?php echo ($_GET['action'] == 'carouseldel') ? "<li class=\"active\">刪除輪播</li>" : ""; ?>
    </ol>
</div>
<div class="col-md-12">
    <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'addcarouselsuccess') { ?>
        <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><strong>新增輪播成功！</strong></h4>
        </div>
    <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'updatesuccess') { ?>
        <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><strong>更新輪播成功！</strong></h4>
        </div>
    <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'emptycsid') { ?>
        <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><strong>無法取得輪播編號，請依正常程序更新輪播！</strong></h4>
        </div>
    <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'delsuccess') { ?>
        <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><strong>刪除輪播成功！</strong></h4>
        </div>
    <?php } ?>
    <?php if ($_GET['action'] == 'frontcarousel') {
        $getData = mysqli_query($connect, "SELECT * FROM `frontcarousel` ORDER BY `imgID` ASC;");
        $dataRows = mysqli_num_rows($getData); ?>
        <!-- 分頁項目 -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" <?php echo (!empty($_GET['type']) && $_GET['type'] == 'carousellist') ? " class=\"active\"" : ""; ?>><a href="#carouseladmin" aria-controls="carouseladmin" role="tab" data-toggle="tab">管理輪播</a></li>
            <li role="presentation" <?php echo (!empty($_GET['type']) && $_GET['type'] == 'carouseladd') ? " class=\"active\"" : ""; ?>><a href="#carouseladd" aria-controls="carouseladd" role="tab" data-toggle="tab">新增輪播</a></li>
        </ul>
        <!-- 分頁內容 -->
        <div class="tab-content">
            <!-- 管理輪播 -->
            <div role="tabpanel" class="tab-pane fade<?php echo (!empty($_GET['type']) && $_GET['type'] == 'carousellist') ? " active in" : ""; ?>" id="carouseladmin">
                <?php // 若尚未新增任何輪播圖
                if ($dataRows == 0) { ?>
                    <div class="panel panel-danger" style="margin-top: 1em; padding-bottom: 8em;">
                        <div class="panel-heading">
                            <h3 class="panel-title">錯誤</h3>
                        </div>
                        <div class="panel-body">
                            <h2 class="news-warn">目前尚未新增任何輪播圖！</h2><br /><br />
                        </div>
                    </div>
                <?php } else { ?>
                    <table class="table table-hover">
                        <thead>
                            <tr class="warning">
                                <th class="news-order">序</th>
                                <th class="news-title">輪播圖片</th>
                                <th class="news-admin">輪播管理</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- 一個輪播項目 -->
                            <?php while ($datas = mysqli_fetch_array($getData, MYSQLI_ASSOC)) { ?>
                                <tr>
                                    <td class="news-order"><?php echo $datas['imgID']; ?></td>
                                    <td class="news-title"><?php echo $datas['imgUrl']; ?></td>
                                    <td class="news-admin">
                                        <a href="?action=carouseladmin&csid=<?php echo $datas['imgID']; ?>" class="btn btn-info">管理</a>
                                        <a href="?action=carouseldel&csid=<?php echo $datas['imgID']; ?>" class="btn btn-danger">刪除</a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <!-- /一個輪播項目 -->
                        </tbody>
                    </table>
                <?php } ?>
            </div>
            <!-- 新增輪播 -->
            <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'emptyuploadfile') { ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><strong>沒有上傳圖片，圖片輪播必須要上傳圖片！</strong></h4>
                </div>
            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'errfilesize') { ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><strong>上傳的檔案大小過大，請調整後再進行上傳！</strong></h4>
                </div>
            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'errfiletype') { ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><strong>上傳的檔案格式不允許，請確認後再進行上傳！</strong></h4>
                </div>
            <?php } ?>
            <div role="tabpanel" class="tab-pane fade<?php echo (!empty($_GET['type']) && $_GET['type'] == 'carouseladd') ? " active in" : ""; ?>" id="carouseladd">
                <form action="adminaction.php?action=addcarousel" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="carouselDescript">輪播描述</label>
                        <input type="text" name="carouselDescript" id="carouselDescript" class="form-control" placeholder="請輸入顯示於輪播圖下方的描述文字，不填可留空" />
                    </div>
                    <div class="form-group">
                        <label for="carouselTarget">輪播位址</label>
                        <input type="text" name="carouselTarget" id="carouselTarget" class="form-control" placeholder="請輸入當按下輪播圖時欲跳轉的位址，不填可留空" />
                    </div>
                    <div class="form-group">
                        <label for="carouselImg" id="prevImg">輪播圖片</label>
                        <input type="file" id="carouselImg" data-prevtype="add" name="carouselImg" />
                        <p class="help-block">建議解析度為 1280 × 620，若上傳非此比例之解析度圖片可能導致樣式跑位，此為必要項目。</p>
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" name="submit" value="送出" class="btn btn-success" />
                    </div>
                </form>
            </div>
        </div>
    <?php } elseif ($_GET['action'] == 'carouseladmin') {
    if (empty($_GET['csid'])) { ?>
            <div class="panel panel-danger" style="margin-top: 1em; padding-bottom: 8em;">
                <div class="panel-heading">
                    <h3 class="panel-title">錯誤</h3>
                </div>
                <div class="panel-body">
                    <h2 class="news-warn">無法取得輪播編號，請依正常程序編輯輪播！<br /><br />
                        <div class="btn-group" role="group">
                            <a class="btn btn-lg btn-info" href="?action=frontcarousel&type=carousellist">返回輪播管理</a>
                        </div>
                    </h2>
                </div>
            </div>
        <?php } else {
        //取得輪播資料
        $csid = $_GET['csid'];
        $carouseldata = mysqli_query($connect, "SELECT * FROM `frontcarousel` WHERE `imgID`=$csid;");
        $datarows = mysqli_num_rows($carouseldata);
        if ($datarows == 0) { ?>
                <div class="panel panel-danger" style="margin-top: 1em; padding-bottom: 8em;">
                    <div class="panel-heading">
                        <h3 class="panel-title">錯誤</h3>
                    </div>
                    <div class="panel-body">
                        <h2 class="news-warn">查無該輪播，請依正常程序編輯輪播！<br /><br />
                            <div class="btn-group" role="group">
                                <a class="btn btn-lg btn-info" href="?action=frontcarousel&type=carousellist">返回輪播管理</a>
                            </div>
                        </h2>
                    </div>
                </div>
            <?php } else {
            $csDatas = mysqli_fetch_array($carouseldata, MYSQLI_ASSOC); ?>
                <form action="adminaction.php?action=editcarousel" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="carouselDescript">輪播描述</label>
                        <input type="text" name="carouselDescript" id="carouselDescript" class="form-control" placeholder="請輸入顯示於輪播圖下方的描述文字，不填可留空" value="<?php echo $csDatas['imgDescript']; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="carouselTarget">輪播位址</label>
                        <input type="text" name="carouselTarget" id="carouselTarget" class="form-control" placeholder="請輸入當按下輪播圖時欲跳轉的位址，不填可留空" value="<?php echo $csDatas['imgReferUrl']; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="carouselImg">輪播圖片</label>
                        <img src="../images/carousel/<?php echo $csDatas['imgUrl']; ?>" id="nowimage" width="100%" />
                        <input type="file" id="carouselImg" name="carouselImg" />
                        <p class="help-block">建議解析度為 1280 × 620，若上傳非此比例之解析度圖片可能導致樣式跑位，此為必要項目。</p>
                    </div>
                    <input type="hidden" name="csid" value="<?php echo $csid; ?>" />
                    <div class="form-group text-center">
                        <input type="submit" name="submit" value="送出" class="btn btn-success" />
                        <a href="?action=frontcarousel&type=carousellist" title="取消" class="btn btn-info">取消</a>
                    </div>
                </form>
            <?php }
    }
} elseif ($_GET['action'] == 'carouseldel') {
    if (empty($_GET['csid'])) { ?>
            <div class="panel panel-danger" style="margin-top: 1em; padding-bottom: 8em;">
                <div class="panel-heading">
                    <h3 class="panel-title">錯誤</h3>
                </div>
                <div class="panel-body">
                    <h2 class="news-warn">無法取得輪播編號，請依正常程序編輯輪播！<br /><br />
                        <div class="btn-group" role="group">
                            <a class="btn btn-lg btn-info" href="?action=frontcarousel&type=carousellist">返回輪播管理</a>
                        </div>
                    </h2>
                </div>
            </div>
        <?php } else {
        //取得輪播資料
        $csid = $_GET['csid'];
        $carouseldata = mysqli_query($connect, "SELECT * FROM `frontcarousel` WHERE `imgID`=$csid;");
        $datarows = mysqli_num_rows($carouseldata);
        if ($datarows == 0) { ?>
                <div class="panel panel-danger" style="margin-top: 1em; padding-bottom: 8em;">
                    <div class="panel-heading">
                        <h3 class="panel-title">錯誤</h3>
                    </div>
                    <div class="panel-body">
                        <h2 class="news-warn">查無該輪播，請依正常程序編輯輪播！<br /><br />
                            <div class="btn-group" role="group">
                                <a class="btn btn-lg btn-info" href="?action=frontcarousel&type=carousellist">返回輪播管理</a>
                            </div>
                        </h2>
                    </div>
                </div>
            <?php } else {
            $csDatas = mysqli_fetch_array($carouseldata, MYSQLI_ASSOC); ?>
                <form method="POST" class="form-horizontal" action="adminaction.php?action=delcarousel" style="margin-top: 1em;">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>您確定要刪除這個輪播項目嗎</strong></h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">輪播編號</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?php echo $csDatas['imgID']; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">輪播描述</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?php echo $csDatas['imgDescript']; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">輪播指向位址</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><?php echo (empty($csDatas['imgReferUrl']))? "無" : $csDatas['imgReferUrl']; ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">輪播圖片</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><img src="../images/carousel/<?php echo $csDatas['imgUrl']; ?>" width="100%" /></p>
                                </div>
                            </div>
                            <input type="hidden" name="csid" value="<?php echo $csDatas['imgID']; ?>" />
                            <div class="col-md-12 text-center">
                                <input type="submit" name="submit" class="btn btn-danger" value="刪除輪播" />
                                <a href="?action=frontcarousel&type=carousellist" class="btn btn-success">取消</a>
                            </div>
                        </div>
                    </div>
                </form>
            <?php }
    }
} ?>
</div>