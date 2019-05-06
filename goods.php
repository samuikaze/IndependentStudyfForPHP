<?php
    require_once "sessionCheck.php";
    $self = basename(__FILE__);
    if(empty($_SERVER['QUERY_STRING']) != True){
        $self .= "?" . $_SERVER['QUERY_STRING'];
        $self = str_replace("&", "+", $self);
    }
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <title>周邊產品 | 洛嬉遊戲 L.S. Games</title>
    <?php include_once "templates/metas.php"; ?>
    <script src="js/simpleCart.min.js"></script>
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
                    <li class="thisPosition">周邊產品</li>
                    <?php include "templates/loginbutton.php"; ?>
                </ol>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <div class="alert alert-warning wadj" role="alert">
                            <div class="ca-r">
                                <div class="cart box_1">
                                    <a href="cart.html">
                                        <h3>
                                            <div class="total">
                                                <span class="simpleCart_total"></span>
                                                <img src="images/cart.png" alt="" />
                                            </div>
                                        </h3>
                                    </a>
                                    <p><a href="javascript:;" class="simpleCart_empty">清空購物車</a></p>
                                </div>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 0px; padding-top: 0px;">
                    <div class="col-md-4 courses-info">
                        <div class="thumbnail">
                            <a href="#"><img src="images/goods/g1.jpg"></a>
                            <div class="caption">
                                <p class="numbers fRight">NT$ <span>99,999</span></p>
                                <h3 class="fLeft">週邊一</h3>
                                <p class="fLeft">週邊一說明文字</p>
                                <div class="clearfix"></div>
                                <p class="text-center">
                                    <div class="text-center" style="margin-bottom: 15px;">
                                        <div class="btn-group" role="group" aria-label="...">
                                            <a href="goods-detail.html" class="btn btn-success">週邊詳細</a>
                                            <a href="cart.html" class="btn btn-info">加入購物車</a>
                                        </div>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 courses-info">
                        <div class="thumbnail">
                            <a href="#"><img src="images/goods/g2.jpg"></a>
                            <div class="caption">
                                <p class="numbers fRight">NT$ <span>99,999</span></p>
                                <h3 class="fLeft">週邊二</h3>
                                <p class="fLeft">週邊二說明文字</p>
                                <div class="clearfix"></div>
                                <p class="text-center">
                                    <div class="text-center" style="margin-bottom: 15px;">
                                        <div class="btn-group" role="group" aria-label="...">
                                            <a href="goods-detail.html" class="btn btn-success">週邊詳細</a>
                                            <a href="cart.html" class="btn btn-info">加入購物車</a>
                                        </div>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 courses-info">
                        <div class="thumbnail">
                            <a href="#"><img src="images/goods/g3.jpg"></a>
                            <div class="caption">
                                <p class="numbers fRight">NT$ <span>99,999</span></p>
                                <h3 class="fLeft">週邊三</h3>
                                <p class="fLeft">週邊三說明文字</p>
                                <div class="clearfix"></div>
                                <p class="text-center">
                                    <div class="text-center" style="margin-bottom: 15px;">
                                        <div class="btn-group" role="group" aria-label="...">
                                            <a href="goods-detail.html" class="btn btn-success">週邊詳細</a>
                                            <a href="cart.html" class="btn btn-info">加入購物車</a>
                                        </div>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 courses-info">
                        <div class="thumbnail">
                            <a href="#"><img src="images/goods/g4.jpg"></a>
                            <div class="caption">
                                <p class="numbers fRight">NT$ <span>99,999</span></p>
                                <h3 class="fLeft">週邊四</h3>
                                <p class="fLeft">週邊四說明文字</p>
                                <div class="clearfix"></div>
                                <p class="text-center">
                                    <div class="text-center" style="margin-bottom: 15px;">
                                        <div class="btn-group" role="group" aria-label="...">
                                            <a href="goods-detail.html" class="btn btn-success">週邊詳細</a>
                                            <a href="cart.html" class="btn btn-info">加入購物車</a>
                                        </div>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 courses-info">
                        <div class="thumbnail">
                            <a href="#"><img src="images/goods/g5.jpg"></a>
                            <div class="caption">
                                <p class="numbers fRight">NT$ <span>99,999</span></p>
                                <h3 class="fLeft">週邊五</h3>
                                <p class="fLeft">週邊五說明文字</p>
                                <div class="clearfix"></div>
                                <p class="text-center">
                                    <div class="text-center" style="margin-bottom: 15px;">
                                        <div class="btn-group" role="group" aria-label="...">
                                            <a href="goods-detail.html" class="btn btn-success">週邊詳細</a>
                                            <a href="cart.html" class="btn btn-info">加入購物車</a>
                                        </div>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 courses-info">
                        <div class="thumbnail">
                            <a href="#"><img src="images/goods/g6.jpg"></a>
                            <div class="caption">
                                <p class="numbers fRight">NT$ <span>99,999</span></p>
                                <h3 class="fLeft">週邊六</h3>
                                <p class="fLeft">週邊六說明文字</p>
                                <div class="clearfix"></div>
                                <p class="text-center">
                                    <div class="text-center" style="margin-bottom: 15px;">
                                        <div class="btn-group" role="group" aria-label="...">
                                            <a href="goods-detail.html" class="btn btn-success">週邊詳細</a>
                                            <a href="cart.html" class="btn btn-info">加入購物車</a>
                                        </div>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <a href="faq.html" class="btn btn-default btn-lg">常見問題</a>
                </div>
            </div>
        </div>
        <?php include_once "templates/footer.php"; ?>
    </div>
</body>
</html>
<?php mysqli_close($connect); ?>