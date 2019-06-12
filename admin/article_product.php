<div class="row content-body">
    <ol class="breadcrumb">
        <li><a href="#"><i class="fas fa-map-marker-alt"></i> 首頁</a></li>
        <?php echo ($_GET['action'] == 'article_product') ? "<li class=\"active\">" : "<li><a href=\"?action=article_product&type=productlist\">"; ?>作品管理<?php echo ($_GET['action'] == 'article_product') ? "" : "</a>" ?></li>
        <?php echo ($_GET['action'] == 'adminproduct') ? "<li class=\"active\">編輯作品內容</li>" : ""; ?>
        <?php echo ($_GET['action'] == 'delproduct') ? "<li class=\"active\">刪除作品</li>" : ""; ?>
    </ol>
</div>
<?php if (!empty($_GET['action']) && $_GET['action'] == 'article_product') { ?>
    <div class="col-md-12">
        <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'addprodsuccess') { ?>
            <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>新增作品成功！</strong></h4>
            </div>
        <?php } ?>
        <!-- 分頁 -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" <?php echo (!empty($_GET['type']) && $_GET['type'] == 'productlist') ? " class=\"active\"" : ""; ?>><a href="#productlist" aria-controls="productlist" role="tab" data-toggle="tab">管理作品</a></li>
            <li role="presentation" <?php echo (!empty($_GET['type']) && $_GET['type'] == 'addproduct') ? " class=\"active\"" : ""; ?>><a href="#addproduct" aria-controls="addproduct" role="tab" data-toggle="tab">新增作品</a></li>
        </ul>
        <!-- 內容 -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade<?php echo (!empty($_GET['type']) && $_GET['type'] == 'productlist') ? " active in" : ""; ?>" id="productlist">
                <?php
                $prodSql = mysqli_query($connect, "SELECT * FROM `productname` ORDER BY `prodOrder` ASC;");
                // 若目前無作品
                if (mysqli_num_rows($prodSql) == 0) { ?>
                    <div class="panel panel-info" style="margin-top: 1em;">
                        <div class="panel-heading">
                            <h3 class="panel-title">訊息</h3>
                        </div>
                        <div class="panel-body text-center">
                            <h2 class="info-warn">目前沒有任何作品！</h2>
                        </div>
                    </div>
                <?php } else { ?>
                    <table class="table table-hover">
                        <thead>
                            <tr class="warning">
                                <th class="news-order">作品編號</th>
                                <th class="news-title">作品名稱</th>
                                <th class="news-admin">管理操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($prodData = mysqli_fetch_array($prodSql, MYSQLI_ASSOC)) { ?>
                                <tr>
                                    <td class="news-order"><?php echo $prodData['prodOrder']; ?></td>
                                    <td class="news-title"><?php echo $prodData['prodTitle']; ?></td>
                                    <td class="news-admin">
                                        <a href="?action=adminproduct&pdid=1" class="btn btn-info">編輯</a>
                                        <a href="?action=delproduct&pdid=1" class="btn btn-danger">刪除</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
            <div role="tabpanel" class="tab-pane fade<?php echo (!empty($_GET['type']) && $_GET['type'] == 'addproduct') ? " active in" : ""; ?>" id="addproduct">
                <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'emptyprodname') { ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>作品名稱不能為空！</strong></h4>
                    </div>
                <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'emptyprodurl') { ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>作品位址不能為空！</strong></h4>
                    </div>
                <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'emptyproddescript') { ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>作品描述不能為空！</strong></h4>
                    </div>
                <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'errfilesize') { ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>作品視覺圖檔案大小過大！</strong></h4>
                    </div>
                <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'errfiletype') { ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>作品視覺圖檔案類型不正確！</strong></h4>
                    </div>
                <?php } ?>
                <form action="adminaction.php?action=addproduct" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="prodname">作品名稱</label>
                        <input type="text" name="prodname" id="prodname" class="form-control" placeholder="請輸入作品名稱，此為必填項" />
                    </div>
                    <div class="form-group">
                        <label for="produrl">作品位址</label>
                        <input type="text" name="produrl" id="produrl" class="form-control" placeholder="請輸入作品位址，此為必填項" />
                    </div>
                    <div class="form-group">
                        <label for="proddescript">作品描述</label>
                        <textarea id="editor1" name="proddescript" class="form-control noResize" rows="3"></textarea>
                        <script>
                            CKEDITOR.replace('editor1');
                        </script>
                    </div>
                    <div class="form-group">
                        <label for="prodimage">作品視覺圖</label>
                        <input type="file" id="prodimage" name="prodimage" />
                        <p class="help-block">建議解析度為 586 × 670 或是等比例之解析度，若未上傳作品視覺圖則會使用系統預設視覺圖</p>
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" name="submit" value="送出" class="btn btn-success" />
                    </div>
                </form>
            </div>
        </div>
    <?php } elseif (!empty($_GET['action']) && $_GET['action'] == 'adminproduct') {
    if (empty($_GET['pdid'])) { ?>
            <div class="panel panel-danger" style="margin-top: 1em;">
                <div class="panel-heading">
                    <h3 class="panel-title">警告</h3>
                </div>
                <div class="panel-body text-center">
                    <h2 class="news-warn">無法識別作品編號，請依正常程序操作！</h2>
                </div>
            </div>
        <?php } else {
        $pdid = $_GET['pdid'];
        $prodDetailSql = mysqli_query($connect, "SELECT * FROM `productname` WHERE `prodOrder`=$pdid;");
        if (mysqli_num_rows($prodDetailSql) == 0) { ?>
                <div class="panel panel-danger" style="margin-top: 1em;">
                    <div class="panel-heading">
                        <h3 class="panel-title">警告</h3>
                    </div>
                    <div class="panel-body text-center">
                        <h2 class="news-warn">該作品編號找不到作品資料，請依正常程序操作！</h2>
                    </div>
                </div>
            <?php } else {
            $prodDetailData = mysqli_fetch_array($prodDetailSql, MYSQLI_ASSOC); ?>
                <form action="adminaction.php?action=adminproduct" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="prodname">作品名稱</label>
                        <input type="text" name="prodname" id="prodname" class="form-control" value="<?php echo $prodDetailData['prodTitle']; ?>" placeholder="請輸入商品名稱，此為必填項" />
                    </div>
                    <div class="form-group">
                        <label for="produrl">作品位址</label>
                        <input type="text" name="produrl" id="produrl" class="form-control" value="<?php echo $prodDetailData['prodPageUrl']; ?>" placeholder="請輸入商品名稱，此為必填項" />
                    </div>
                    <div class="form-group">
                        <label for="proddescript">作品描述</label>
                        <textarea id="editor1" name="proddescript" class="form-control noResize" rows="3"><?php echo $prodDetailData['prodDescript']; ?></textarea>
                        <script>
                            CKEDITOR.replace('editor1');
                        </script>
                    </div>
                    <div class="form-group">
                        <label for="prodimage">作品視覺圖</label>
                        <input type="file" id="prodimage" name="prodimage" />
                        <p class="help-block">建議解析度為 586 × 670 或是等比例之解析度，若未上傳作品視覺圖則會使用系統預設視覺圖</p>
                        <?php if ($prodDetailData['prodImgUrl'] != "nowprint.jpg") { ?>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="delprodimage" value="true" /> 刪除作品視覺圖
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="nowimage">目前作品視覺圖</label><br />
                        <?php if (empty($prodDetailData['prodImgUrl'])) { ?>
                            <p class="form-control-static text-info" id="nowimage"><strong>此作品目前尚無視覺圖！</strong></p>
                        <?php } else { ?>
                            <img src="../images/products/<?php echo $prodDetailData['prodImgUrl']; ?>" id="nowimage" width="100%" />
                        <?php } ?>
                    </div>
                    <input type="hidden" name="pdid" value="<?php echo $_GET['pdid']; ?>" />
                    <div class="form-group text-center">
                        <input type="submit" name="submit" value="送出" class="btn btn-success" />
                        <a href="?action=article_product&type=productlist" class="btn btn-info">取消</a>
                    </div>
                </form>
            <?php }
    } ?>

    <?php } ?>
</div>