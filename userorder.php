<?php
require_once "sessionCheck.php";
$self = basename(__FILE__);
if (!empty($_SERVER['QUERY_STRING'])) {
    $self .= "?" . $_SERVER['QUERY_STRING'];
    $self = str_replace("&", "+", $self);
} else {
    header("Location: ?action=viewcart");
    exit;
}
//echo "<script>console.log(\"" . $_SESSION['auth'] . "\");</script>";
// 若沒登入就踢出去
if (empty($_SESSION['auth']) || $_SESSION['auth'] != True) {
    mysqli_close($connect);
    header("Location: member.php?action=login&loginErrType=5&refer=" . urlencode($self));
    exit;
}
if (!empty($_GET['action']) && $_GET['action'] == 'viewcart') {
    $ptitle = "檢視購物車";
} elseif (!empty($_GET['action']) && $_GET['action'] == 'order') {
    if (empty($_GET['step'])) {
        mysqli_close($connect);
        header("Location: userorder.php?action=viewcart&msg=nostepid");
        exit;
    } elseif (empty($_SESSION['cart'])) {
        mysqli_close($connect);
        header("Location: userorder.php?action=viewcart&msg=nocartdata");
        exit;
    }
    $ptitle = "結帳程序";
}
// 開始判定購物車的一些輸入錯誤
if ($_GET['action'] == 'order') {
    if ($_GET['step'] == 2 && (empty($_POST['fPattern']) && empty($_SESSION['cart']['fpattern']))) {
        mysqli_close($connect);
        header("Location: userorder.php?action=order&step=1&msg=nocheckoutdata");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <title><?php echo $ptitle; ?> | 洛嬉遊戲 L.S. Games</title>
    <?php include_once "templates/metas.php"; ?>
    <!--script src="js/simpleCart.min.js"></script-->
    <?php if ($_GET['action'] == 'viewgoodsdetail') { ?>
        <script src="js/simple-lightbox.min.js"></script>
        <link href="css/simplelightbox.min.css" rel="stylesheet" type="text/css" />
        <script>
            $(function() {
                var $gallery = $('.goods-detail-img a').simpleLightbox();
            });
        </script>
    <?php } ?>
</head>

<body onload="loadProgress()">
    <!-- 要加入載入動畫這邊請加上 onload="loadProgress()" -->
    <?php include_once "templates/loadscr.php"; ?>
    <div class="pageWrap">
        <?php
        if (isset($_COOKIE['sid']) == False) {
            include_once "templates/loginform.php";
        }
        include_once "templates/header.php";
        ?>
        <div class="courses">
            <div class="container">
                <!-- 麵包屑 -->
                <ol class="breadcrumb">
                    <li><a href="index.html"><i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;洛嬉遊戲</a></li>
                    <li><a href="goods.php?action=viewallgoods">周邊產品</a></li>
                    <?php echo ($_GET['action'] == 'viewcart') ? "<li class=\"thisPosition\">檢視購物車內容</li>" : "<li><a href=\"?action=viewcart\">檢視購物車內容</a></li>"; ?>
                    <?php echo ($_GET['action'] == 'order') ? "<li class=\"thisPosition\">結帳</li>" : ""; ?>
                    <?php include "templates/loginbutton.php"; ?>
                </ol>
                <div class="container">
                    <div class="check">
                        <?php if (!empty($_GET['action']) && $_GET['action'] == 'viewcart') { ?>
                            <h1>購物車項目 (<?php echo (empty($_SESSION['cart'])) ? "0" : sizeof($_SESSION['cart'][0]); ?>)</h1>
                        <?php } elseif (!empty($_GET['action']) && $_GET['action'] == 'order') { ?>
                            <h1 class="orderBreadcrumb"><?php echo (!empty($_GET['step']) && $_GET['step'] == '1') ? "<span>" : ""; ?><i class="fas fa-check-square"></i> 選擇付款及收貨方式<?php echo (!empty($_GET['step']) && $_GET['step'] == '1') ? "</span>" : ""; ?>&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo (!empty($_GET['step']) && $_GET['step'] == '2') ? "<span>" : ""; ?><i class="fas fa-scroll"></i> 輸入相關資料<?php echo (!empty($_GET['step']) && $_GET['step'] == '2') ? "</span>" : ""; ?>&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo (!empty($_GET['step']) && $_GET['step'] == '3') ? "<span>" : ""; ?><i class="fas fa-check-double"></i> 確認資料<?php echo (!empty($_GET['step']) && $_GET['step'] == '3') ? "</span>" : ""; ?>&nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo (!empty($_GET['step']) && $_GET['step'] == '4') ? "<span>" : ""; ?><i class="fas fa-clipboard-check"></i> 完成訂單<?php echo (!empty($_GET['step']) && $_GET['step'] == '4') ? "</span>" : ""; ?></h1>
                            <hr class="divideBC" />
                        <?php } ?>
                        <?php if (!empty($_GET['action']) && $_GET['action'] == 'viewcart') {
                            if (!empty($_GET['msg']) && $_GET['msg'] == 'nostepid') { ?>
                                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4><strong>請依正常程序訂購商品！</strong></h4>
                                </div>
                            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'nocartdata') { ?>
                                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4><strong>您的購物車為空，請依正常程序訂購商品！</strong></h4>
                                </div>
                            <?php } ?>
                            <div class="col-md-9 cart-items">
                                <?php
                                // 若購物車項目不為空
                                if (!empty($_SESSION['cart'])) {
                                    $inCar = $_SESSION['cart'][0];
                                    $qty = $_SESSION['cart'][0];
                                    // 處理取資料 SQL
                                    foreach ($inCar as $i => $inCarVal) {
                                        if ($i == 0) {
                                            $gdSql = "`goodsOrder`=$inCarVal";
                                            $order = "ORDER BY CASE `goodsOrder` WHEN $inCarVal THEN " . ($i + 1);
                                        } else {
                                            $gdSql .= " OR `goodsOrder`=$inCarVal";
                                            $order .= " WHEN $inCarVal THEN " . ($i + 1);
                                        }
                                    }
                                    $order .= " END";
                                    // 取資料顯示
                                    $perfSql = mysqli_query($connect, "SELECT * FROM `goodslist` WHERE $gdSql $order;");
                                    //echo "SELECT * FROM `goodslist` WHERE $gdSql $order;";
                                    $j = 0;
                                    //echo "SELECT * FROM `goodslist` WHERE $gdSql;";
                                    while ($goodsData = mysqli_fetch_array($perfSql, MYSQLI_ASSOC)) {
                                        ?>
                                        <!-- 一個購物車項目 -->
                                        <div class="cart-header">
                                            <div class="close1"></div>
                                            <div class="cart-sec simpleCart_shelfItem">
                                                <div class="cart-item cyc">
                                                    <img src="images/goods/<?php echo $goodsData['goodsImgUrl']; ?>" class="img-responsive" alt="" />
                                                </div>
                                                <div class="cart-item-info">
                                                    <h3><a href="goods.php?action=viewgoodsdetail&goodid=<?php echo $goodsData['goodsOrder']; ?>" class="cartItemTitle"><?php echo $goodsData['goodsName']; ?></a><span><?php echo $goodsData['goodsDescript']; ?></span></h3>
                                                    <div class="alert alert-warning" role="alert">
                                                        <span class="qty">數量：<?php echo $_SESSION['cart'][1][$j]; ?>&nbsp;・&nbsp;單價：NT$ <?php echo $goodsData['goodsPrice']; ?></span>
                                                        <span class="tot" style="font-size: 1.2em;">小計：NT$ <?php echo $_SESSION['cart'][1][$j] * $goodsData['goodsPrice']; ?></span>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <hr class="cartitem-margin" />
                                        <!-- /一個購物車項目 -->
                                        <?php $j += 1;
                                    }
                                    // 若購物車為空
                                } else { ?>
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">訊息</h3>
                                        </div>
                                        <div class="panel-body">
                                            <h2 class="info-warn">您的購物車為空。<br /><br />
                                                <a href="goods.php" class="btn btn-lg btn-success">立即前往選購</a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php //下訂單
                    } elseif (!empty($_GET['action']) && $_GET['action'] == 'order') {
                        if (!empty($_GET['msg']) && $_GET['msg'] == 'nocheckoutdata') { ?>
                                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4><strong>請確實選擇一種結帳方式！</strong></h4>
                                </div>
                            <?php } ?>
                            <div class="col-md-9 cart-total">
                                <?php /*第一步*/ if ($_GET['step'] == '1') {
                                    $dataSql = mysqli_query($connect, "SELECT * FROM `checkout` WHERE `type`='freight';");
                                    // 先把資料處理起來
                                    $i = 0;
                                    while ($data[$i] = mysqli_fetch_array($dataSql, MYSQLI_ASSOC)) {
                                        $i += 1;
                                    } ?>
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Step 1 - 請選擇您的結帳方式</h3>
                                        </div>
                                        <div class="panel-body">
                                            <form action="userorder.php?action=order&step=2" method="POST">
                                                <div class="form-group">
                                                    <label for="fPattern">結帳方式</label>
                                                    <select class="form-control" name="fPattern" id="fPattern">
                                                        <option value="">請選擇結帳方式</option>
                                                        <?php foreach ($data as $i => $val) {
                                                            if (empty($val)) {
                                                                $i += 1;
                                                                continue;
                                                            } ?>
                                                            <option value="<?php echo $data[$i]['pattern']; ?>"><?php echo $data[$i]['pattern']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group text-center">
                                                    <a href="goods.php?action=viewallgoods" class="btn btn-lg btn-info">繼續選購</a>
                                                    <input type="submit" name="submit" class="btn btn-success btn-lg" value="確認結帳" />
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php /* 第二步 */ } elseif ($_GET['step'] == '2') {
                                // 判斷是否處於結帳狀態
                                $_SESSION['cart']['checkoutstatus'] = "notcomplete";
                                if (!empty($_POST['fPattern'])) {
                                    $_SESSION['cart']['fpattern'] = $_POST['fPattern'];
                                }
                                $freight = mysqli_fetch_array(mysqli_query($connect, "SELECT `fee` FROM `checkout` WHERE `type`='freight' AND `pattern`='" . $_SESSION['cart']['fpattern'] . "';"), MYSQLI_ASSOC);
                                $_SESSION['cart']['freight'] = $freight['fee'];
                                if(!empty($_SESSION['cart']['clientname'])){
                                    $clientname = "value=\"" . $_SESSION['cart']['clientname'] . "\" ";
                                }else{
                                    if(!empty($suserdata['userRealName'])){
                                        $clientname = "value=\"" . $suserdata['userRealName'] . "\" ";
                                    }else{
                                        $clientname = "";
                                    }
                                }
                                if(!empty($_SESSION['cart']['clientphone'])){
                                    $clienphone = "value=\"" . $_SESSION['cart']['clientphone'] . "\" ";
                                }else{
                                    if(!empty($suserdata['userPhone'])){
                                        $clienphone = "value=\"" . $suserdata['userPhone'] . "\" ";
                                    }else{
                                        $clienphone = "";
                                    }
                                }
                                if(!empty($_SESSION['cart']['clientaddress'])){
                                    $clienaddress = "value=\"" . $_SESSION['cart']['clientaddress'] . "\" ";
                                }else{
                                    if($_SESSION['cart']['fpattern'] != "貨送到府"){
                                        if(!empty($suserdata['userPhone'])){
                                            $clienaddress = "value=\"" . $suserdata['userPhone'] . "\" ";
                                        }else{
                                            $clienaddress = "";
                                        }
                                    }else{
                                        $clienaddress = "";
                                    }
                                }
                                ?>
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Step 2 - 輸入下訂資料</h3>
                                        </div>
                                        <div class="panel-body">
                                            <form action="userorder.php?action=order&step=3" method="POST">
                                                <div class="form-group">
                                                    <label for="clientname">姓名</label>
                                                    <input type="text" name="clientname" class="form-control" id="clientname" placeholder="請輸入您的姓名" <?php echo $clientname; ?>required />
                                                </div>
                                                <div class="form-group">
                                                    <label for="clientphone">連絡電話</label>
                                                    <input type="text" name="clientphone" class="form-control" id="clientphone" placeholder="請輸入您的電話" <?php echo $clienphone; ?>required />
                                                </div>
                                                <div class="form-group">
                                                    <label for="clientaddress"><?php echo ($_SESSION['cart']['fpattern'] == "貨送到府") ? "收貨地址" : "最近的郵局或超商名稱"; ?></label>
                                                    <input type="text" name="clientaddress" class="form-control" id="clientaddress" placeholder="請輸入<?php echo ($_SESSION['cart']['fpattern'] == "貨送到府") ? "您的收貨地址" : "最近的郵局或超商名稱"; ?>" <?php echo $clienaddress; ?>required />
                                                </div>
                                                <div class="form-group">
                                                    <label for="fPattern">結帳方式</label>
                                                    <div class="col-sm-12">
                                                        <p class="form-control-static"><?php echo $_SESSION['cart']['fpattern']; ?></p>
                                                    </div>
                                                </div>
                                                <div class="form-group text-center">
                                                    <input type="submit" name="submit" class="btn btn-success btn-lg" value="下一步" />
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php /* 第三步 */ } elseif ($_GET['step'] == '3') {
                                $_SESSION['cart']['clientname'] = $_POST['clientname'];
                                $_SESSION['cart']['clientphone'] = $_POST['clientphone'];
                                $_SESSION['cart']['clientaddress'] = $_POST['clientaddress'];
                                // 處理商品資訊
                                $inCar = $_SESSION['cart'][0];
                                $qty = $_SESSION['cart'][0];
                                // 處理取資料 SQL
                                foreach ($inCar as $i => $inCarVal) {
                                    if ($i == 0) {
                                        $gdSql = "`goodsOrder`=$inCarVal";
                                        $order = "ORDER BY CASE `goodsOrder` WHEN $inCarVal THEN " . ($i + 1);
                                    } else {
                                        $gdSql .= " OR `goodsOrder`=$inCarVal";
                                        $order .= " WHEN $inCarVal THEN " . ($i + 1);
                                    }
                                }
                                $order .= " END";
                                // 取資料顯示
                                $perfSql = mysqli_query($connect, "SELECT * FROM `goodslist` WHERE $gdSql $order;");
                                ?>
                                    <form action="userorder.php?action=order&step=4" method="POST">
                                        <div class="panel panel-info">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">確認您的購物清單</h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="alert alert-warning" role="alert">購物清單一經確認結帳方式後不可修改</div>
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr class="warning">
                                                            <th>商品名稱</th>
                                                            <th>選購數量</th>
                                                            <th>單價</th>
                                                            <th>小計</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php  
                                                        $j = 0;
                                                        while ($goodsData = mysqli_fetch_array($perfSql, MYSQLI_ASSOC)) { ?>
                                                        <!-- 一個商品 -->
                                                        <tr>
                                                            <td><?php echo $goodsData['goodsName']; ?></td>
                                                            <td><?php echo $_SESSION['cart'][1][$j]; ?></td>
                                                            <td><?php echo $goodsData['goodsPrice']; ?></td>
                                                            <td><?php echo $_SESSION['cart'][1][$j] * $goodsData['goodsPrice']; ?> 元</td>
                                                        </tr>
                                                        <!-- /一個商品 -->
                                                        <?php
                                                        $j += 1;
                                                        } ?>
                                                        <tr>
                                                            <td colspan="2"></td>
                                                            <td class="checkout-lasttext">運費</td>
                                                            <td><?php echo $_SESSION['cart']['freight']; ?> 元</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"></td>
                                                            <td class="checkout-lasttext">總計</td>
                                                            <td><?php echo $_SESSION['cartTotal'] + $_SESSION['cart']['freight']; ?> 元</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="panel panel-info">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">確認您的結帳個人資料</h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="alert alert-warning" role="alert">以下資料將會用於出貨與取貨的基本資料，若內容有問題此次訂單將會被取消，請特別留意。</div>
                                                <div class="form-group">
                                                    <label for="fPattern">姓名</label>
                                                    <div class="col-sm-12">
                                                        <p class="form-control-static"><?php echo $_POST['clientname']; ?></p>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="fPattern">電話</label>
                                                    <div class="col-sm-12">
                                                        <p class="form-control-static"><?php echo $_POST['clientphone']; ?></p>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="fPattern"><?php echo ($_SESSION['cart']['fpattern'] == "貨送到府") ? "收貨地址" : "最近的郵局或超商名稱"; ?></label>
                                                    <div class="col-sm-12">
                                                        <p class="form-control-static"><?php echo $_POST['clientaddress']; ?></p>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="fPattern">結帳方式</label>
                                                    <input type="hidden" name="fPattern" id="fPattern" value="<?php echo $_SESSION['cart']['fpattern']; ?>" />
                                                    <div class="col-sm-12">
                                                        <p class="form-control-static"><?php echo $_SESSION['cart']['fpattern']; ?></p>
                                                    </div>
                                                </div>
                                                <?php if (empty($suserdata['userRealName']) && empty($suserdata['userPhone']) && empty($suserdata['userAddress'])) { ?>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="savedata" value="true" />
                                                            將資料儲存至我的帳號資料內
                                                        </label>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group text-center">
                                            <div class="btn-group btn-group-lg text-center" role="group">
                                                <a href="userorder.php?action=order&step=2" class="btn btn-info">返回修改</a>
                                                <input type="submit" name="submit" class="btn btn-success" value="確認無誤" />
                                            </div>
                                        </div>
                                    </form>
                                <?php /* 第四步 */ } elseif ($_GET['step'] == '4') { 
                                    unset($_SESSION['cart']);?>

                                <?php } ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-3 cart-total">
                            <?php if ($_GET['action'] != 'order' && (empty($_SESSION['cart']['checkoutstatus']) || $_SESSION['cart']['checkoutstatus'] != 'notcomplete')) { ?>
                                <a class="btn btn-info btn-block btn-lg" <?php echo (empty($_SESSION['cart']['checkoutstatus']) || $_SESSION['cart']['checkoutstatus'] != 'notcomplete') ? " href=\"goods.php?action=viewallgoods\"" : ""; ?> style="margin-bottom: 1em;" <?php echo (!empty($_SESSION['cart']['checkoutstatus']) && $_SESSION['cart']['checkoutstatus'] == 'notcomplete') ? "disabled=\"disabled\" title=\"進入結帳程序後不可修改您的購物車內容\"" : ""; ?>>繼續選購</a>
                            <?php } ?>
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title">總額<?php echo ($_GET['action'] == 'viewcart' || ($_GET['action'] == 'order' && $_GET['step'] == '1')) ? "（不含運費）" : ""; ?></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="totPanel"><span class="<?php echo ($_GET['action'] == 'order' && $_GET['step'] > 1) ? "cartPanelSmall" : "cartPanel"; ?>">小計</span></div>
                                        <div class="totValPanel"><span class="<?php echo ($_GET['action'] == 'order' && $_GET['step'] > 1) ? "cartPanelSmall" : "cartPanel"; ?>">NT$ <?php echo (empty($_SESSION['cart'])) ? 0 : $_SESSION['cartTotal']; ?></span></div>
                                        <?php if ($_GET['action'] == 'order' && $_GET['step'] != '1') { ?>
                                            <div class="totPanel"><span class="cartPanelSmall">運費</span></div>
                                            <div class="totValPanel"><span class="cartPanelSmall">NT$ <?php echo $_SESSION['cart']['freight']; ?></span></div>
                                            <div class="clearfix"></div>
                                            <hr class="divideTotal" />
                                            <div class="totPanel"><span class="cartPanel">總計</span></div>
                                            <div class="totValPanel"><span class="cartPanel">NT$ <?php echo $_SESSION['cartTotal'] + $_SESSION['cart']['freight']; ?></span></div>
                                        <?php } ?>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($_GET['action'] != 'order' && (empty($_SESSION['cart']['checkoutstatus']) || $_SESSION['cart']['checkoutstatus'] != 'notcomplete')) { ?>
                                <a class="btn btn-success btn-block btn-lg<?php echo ($_GET['action'] != 'order') ? "" : " rstcart"; ?>" href="<?php echo (!empty($_SESSION['cart'])) ? "?action=order&step=1" : "goods.php" ?>"><?php echo (!empty($_SESSION['cart'])) ? "立即下單" : "立即選購"; ?></a>
                            <?php }
                        if ($_GET['action'] != 'order' && (empty($_SESSION['cart']['checkoutstatus']) || $_SESSION['cart']['checkoutstatus'] != 'notcomplete')) { ?>
                                <hr class="cartbtn-margin" />
                                <form action="ajax.php?action=clearcart" method="POST">
                                    <input type="hidden" name="identify" value="form" />
                                    <input type="submit" name="submit" class="btn btn-danger btn-lg btn-block rstcart" value="重置購物車" />
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once "templates/footer.php"; ?>
    </div>
</body>

</html>