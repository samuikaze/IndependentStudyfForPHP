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
            <div class="container">
                <!-- 麵包屑 -->
                <ol class="breadcrumb">
                    <li><a href="index.html"><i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;洛嬉遊戲</a></li>
                    <li class="thisPosition">作品一覽</li>
                    <?php include "templates/loginbutton.php"; ?>
                </ol>
                <div class="row" style="margin-top: 0px; padding-top: 0px;">
                    <div class="col-md-6 courses-info">
                        <div class="prodLists thumbnail">
                            <a href="images/products/nowprint.jpg"><img src="images/products/nowprint.jpg"></a>
                            <div class="prodText">
                                <h3 class="fLeft prodTitle">作品一</h3>
                                <div class="fLeft">
                                    <p>作品一說明文字作品一說明文字作品一說明文字作品一說明文字作品一說明文字作品一說明文字</p>
                                    <hr class="fLeft prodDivide" />
                                    <div class="col-md-6 col-xs-12 pull-left noPadding">
                                        <p>類型：遊戲三類型</p>
                                    </div>
                                    <div class="col-md-6 col-xs-12 fRight noPadding">
                                        <p>平台：PC</p>
                                    </div>
                                    <hr class="fLeft prodDivide" />
                                    <div class="col-md-12 col-xs-12 relDate noPadding">
                                        <p>發行日：20○○/○○/○○</p>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="text-left goProd">
                                    <a href="#" class="btn btn-block btn-success">前往頁面</a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="col-md-6 courses-info prodList-noMargin">
                        <div class="prodLists thumbnail">
                            <a href="images/products/nowprint.jpg"><img src="images/products/nowprint.jpg"></a>
                            <div class="prodText">
                                <h3 class="fLeft prodTitle">作品二</h3>
                                <div class="fLeft">
                                    <p>作品二說明文字作品二說明文字作品二說明文字作品二說明文字作品二說明文字作品二說明文字</p>
                                    <hr class="fLeft prodDivide" />
                                    <div class="col-md-6 col-xs-12 pull-left noPadding">
                                        <p>類型：遊戲三類型</p>
                                    </div>
                                    <div class="col-md-6 col-xs-12 fRight noPadding">
                                        <p>平台：PC</p>
                                    </div>
                                    <hr class="fLeft prodDivide" />
                                    <div class="col-md-12 col-xs-12 relDate noPadding">
                                        <p>發行日：20○○/○○/○○</p>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="text-left goProd">
                                    <a href="#" class="btn btn-block btn-success">前往頁面</a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="col-md-6 courses-info prodList-noMargin">
                        <div class="prodLists thumbnail">
                            <a href="images/products/nowprint.jpg"><img src="images/products/nowprint.jpg"></a>
                            <div class="prodText text-left">
                                <h3 class="fLeft prodTitle">作品三</h3>
                                <div class="fLeft">
                                    <p>作品三說明文字作品三說明文字作品三說明文字作品三說明文字作品三說明文字作品三說明文字</p>
                                    <hr class="fLeft prodDivide" />
                                    <div class="col-md-6 col-xs-12 pull-left noPadding">
                                        <p>類型：遊戲三類型</p>
                                    </div>
                                    <div class="col-md-6 col-xs-12 fRight noPadding">
                                        <p>平台：PC</p>
                                    </div>
                                    <hr class="fLeft prodDivide" />
                                    <div class="col-md-12 col-xs-12 relDate noPadding">
                                        <p>發行日：20○○/○○/○○</p>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="text-left goProd">
                                    <a href="#" class="btn btn-block btn-success">前往頁面</a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="col-md-6 courses-info prodList-noMargin">
                        <div class="prodLists thumbnail">
                            <a href="images/products/nowprint.jpg"><img src="images/products/nowprint.jpg"></a>
                            <div class="prodText">
                                <h3 class="fLeft prodTitle">作品四</h3>
                                <div class="fLeft">
                                    <p>作品四說明文字作品四說明文字作品四說明文字作品四說明文字作品四說明文字作品四說明文字</p>
                                    <hr class="fLeft prodDivide" />
                                    <div class="col-md-6 col-xs-12 pull-left noPadding">
                                        <p>類型：遊戲三類型</p>
                                    </div>
                                    <div class="col-md-6 col-xs-12 fRight noPadding">
                                        <p>平台：PC</p>
                                    </div>
                                    <hr class="fLeft prodDivide" />
                                    <div class="col-md-12 col-xs-12 relDate noPadding">
                                        <p>發行日：20○○/○○/○○</p>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="text-left goProd">
                                    <a href="#" class="btn btn-block btn-success">前往頁面</a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once "templates/footer.php"; ?>
    </div>
	<script src="js/simpleLightbox.js"></script>
	<script>
		$('.thumbnail a').simpleLightbox();
	</script>
</body>
</html>
<?php mysqli_close($connect); ?>