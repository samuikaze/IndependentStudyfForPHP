<?php require_once "sessionCheck.php"; ?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
	<title>討論專區 | 洛嬉遊戲 L.S. Games</title>
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
            <div class="container">
                <!-- 麵包屑 -->
                <ol class="breadcrumb">
                    <li><a href="index.html"><i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;洛嬉遊戲</a></li>
                    <li class="thisPosition">討論區首頁</li>
                    <?php if (isset($_COOKIE['sid']) == False) { ?>
                        <a id="loginForm" class="btn btn-info pull-right">登入</a>
                    <?php } else { ?>
                        <div class="dropdown pull-right" style="display: inline-block; ">
                            <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <?php echo $_SESSION['user']; ?>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right" aria-labelledby="dropdownMenu1">
                                <li class="dropdown-header">使用者選單</li>
                                <li><a>使用者設定（尚未完成）</a></li>
                                <li><a href="member.php?action=logout&refer=<?php echo substr($_SERVER['PHP_SELF'], 1); ?>">登出</a></li>
                            </ul>
                        </div>
                    <?php } ?>
                </ol>
                <!-- 討論版塊放置區 -->
                <div class="row" style="margin-top: 0px; padding-top: 0px;">
                    <div class="col-md-4 courses-info">
                        <div class="thumbnail">
                            <a href="#"><img src="images/services1.jpg"></a>
                            <div class="caption">
                                <p class="numbers fRight">文章數 <span>99,999</span></p>
                                <h3 class="pull-left">作品一板</h3>
                                <p class="fLeft">作品一說明文字</p>
                                <div class="clearfix"></div>
                                <p class="text-center">
                                    <div class="text-center" style="margin-bottom: 15px;">
                                        <a href="bbs-prod01.html" class="btn btn-block btn-warning">進入討論板</a>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 courses-info">
                        <div class="thumbnail">
                            <a href="#"><img src="images/nowprint.jpg"></a>
                            <div class="caption">
                                <p class="numbers fRight">文章數 <span>99,999</span></p>
                                <h3 class="pull-left">作品二板</h3>
                                <p class="fLeft">作品二說明文字</p>
                                <div class="clearfix"></div>
                                <p class="text-center">
                                    <div class="text-center" style="margin-bottom: 15px;">
                                        <a href="bbs-prod01.html" class="btn btn-block btn-warning">進入討論板</a>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 courses-info">
                        <div class="thumbnail">
                            <a href="#"><img src="images/nowprint.jpg"></a>
                            <div class="caption">
                                <p class="numbers fRight">文章數 <span>99,999</span></p>
                                <h3 class="pull-left">作品三板</h3>
                                <p class="fLeft">作品三說明文字</p>
                                <div class="clearfix"></div>
                                <p class="text-center">
                                    <div class="text-center" style="margin-bottom: 15px;">
                                        <a href="bbs-prod01.html" class="btn btn-block btn-warning">進入討論板</a>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once "templates/footer.php"; ?>
    </div>
</body>
</html>