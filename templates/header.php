<div id="home" <?php echo ($self == 'index.php')? "class=\"banner\" style=\"background: black;\"" : "class=\"banner banner-load inner-banner\""; ?>>
    <header <?php echo ($self == 'index.php')? "id=\"headerForCalc\"" : "style=\"padding: 15px;\""; ?>>
        <?php if ($self == 'index.php'){ ?>
            <div class="header-wrap">
        <?php } ?>
        <div class="header-bottom-w3layouts">
            <div class="main-w3ls-logo">
                <a href="./">
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
                        <?php if(empty($_SERVER['QUERY_STRING'])){
                            $qs = "";
                        }else{
                            $qs = $_SERVER['QUERY_STRING'];
                            $qs = str_replace("&", "+", $qs);
                        }?>
                        <li><a <?php echo ($self == "about.php") ? "class=\"active\"" : "class=\"colorTran\""; ?> href="about.php">關於團隊</a></li>
                        <li><a <?php echo ($self == "news.php?" . $qs) ? "class=\"active\"" : "class=\"colorTran\""; ?> href="news.php">最新消息</a></li>
                        <li><a <?php echo ($self == "products.php") ? "class=\"active\"" : "class=\"colorTran\""; ?> href="products.php">作品一覽</a></li>
                        <li><a <?php echo ($self == "goods.php?" . $qs || $self == "userorder.php?" . $qs ) ? "class=\"active\"" : "class=\"colorTran\""; ?> href="goods.php">周邊產品</a></li>
                        <li><a <?php echo ($self == "bbs.php?" . $qs) ? "class=\"active\"" : "class=\"colorTran\""; ?> href="bbs.php">討論專區</a></li>
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
        <?php if($self == 'index.php') { ?>
        </div>
        <?php } ?>
    </header>
    <?php if($self == 'index.php'){ ?>
        <!-- 圖片輪播 v3 -->
        <div id="lsgames-index" class="carousel slide" data-ride="carousel">
            <!-- 底部指示器（小圓點） -->
            <ol class="carousel-indicators">
                <li data-target="#lsgames-index" data-slide-to="0" class="active"></li>
                <li data-target="#lsgames-index" data-slide-to="1"></li>
                <li data-target="#lsgames-index" data-slide-to="2"></li>
            </ol>

            <!-- 輪播項目 -->
            <div class="carousel-inner" role="listbox">
                <!-- 一個輪播項目 -->
                <div class="item active">
                    <img src="images/services2.jpg" class="carousel-img">
                    <div class="carousel-caption carousel-text">
                        輪播一
                    </div>
                </div>
                <!-- /一個輪播項目 -->
                <div class="item">
                    <img src="images/services1.jpg" class="carousel-img">
                    <div class="carousel-caption carousel-text">
                        輪播二
                    </div>
                </div>
                <div class="item">
                    <img src="images/g4.jpg" class="carousel-img">
                    <div class="carousel-caption carousel-text">
                        輪播三
                    </div>
                </div>
            </div>

            <!-- 左右控制項 -->
            <a class="left carousel-control" href="#lsgames-index" role="button" data-slide="prev">
                <!-- <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> -->
                <div class="carousel-control-arrow"><i class="fas fa-chevron-left"></i></div>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#lsgames-index" role="button" data-slide="next">
                <!-- <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> -->
                <div class="carousel-control-arrow"><i class="fas fa-chevron-right"></i></div>
                <span class="sr-only">Next</span>
            </a>
        </div>
    <?php } ?>
</div>