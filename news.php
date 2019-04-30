<?php
    require_once "sessionCheck.php";
    $self = basename(__FILE__);
    if(empty($_SERVER['QUERY_STRING']) != True){
        $self .= "?" . $_SERVER['QUERY_STRING'];
    }
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <title>最新消息 | 洛嬉遊戲 L.S. Games</title>
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
                    <li class="thisPosition">最新消息</li>
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
                                <li><a href="member.php?action=logout&refer=<?php echo $self; ?>">登出</a></li>
                            </ul>
                        </div>
                    <?php } ?>
                </ol>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-10" style="float:unset; margin: 0 auto;">
                            <!-- 分類按鈕開始（外層置中用） -->
                            <ul class="nav nav-tabs">
                                <li role="presentation" class="active"><a href="#">全部</a></li>
                                <li role="presentation"><a href="#">一般</a></li>
                                <li role="presentation"><a href="#">活動</a></li>
                            </ul><br />
                            <!-- 分類按鈕結束 -->
                            <!-- 消息面板開始 -->
                            <table id="news" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>類型</th>
                                        <th>標題</th>
                                        <th>發佈時間</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="newsType"><span class="badge badge-primary">一般</span></td>
                                        <td><a href="#">消息標題一</a>&nbsp;&nbsp;<span class="badge badge-warning">new!</span></td>
                                        <td class="releaseTime">2019/04/02</td>
                                    </tr>
                                    <tr>
                                        <td class="newsType"><span class="badge badge-success">活動</span></td>
                                        <td><a href="#">消息標題二</a>&nbsp;&nbsp;<span class="badge badge-warning">new!</span></td>
                                        <td class="releaseTime">2019/03/20</td>
                                    </tr>
                                    <tr>
                                        <td class="newsType"><span class="badge badge-success">活動</span></td>
                                        <td class="newsTitle"><a href="#">消息標題三</a></td>
                                        <td class="releaseTime">2019/03/04</td>
                                    </tr>
                                    <tr>
                                        <td class="newsType"><span class="badge badge-primary">一般</span></td>
                                        <td><a href="#">消息標題四</a></td>
                                        <td class="releaseTime">2019/02/15</td>
                                    </tr>
                                    <tr>
                                        <td class="newsType"><span class="badge badge-primary">一般</span></td>
                                        <td><a href="#">消息標題五</a></td>
                                        <td class="releaseTime">2019/02/10</td>
                                    </tr>
                                    <tr>
                                        <td class="newsType"><span class="badge badge-primary">一般</span></td>
                                        <td><a href="#">消息標題六</a></td>
                                        <td class="releaseTime">2019/01/01</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- 消息面板結束 -->
                            <!-- 頁數按鈕開始 -->
                            <div class="text-center">
                                <ul class="pagination">
                                    <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                                    <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#">5</a></li>
                                    <li><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
                                </ul>
                            </div>
                            <!-- 頁數按鈕結束 -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once "templates/footer.php"; ?>
    </div>
</body>
</html>