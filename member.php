<?php
    require_once 'sessionCheck.php';
    if(empty($_GET['action'])){
        header("Location: member.php?action=login");
        exit;
    }elseif($_GET['action'] == 'logout'){
        header("Location: authentication.php?action=logout");
        exit;
    }
    
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <title>會員專區 | 洛嬉遊戲 L.S. Games</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="洛嬉遊戲 L.S. Games LSGames" />
    <script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/PreloadJS.js"></script> <!-- 載入動畫（修改中） -->
    <script type="text/javascript" src="js/slick.min.js"></script> <!-- 圖片輪播 -->
    <script src="js/custom.js"></script>
    <script type="application/x-javascript">
        addEventListener("load", function() {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <link rel="stylesheet" href="css/jquery-ui.min.css" type="text/css" />
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <link rel="stylesheet" type="text/css" href="css/slick.css" />
    <link rel="stylesheet" type="text/css" href="slick/slick-theme.css" />
    <link rel="stylesheet" href="css/custom.css" />
</head>

<body onload="loadProgress()">
    <!-- 要加入載入動畫這邊請加上 onload="loadProgress()" -->
    <div class="loadscr">
        <div class="loadTitle"><img src="images/logo.png" class="logo" />&nbsp;&nbsp;&nbsp;L.S. Games</div>
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                <span class="sr-only">75% Complete</span>
            </div>
        </div>
        <div class="loadHint">頁面載入中...</div>
    </div>
    <div class="pageWrap">
        <div id="home" class="banner banner-load inner-banner">
            <header style="padding: 15px;">
                <div class="header-bottom-w3layouts">
                    <div class="main-w3ls-logo">
                        <a href="index.html">
                            <h1><img src="images/logo.png">洛嬉遊戲</h1>
                        </a>
                    </div>
                    <nav class="navbar navbar-default">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                <li><a class="colorTran" href="about.html">關於團隊</a></li>
                                <li><a class="colorTran" href="news.html">最新消息</a></li>
                                <li><a class="colorTran" href="products.html">作品一覽</a></li>
                                <li><a class="colorTran" href="goods.html">周邊產品</a></li>
                                <li><a class="colorTran" href="bbs.html">討論專區</a></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle colorTran" data-toggle="dropdown">其他連結<b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="recruit.html">招募新血</a></li>
                                        <li><a href="faq.html">常見問題</a></li>
                                        <li><a href="contact.html">連絡我們</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
                <div class="clearfix"></div>
            </header>
        </div>
        <div class="courses">
            <div class="container">
                <!-- 麵包屑 -->
                <ol class="breadcrumb">
                    <li><a href="index.html"><i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;洛嬉遊戲</a></li>
                    <li class="thisPosition">會員首頁</li>
                </ol>
                <?php if(empty($_SESSION['auth']) || $_SESSION['auth'] != True){?>
                <!-- 登入 / 註冊表單 -->
                <!-- 要有一個導向原頁面的隱藏欄位，這個欄位可以用 GET 取值 -->
                <div class="memberForm">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <!-- Bootstrap 標籤頁 -->
                            <ul class="nav nav-tabs">
                                <?php echo ($_GET['action'] == 'login') ? "<li class=\"active\">" : "<li>"; ?><a href="#login-form" data-toggle="tab">登入</a></li>
                                <?php echo ($_GET['action'] == 'register') ? "<li class=\"active\">" : "<li>"; ?><a href="#register-form" data-toggle="tab">註冊</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active fade<?php echo ($_GET['action'] == 'login') ? " in" : ""; ?>" id="login-form">
                                    <?php if(!empty($_GET['loginErrType']) && $_GET['loginErrType'] == 1){ ?>
                                        <h3 class="member-warn">帳號欄為不能為空！</h3>
                                    <?php }elseif(!empty($_GET['loginErrType']) && $_GET['loginErrType'] == 2){ ?>
                                        <h3 class="member-warn">密碼欄為不能為空！</h3>
                                    <?php }elseif(!empty($_GET['loginErrType']) && $_GET['loginErrType'] == 3){ ?>
                                        <h3 class="member-warn">您輸入的帳號或密碼有誤！</h3>
                                    <?php }else{ echo ""; } ?>   
                                    <form method="POST" action="authentication.php?action=login">
                                        <input type="text" name="username" placeholder="請輸入使用者名稱" />
                                        <input type="password" name="password" placeholder="請輸入密碼" />
                                        <input type="hidden" name="refer" value="<?php echo ( empty($_GET['refer']) ) ? "index.html" : $_GET['refer'];?>" />
                                        <input type="submit" name="submit" value="登入" />
                                    </form>
                                </div>
                                <div class="tab-pane active fade<?php echo ($_GET['action'] == 'register') ? " in" : ""; ?>" id="registerForm">
                                    <form method="POST" action="authentication.php?action=register">
                                        <input type="text" name="username" placeholder="請輸入使用者名稱" />
                                        <input type="password" name="password" placeholder="請輸入密碼" />
                                        <input type="password" name="passwordConfirm" placeholder="請再次輸入密碼" />
                                        <input type="hidden" name="refer" value="member.php?action=login" />
                                        <input type="submit" name="submit" value="註冊" />
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <?php }else{ ?>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-center">
                            <h3 class="member-warn">您已經登入了</h3>
                            <a href="authentication.php?action=logout" class="btn btn-lg btn-info">按此登出</a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="copyright-w3layouts">
            <div class="container">
                <p>洛嬉遊戲，為造遊戲而生，為玩家利益而存。</p>
                <p>&copy; 2019 L.S. Games. All Rights NOT Reserved. | DO NOT PUBLISH THIS SITE TO ANY OTHER SERVER OR SERVICE.</p>
            </div>
        </div>
        <a href="#home" class="scroll toTop" style="display: block;"><img src="images/arr.png" class="toTop" /></a>
    </div>

    <!-- 搜尋列 -->
    <script src="js/main.js"></script>
    <script src="js/bootstrap.js"></script>
</body>

</html>