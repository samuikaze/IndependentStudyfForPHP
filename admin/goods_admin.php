<div class="row content-body">
    <ol class="breadcrumb">
        <li><a href="?action=index"><i class="fas fa-map-marker-alt"></i> 首頁</a></li>
        <?php echo ($_GET['action'] == 'goods_admin') ? "<li class=\"active\">" : "<li><a href=\"?action=goods_admin&type=goodslist\">"; ?>商品管理<?php echo ($_GET['action'] == 'goods_admin') ? "" : "</a>" ?></li>
        <?php echo ($_GET['action'] == 'modifygoods') ? "<li class=\"active\">修改商品內容</li>" : ""; ?>
        <?php echo ($_GET['action'] == 'delgoods') ? "<li class=\"active\">確認下架商品</li>" : ""; ?>
    </ol>
</div>
<div class="col-md-12">
    <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'addgoodsuccess') { ?>
        <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><strong>商品上架成功！</strong></h4>
        </div>
    <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editgoodsuccess') { ?>
        <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><strong>商品修改成功！</strong></h4>
        </div>
    <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'delgoodsuccess') { ?>
        <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><strong>商品下架成功！</strong></h4>
        </div>
    <?php }
    if (!empty($_GET['type']) && ($_GET['type'] == 'goodslist' || $_GET['type'] == 'addgoods')) { ?>
        <!-- 標籤 -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"<?php echo ($_GET['type'] == 'goodslist')? " class=\"active\"" : ""; ?>><a href="#goodlist" aria-controls="goodlist" role="tab" data-toggle="tab">商品管理</a></li>
            <li role="presentation"<?php echo ($_GET['type'] == 'addgoods')? " class=\"active\"" : ""; ?>><a href="#addgoods" aria-controls="addgoods" role="tab" data-toggle="tab">上架商品</a></li>
        </ul>

        <!-- 內容 -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade<?php echo ($_GET['type'] == 'goodslist')? " active in" : ""; ?>" id="goodlist">
                <?php
                    $sql = mysqli_query($connect, "SELECT * FROM `goodslist` ORDER BY goodsOrder DESC;");
                    $datarows = mysqli_num_rows($sql);
                    // 若沒有商品
                    if($datarows == 0){ ?>
                        <div class="panel panel-info" style="margin-top: 1em;">
                            <div class="panel-heading">
                                <h3 class="panel-title">錯誤</h3>
                            </div>
                            <div class="panel-body text-center">
                                <h2 class="news-warn">目前沒有上架任何商品！<br /><br />
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-lg btn-info" href="?action=index">返回首頁</a>
                                    </div>
                                </h2>
                            </div>
                        </div>
                    <?php //若有商品則開始取資料並印出資料
                    }else{ ?>
                    <table class="table table-hover">
                        <thead>
                            <tr class="warning">
                                <th class="goodsid">商品識別碼</th>
                                <th class="goodname">商品名稱</th>
                                <th class="goodother">商品價格</th>
                                <th class="goodother">商品在庫量</th>
                                <th class="goodadmin">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)){ ?>
                            <tr>
                                <td class="goodsid"><?php echo $row['goodsOrder']; ?></td>
                                <td class="goodname"><?php echo $row['goodsName']; ?></td>
                                <td class="goodother"><?php echo $row['goodsPrice']; ?></td>
                                <td class="goodother"><?php echo $row['goodsQty']; ?></td>
                                <td class="goodadmin">
                                    <a href="?action=modifygoods&goodid=<?php echo $row['goodsOrder']; ?>&refpage=<?php echo (empty($_GET['refpage']))? "1" : $_GET['refpage']; ?>" class="btn btn-info">編輯</a>
                                    <a href="?action=delgoods&goodid=<?php echo $row['goodsOrder']; ?>&refpage=<?php echo (empty($_GET['refpage']))? "1" : $_GET['refpage']; ?>" class="btn btn-danger">下架</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php } ?>
                
            </div>
            <div role="tabpanel" class="tab-pane fade<?php echo ($_GET['type'] == 'addgoods')? " active in" : ""; ?>" id="addgoods">
            <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'addgoodserrname') { ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><strong>商品名稱不能為空！</strong></h4>
                </div>
            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'addgoodserrprice') { ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><strong>商品價格不能為空！</strong></h4>
                </div>
            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'addgoodserrquantity') { ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><strong>商品數量不能為空或為零！</strong></h4>
                </div>
            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'addgoodserrdescript') { ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><strong>商品描述不能為空！</strong></h4>
                </div>
            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'addgoodserrfilesize') { ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><strong>上傳檔案大小過大！</strong></h4>
                </div>
            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'addgoodserrfiletype') { ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><strong>上傳檔案類型不正確！</strong></h4>
                </div>
            <?php } ?>
                <form action="adminaction.php?action=addgoods" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="goodname">商品名稱</label>
                        <input type="text" name="goodname" id="goodname" class="form-control" placeholder="請輸入商品名稱，此為必填項" />
                    </div>
                    <div class="form-group">
                        <label for="goodprice">商品價格</label>
                        <input type="text" name="goodprice" id="goodprice" class="form-control" placeholder="請輸入商品價格，此為必填項" />
                    </div>
                    <div class="form-group">
                        <label for="goodquantity">商品在庫量</label>
                        <input type="text" name="goodquantity" id="goodquantity" class="form-control" placeholder="請輸入商品在庫量，此為必填項" />
                    </div>
                    <div class="form-group">
                        <label for="gooddescript">商品描述</label>
                        <textarea id="editor1" name="gooddescript" class="form-control noResize" rows="3" placeholder="請輸入商品描述，此為必填項"></textarea>
                        <script>CKEDITOR.replace( 'editor1' );</script>
                    </div>
                    <div class="form-group">
                        <label for="goodimage" id="prevImg">商品圖片</label>
                        <input type="file" id="goodimage" data-prevtype="add" name="goodimage" />
                        <p class="help-block">建議解析度為 1000 × 501，若未上傳商品圖則會使用系統預設商品圖</p>
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" name="submit" value="送出" class="btn btn-success" />
                    </div>
                </form>
            </div>
        </div>
    <?php } elseif (!empty($_GET['action']) && $_GET['action'] == 'modifygoods') { 
        if(empty($_GET['goodid'])){ ?>
            <div class="panel panel-info" style="margin-top: 1em;">
                <div class="panel-heading">
                    <h3 class="panel-title">錯誤</h3>
                </div>
                <div class="panel-body text-center">
                    <h2 class="news-warn">無法取得商品識別碼，請依正常程序修改商品！<br /><br />
                        <div class="btn-group" role="group">
                            <a class="btn btn-lg btn-info" href="?action=goods_admin&type=goodslist<?php echo (empty($_GET['refpage']))? "" : "&pid=" . $_GET['refpage']; ?>">返回商品管理</a>
                        </div>
                    </h2>
                </div>
            </div>
        <?php }else{ 
            $gid = $_GET['goodid'];
            // 取得商品內容
            $gContentSql = mysqli_query($connect, "SELECT * FROM `goodslist` WHERE `goodsOrder`=$gid;");
            $gRows = mysqli_num_rows($gContentSql);
            // 若查詢資料為空，意即找不到該項商品
            if($gRows == 0){ ?>
            <div class="panel panel-info" style="margin-top: 1em;">
                <div class="panel-heading">
                    <h3 class="panel-title">錯誤</h3>
                </div>
                <div class="panel-body text-center">
                    <h2 class="news-warn">找不到該項商品，請依正常程序修改商品！<br /><br />
                        <div class="btn-group" role="group">
                            <a class="btn btn-lg btn-info" href="?action=goods_admin&type=goodslist<?php echo (empty($_GET['refpage']))? "" : "&pid=" . $_GET['refpage']; ?>">返回商品管理</a>
                        </div>
                    </h2>
                </div>
            </div>
            <?php }else{
                $gContent = mysqli_fetch_array($gContentSql, MYSQLI_ASSOC);
                $uploaderID = $gContent['goodsUp'];
                $gUploader = mysqli_fetch_array(mysqli_query($connect, "SELECT `userNickname` AS `Upnn` FROM `member` WHERE `userName`='$uploaderID';"), MYSQLI_ASSOC); ?>
        <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'editgoodserrgid') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>無法識別商品，請依正常程序新增商品！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editgoodserrname') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>商品名稱不能為空！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editgoodserrprice') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>商品價格不能為空！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editgoodserrquantity') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>商品價格不能留空或為零！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editgoodserrdescript') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>商品描述不能為空！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editgoodserrfilesize') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>上傳的檔案大小過大！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editgoodserrfiletype') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>上傳的檔案類型不正確！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editgoodserrupdel') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>上傳商品圖片與刪除商品圖片不可同時進行，請確定要執行的動作後再試一次！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editgoodserrnodata') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>找不到該商品，請依正常程序修改商品！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editgoodserrnodel') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>無商品圖片可刪除，請依正常程序修改商品！</strong></h4>
            </div>
        <?php } ?>
        <form action="adminaction.php?action=editgoods" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="goodname">商品名稱</label>
                <input type="text" name="goodname" id="goodname" class="form-control" placeholder="請輸入商品名稱，此為必填項" value="<?php echo $gContent['goodsName']; ?>" />
            </div>
            <div class="form-group">
                <label class="control-label" style="margin: 0;">商品上架時間</label>
                <div class="col-sm-12">
                    <p class="form-control-static"><?php echo date('Y-m-d', strtotime($gContent['goodsPostDate'])); ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label" style="margin: 0;">商品上架者</label>
                <div class="col-sm-12">
                    <p class="form-control-static"><?php echo $gUploader['Upnn']; ?></p>
                </div>
            </div>
            <div class="form-group">
                <label for="goodprice">商品價格</label>
                <input type="text" name="goodprice" id="goodprice" class="form-control" placeholder="請輸入商品價格，此為必填項" value="<?php echo $gContent['goodsPrice']; ?>" />
            </div>
            <div class="form-group">
                <label for="goodquantity">商品在庫量</label>
                <input type="text" name="goodquantity" id="goodquantity" class="form-control" placeholder="請輸入商品在庫量，此為必填項" value="<?php echo $gContent['goodsQty']; ?>" />
            </div>
            <div class="form-group">
                <label for="gooddescript">商品描述</label>
                <textarea id="editor1" name="gooddescript" class="form-control noResize" rows="3" placeholder="請輸入商品描述，此為必填項"><?php echo $gContent['goodsDescript']; ?></textarea>
                <script>CKEDITOR.replace( 'editor1' );</script>
            </div>
            <div class="form-group">
                <label for="goodimage">商品圖片</label>
                <input type="file" id="goodimage" name="goodimage" />
                <p class="help-block">建議解析度為 1000 × 501，若未上傳商品圖則會使用系統預設商品圖</p>
                <?php if($gContent['goodsImgUrl'] != "default.jpg"){ ?>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="delgoodimage" value="true" /> 刪除商品圖片
                        </label>
                    </div>
                <?php } ?>
            </div>
            <div class="form-group">
                <label for="nowimage">目前商品圖片</label><br />
                <?php if (empty($gContent['goodsImgUrl'])) { ?>
                    <p class="form-control-static text-info" id="nowimage"><strong>此商品目前尚無圖片！</strong></p>
                <?php } else { ?>
                    <img src="../images/goods/<?php echo $gContent['goodsImgUrl']; ?>" id="nowimage" width="100%" />
                <?php } ?>
            </div>
            <input type="hidden" name="gid" value="<?php echo $_GET['goodid']; ?>" />
            <input type="hidden" name="refpage" value="<?php echo (empty($_GET['refpage']))? "" : $_GET['refpage']; ?>" />
            <div class="form-group text-center">
                <input type="submit" name="submit" value="送出" class="btn btn-success" />
                <a href="index.php?action=goods_admin&type=goodslist<?php echo (empty($_GET['refpage']))? "" : "&pid=" . $_GET['refpage']; ?>" class="btn btn-info">取消</a>
            </div>
        </form>
        <?php }
        }
    } elseif (!empty($_GET['action']) && $_GET['action'] == 'delgoods') { 
        if(empty($_GET['goodid'])){ ?>
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title">錯誤</h3>
                </div>
                <div class="panel-body text-center">
                    <h2 class="news-warn">無法識別商品，請依正常程序刪除商品。<br /><br />
                        <div class="btn-group" role="group">
                            <a class="btn btn-lg btn-info" href="?action=goods_admin&type=goodslist">返回商品管理</a>
                        </div>
                    </h2>
                </div>
            </div>
        <?php }else{
            $gid = $_GET['goodid'];
            $sql = mysqli_query($connect, "SELECT * FROM `goodslist` WHERE `goodsOrder`=$gid;");
            $numrows = mysqli_num_rows($sql);
            // 若找不到該商品
            if($numrows == 0){ ?>
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">錯誤</h3>
                    </div>
                    <div class="panel-body text-center">
                        <h2 class="news-warn">該識別碼無法找到商品，請依正常程序刪除商品。<br /><br />
                            <div class="btn-group" role="group">
                                <a class="btn btn-lg btn-info" href="?action=goods_admin&type=goodslist">返回商品管理</a>
                            </div>
                        </h2>
                    </div>
                </div>
            <?php }else{
                $row = mysqli_fetch_array($sql);
            }
            ?>
            <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'delgoodserrgid') { ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><strong>無法識別商品，請依正常程序下架商品！</strong></h4>
                </div>
            <?php } ?>
            <form method="POST" class="form-horizontal" action="adminaction.php?action=delgoods" style="margin-top: 1em;">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>您確定要下架這個商品嗎？這個動作無法復原！</strong></h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品識別碼</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $row['goodsOrder']; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品名稱</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $row['goodsName']; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品上架時間</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo date("Y-m-d", strtotime($row['goodsPostDate'])); ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品價格</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $row['goodsPrice']; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品剩餘庫存量</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $row['goodsQty']; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品上架者</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $row['goodsUp']; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品描述</label>
                            <div class="col-sm-10">
                                <div class="form-control-static"><?php echo $row['goodsDescript']; ?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品圖片</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><img src="../images/goods/<?php echo $row['goodsImgUrl']; ?>" width="100%" /></p>
                            </div>
                        </div>
                        <input type="hidden" name="gid" value="<?php echo $gid; ?>" />
                        <input type="hidden" name="refpage" value="<?php echo $_GET['refpage']; ?>" />
                        <div class="col-md-12 text-center">
                            <input type="submit" name="submit" class="btn btn-danger" value="確認下架" />
                            <a href="?action=goods_admin&type=goodslist<?php echo (empty($_GET['refpage']))? "" : "&pid=" . $_GET['refpage']; ?>" class="btn btn-success">返回商品管理</a>
                        </div>
                    </div>
                </div>
            </form>
        <?php } ?>
    <?php } else { ?>
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">錯誤</h3>
            </div>
            <div class="panel-body text-center">
                <h2 class="news-warn">請依正常程序以上方選單選擇管理項目。<br /><br />
                    <div class="btn-group" role="group">
                        <a class="btn btn-lg btn-info" href="?action=index">返回首頁</a>
                    </div>
                </h2>
            </div>
        </div>
    <?php } ?>
</div>