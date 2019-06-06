<?php
require_once "sessionCheck.php";
$self = basename(__FILE__);
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <title>洛嬉遊戲 L.S. Games</title>
    <?php include_once "templates/metas.php"; ?>
    <!-- 搜尋列 -->
    <script src="js/main.js"></script>
    <link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" property="" />
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
        <div class="about-section" id="about">
            <div class="container">
                <!-- 麵包屑 -->
                <ol class="breadcrumb">
                    <li class="thisPosition"><i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;洛嬉遊戲</li>
                    <?php include "templates/loginbutton.php"; ?>
                </ol>
                <h5 class="main-w3l-title">洛嬉遊戲</h5>
                <div class="about-top">
                    <h3 class="subheading-wthree">關於我們 About L.S. Games</h3>
                    <p class="paragraph-agileinfo">我們是一支新創的遊戲團隊，成員都對製作遊戲富含熱情，我們的目標是製作出「有創意」、「玩自由」及「能嬉笑」的遊戲。
                    </p>
                </div>
                <div class="about-main">
                    <div class="about-w3-left">
                        <div class="about-img">
                        </div>
                        <div class="about-bottom">
                            <p class="paragraph-agileinfo white-clr">儘管程式碼有上萬行，只要玩家盡興就好</p>
                        </div>
                    </div>

                    <div class="about-w3ls-right">
                        <h3 class="subheading-wthree">遊戲製作宗旨</h3>
                        <p class="paragraph-agileinfo">做為新創的遊戲團隊，我們為了向各位玩家呈上最有趣的內容，我們在製作上堅持：</p>
                        <ul>
                            <li><i class="far fa-check-square"></i> 劇情完整及可讀性</li>
                            <li><i class="far fa-check-square"></i> 美術衝擊性</li>
                            <li><i class="far fa-check-square"></i> 遊戲可玩性</li>
                            <li><i class="far fa-check-square"></i> 執行順暢性</li>
                            <li><i class="far fa-check-square"></i> 隱藏要素</li>
                        </ul>
                    </div>
                    <div class="clearfix"> </div>
                </div>
            </div>
        </div>
        <div class="services-section">
            <div class="services-grids">
                <div class="services-img1"></div>
                <div class="services-info top-services">
                    <h3 class="subheading-wthree">最新作品 Release</h3>
                    <p class="paragraph-agileinfo">標題未定，目前尚在製作中。</p>
                    <div class="header-top">
                        <h3 class="subheading-wthree white-clr">現在立刻預購！</h3>
                        <span>現在預購可享價格減半優惠！</span>
                        <ul class="form-buttons">
                            <li><a href="#" data-toggle="modal" data-target="#myModal3"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 預購</a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="testimonials-section">
                <div class="container">
                    <h5 class="main-w3l-title">最新消息 News</h5>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <table class="table table-hover newsTable">
                                <?php
                                    $newsSql = mysqli_query($connect, "SELECT * FROM `news` ORDER BY `newsOrder` DESC LIMIT 0, 3;");
                                    while($newsData = mysqli_fetch_array($newsSql, MYSQLI_ASSOC)){ ?>
                                <tr>
                                    <td><span class="badge <?php echo ($newsData['newsType'] == '一般')? "badge-success" : "badge-primary";?> newsbadge"><?php echo $newsData['newsType']; ?></span></td>
                                    <td><a href="<?php echo "news.php?action=viewcontent&nid=" . $newsData['newsOrder']; ?>"><?php echo $newsData['newsTitle']; ?></a></td>
                                    <td><?php echo $newsData['postTime']; ?></td>
                                </tr>
                                    <?php } ?>
                                <tr>
                                    <td colspan="3"><a class="btn btn-block btn-lg btn-info" href="news.php">我想知道更多消息！</a></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="services-grids">
                <div class="services-info bottom-services">
                    <h3 class="subheading-wthree">招募新血 Recruit</h3>
                    <p class="paragraph-agileinfo">只要您有以下條件，都歡迎加入我們的製作團隊，一起步上遊戲製作人生！</p>
                    <div class="serv-inner1">
                        <div class="serv-left">
                            <h6>不做遊戲就是廢人</h6>
                            <ul>
                                <li>決心堅定</li>
                                <li>非遊戲不可</li>
                            </ul>
                        </div>
                        <div class="serv-right">
                            <h6>覺得沒人做的比你好</h6>
                            <ul>
                                <li>能力堅強</li>
                                <li>有獨一無二的構想</li>
                            </ul>
                        </div>
                    </div>
                    <div class="serv-inner2">
                        <div class="serv-left">
                            <h6>能把自己的 BUG 把玩於掌間</h6>
                            <ul>
                                <li>當 BUG 不再是 BUG</li>
                                <li>DEBUG 隨心應手</li>
                                <li>把 BUG 當作遊戲</li>
                            </ul>
                        </div>
                        <div class="serv-right">
                            <h6>越是新鮮的題材越有興趣</h6>
                            <ul>
                                <li>勇於嘗鮮</li>
                                <li>不想落後於潮流</li>
                            </ul>
                        </div>
                    </div>
                    <div class="clearfix"> </div>
                </div>
                <div class="services-img2"></div>
                <div class="clearfix"> </div>
            </div>
        </div>
        <div class="footer-agileits-w3layouts">
            <div class="container">
                <div class="btm-logo-w3ls">
                    <h2><a href="index.html"><span class="fa fa-check-square-o" aria-hidden="true"></span>提出問題 Contact</a></h2>
                </div>
                <div class="subscribe-w3ls">
                    <h6>若有任何問題都可以藉由下面的表單告知我們！</h6>
                    <form action="#" method="post">
                        <div class="contact-form">
                            <input type="email" name="Email" placeholder="輸入您的 Email" required="">
                            <input type="text" name="nickName" placeholder="怎麼稱呼您呢？" required="">
                            <textarea row="9" name="contactContent" placeholder="請詳述您的問題" required=""></textarea>
                            <input type="submit" value="送出">
                        </div>
                        <div class="clearfix"> </div>
                    </form>
                </div>
                <div class="social-icons-agileits">
                    <ul>
                        <li><a href="#"><i class="fab fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fab fa-google-plus"></i></a></li>
                    </ul>
                </div>
                <div class="clearfix"> </div>
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


</body>

</html>