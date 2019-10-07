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
    } elseif ($_GET['step'] == 3 && $_SESSION['cart']['cashType'] == 'cash' && empty($_POST['clientcasher'])) {
        mysqli_close($connect);
        header("Location: userorder.php?action=order&step=2&msg=nocasherdata");
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
            <div id="content-wrap" class="container">
                <!-- 麵包屑 -->
                <ol class="breadcrumb">
                    <li><a href="./"><i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;洛嬉遊戲</a></li>
                    <li><a href="goods.php?action=viewallgoods">周邊產品</a></li>
                    <?php echo ($_GET['action'] == 'viewcart') ? "<li class=\"thisPosition\">檢視購物車內容</li>" : "<li><a href=\"?action=viewcart\">檢視購物車內容</a></li>"; ?>
                    <?php echo ($_GET['action'] == 'order') ? "<li class=\"thisPosition\">結帳</li>" : ""; ?>
                    <?php include "templates/loginbutton.php"; ?>
                </ol>
                <div class="container">
                    <div class="check">
                        <div id="ajaxmsg"></div>
                        <?php if (!empty($_GET['action']) && $_GET['action'] == 'viewcart') { ?>
                            <h1>購物車項目 (<span id="itemqty"><?php echo (empty($_SESSION['cart'])) ? "0" : sizeof($_SESSION['cart'][0]); ?></span>)</h1>
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
                            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'notinorder') { ?>
                                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4><strong>您目前不在結帳中的狀態，請依正常程序操作！</strong></h4>
                                </div>
                            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'cancelsuccess') { ?>
                                <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4><strong>取消結帳成功，請重新將商品加入購物車！</strong></h4>
                                </div>
                            <?php } ?>
                            <div class="col-md-9 cart-items">
                                <?php
                                // 若購物車項目不為空
                                if (!empty($_SESSION['cart'])) {
                                    $inCar = $_SESSION['cart'][0];
                                    $qty = $_SESSION['cart'][0];
                                    // AJAX 檢查用(變更檢查用)
                                    $_SESSION['cart']['view']['nogid'] = false;
                                    // AJAX 檢查用(移除商品用)
                                    $_SESSION['cart']['view']['rnogid'] = false;
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
                                    $j = 0;
                                    while ($goodsData = mysqli_fetch_array($perfSql, MYSQLI_ASSOC)) {
                                        ?>
                                        <!-- 一個購物車項目 -->
                                        <div id="anCartItem<?php echo $goodsData['goodsOrder']; ?>">
                                            <div class="cart-header">
                                                <div class="close1"><a id="removeitem" data-gid="<?php echo $goodsData['goodsOrder']; ?>" class="btn btn-warning">×</a></div>
                                                <div class="cart-sec simpleCart_shelfItem">
                                                    <div class="cart-item cyc">
                                                        <img src="images/goods/<?php echo $goodsData['goodsImgUrl']; ?>" class="img-responsive cartitemimage" alt="" />
                                                    </div>
                                                    <div class="cart-item-info">
                                                        <h3><a href="goods.php?action=viewgoodsdetail&goodid=<?php echo $goodsData['goodsOrder']; ?>" class="cartItemTitle"><?php echo $goodsData['goodsName']; ?></a><span><?php echo $goodsData['goodsDescript']; ?></span></h3>
                                                        <div class="alert alert-warning" role="alert">
                                                            <span class="qty">數量：<input name="goodsQty" id="goodsQty" data-gid="<?php echo $goodsData['goodsOrder']; ?>" type="number" value="<?php echo $_SESSION['cart'][1][$j]; ?>" style="width: 6em;" />&nbsp;・&nbsp;單價：NT$ <span id="gPrice<?php echo $goodsData['goodsOrder']; ?>"><?php echo $goodsData['goodsPrice']; ?></span></span>
                                                            <span id="gTot<?php echo $goodsData['goodsOrder']; ?>" class="tot" style="font-size: 1.2em;">小計：NT$ <?php echo $_SESSION['cart'][1][$j] * $goodsData['goodsPrice']; ?></span>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                            <hr class="cartitem-margin" />
                                        </div>
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
                        <?php 
                    }
                    if (empty($_GET['step']) || $_GET['step'] != 4) { ?>
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
                                            <div class="totValPanel"><span class="<?php echo ($_GET['action'] == 'order' && $_GET['step'] > 1) ? "cartPanelSmall" : "cartPanel"; ?>">NT$ <span id="ajaxTotal"><?php echo (empty($_SESSION['cart'])) ? 0 : $_SESSION['cartTotal']; ?></span></span></div>
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
                                <?php if ($_GET['action'] != 'order' && (empty($_SESSION['cart']['checkoutstatus']) || $_SESSION['cart']['checkoutstatus'] != 'notcomplete')) { 
                                    // 取得該會員目前共下定幾筆訂單決定現在送出的訂單編號
                                    $sql = mysqli_fetch_array(mysqli_query($connect, "SELECT COUNT(*) AS `cono` FROM `orders` WHERE `orderMember`='" . $_SESSION['uid'] . "'"), MYSQLI_ASSOC); ?>
                                    <a id="submitorder" class="btn btn-success btn-block btn-lg<?php echo ($_GET['action'] != 'order') ? "" : " rstcart"; ?>" href="<?php echo (!empty($_SESSION['cart'])) ? "actions.php?action=checkout&mcid=lsg" . $_SESSION['uid'] . date('YmdHis')/*$_SESSION['uid'] . $sql['cono']*/ : "goods.php" ?>"><?php echo (!empty($_SESSION['cart'])) ? "立即下單（綠界金流）" : "立即選購"; ?></a>
                                    <a id="submitorder" class="btn btn-success btn-block btn-lg<?php echo ($_GET['action'] != 'order') ? "" : " rstcart"; ?>" href="<?php echo (!empty($_SESSION['cart'])) ? "actions.php?action=order&step=1" : "goods.php" ?>"><?php echo (!empty($_SESSION['cart'])) ? "立即下單（站內結帳）" : "立即選購"; ?></a>
                                <?php }elseif($_GET['action'] != 'order' && (!empty($_SESSION['cart']['checkoutstatus']) || $_SESSION['cart']['checkoutstatus'] == 'notcomplete')){ ?>
                                    <a class="btn btn-success btn-block btn-lg" href="?action=order&step=2">繼續結帳</a>
                                    <hr class="cartbtn-margin" />
                                    <a href="actions.php?action=cancelorder" id="cancelorder" class="btn btn-danger btn-block btn-lg">取消結帳</a>
                                <?php }
                            if ($_GET['action'] != 'order' && (empty($_SESSION['cart']['checkoutstatus']) || $_SESSION['cart']['checkoutstatus'] != 'notcomplete')) { ?>
                                    <hr class="cartbtn-margin" />
                                    <form action="ajax.php?action=clearcart" method="POST">
                                        <input type="hidden" name="identify" value="form" />
                                        <input type="submit" name="submit" class="btn btn-danger btn-lg btn-block rstcart" value="重置購物車" />
                                    </form>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once "templates/footer.php"; ?>
    </div>
</body>

</html>