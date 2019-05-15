<?php
require_once 'sessionCheck.php';
if (empty($_GET['refer'])) {
    $refer = "/";
} elseif ($_GET['action'] == 'relogin') {
    $refer = "admin/" . $_GET['refer'];
} else {
    $refer = $_GET['refer'];
}
if (empty($_GET['action'])) {
    mysqli_close($connect);
    header("Location: member.php?action=login");
    exit;
} elseif ($_GET['action'] == 'logout') {
    mysqli_close($connect);
    header("Location: authentication.php?action=logout&refer=" . urlencode($refer));
    exit;
}
$self = basename(__FILE__);
if (empty($_SERVER['QUERY_STRING']) != True) {
    $self .= "?" . $_SERVER['QUERY_STRING'];
}
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <title>會員 Actions | 洛嬉遊戲 L.S. Games</title>
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
                    <?php echo (!empty($_GET['action']) && ($_GET['action'] == 'login' || $_GET['action'] == 'register')) ? "<li class=\"thisPosition\">登入 / 註冊</li>" : ""; ?>
                    <?php include "templates/loginbutton.php"; ?>
                </ol>
                <?php if (empty($_SESSION['auth']) || $_SESSION['auth'] != True) { ?>
                    <!-- 登入 / 註冊表單 -->
                    <div class="memberForm">
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <!-- Bootstrap 標籤頁 -->
                                <ul class="nav nav-tabs">
                                    <?php echo ($_GET['action'] == 'login' || $_GET['action'] == 'relogin') ? "<li class=\"active\">" : "<li>"; ?><a href="#login-form" data-toggle="tab">登入</a></li>
                                    <?php echo ($_GET['action'] == 'register') ? "<li class=\"active\">" : "<li>"; ?><a href="#register-form" data-toggle="tab">註冊</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade<?php echo ($_GET['action'] == 'login' || $_GET['action'] == 'relogin') ? " in active" : ""; ?>" id="login-form">
                                        <?php if (!empty($_GET['loginErrType']) && $_GET['loginErrType'] == 1) { ?>
                                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4><strong>使用者名稱欄位不能為空！</strong></h4>
                                            </div>
                                        <?php } elseif (!empty($_GET['loginErrType']) && $_GET['loginErrType'] == 2) { ?>
                                            <div class="alert alert-danger alert-dismissible" role="alert" style="margin-top: 1em;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4><strong>密碼欄位不能為空！</strong></h4>
                                            </div>
                                        <?php } elseif (!empty($_GET['loginErrType']) && $_GET['loginErrType'] == 3) { ?>
                                            <div class="alert alert-danger alert-dismissible" role="alert" style="margin-top: 1em;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4><strong>您輸入的使用者名稱或密碼有誤</strong></h4>
                                            </div>
                                        <?php } elseif (!empty($_GET['regErrType']) && $_GET['regErrType'] == 8) { ?>
                                            <div class="alert alert-success alert-dismissible" role="alert" style="margin-top: 1em;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4><strong>註冊成功，請利用下方表單登入帳號</strong></h4>
                                            </div>
                                        <?php } elseif ($_GET['action'] == 'relogin') {
                                        if (empty($_GET['loginErr'])) { ?>
                                                <div class="alert alert-danger alert-dismissible" role="alert" style="margin-top: 1em;">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4><strong>您的權限不足，請登入較高權限的帳號後再試一次！</strong></h4>
                                                </div>
                                            <?php } else { ?>
                                                <div class="alert alert-danger alert-dismissible" role="alert" style="margin-top: 1em;">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4><strong>該功能須登入後才可使用！</strong></h4>
                                                </div>
                                            <?php } ?>
                                        <?php } elseif (!empty($_GET['loginErrType']) && $_GET['loginErrType'] == 4) { ?>
                                            <div class="alert alert-danger alert-dismissible" role="alert" style="margin-top: 1em;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4><strong>您的登入資訊有誤，請重新登入！</strong></h4>
                                            </div>
                                        <?php } elseif (!empty($_GET['loginErrType']) && $_GET['loginErrType'] == 5) { ?>
                                            <div class="alert alert-danger alert-dismissible" role="alert" style="margin-top: 1em;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4><strong>該功能須登入後才可使用！</strong></h4>
                                            </div>
                                        <?php } elseif (!empty($_GET['type']) && $_GET['type'] == 'updatepwd') { ?>
                                            <div class="alert alert-success alert-dismissible" role="alert" style="margin-top: 1em;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4><strong>您的密碼更新成功，請重新登入驗證新密碼！</strong></h4>
                                            </div>
                                        <?php } else {
                                        echo "";
                                    } ?>
                                        <form method="POST" action="authentication.php?action=login" style="margin-top: 1em;">
                                            <div class="form-group text-left">
                                                <label for="username">使用者名稱</label>
                                                <input type="text" class="form-control" name="username" id="username" placeholder="請輸入使用者名稱" />
                                            </div>
                                            <div class="form-group text-left">
                                                <label for="password">密碼</label>
                                                <input type="password" class="form-control" name="password" placeholder="請輸入密碼" />
                                            </div>
                                            <input type="hidden" name="refer" value="<?php echo $refer; ?>" />
                                            <input type="submit" class="btn btn-success btn-lg" name="submit" value="登入" />
                                        </form>
                                    </div>
                                    <div class="tab-pane fade<?php echo ($_GET['action'] == 'register') ? " in active" : ""; ?>" id="register-form">
                                        <?php if (!empty($_GET['regErrType']) && $_GET['regErrType'] == 1) { ?>
                                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4><strong>帳號欄位不能為空！</strong></h4>
                                            </div>
                                        <?php } elseif (!empty($_GET['regErrType']) && $_GET['regErrType'] == 2) { ?>
                                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4><strong>密碼欄位不能為空！</strong></h4>
                                            </div>
                                        <?php } elseif (!empty($_GET['regErrType']) && $_GET['regErrType'] == 3) { ?>
                                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4><strong>確認密碼欄位不能為空！</strong></h4>
                                            </div>
                                        <?php } elseif (!empty($_GET['regErrType']) && $_GET['regErrType'] == 4) { ?>
                                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4><strong>電子郵件欄位不能為空！</strong></h4>
                                            </div>
                                        <?php } elseif (!empty($_GET['regErrType']) && $_GET['regErrType'] == 5) { ?>
                                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4><strong>密碼與確認密碼必須一致！</strong></h4>
                                            </div>
                                        <?php } elseif (!empty($_GET['regErrType']) && $_GET['regErrType'] == 6) { ?>
                                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4><strong>暱稱欄位不能為空！</strong></h4>
                                            </div>
                                        <?php } elseif (!empty($_GET['regErrType']) && $_GET['regErrType'] == 7) { ?>
                                            <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4><strong>該使用者名稱已被使用，請使用其它使用者名稱！</strong></h4>
                                            </div>
                                        <?php } else {
                                        echo "";
                                    } ?>
                                        <form method="POST" action="authentication.php?action=register" style="margin-top: 1em;">
                                            <div class="form-group text-left">
                                                <label for="username">使用者名稱</label>
                                                <input type="text" class="form-control" name="username" id="username" placeholder="請輸入使用者名稱" />
                                            </div>
                                            <div class="form-group text-left">
                                                <label for="usernickname">暱稱</label>
                                                <input type="text" class="form-control" name="usernickname" placeholder="請輸入您的暱稱" />
                                            </div>
                                            <div class="form-group text-left">
                                                <label for="password">密碼</label>
                                                <input type="password" class="form-control" name="password" placeholder="請輸入密碼" />
                                            </div>
                                            <div class="form-group text-left">
                                                <label for="passwordConfirm">確認密碼</label>
                                                <input type="password" class="form-control" name="passwordConfirm" placeholder="請再次輸入密碼" />
                                            </div>
                                            <div class="form-group text-left">
                                                <label for="email">電子郵件</label>
                                                <input type="email" class="form-control" name="email" placeholder="請輸入電子信箱地址" />
                                            </div>
                                            <input type="hidden" name="refer" value="member.php?action=login" />
                                            <input type="hidden" name="refer" value="<?php echo $refer; ?>" />
                                            <input type="submit" class="btn btn-success btn-lg" name="submit" value="註冊" />
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php } else { ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 col-xs-12 text-center">
                                <div class="panel panel-danger">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">錯誤</h3>
                                    </div>
                                    <div class="panel-body">
                                        <h2 class="news-warn">您已經登入了！<br /><br />
                                            <div class="btn-group" role="group">
                                                <?php echo "<a class=\"btn btn-lg btn-success\" onClick=\"javascript:history.back();\">返回上一頁</a>"; ?>
                                                <a href="authentication.php?action=logout" class="btn btn-lg btn-danger">按此登出</a>
                                            </div>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php include_once "templates/footer.php"; ?>
    </div>
</body>

</html>
<?php mysqli_close($connect); ?>