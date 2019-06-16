<?php
$configSql = mysqli_query($connect, "SELECT * FROM `systemsetting`;");
// 先把資料處理為陣列
$i = 0;
$configs = array();
while ($configs[$i] = mysqli_fetch_array($configSql, MYSQLI_ASSOC)) {
    $i += 1;
}
// $config[getItemFromArray(<str DBconfigName>, $configs)]['settingValue'];
?>
<div class="row content-body">
    <ol class="breadcrumb">
        <li><a href="?action=index"><i class="fas fa-map-marker-alt"></i> 首頁</a></li>
        <?php echo ($_GET['action'] == 'sysconfig') ? "<li class=\"active\">" : "<li><a href=\"?action=sysconfig\">"; ?>主要系統設定<?php echo ($_GET['action'] == 'sysconfig') ? "" : "</a>" ?></li>
    </ol>
</div>
<div class="col-md-12">
    <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'emptypostvalue') { ?>
        <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><strong>所有欄位皆為必填欄位，請檢查是否有漏填之欄位後再行送出！</strong></h4>
        </div>
    <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'updatesuccess') { ?>
        <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><strong>更新系統設定成功！</strong></h4>
        </div>
    <?php } ?>
    <form class="form-horizontal" method="POST" action="adminaction.php?action=updatesysconfig">
        <div class="form-group">
            <label for="numNews" class="col-sm-2 control-label">最新消息單頁顯示行數</label>
            <div class="col-sm-10">
                <input type="number" name="numNews" class="form-control" id="numNews" value="<?php echo $configs[getItemFromArray("newsNum", $configs)]['settingValue']; ?>" placeholder="最新消息單頁顯示行數" />
            </div>
        </div>
        <div class="form-group">
            <label for="numGoods" class="col-sm-2 control-label">週邊商品單頁顯示行數</label>
            <div class="col-sm-10">
                <input type="number" name="numGoods" class="form-control" id="numGoods" value="<?php echo $configs[getItemFromArray("goodsNum", $configs)]['settingValue']; ?>" placeholder="週邊商品單頁顯示行數" />
            </div>
        </div>
        <div class="form-group">
            <label for="numPosts" class="col-sm-2 control-label">討論板單頁顯示項目數</label>
            <div class="col-sm-10">
                <input type="number" name="numPosts" class="form-control" id="numPosts" value="<?php echo $configs[getItemFromArray("postsNum", $configs)]['settingValue']; ?>" placeholder="討論板單頁顯示項目數" />
            </div>
        </div>
        <div class="form-group">
            <label for="numArticles" class="col-sm-2 control-label">文章頁面單頁顯示個數</label>
            <div class="col-sm-10">
                <input type="number" name="numArticles" class="form-control" id="numArticles" value="<?php echo $configs[getItemFromArray("articlesNum", $configs)]['settingValue']; ?>" placeholder="文章頁面單頁顯示個數" />
            </div>
        </div>
        <div class="form-group">
            <label for="adminPriv" class="col-sm-2 control-label">討論版管理權限授權</label>
            <div class="col-sm-10">
                <select id="adminPriv" name="adminPriv" class="form-control">
                    <option>-- 請選擇 --</option>
                    <option value="99" <?php echo ($configs[getItemFromArray("adminPriv", $configs)]['settingValue'] == 99) ? "selected" : ""; ?>>超級管理員</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="backendPriv" class="col-sm-2 control-label">後台登入權限授權</label>
            <div class="col-sm-10">
                <select id="backendPriv" name="adminPriv" class="form-control">
                    <option>-- 請選擇 --</option>
                    <option value="99" <?php echo ($configs[getItemFromArray("backendPriv", $configs)]['settingValue'] == 99) ? "selected" : ""; ?>>超級管理員</option>
                </select>
            </div>
        </div>
        <div class="form-group text-center">
            <input type="submit" name="submit" value="送出" class="btn btn-success" />
        </div>
    </form>
</div>