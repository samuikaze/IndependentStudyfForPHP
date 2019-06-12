<div class="row content-body">
    <ol class="breadcrumb">
        <li><a href="?action=index"><i class="fas fa-map-marker-alt"></i> 首頁</a></li>
        <?php echo ($_GET['action'] == 'dbadmin') ? "<li class=\"active\">" : "<li><a href=\"?action=dbadmin\">"; ?>資料庫管理<?php echo ($_GET['action'] == 'dbadmin') ? "" : "</a>" ?></li>
    </ol>
</div>
<div class="col-md-12">
    <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'optimizesuccess') { ?>
        <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><strong>最佳化資料表成功！</strong></h4>
        </div>
    <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'repairsuccess') { ?>
        <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><strong>修復資料表成功！</strong></h4>
        </div>
    <?php } ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">修復或最佳化網站資料庫</h3>
        </div>
        <div class="panel-body">
            網站使用久了會有資料分散問題，且若遇到斷電可能會有資料表損壞的問題，此時可以藉由下列選項最佳化或修復您的資料庫資料表。<br />
            若修復後資料庫能無法使用請聯絡本公司協助您排除問題。
            <div class="container-fluid text-center">
                <form method="POST" action="adminaction.php?action=optimizedb" style="display: inline-block; width: auto!important;">
                    <input type="submit" name="submit" value="最佳化資料表" class="btn btn-success" />
                </form>
                <form method="POST" action="adminaction.php?action=repairdb" style="display: inline-block; width: auto!important;">
                    <input type="submit" name="submit" value="修復資料表" class="btn btn-success" />
                </form>
            </div>
        </div>
    </div>
</div>