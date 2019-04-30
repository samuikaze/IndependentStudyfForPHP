<?php
    require_once 'sessionCheck.php';
    if(empty($_GET['action'])){
        header("Location: member.php?action=login");
        exit;
    }elseif($_GET['action'] == 'logout'){
        if( isset($_GET['refer']) == True ){
            $refer = $_GET['refer'];
        }else{
            $refer = "index.html";
        }
        header("Location: authentication.php?action=logout&refer=$refer");
        exit;
    }
    
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <title>會員專區 | 洛嬉遊戲 L.S. Games</title>
    <?php include_once "templates/metas.php"; ?>
</head>
<body onload="loadProgress()">
    <!-- 要加入載入動畫這邊請加上 onload="loadProgress()" -->
    <?php include_once "templates/loadscr.php"; ?>
    <div class="pageWrap">
        <?php include_once "templates/header.php"; ?>
        <div class="courses">
            <div class="container">
                <!-- 麵包屑 -->
                <ol class="breadcrumb">
                    <li><a href="index.html"><i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;洛嬉遊戲</a></li>
                    <li class="thisPosition">會員首頁</li>
                    <?php if (isset($_COOKIE['sid']) == True){ ?>
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
        <?php include_once "templates/footer.php"; ?>
    </div>
</body>
</html>