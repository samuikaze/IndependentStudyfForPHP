<?php
require_once "sessionCheck.php";
$self = basename(__FILE__);
if (!empty($_SERVER['QUERY_STRING'])) {
    $self .= "?" . $_SERVER['QUERY_STRING'];
    $self = str_replace("&", "+", $self);
} else {
    header("Location: ?action=viewallgoods");
    exit;
}
if (empty($_GET['pid'])) {
    $page = 1;
} else {
    $page = $_GET['pid'];
}
if(empty($_GET['refpage'])){
    $refpage = "";
}else{
    $refpage = "&pid=" . $_GET['refpage'];
}
// 限制一頁顯示幾項
$pgnums = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM `systemsetting` WHERE `settingName`='goodsNum';"), MYSQLI_ASSOC);
$gpp = $pgnums['settingValue'];
// 商品數量反紅值
$qtydanger = 15;
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <title>周邊產品 | 洛嬉遊戲 L.S. Games</title>
    <?php include_once "templates/metas.php"; ?>
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
                    <?php echo ($_GET['action'] == 'viewallgoods')? "<li class=\"thisPosition\">": "<li><a href=\"?action=viewallgoods$refpage\">"; ?>周邊產品<?php echo ($_GET['action'] == 'viewallgoods')? "": "</a>"; ?></li>
                    <?php echo ($_GET['action'] == 'viewgoodsdetail')? "<li class=\"thisPosition\">周邊產品詳細資料</li>": ""; ?>
                    <?php include "templates/loginbutton.php"; ?>
                </ol>
                <?php if(!empty($_SESSION['auth'])){ ?>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <div class="alert alert-warning wadj" role="alert">
                            <div class="ca-r">
                                <div class="cart box_1">
                                    <a href="userorder.php?action=viewcart">
                                        <h3>
                                            <div class="total">
                                                <span id="simpleCart_total" class="simpleCart_total">NT$<?php echo (!empty($_SESSION['cart']))? $_SESSION['cartTotal'] : 0; ?></span>
                                                <i class="fas fa-shopping-cart simpleCart_total"></i>
                                            </div>
                                        </h3>
                                        <p class="simpleCart_total">檢視購物車項目</p>
                                    </a>
                                </div>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                    </div>
                </div>
                <?php }
                if (!empty($_GET['msg']) && $_GET['msg'] == 'checkoutfailed') { ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>交易失敗！請<a href="userorder.php?action=order&casher=ecpay">按此</a>重新結帳。</strong></h4>
                    </div>
                <?php }
                if (!empty($_GET['action']) && $_GET['action'] == 'viewallgoods') {
                    $lpp = ($page - 1) * $gpp;      //一頁顯示項目左極限
                    $rpp = $page * $gpp;            //一頁顯示項目右極限
                    $alldatasSql = mysqli_query($connect, "SELECT * FROM `goodslist` ORDER BY `goodsOrder` ASC;");
                    $alldatasRow = mysqli_num_rows($alldatasSql);
                    // 計算總頁數
                    $tpg = ceil($alldatasRow / $gpp);
                    $alldatas = array();
                    $i = 0;
                    ?>
                    <div class="row" style="margin-top: 0px; padding-top: 0px;">
                        <?php // 若沒有資料
                        if ($alldatasRow == 0) { ?>
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h3 class="panel-title">警告</h3>
                                </div>
                                <div class="panel-body text-center">
                                    <h2 class="warning-warn">目前尚無上架商品。<br /><br />
                                    </h2>
                                </div>
                            </div>
                        <?php } else {
                        // 先把所有資料處理成二維陣列
                        while ($alldatas = mysqli_fetch_array($alldatasSql, MYSQLI_ASSOC)) {
                            if ($i < $lpp) {
                                $i += 1;
                                continue;
                            } elseif ($i >= $rpp) {
                                break;
                            } else { ?>
                                    <!-- 一個完整商品項 -->
                                    <div class="col-md-4 courses-info">
                                        <div class="thumbnail">
                                            <a href="?action=viewgoodsdetail&goodid=<?php echo $alldatas['goodsOrder'] . "&refpage=$page"; ?>"><img src="images/goods/<?php echo $alldatas['goodsImgUrl']; ?>"></a>
                                            <div class="caption">
                                                <div class="numbers fRight">NT$ <span><?php echo $alldatas['goodsPrice']; ?></span></div>
                                                <h3 class="fLeft"><?php echo $alldatas['goodsName']; ?></h3>
                                                <div class="fLeft"><?php echo $alldatas['goodsDescript']; ?></div>
                                                <div class="clearfix"></div>
                                                <p class="text-center">
                                                    <div class="text-center" style="margin-bottom: 15px;">
                                                        <div class="btn-group" role="group" aria-label="...">
                                                            <a href="?action=viewgoodsdetail&goodid=<?php echo $alldatas['goodsOrder'] . "&refpage=$page"; ?>" class="btn btn-success">週邊詳細</a>
                                                            <a id="goodsjCart<?php echo $alldatas['goodsOrder']; ?>" data-gid="<?php echo $alldatas['goodsOrder']; ?>" data-clicked="false" class="btn btn-info<?php echo (empty($_SESSION['auth']))? "" : " joinCart"; ?>" <?php echo (empty($_SESSION['auth']))? "disabled=\"disabled\" title=\"此功能登入後才可使用\"" : "";?>>加入購物車</a>
                                                        </div>
                                                    </div>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /一個完整商品項 -->
                                <?php }
                            $i += 1;
                        }
                    } ?>
                    </div>
                    <?php if ($tpg > 1) { ?>
                        <!-- 頁數按鈕開始 -->
                        <div class="text-center">
                            <ul class="pagination">
                                <?php echo ($page == 1) ? "<li class=\"disabled\">" : "<li>"; ?><a <?php echo ($page == 1) ? "" : "href=\"?action=viewallgoods&pid=" . ($page - 1) . "\""; ?> aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                                <?php
                                // 目前頁數
                                $j = 1;
                                // WHILE 運算不要改到原值
                                $pg = $tpg;
                                while ($pg > 0) { ?>
                                    <?php /* 如果這頁就是這顆按鈕就變顏色 */ echo ($page == $j) ? "<li class=\"active\">" : "<li>"; ?><a <?php /* 如果是這頁就不印出連結 */ echo ($page == $j) ? "" : "href=\"?action=viewallgoods&pid=$j\""; ?>><?php echo $j; ?> <?php echo ($page == $j) ? "<span class=\"sr-only\">(current)</span>" : ""; ?></a></li>
                                    <?php
                                    $j += 1;
                                    $pg -= 1;
                                }
                                echo ($page == $tpg) ? "<li class=\"disabled\">" : "<li>"; ?><a <?php echo ($page == $tpg) ? "" : "href=\"?action=viewallgoods&pid=" . ($page + 1) . "\""; ?> aria-label="Next"><span aria-hidden="true">»</span></a></li>
                            </ul>
                        </div>
                        <!-- 頁數按鈕結束 -->
                    <?php } ?>
                </div>
            <?php } elseif (!empty($_GET['action']) && $_GET['action'] == 'viewgoodsdetail') { 
                if(empty($_GET['goodid'])){ ?>
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title">錯誤</h3>
                        </div>
                        <div class="panel-body text-center">
                            <h2 class="news-warn">無法識別商品，請依正常程序檢視商品詳細資料。<br /><br />
                                <div class="btn-group" role="group">
                                    <a class="btn btn-lg btn-info" href="?action=viewallgoods">返回商品管理</a>
                                </div>
                            </h2>
                        </div>
                    </div>
                <?php }else{ 
                    $gid = $_GET['goodid'];
                    $goodsdetailSql = mysqli_query($connect, "SELECT * FROM `goodslist` WHERE `goodsOrder`=$gid;");
                    $goodsdetailRows = mysqli_num_rows($goodsdetailSql);
                    if($goodsdetailRows == 0){ ?>
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <h3 class="panel-title">錯誤</h3>
                            </div>
                            <div class="panel-body text-center">
                                <h2 class="news-warn">無法識別商品，請依正常程序檢視商品詳細資料。<br /><br />
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-lg btn-info" href="?action=viewallgoods">返回商品管理</a>
                                    </div>
                                </h2>
                            </div>
                        </div>
                    <?php }else{
                        $goodsdetail = mysqli_fetch_array($goodsdetailSql, MYSQLI_ASSOC); ?>
                        <div class="row goodDetail">
                            <div class="col-md-4 goods-detail-img"><a data-fancybox href="images/goods/<?php echo $goodsdetail['goodsImgUrl']; ?>"><img src="images/goods/<?php echo $goodsdetail['goodsImgUrl']; ?>" class="img-responsive img-thumbnail"></a></div>
                            <div class="col-md-7 thumbnail goods-detail-text">
                                <h1 style="margin-bottom: 10px;"><?php echo $goodsdetail['goodsName']; ?></h1><br />
                                <div class="numbers">NT$ <span><?php echo $goodsdetail['goodsPrice']; ?></span></div>
                                <div class="numbers">目前庫存：<span style="font-weight: bold;<?php echo ($goodsdetail['goodsQty'] <= $qtydanger)? "color: #d9534f!important;" : "color: #5cb85c!important;"; ?>"><?php echo $goodsdetail['goodsQty']; ?><?php echo ($goodsdetail['goodsQty'] <= $qtydanger)? "&nbsp;&nbsp;數量不多，要買要快！" : ""; ?></span></div>
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong>注意！</strong> 賣家若要求您「使用LINE帳號私下聯絡或轉帳匯款」是常見的詐騙手法
                                </div>
                                <div class="alert alert-warning alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    符合消費者保護法所定義企業經營者之賣家，應遵守消費者保護法第19條之規範。買家檢視商品時，應維持商品之原狀，以免影響退款權益。
                                </div>
                                <p class="goodDescript"><?php echo $goodsdetail['goodsDescript']; ?></p>
                                <div class="col-md-6 col-xs-12">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td rowspan="4" class="warning" style="vertical-align: middle;"><i class="fas fa-cash-register"></i><br /> 接受付款方式</td>
                                                <td><i class="far fa-credit-card"></i> 信用卡</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-money-bill-wave"></i> 超商取貨<br />（須事先付款）</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-money-bill-wave"></i> ATM 轉帳</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-money-bill-wave"></i> 超商取貨付款</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td rowspan="4" class="success" style="vertical-align: middle;"><i class="fas fa-truck"></i><br /> 運送方式</td>
                                                <td><i class="fas fa-store-alt"></i> 超商取貨付款</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-store-alt"></i> 超商取貨<br />（須事先付款）</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-shipping-fast"></i> 郵寄／貨運</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-inbox"></i> 郵局取貨</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="clearfix"></div>
                                <a id="goodsjCart<?php echo $goodsdetail['goodsOrder']; ?>" data-gid="<?php echo $goodsdetail['goodsOrder']; ?>" data-clicked="false" class="btn btn-info btn-lg btn-block<?php echo (empty($_SESSION['auth']))? "" : " joinCart"; ?>" <?php echo (empty($_SESSION['auth']))? "disabled=\"disabled\" title=\"此功能登入後才可使用\"" : "";?>>加入購物車</a>
                            </div>
                        </div>
                    <?php }
                } 
            } ?>
            <div class="row">
                <div class="col-md-12 text-center" style="margin-bottom: 1em;">
                    <a href="faq.html" class="btn btn-default btn-lg">常見問題</a>
                    <?php echo ($_GET['action'] == 'viewgoodsdetail')? "<a href=\"?action=viewallgoods$refpage\" class=\"btn btn-info btn-lg\">返回周邊商品一覽</a>" : ""; ?>
                </div>
            </div>
        </div>
        <?php include_once "templates/footer.php"; ?>
    </div>
</body>

</html>
<?php mysqli_close($connect); ?>