<div id="home" class="banner banner-load inner-banner">
    <header style="padding: 15px;">
        <div class="header-bottom-w3layouts">
            <div class="main-w3ls-logo">
                <a href="index.html">
                    <h1><img src="images/logo.png">洛嬉遊戲</h1>
                </a>
            </div>
            <nav class="navbar navbar-default">
                <!-- Brand and toggle get grouped for better mobile display -->
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
                        <?php if(empty($_SERVER['QUERY_STRING'])){
                            $qs = "";
                        }else{
                            $qs = $_SERVER['QUERY_STRING'];   
                        }?>
                        <li><a <?php echo ($self == "about.php") ? "class=\"active\"" : "class=\"colorTran\""; ?> href="about.php">關於團隊</a></li>
                        <li><a <?php echo ($self == "news.php?" . $qs) ? "class=\"active\"" : "class=\"colorTran\""; ?> href="news.php">最新消息</a></li>
                        <li><a <?php echo ($self == "products.php") ? "class=\"active\"" : "class=\"colorTran\""; ?> href="products.php">作品一覽</a></li>
                        <li><a <?php echo ($self == "goods.php") ? "class=\"active\"" : "class=\"colorTran\""; ?> href="goods.php">周邊產品</a></li>
                        <li><a <?php echo ($self == "bbs.php") ? "class=\"active\"" : "class=\"colorTran\""; ?> href="bbs.php">討論專區</a></li>
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