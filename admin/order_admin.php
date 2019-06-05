<div class="row content-body">
    <ol class="breadcrumb">
        <li><a href="?action=index"><i class="fas fa-map-marker-alt"></i> 首頁</a></li>
        <?php echo ($_GET['action'] == 'order_admin') ? "<li class=\"active\">" : "<li><a href=\"?action=order_admin&type=vieworderlist\">"; ?>訂單管理<?php echo ($_GET['action'] == 'order_admin') ? "" : "</a>" ?></li>
        <?php echo (!empty($_GET['action']) && $_GET['action'] == 'vieworderdetail') ? "<li class=\"active\">檢視訂單詳細資料</li>" : ""; ?>
    </ol>
</div>
<div class="col-md-12">
    <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'nooid') { ?>
        <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><strong>無法識別訂單編號，請依正常程序操作！</strong></h4>
        </div>
    <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'sendupdatesuccess') { ?>
        <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><strong>訂單狀態更新成功！</strong></h4>
        </div>
    <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'completeordersuccess') { ?>
        <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><strong>結單成功！</strong></h4>
        </div>
    <?php }
if (!empty($_GET['action']) && $_GET['action'] == 'order_admin') { ?>
        <!-- 標籤 -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" <?php echo (!empty($_GET['type']) && $_GET['type'] == 'vieworderlist') ? "  class=\"active\"" : ""; ?>><a href="#orderlist" aria-controls="orderlist" role="tab" data-toggle="tab">訂單一覽</a></li>
            <li role="presentation" <?php echo (!empty($_GET['type']) && $_GET['type'] == 'viewrefundlist') ? "  class=\"active\"" : ""; ?>><a href="#refundlist" aria-controls="refundlist" role="tab" data-toggle="tab">退訂一覽</a></li>
        </ul>

        <!-- 內容 -->
        <div class="tab-content">
            <!-- 訂單一覽 -->
            <div role="tabpanel" class="tab-pane fade<?php echo (!empty($_GET['type']) && $_GET['type'] == 'vieworderlist') ? " active in" : ""; ?>" id="orderlist">
                <?php
                // 取資料
                $orderSql = mysqli_query($connect, "SELECT * FROM `orders` WHERE `orderStatus`!='訂單已取消' AND `orderStatus`!='已結單' ORDER BY `orderID` ASC;");
                $orderNumRows = mysqli_num_rows($orderSql);
                // 若沒有訂單
                if ($orderNumRows == 0) { ?>
                    <div class="panel panel-warning" style="margin-top: 1em;">
                        <div class="panel-heading">
                            <h3 class="panel-title">資訊</h3>
                        </div>
                        <div class="panel-body text-center">
                            <h2 class="news-warn">目前沒有任何訂單！<br /><br /></h2>
                        </div>
                    </div>
                <?php } else { ?>
                    <table class="table table-hover">
                        <thead>
                            <tr class="warning">
                                <th>訂單編號</th>
                                <th>應付金額</th>
                                <th>訂單狀態</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($orderDatas = mysqli_fetch_array($orderSql, MYSQLI_ASSOC)) { ?>
                                <!-- 一筆訂單 -->
                                <tr>
                                    <td><?php echo $orderDatas['orderID']; ?></td>
                                    <td><?php echo $orderDatas['orderPrice']; ?></td>
                                    <td <?php echo ($orderDatas['orderStatus'] == '已申請取消訂單') ? "style=\"color: red;\"" : ""; ?>><?php echo ($orderDatas['orderStatus'] == '已申請取消訂單') ? "<strong>" : ""; ?><?php echo $orderDatas['orderStatus']; ?><?php echo ($orderDatas['orderStatus'] == '已申請取消訂單') ? "</strong>" : ""; ?></td>
                                    <td><a href="?action=vieworderdetail&oid=<?php echo $orderDatas['orderID']; ?>" class="btn btn-info">詳細</a></td>
                                </tr>
                                <!-- /一筆訂單 -->
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
            <!-- 退訂一覽 -->
            <div role="tabpanel" class="tab-pane fade<?php echo (!empty($_GET['type']) && $_GET['type'] == 'viewrefundlist') ? " active in" : ""; ?>" id="refundlist">
                <?php
                // 取資料
                $removeOrderSql = mysqli_query($connect, "SELECT * FROM `orders` WHERE `orderStatus`='已申請取消訂單' OR `orderStatus`='訂單已取消' ORDER BY `orderID` ASC;");
                $removeOrderNumRows = mysqli_num_rows($removeOrderSql);
                // 若沒有訂單
                if ($removeOrderNumRows == 0) { ?>
                    <div class="panel panel-warning" style="margin-top: 1em;">
                        <div class="panel-heading">
                            <h3 class="panel-title">資訊</h3>
                        </div>
                        <div class="panel-body text-center">
                            <h2 class="news-warn">目前沒有任何申請取消的訂單！<br /><br /></h2>
                        </div>
                    </div>
                <?php } else { ?>
                    <table class="table table-hover">
                        <thead>
                            <tr class="warning">
                                <th>訂單編號</th>
                                <th>應付金額</th>
                                <th>訂單狀態</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($removeOrderDatas = mysqli_fetch_array($removeOrderSql, MYSQLI_ASSOC)) { ?>
                                <!-- 一筆退訂申請 -->
                                <tr>
                                    <td><?php echo $removeOrderDatas['orderID']; ?></td>
                                    <td><?php echo $removeOrderDatas['orderPrice']; ?></td>
                                    <td><?php echo $removeOrderDatas['orderStatus']; ?></td>
                                    <td><a href="?action=vieworderdetail&oid=<?php echo $removeOrderDatas['orderID']; ?>" class="btn btn-info">詳細</a></td>
                                </tr>
                                <!-- /一筆退訂申請 -->
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
            <!-- /退訂一覽 -->
        </div>
    <?php } elseif (!empty($_GET['action']) && $_GET['action'] == 'vieworderdetail') {
    // 檢視訂單詳細內容
    if (empty($_GET['oid'])) { ?>
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title">錯誤</h3>
                </div>
                <div class="panel-body text-center">
                    <h2 class="news-warn">找不到此訂單，請依正常程序操作！<br /><br />
                        <div class="btn-group" role="group">
                            <a class="btn btn-lg btn-info" href="?action=order_admin&type=vieworderlist">返回訂單一覽</a>
                        </div>
                    </h2>
                </div>
            </div>
        <?php } else {
        $oid = $_GET['oid'];
        // 開始取資料
        $orderDataSql = mysqli_query($connect, "SELECT * FROM `orders` WHERE `orderID`=$oid;");
        // 如果沒有資料，意即沒有該筆訂單
        if (mysqli_num_rows($orderDataSql) < 1) { ?>
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">錯誤</h3>
                    </div>
                    <div class="panel-body text-center">
                        <h2 class="news-warn">查無該筆訂單，請依正常程序操作！<br /><br />
                            <div class="btn-group" role="group">
                                <a class="btn btn-lg btn-info" href="?action=order_admin&type=vieworderlist">返回訂單一覽</a>
                            </div>
                        </h2>
                    </div>
                </div>
            <?php } else {
            if (!empty($_GET['msg']) && $_GET['msg'] == 'nogoodsqty') { ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><strong>沒有商品數量資料，請依正常程序操作！</strong></h4>
                </div>
            <?php }elseif(!empty($_GET['msg']) && $_GET['msg'] == 'noreviewnotify'){ ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><strong>審核理由欄位為空，請確實填寫審核理由！</strong></h4>
                </div>
            <?php }elseif(!empty($_GET['msg']) && $_GET['msg'] == 'noreviewresult'){ ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><strong>沒有審核選項被選擇，請依正常程序操作！</strong></h4>
                </div>
            <?php }
            // 開始顯示資料
            $orderDetailData = mysqli_fetch_array($orderDataSql, MYSQLI_ASSOC);
            // 先處理訂單裡商品的資料
            // 先拆品項
            $goods = explode(",", $orderDetailData['orderContent']);
            // 再把商品ID($goodsinfo[$i][0])和價格拆開($goodsinfo[$i][1])
            $goodsinfo = array();
            foreach ($goods as $i => $val) {
                $goodsinfo[$i] = explode(":", $goods[$i]);
                // 處理 SQL 條件語法
                if ($i == 0) {
                    $condition = "`goodsOrder`=" . $goodsinfo[$i][0];
                    $gOrder = "ORDER BY CASE `goodsOrder` WHEN " . $goodsinfo[$i][0] . " THEN " . ($i + 1);
                } else {
                    $condition .= " OR `goodsOrder`=" . $goodsinfo[$i][0];
                    $gOrder .= " WHEN " . $goodsinfo[$i][0] . " THEN " . ($i + 1);
                }
            }
            $gOrder .= " END";
            $goodsdata = mysqli_query($connect, "SELECT * FROM `goodslist` WHERE $condition $gOrder;");
            // 取得訂貨人資料
            $username = $orderDetailData['orderMember'];
            $orderMem = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM `member` WHERE `userName`='$username'"), MYSQLI_ASSOC);
            // 如果有申請取消訂單
            if ($orderDetailData['orderStatus'] == '已申請取消訂單') {
                $removeoid = $orderDetailData['orderID'];
                $removeOrderApply = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM `removeorder` WHERE `targetOrder`=$removeoid"), MYSQLI_ASSOC); ?>
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title">申請取消訂單資料</h3>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" action="adminaction.php?action=applyremoveorder" method="POST">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">申請編號</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static"><?php echo $removeOrderApply['removeID']; ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">申請理由</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static"><?php echo $removeOrderApply['removeReason']; ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">申請日期</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static"><?php echo $removeoid; ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">審核結果</label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <input type="radio" name="reviewResult" id="true" value="true"> 通過申請
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="reviewResult" id="false" value="false" checked> 駁回申請
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="reviewNotify">審核通知</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control noResize" name="reviewNotify" id="reviewNotify" rows="3" placeholder="請輸入審核理由，內容會顯示於用戶側的通知內，此欄位不可留空"></textarea>
                                    </div>
                                </div>
                                <input type="hidden" name="oid" value="<?php echo $removeOrderApply['targetOrder']; ?>" />
                                <div class="col-sm-12 text-center" style="margin-bottom: 1em;">
                                    <input class="btn btn-danger" type="submit" name="submit" value="送出" />
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } ?>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">訂單資料</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td>訂單編號</td>
                                    <td><?php echo $orderDetailData['orderID']; ?></td>
                                </tr>
                                <tr>
                                    <td>訂貨人</td>
                                    <td><?php echo $orderMem['userNickname']; ?></td>
                                </tr>
                                <tr>
                                    <td>聯絡電話</td>
                                    <td><?php echo $orderDetailData['orderPhone']; ?></td>
                                </tr>
                                <tr>
                                    <td>付款方式</td>
                                    <td><?php echo $orderDetailData['orderCasher']; ?></td>
                                </tr>
                                <tr>
                                    <td>取貨方式</td>
                                    <td><?php echo $orderDetailData['orderPattern']; ?></td>
                                </tr>
                                <tr>
                                    <td>送貨地點</td>
                                    <td><?php echo $orderDetailData['orderAddress']; ?></td>
                                </tr>
                                <tr>
                                    <td>應付金額</td>
                                    <td><?php echo $orderDetailData['orderPrice']; ?> 元</td>
                                </tr>
                                <tr>
                                    <td>下訂日期</td>
                                    <td><?php echo $orderDetailData['orderDate']; ?></td>
                                </tr>
                                <tr>
                                    <td>訂單狀態</td>
                                    <td <?php echo ($orderDetailData['orderStatus'] == '已申請取消訂單') ? "text-danger" : ""; ?>><?php echo ($orderDetailData['orderStatus'] == '已申請取消訂單') ? "<strong>" : ""; ?><?php echo $orderDetailData['orderStatus']; ?><?php echo ($orderDetailData['orderStatus'] == '已申請取消訂單') ? "</strong>" : ""; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr />
                <h3>訂單內容</h3>
                <table class="table table-hover">
                    <thead>
                        <tr class="info">
                            <td>商品名稱</td>
                            <td>下訂數量</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0;
                        while ($goodsDetail = mysqli_fetch_array($goodsdata, MYSQLI_ASSOC)) { ?>
                            <tr>
                                <td><?php echo $goodsDetail['goodsName']; ?></td>
                                <td><?php echo $goodsinfo[$i][1]; ?></td>
                            </tr>
                            <?php $i += 1;
                        } ?>
                    </tbody>
                </table>
                <div class="form-group text-center">
                    <?php if ($orderDetailData['orderStatus'] == '等待付款') { ?>
                        <a class="btn btn-success" disabled="disabled" title="待買家付款後才可行出貨">等待付款</a>
                    <?php } elseif ($orderDetailData['orderStatus'] != '已出貨' && $orderDetailData['orderStatus'] != '已申請取消訂單') {
                    if ($orderDetailData['orderStatus'] == '已取貨') {
                        $submitTarget = "adminaction.php?action=completeorder&oid=" . $orderDetailData['orderID'];
                        $btnName = "結單";
                    } else {
                        $submitTarget = "adminaction.php?action=notifysend&oid=" . $orderDetailData['orderID'];
                        $btnName = "通知已出貨";
                    } ?>
                        <form method="POST" action="<?php echo $submitTarget; ?>">
                            <input type="hidden" name="goodsqty" value="<?php echo $orderDetailData['orderContent']; ?>:" />
                            <input type="submit" class="btn btn-success" value="<?php echo $btnName; ?>" />
                        <?php } ?>
                        <a href="?action=order_admin&type=vieworderlist" class="btn btn-info">返回訂單一覽</a>
                        <?php if ($orderDetailData['orderStatus'] != '已出貨' && $orderDetailData['orderStatus'] != '已申請取消訂單') { ?>
                        </form>
                    <?php } ?>
                </div>
            <?php }
    }
    ?>

    <?php } ?>
</div>