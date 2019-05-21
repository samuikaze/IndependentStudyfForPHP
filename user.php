<?php
require_once 'sessionCheck.php';
require_once 'templates/functions.php';
$self = basename(__FILE__);
if (empty($_SERVER['QUERY_STRING'])) {
    header("Location: ?action=usersetting");
    exit;
} elseif ($_GET['action'] != 'usersetting' && $_GET['action'] != 'sessioncontrol') {
    header("Location: ?action=usersetting");
    exit;
} else {
    $self .= "?" . $_SERVER['QUERY_STRING'];
}
// 如果沒有登入就跑進來的話就踢出去
if (empty($_SESSION['auth'])) {
    header("Location: member.php?action=login&loginErrType=5&refer=" . urlencode($self));
    exit;
} else {
    $username = $_SESSION['uid'];
    $datasql = mysqli_query($connect, "SELECT * FROM `member` WHERE `userName`='$username';");
    $sessionsql = mysqli_query($connect, "SELECT * FROM `sessions` WHERE `userName`='$username';");
    $datarow = mysqli_fetch_array($datasql, MYSQLI_ASSOC);
    $sessionRowNums = mysqli_num_rows($sessionsql);
    // 處理SESSION
    if ($sessionRowNums > 1) {
        $sess_i = 0;
        while ($sessionrow[$sess_i] = mysqli_fetch_array($sessionsql, MYSQLI_ASSOC)) {
            $sess_i += 1;
        }
    } else {
        $sessionrow = mysqli_fetch_array($sessionsql, MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <title>使用者帳號管理 | 洛嬉遊戲 L.S. Games</title>
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
        <div class="container">
            <!-- 麵包屑 -->
            <ol class="breadcrumb">
                <li><a href="index.html"><i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;洛嬉遊戲</a></li>
                <li class="thisPosition">使用者帳號管理</li>
                <?php include "templates/loginbutton.php"; ?>
            </ol>
            <div class="row">
                <div class="col-md-10 col-md-push-1">
                    <!-- 標籤 -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" <?php echo (!empty($_GET['action']) && $_GET['action'] == 'usersetting') ? " class=\"active\"" : ""; ?>><a href="#usersetting" aria-controls="usersetting" role="tab" data-toggle="tab">使用者資料</a></li>
                        <li role="presentation" <?php echo (!empty($_GET['action']) && $_GET['action'] == 'sessioncontrol') ? " class=\"active\"" : ""; ?>><a href="#sessioncontrol" aria-controls="sessioncontrol" role="tab" data-toggle="tab">登入階段管理</a></li>
                    </ul>
                    <!-- 內容 -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade<?php echo (!empty($_GET['action']) && $_GET['action'] == 'usersetting') ? " in active" : ""; ?>" id="usersetting">
                            <!-- 修改資料 -->
                            <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'usrseterremptypwdcnfrm') { ?>
                                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4><strong>如欲修改密碼請確實填妥密碼與確認密碼欄位！</strong></h4>
                                </div>
                            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'usersettingerrpwdcnfrm') { ?>
                                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4><strong>密碼與確認密碼欄位輸入不一致，請再重新輸入一次！</strong></h4>
                                </div>
                            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'usersettingerrfilesize') { ?>
                                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4><strong>上傳的檔案過大，請重新上傳！</strong></h4>
                                </div>
                            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'usersettingerrfiletype') { ?>
                                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4><strong>上傳的檔案類型錯誤！</strong></h4>
                                </div>
                            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'usrseterravatorupdel') { ?>
                                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4><strong>修改語刪除虛擬形象無法同時進行，請確定是要刪除還是修改！</strong></h4>
                                </div>
                            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'usrseterravatornodel') { ?>
                                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4><strong>目前並沒有虛擬形象可以刪除！</strong></h4>
                                </div>
                            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'usersettingsuccess') { ?>
                                <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4><strong>資料修改成功！</strong></h4>
                                </div>
                            <?php } ?>
                            <form action="actions.php?action=edituserdata" method="POST" enctype="multipart/form-data" style="margin-top: 1em;">
                                <div class="form-group">
                                    <label for="username">使用者名稱</label>
                                    <div class="col-sm-12">
                                        <p class="form-control-static"><?php echo $datarow['userName']; ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="useremail">使用者權限</label>
                                    <div class="col-sm-12">
                                        <p class="form-control-static"><?php echo priviledgeText($datarow['userPriviledge']); ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="avatorimage">虛擬形象</label>
                                    <div style="width: 100%; margin-bottom: 5px;"><img src="images/userAvator/<?php echo $datarow['userAvator']; ?>" id="nowimage" width="15%" /></div>
                                    <input type="file" id="avatorimage" name="avatorimage" />
                                    <p class="help-block">接受格式為 JPG、PNG、GIF，另虛擬形象只會顯示於討論區中</p>
                                </div>
                                <?php if($datarow['userAvator'] != 'exampleAvator.jpg'){ ?>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="delavatorimage" value="true" /> 刪除虛擬形象
                                        </label>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label for="password">密碼</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="如不修改請留空" />
                                </div>
                                <div class="form-group">
                                    <label for="passwordConfirm">確認密碼</label>
                                    <input type="password" name="passwordConfirm" class="form-control" id="passwordConfirm" placeholder="如不修改請留空" />
                                </div>
                                <div class="form-group">
                                    <label for="usernickname">暱稱</label>
                                    <input type="text" name="usernickname" class="form-control" id="usernickname" placeholder="請輸入欲修改的暱稱，如不修改請留空" value="<?php echo $datarow['userNickname']; ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="useremail">電子郵件</label>
                                    <input type="email" name="useremail" class="form-control" id="useremail" placeholder="請輸入欲修改的電子郵件，如不修改請留空" value="<?php echo $datarow['userEmail']; ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="userrealname">真實姓名</label>
                                    <input type="text" name="userrealname" class="form-control" id="userrealname" placeholder="請輸入您的真實姓名，此項目用於訂購商品用" value="<?php echo $datarow['userRealName']; ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="userphone">電話</label>
                                    <input type="text" name="userphone" class="form-control" id="userphone" placeholder="請輸入您的連絡電話，此項目用於訂購商品用" value="<?php echo $datarow['userPhone']; ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="useraddress">地址</label>
                                    <input type="text" name="useraddress" class="form-control" id="useraddress" placeholder="請輸入您的地址，此項目用於訂購商品用" value="<?php echo $datarow['userAddress']; ?>" />
                                </div>
                                <div class="form-group text-center">
                                    <input type="submit" name="submit" value="確認修改" class="btn btn-success" />
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane fade<?php echo (!empty($_GET['action']) && $_GET['action'] == 'sessioncontrol') ? " in active" : ""; ?>" id="sessioncontrol">
                            <?php
                            // 如果 SESSION 數量大於一筆
                            if ($sessionRowNums > 1) {
                                // 宣告目標索引值
                                $targetIndex = 0;
                                // 循環找出目前的 SESSION 並記下其索引值
                                foreach ($sessionrow as $i => $val) {
                                    if ($val['sessionID'] == session_id()) {
                                        $targetIndex = $i;
                                        break;
                                    } else {
                                        continue;
                                    }
                                }
                                $sysID = $sessionrow[$targetIndex]['sID'];
                                $usebrowser = $sessionrow[$targetIndex]['useBrowser'];
                                if (!empty($sessionrow[$targetIndex]['ipRmtAddr'])) {
                                    $loginIP = $sessionrow[$targetIndex]['ipRmtAddr'];
                                } elseif (!empty($sessionrow[$targetIndex]['ipXFwFor'])) {
                                    $loginIP = $sessionrow[$targetIndex]['ipXFwFor'];
                                } elseif (!empty($sessionrow[$targetIndex]['ipHttpVia'])) {
                                    $loginIP = $sessionrow[$targetIndex]['ipHttpVia'];
                                } else {
                                    $loginIP = "您可能使用 VPN 或 Proxy 瀏覽本網站，系統無法取得您的 IP";
                                }
                                $llogintime = $sessionrow[$targetIndex]['loginTime'];
                                // 如果 SESSION 數量為一筆
                            } else {
                                $sysID = $sessionrow['sID'];
                                $usebrowser = $sessionrow['useBrowser'];
                                if(!empty($sessionrow['ipHTTPCIP'])){
                                    $loginIP = $sessionrow['ipHTTPCIP'];
                                } elseif (!empty($sessionrow['ipXFwFor'])) {
                                    $loginIP = $sessionrow['ipXFwFor'];
                                } elseif (!empty($sessionrow['ipRmtAddr'])) {
                                    $loginIP = $sessionrow['ipRmtAddr'];
                                } elseif (!empty($sessionrow['ipHttpVia'])) {
                                    $loginIP = $sessionrow['ipHttpVia'];
                                } else {
                                    $loginIP = "您可能使用 VPN 或 Proxy 瀏覽本網站，系統無法取得您的 IP";
                                }
                                $llogintime = $sessionrow['loginTime'];
                            }
                            ?>
                            <!-- Session 管理 -->
                            <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'delsessionerrsid') { ?>
                                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4><strong>未指定登入階段的識別碼，請依正常程序終止登入階段！</strong></h4>
                                </div>
                            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'delsessionerrnodata') { ?>
                                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4><strong>找不到該登入階段，請依正常程序終止登入階段！</strong></h4>
                                </div>
                            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'delsessionerroperator') { ?>
                                <div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4><strong>該登入階段身分與您登入之身分不符，請依正常程序終止登入階段！</strong></h4>
                                </div>
                            <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'delsessionsuccess') { ?>
                                <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4><strong>該登入階段登出成功！</strong></h4>
                                </div>
                            <?php } ?>
                            <div class="panel panel-success" style="margin-top: 1em;">
                                <div class="panel-heading">
                                    <h3 class="panel-title">您目前的登入階段</h3>
                                </div>
                                <div class="panel-body">
                                    <div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label sessionCtrlTable">系統 ID</label>
                                            <div class="col-sm-10">
                                                <p class="form-control-static"><?php echo $sysID; ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label sessionCtrlTable">使用瀏覽器</label>
                                            <div class="col-sm-10">
                                                <p class="form-control-static"><?php echo $usebrowser; ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label sessionCtrlTable">登入 IP</label>
                                            <div class="col-sm-10">
                                                <p class="form-control-static"><?php echo $loginIP; ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label sessionCtrlTable">最後登入時間</label>
                                            <div class="col-sm-7">
                                                <p class="form-control-static"><?php echo $llogintime; ?></p>
                                            </div>
                                            <div class="col-sm-3 text-right">
                                                <a href="member.php?action=logout" class="btn btn-danger btn-block">登出我的瀏覽階段</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($sessionRowNums > 1) { ?>
                                <table class="table table-hover session-table">
                                    <thead>
                                        <tr class="warning">
                                            <td class="ss-id ss-thead"><strong>系統 ID</strong></td>
                                            <td class="ss-other ss-thead"><strong>登入 IP</strong></td>
                                            <td class="ss-other ss-thead"><strong>使用瀏覽器</strong></td>
                                            <td class="ss-other ss-thead"><strong>登入時間</strong></td>
                                            <td class="ss-operate ss-thead"><strong>操作</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($sessionrow as $j => $val) {
                                            if ($val['sessionID'] == session_id() || empty($val)) {
                                                continue;
                                            } else {
                                                $sysID = $sessionrow[$j]['sID'];
                                                $usebrowser = $sessionrow[$j]['useBrowser'];
                                                if (!empty($sessionrow[$j]['ipRmtAddr'])) {
                                                    $loginIP = $sessionrow[$j]['ipRmtAddr'];
                                                } elseif (!empty($sessionrow[$j]['ipXFwFor'])) {
                                                    $loginIP = $sessionrow[$j]['ipXFwFor'];
                                                } elseif (!empty($sessionrow[$j]['ipHttpVia'])) {
                                                    $loginIP = $sessionrow[$j]['ipHttpVia'];
                                                } else {
                                                    $loginIP = "系統無法取得 IP";
                                                }
                                                $llogintime = $sessionrow[$j]['loginTime'];
                                                ?>
                                                <tr>
                                                    <td class="ss-id"><?php echo $sysID; ?></td>
                                                    <td class="ss-other"><?php echo $loginIP; ?></td>
                                                    <td class="ss-other"><?php echo $usebrowser; ?></td>
                                                    <td class="ss-other"><?php echo $llogintime; ?></td>
                                                    <td class="ss-operate"><a href="actions.php?action=delsession&sid=<?php echo $sysID; ?>" class="btn btn-warning btn-block">登出此階段</a></td>
                                                </tr>
                                            <?php }
                                    } ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once "templates/footer.php"; ?>
    </div>
</body>

</html>