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
                    <?php echo ($_GET['action'] == 'viewcart') ? "<li class=\"thisPosition\">檢視購物車內容</li>" : ""; ?>
                    <?php include "templates/loginbutton.php"; ?>
                </ol>
                <div class="container">
                    <div class="check">
                        <h1>購物車項目 (<?php echo (empty($_SESSION['cart'])) ? "0" : sizeof($_SESSION['cart'][0]); ?>)</h1>
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
                                                    <span style="font-size: 1.2em;">小計：NT$ <?php echo $_SESSION['cart'][1][$j] * $goodsData['goodsPrice']; ?></span>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                    <hr style="margin: 10px 0 10px 0;" />
                                    <!-- /一個購物車項目 -->
                                    <?php $j += 1;
                                }
                            // 開始下單
                            } elseif(!empty($_GET['action']) && $_GET['action'] == 'order') { ?>
                            
                            <?php // 若購物車為空
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
                        <div class="col-md-3 cart-total">
                            <a class="btn btn-info btn-block btn-lg" href="goods.php?action=viewallgoods" style="margin-bottom: 1em;">繼續選購</a>

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title">小計</h3>
                                </div>
                                <div class="panel-body">
                                    <!--div class="row">
                                        <div class="col-sm-6"><span class="cartPanel">應付</span></div>
                                        <div class="col-sm-6 total1"><span class="cartPanel"><?php echo (empty($_SESSION['cart'])) ? 0 : $_SESSION['cartTotal']; ?></span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">折扣</div>
                                        <div class="total1 col-sm-6">0</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">優惠</div>
                                        <div class="total1 col-sm-6">0</div>
                                    </div-->
                                    <!--div class="clearfix"></div>
                                    <hr style="margin: 5px 0 5px 0;" /-->
                                    <div class="row">
                                        <div class="col-sm-6"><span class="cartPanel">總額</span></div>
                                        <div class="col-sm-6"><span class="cartPanel">NT$ <?php echo (empty($_SESSION['cart'])) ? 0 : $_SESSION['cartTotal']; ?></span></div>
                                    </div>
                                </div>
                            </div>
                            <!--div class="price-details">
                                <h3>結帳清單</h3>
                                <span>應付</span>
                                <span class="total1"><?php echo (empty($_SESSION['cart'])) ? 0 : $_SESSION['cartTotal']; ?></span>
                                <span>折扣</span>
                                <span class="total1">0</span>
                                <span>優惠</span>
                                <span class="total1">0</span>
                                <div class="clearfix"></div>
                            </div>
                            <ul class="total_price">
                                <li class="last_price">
                                    <h4>總額</h4>
                                </li>
                                <li class="last_price"><span><?php echo (empty($_SESSION['cart'])) ? 0 : $_SESSION['cartTotal']; ?></span></li>
                                <div class="clearfix"> </div>
                            </ul-->
                            <div class="clearfix"></div>
                            <?php if($_GET['action'] == 'viewcart'){ ?>
                                <a class="btn btn-success btn-block btn-lg" href="?action=order" style="margin: 1em 0 1em 0;" <?php echo (empty($_SESSION['cart'])) ? " disabled=\"disabled\" title=\"您的購物車目前為空\"" : ""; ?>><?php echo (empty($_SESSION['cart'])) ? "購物車為空" : "立即下單"; ?></a>
                            <?php }
                            if (!empty($_SESSION['cart'])) { ?>
                                <form action="ajax.php?action=clearcart" method="POST">
                                    <input type="hidden" name="identify" value="form" />
                                    <input type="submit" name="submit" class="btn btn-danger btn-lg btn-block" value="重置購物車" />
                                </form>
                            <?php } ?>
                            <!--div class="total-item">
                                <h3>其它選項</h3>
                                <h4>優惠券</h4>
                                <a class="cpns" href="#">使用優惠券</a>
                                <p>登入後可享有更多優惠，例如優惠券</p>
                            </div-->
                        </div>
                    </div>

                </div>
            </div>

</body>

</html>