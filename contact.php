<?php
require_once "sessionCheck.php";
$self = basename(__FILE__);
if (empty($_SERVER['QUERY_STRING']) != True) {
    $self .= "?" . $_SERVER['QUERY_STRING'];
    $self = str_replace("&", "+", $self);
}
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <title>連絡我們 | 洛嬉遊戲 L.S. Games</title>
    <?php include_once "templates/metas.php"; ?>
    <link href="css/style.min.css" rel="stylesheet" type="text/css" media="all" />
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
                    <li class="thisPosition">連絡我們</li>
                    <?php include "templates/loginbutton.php"; ?>
                </ol>
                <h5 class="main-w3l-title">讓我們知道你所遇到的問題！</h5>
                <div class="form-bg">
                    <form action="#" method="post">
                        <div class="col-md-6 contact-fields">
                            <input type="text" name="Name" placeholder="稱呼" required="">
                        </div>
                        <div class="col-md-6 contact-fields">
                            <input type="email" name="Email" placeholder="電子郵件" required="">
                        </div>
                        <div class="contact-fields">
                            <input type="text" name="Subject" placeholder="主題" required="">
                        </div>
                        <div class="contact-fields">
                            <textarea name="Message" placeholder="內容" required=""></textarea>
                        </div>
                        <input type="submit" value="送出">
                    </form>
                </div>
                <div class="contact-maps">
                    <h5 class="main-w3l-title">直接臨櫃詢問</h5>
                    <div class="col-md-5 add-left">
                        <p class="paragraph-agileinfo"><span>地址 : </span>臺南市官田區官田工業區工業路40號</p>
                        <p class="paragraph-agileinfo"><span>電話 : </span>(06)698-5945~50</p>
                        <p class="paragraph-agileinfo"><span>Email : </span><a href="mailto:example@gmail.com">example@gmail.com</a></p>
                    </div>
                    <div class="col-md-7 add-right">
                        <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3666.795133746354!2d120.3202832507034!3d23.21413551489199!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x346e87f572beb163%3A0x191f52e398135526!2z5Yq05YuV6YOo5Yq05YuV5Yqb55m65bGV572y6Zuy5ZiJ5Y2X5YiG572y!5e0!3m2!1sja!2stw!4v1560992757455!5m2!1sja!2stw"></iframe>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <?php include_once "templates/footer.php"; ?>
    </div>
</body>

</html>
<?php
mysqli_close($connect);
?>