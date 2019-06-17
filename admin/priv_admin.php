<div class="row content-body">
    <ol class="breadcrumb">
        <li><a href="?action=index"><i class="fas fa-map-marker-alt"></i> 首頁</a></li>
        <?php echo ($_GET['action'] == 'privadmin') ? "<li class=\"active\">" : "<li><a href=\"?action=privadmin&type=privlist\">"; ?>權限管理<?php echo ($_GET['action'] != 'privadmin') ? "</a>" : ""; ?></li>
        <?php echo ($_GET['action'] == 'editpriv') ? "<li class=\"active\">編輯權限</li>" : ""; ?>
        <?php echo ($_GET['action'] == 'delpriv') ? "<li class=\"active\">刪除權限</li>" : ""; ?>
    </ol>
</div>
<div class="col-md-12">
    <?php if ($_GET['action'] == 'privadmin') { ?>
        <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'emptyprivnum') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>權限編號不可為空！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'emptyprivname') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>權限名稱不能為空！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'errtypeprivnum') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>權限編號應為數字！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'conflictprivnum') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>權限編號重複！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'addprivsuccess') { ?>
            <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>新增權限成功！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editprivsuccess') { ?>
            <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>編輯權限成功！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'emptypnum') { ?>
            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>無法識別權限編號，請依正常程序刪除權限！</strong></h4>
            </div>
        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'delprivsuccess') { ?>
            <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><strong>刪除權限成功！</strong></h4>
            </div>
        <?php } ?>
        <!-- 分頁 -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" <?php echo ($_GET['type'] == 'privlist') ? " class=\"active\"" : ""; ?>><a href="#privlist" aria-controls="privlist" role="tab" data-toggle="tab">權限一覽</a></li>
            <li role="presentation" <?php echo ($_GET['type'] == 'addpriv') ? " class=\"active\"" : ""; ?>><a href="#addpriv" aria-controls="addpriv" role="tab" data-toggle="tab">新增權限</a></li>
        </ul>

        <!-- 內容 -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade<?php echo ($_GET['type'] == 'privlist') ? " active in" : ""; ?>" id="privlist">
                <?php
                $privSql = mysqli_query($connect, "SELECT * FROM `mempriv` ORDER BY `privNum`;");
                if (mysqli_num_rows($privSql) == 0) { ?>
                    <div class="panel panel-info" style="margin-top: 1em;">
                        <div class="panel-heading">
                            <h3 class="panel-title">錯誤</h3>
                        </div>
                        <div class="panel-body text-center">
                            <h2 class="info-warn">目前沒有新增任何權限！<br /><br />
                            </h2>
                        </div>
                    </div>
                <?php } else { ?>
                    <table class="table table-hover">
                        <thead>
                            <tr class="warning">
                                <th style="width: 10%;">權限編號</th>
                                <th style="width: 70%;">權限名稱</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($privData = mysqli_fetch_array($privSql, MYSQLI_ASSOC)) { ?>
                                <tr>
                                    <td><?php echo $privData['privNum']; ?></td>
                                    <td><?php echo $privData['privName']; ?></td>
                                    <td>
                                        <?php if ($privData['privPreset'] == 1) { ?>
                                            <p>此為內建的權限設定，不可編輯或刪除</p>
                                        <?php } else { ?>
                                            <a href="?action=editpriv&pnum=<?php echo $privData['privNum']; ?>" class="btn btn-info">編輯</a>
                                            <a href="?action=delpriv&pnum=<?php echo $privData['privNum']; ?>" class="btn btn-danger">刪除</a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
            <div role="tabpanel" class="tab-pane fade<?php echo ($_GET['type'] == 'addpriv') ? " active in" : ""; ?>" id="addpriv">
                <form action="adminaction.php?action=addpriv" method="POST">
                    <div class="form-group">
                        <label for="privnum">權限編號</label>
                        <input type="text" name="privnum" class="form-control" id="privnum" placeholder="請輸入權限的編號，注意請輸入數字。">
                    </div>
                    <div class="form-group">
                        <label for="privname">權限名稱</label>
                        <input type="text" name="privname" class="form-control" id="privname" placeholder="請輸入權限的名稱。">
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" name="submit" value="送出" class="btn btn-success" />
                    </div>
                </form>
            </div>
        <?php } elseif ($_GET['action'] == 'editpriv') {
        if (empty($_GET['pnum'])) { ?>
                <div class="panel panel-danger" style="margin-top: 1em;">
                    <div class="panel-heading">
                        <h3 class="panel-title">錯誤</h3>
                    </div>
                    <div class="panel-body text-center">
                        <h2 class="news-warn">無法識別權限編號，請依正常程序編輯權限！<br /><br />
                        </h2>
                    </div>
                </div>
            <?php } else {
            $pnum = $_GET['pnum'];
            $privSql = mysqli_query($connect, "SELECT * FROM `mempriv` WHERE `privNum`='$pnum';");
            if (mysqli_num_rows($privSql) == 0) { ?>
                    <div class="panel panel-danger" style="margin-top: 1em;">
                        <div class="panel-heading">
                            <h3 class="panel-title">錯誤</h3>
                        </div>
                        <div class="panel-body text-center">
                            <h2 class="news-warn">找不到該權限，請依正常程序編輯權限！<br /><br />
                            </h2>
                        </div>
                    </div>
                <?php } else {
                $privData = mysqli_fetch_array($privSql, MYSQLI_ASSOC);
                if ($privData['privPreset'] == 1) { ?>
                        <div class="panel panel-danger" style="margin-top: 1em;">
                            <div class="panel-heading">
                                <h3 class="panel-title">錯誤</h3>
                            </div>
                            <div class="panel-body text-center">
                                <h2 class="news-warn">此為內建權限，不可編輯！<br /><br />
                                </h2>
                            </div>
                        </div>
                    <?php } else { ?>
                        <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'emptyprivnum') { ?>
                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4><strong>權限編號不可為空！</strong></h4>
                            </div>
                        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'emptyprivname') { ?>
                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4><strong>權限名稱不能為空！</strong></h4>
                            </div>
                        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'errtypeprivnum') { ?>
                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4><strong>權限編號應為數字！</strong></h4>
                            </div>
                        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'conflictprivnum') { ?>
                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4><strong>權限編號重複！</strong></h4>
                            </div>
                        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'emptyorigpnum') { ?>
                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4><strong>無法識別權限編號，請依正常程序編輯權限！</strong></h4>
                            </div>
                        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'errtypeorigpnum') { ?>
                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4><strong>權限編號應為數字，請依正常程序編輯權限！</strong></h4>
                            </div>
                        <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'defaultpreset') { ?>
                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4><strong>無法編輯預設權限，請依正常程序編輯權限！</strong></h4>
                            </div>
                        <?php } ?>
                        <form action="adminaction.php?action=editpriv" method="POST">
                            <div class="form-group">
                                <label for="privnum">權限編號</label>
                                <input type="text" name="privnum" class="form-control" id="privnum" value="<?php echo $privData['privNum']; ?>" placeholder="請輸入權限的編號，注意請輸入數字。">
                            </div>
                            <div class="form-group">
                                <label for="privname">權限名稱</label>
                                <input type="text" name="privname" class="form-control" id="privname" value="<?php echo $privData['privName']; ?>" placeholder="請輸入權限的名稱。">
                            </div>
                            <input type="hidden" name="origpnum" value="<?php echo $privData['privNum']; ?>" />
                            <div class="form-group text-center">
                                <input type="submit" name="submit" value="送出" class="btn btn-success" />
                                <a href="?action=privadmin&type=privlist" class="btn btn-info">取消</a>
                            </div>
                        </form>
                    <?php }
            }
        }
    } elseif ($_GET['action'] == 'delpriv') {
        if (empty($_GET['pnum'])) { ?>
                <div class="panel panel-danger" style="margin-top: 1em;">
                    <div class="panel-heading">
                        <h3 class="panel-title">錯誤</h3>
                    </div>
                    <div class="panel-body text-center">
                        <h2 class="news-warn">無法識別權限編號，請依正常程序編輯權限！<br /><br />
                        </h2>
                    </div>
                </div>
            <?php } else {
            $pnum = $_GET['pnum'];
            $privSql = mysqli_query($connect, "SELECT * FROM `mempriv` WHERE `privNum`='$pnum';");
            if (mysqli_num_rows($privSql) == 0) { ?>
                    <div class="panel panel-danger" style="margin-top: 1em;">
                        <div class="panel-heading">
                            <h3 class="panel-title">錯誤</h3>
                        </div>
                        <div class="panel-body text-center">
                            <h2 class="news-warn">找不到該權限，請依正常程序編輯權限！<br /><br />
                            </h2>
                        </div>
                    </div>
                <?php } else {
                $privData = mysqli_fetch_array($privSql, MYSQLI_ASSOC);
                if ($privData['privPreset'] == 1) { ?>
                        <div class="panel panel-danger" style="margin-top: 1em;">
                            <div class="panel-heading">
                                <h3 class="panel-title">錯誤</h3>
                            </div>
                            <div class="panel-body text-center">
                                <h2 class="news-warn">此為內建權限，不可編輯！<br /><br />
                                </h2>
                            </div>
                        </div>
                    <?php } else {
                    if ($privData['privPreset'] == 1) { ?>
                            <div class="panel panel-danger" style="margin-top: 1em;">
                                <div class="panel-heading">
                                    <h3 class="panel-title">錯誤</h3>
                                </div>
                                <div class="panel-body text-center">
                                    <h2 class="news-warn">此為內建權限，不可編輯！<br /><br />
                                    </h2>
                                </div>
                            </div>
                        <?php } else { ?>
                            <form class="form-horizontal" action="adminaction.php?action=delpriv" method="POST">
                                <div class="panel panel-danger">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><strong>確定要刪除這個權限嗎？</strong></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="privnum" class="col-sm-2 control-label">權限編號</label>
                                            <div class="col-sm-10">
                                                <p class="form-control-static"><?php echo $privData['privNum']; ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="privnum" class="col-sm-2 control-label">權限名稱</label>
                                            <div class="col-sm-10">
                                                <p class="form-control-static"><?php echo $privData['privName']; ?></p>
                                            </div>
                                        </div>
                                        <input type="hidden" name="pnum" value="<?php echo $privData['privNum']; ?>" />
                                        <div class="form-group text-center">
                                            <input type="submit" name="submit" value="送出" class="btn btn-success" />
                                            <a href="?action=privadmin&type=privlist" class="btn btn-info">取消</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <?php }
                }
            }
        }
    } ?>
    </div>
</div>