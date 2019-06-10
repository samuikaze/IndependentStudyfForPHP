<div class="row content-body">
    <ol class="breadcrumb">
        <li><a href="?action=index"><i class="fas fa-map-marker-alt"></i> 首頁</a></li>
        <?php echo ($_GET['action'] == 'frontcarousel') ? "<li class=\"active\">" : "<li><a href=\"?action=frontcarousel\">"; ?>輪播管理<?php echo ($_GET['action'] == 'frontcarousel') ? "" : "</a>" ?></li>
        <?php echo ($_GET['action'] == 'carouseladmin') ? "<li class=\"active\">編輯輪播</li>" : ""; ?>
        <?php echo ($_GET['action'] == 'carouseldel') ? "<li class=\"active\">刪除輪播</li>" : ""; ?>
    </ol>
</div>
<div class="col-md-12">
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
                            <h2 class="news-warn">目前尚未新增任何輪播圖！<br /><br />
                        </div>
                    </div>
                <?php } else { ?>
                    <table class="table table-hover">
                        <thead>
                            <tr class="warning">
                                <th>序</th>
                                <th>輪播圖片</th>
                                <th>輪播管理</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- 一個輪播項目 -->
                            <?php while ($datas = mysqli_fetch_array($getData, MYSQLI_ASSOC)) { ?>
                                <tr>
                                    <td><?php echo $getData['imgID']; ?></td>
                                    <td><?php echo $getData['imgUrl']; ?></td>
                                    <td>
                                        <a href="?action=carouseladmin&csid=<?php echo $getData['imgID']; ?>" class="btn btn-info">管理</a>
                                        <a href="?action=carouseldel&csid=<?php echo $getData['imgID']; ?>" class="btn btn-danger">刪除</a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <!-- /一個輪播項目 -->
                        </tbody>
                    </table>
                <?php } ?>
            </div>
            <!-- 新增輪播 -->
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
                        <label for="carouselImg">輪播圖片</label>
                        <input type="file" id="carouselImg" name="carouselImg" />
                        <p class="help-block">建議解析度為 1280 × 620，若上傳非此比例之解析度圖片可能導致樣式跑位，此為必要項目。</p>
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" name="submit" value="送出" class="btn btn-success" />
                    </div>
                </form>
            </div>
        </div>
    <?php } ?>
</div>