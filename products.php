<?php
require_once "sessionCheck.php";
$self = basename(__FILE__);
if (empty($_SERVER['QUERY_STRING']) != True) {
    $self .= "?" . $_SERVER['QUERY_STRING'];
    $self = str_replace("&", "+", $self);
}
$prodDataSql = mysqli_query($connect, "SELECT * FROM `productname` ORDER BY `prodOrder` ASC;");
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <title>作品一覽 | 洛嬉遊戲 L.S. Games</title>
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
        <div class="gallery">
            <div id="content-wrap" class="container">
                <!-- 麵包屑 -->
                <ol class="breadcrumb">
                    <li><a href="./"><i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;洛嬉遊戲</a></li>
                    <li class="thisPosition">作品一覽</li>
                    <?php include "templates/loginbutton.php"; ?>
                </ol>
                <div class="row" style="margin-top: 0px; padding-top: 0px;">
                    <?php if (mysqli_num_rows($prodDataSql) == 0) { ?>
                        <div class="container">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h3 class="panel-title">資訊</h3>
                                </div>
                                <div class="panel-body text-center">
                                    <h2 class="warning-warn">目前尚無作品可顯示。<br /><br />
                                    </h2>
                                </div>
                            </div>
                        </div>
                    <?php } else {
                    while ($prodDatas = mysqli_fetch_array($prodDataSql, MYSQLI_ASSOC)) { ?>
                            <!-- 一個作品項目 -->
                            <div class="col-md-6 courses-info" style="margin-bottom: 1em;">
                                <div class="prodLists thumbnail">
                                    <a data-fancybox href="images/products/<?php echo $prodDatas['prodImgUrl']; ?>"><img src="images/products/<?php echo $prodDatas['prodImgUrl']; ?>"></a>
                                    <div class="prodText">
                                        <h3 class="fLeft prodTitle"><?php echo $prodDatas['prodTitle']; ?></h3>
                                        <div class="fLeft">
                                            <p><?php echo $prodDatas['prodDescript']; ?></p>
                                            <hr class="fLeft prodDivide" />
                                            <div class="col-md-6 col-xs-12 pull-left noPadding">
                                                <p>類型：<?php echo $prodDatas['prodType']; ?></p>
                                            </div>
                                            <div class="col-md-6 col-xs-12 fRight noPadding">
                                                <p>平台：<?php echo $prodDatas['prodPlatform']; ?></p>
                                            </div>
                                            <hr class="fLeft prodDivide" />
                                            <div class="col-md-12 col-xs-12 relDate noPadding">
                                                <p>發售日：<?php echo date("Y / m / d", strtotime($prodDatas['prodRelDate'])); ?></p>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="text-left goProd">
                                            <a href="<?php echo $prodDatas['prodPageUrl']; ?>" class="btn btn-block btn-success">前往頁面</a>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <!-- /一個作品項目 -->
                        <?php }
                } ?>
                </div>
            </div>
        </div>
        <?php include_once "templates/footer.php"; ?>
    </div>
</body>

</html>
<?php mysqli_close($connect); ?>