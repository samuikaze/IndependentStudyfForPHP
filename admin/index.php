<?php
$type = "important";
require "../sessionCheck.php";
if($_SESSION['priv'] != 99){
    header("Location: ../member.php?action=logout&refer=member.php?action=relogin");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
<title>後台管理 | 洛嬉遊戲 L.S. Games</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<script type="text/javascript" src="../js/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.min.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="js/custom.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.min.css" type="text/css" />
<link rel="stylesheet" href="../css/bootstrap.css" type="text/css" media="all" />
<link rel="stylesheet" href="../css/font-awesome.css" type="text/css" />
<link rel="stylesheet" href="css/custom.css" type="text/css" />
</head>
<body>
    <div class="container">
        <div class="row">
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="?action=index">洛嬉遊戲後台管理系統</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="active"><a href="?action=index">首頁</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">文章管理 <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">關於我們</a></li>
                                    <li><a href="?action=article_news">最新消息</a></li>
                                    <li><a href="?action=article_product">作品一覽</a></li>
                                    <li><a href="#">招募新血</a></li>
                                    <li><a href="#">常見問題</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">討論區管理 <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">註冊審核</a></li>
                                    <li><a href="#">會員管理</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">會員管理 <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">註冊審核</a></li>
                                    <li><a href="#">會員管理</a></li>
                                </ul>
                            </li>
                            <li><a href="#">商品管理</a></li>
                            <li><a href="../">離開後台</a></li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
        </div>
        <div class="row">
            <?php
                if(empty($_SERVER['QUERY_STRING']) || $_GET['action'] == 'index'){
                    include("frontpage.php");
                }elseif($_GET['action'] == 'article_news'){
                    include("article_news.php");
                }elseif($_GET['action'] == 'article_product'){
                    include("article_product.php");
                }
            ?>
        </div>
    </div>

</body>
</html>