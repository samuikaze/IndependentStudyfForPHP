<?php
// 上面是DEBUG用，用完可以刪除，包含此檔案最下方的 } 及 bbs.php 中的 action 條件式
if (empty($_GET['postid'])) { ?>
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title">錯誤</h3>
        </div>
        <div class="panel-body text-center">
            <h2 class="news-warn">找不到這則文章！<br /><br />
                <div class="btn-group" role="group">
                    <a class="btn btn-lg btn-info" onClick="javascript:history.back();">返回上一頁</a>
                    <a href="bbs.php?action=viewboard" class="btn btn-lg btn-success">返回討論板列表</a>
                </div>
            </h2>
        </div>
    </div>
<?php
} else {
    $postid = $_GET['postid'];
    if (empty($_GET['p'])) {
        $page = 1;
    } else {
        $page = $_GET['p'];
    }
    // 一頁顯示 10 則貼文
    $ppp = 10;
    $llimit = ($page - 1) * $ppp;   //SQL 用，左極限
    $rlimit = $page * $ppp;         //SQL 用，右極限
    $sql = mysqli_query($connect, "SELECT `bbspost`.*, `bbsarticle`.* FROM `bbspost` LEFT OUTER JOIN `bbsarticle` ON `bbsarticle`.`articlePost`=`bbspost`.`postID` WHERE `bbspost`.`postID`=$postid");
    $datarows = mysqli_num_rows($sql);
    if ($datarows == 0) { ?>
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">錯誤</h3>
            </div>
            <div class="panel-body text-center">
                <h2 class="news-warn">找不到這則文章！<br /><br />
                    <div class="btn-group" role="group">
                        <a class="btn btn-lg btn-info" onClick="javascript:history.back();">返回上一頁</a>
                        <a href="bbs.php?action=viewboard" class="btn btn-lg btn-success">返回討論板列表</a>
                    </div>
                </h2>
            </div>
        </div>
    <?php } else {
    $data_i = 0;
    $row = array();
    // 先把貼文資料都處理完
    while ($row[$data_i] = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
        $data_i += 1;
    }
    // 取得有貼文的會員資料
    $temp = array();    // 檢查用
    $sqlcondition = "";
    // 處理 SQL 語法中的條件式
    foreach ($row as $i => $val) {
        // 第一次跑迴圈要處理主貼文和第一則回覆貼文
        if ($i == 0) {
            // 主貼文者帳號
            $sqlcondition .= "'" . $val['postUserID'] . "'";
            array_push($temp, $val['postUserID']);
            // 第一則回覆者帳號
            if (!in_array($val['articleUserID'], $temp)) {
                $sqlcondition .= ", '" . $val['articleUserID'] . "'";
                array_push($temp, $val['articleUserID']);
            }
            // 不是第一次
        } else {
            // 注意會有空值的問題（程式不知道為何會多做一次迴圈）
            if (!in_array($val['articleUserID'], $temp) && !empty($val['articleUserID'])) {
                $sqlcondition .= ", '" . $val['articleUserID'] . "'";
                array_push($temp, $val['articleUserID']);
            }
        }
    }
    // 釋放暫存陣列
    unset($temp);
    $sql = mysqli_query($connect, "SELECT * FROM `member` WHERE `userName` IN ($sqlcondition) ORDER BY `uid` ASC;");
    $mem_i = 0;
    $memberRow = array();
    // 處裡貼文暱稱問題
    while ($memberRow[$mem_i] = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
        $mem_i += 1;
    }
    // 釋放不須使用的變數
    unset($sqlcondition);
    ?>
        <div class="container-fluid">
            <div class="row">
                <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'delarticlesuccess') { ?>
                    <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>刪除回文成功！</strong></h4>
                    </div>
                <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editpostsuccess') { ?>
                    <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>修改主貼文成功！</strong></h4>
                    </div>
                <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'editarticlesuccess') { ?>
                    <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>修改回文成功！</strong></h4>
                    </div>
                <?php } elseif (!empty($_GET['msg']) && $_GET['msg'] == 'addreplysuccess') { ?>
                    <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top: 1em;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4><strong>張貼回文成功！</strong></h4>
                    </div>
                <?php } ?>
                <div class="dropdown pull-right">
                    <a href="?action=replypost&postid=<?php echo $postid; ?>&refbid=<?php echo (empty($_GET['refbid'])) ? "" : $_GET['refbid']; ?>" class="btn btn-success">回覆此文章</a>
                </div>
                <?php
                // 開始正式處理貼文，下面這行改用 foreach 實作，然後會員暱稱用迴圈取，當帳號相符就取Nickname
                // 注意不用在去資料庫撈資料了，上面都撈完了，剩下就是 PHP 程式處理就好
                foreach ($row as $i => $val) {
                    if (empty($val)) {
                        break;
                    }

                    // 限制一頁顯示的筆數
                    // 先判斷一開始到底要不要開始印資料
                    if($i < $llimit){
                        continue;
                    }
                    // 在判斷要不要繼續印資料
                    if($i >= $rlimit){
                        break;
                    }

                    if ($i == 0) {
                        // 取得這篇主貼文的使用者暱稱
                        foreach ($memberRow as $j => $val_mem) {
                            if ($val_mem['userName'] == $val['postUserID']) {
                                $postuid = $val_mem['userNickname'];
                            }
                            if ($val_mem['userName'] == $val['articleUserID']) {
                                $articleuid = $val_mem['userNickname'];
                            }
                        } 
                        if($val['postStatus'] == 2 || $val['postStatus'] == 3){ ?>
                            <div class="alert alert-danger alert-dismissible fade in col-md-11" role="alert" style="margin-top: 1em;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4><strong>本討論文章已被鎖定！</strong></h4>
                            </div>
                        <?php } ?>
                        <div class="col-xs-12 col-sm-12 col-md-12 articles">
                            <!-- 主貼文開始 -->
                            <div class="col-xs-12 col-sm-12 col-md-2 noPadding flex-container">
                                <div class="postUser">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-6 col-sm-6"><img src="images/bbs/exampleAvator.jpg" class="img-responsive avator" /></div>
                                        <div class="col-md-12 col-xs-6 col-sm-6">
                                            <h3><?php echo $postuid; ?></h3>
                                            <h4 style="font-weight: normal;"><?php echo $val['postUserID']; ?></h4>
                                            <!--<p>等級: 100</p>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-10 noPadding">
                                <div class="post">
                                    <div class="postControl">
                                        <span class="pull-left">#0&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo $val['postTime']; ?><?php echo ($val['postStatus'] == 0) ? "" : "&nbsp;&nbsp;|&nbsp;&nbsp;此文章於 " . $val['postEdittime'] . " 被編輯"; ?></span>
                                        <span><?php echo (!empty($_SESSION['uid']) && $val['postUserID'] == $_SESSION['uid']) ? "<a class=\"post-link\" href=\"?action=editpost&type=post&id=" . $val['postID'] . "&refbid=$refbid&refpage=$refpage&refpostid=" . $_GET['postid'] . "\">編輯</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class=\"post-link\" href=\"?action=delpost&type=post&id=" . $val['postID'] . "&refbid=$refbid&refpage=$refpage&refpostid=" . $_GET['postid'] . "\">刪除</a>&nbsp;&nbsp;|&nbsp;&nbsp;" : ""; ?>大 中 小</span>
                                    </div>
                                    <?php echo (!empty($val['postTitle'])) ? "<h2 class=\"postTitle\">" . $val['postTitle'] . "</h2><hr class=\"postHR\" />" : ""; ?><p class="postContent"><?php echo $val['postContent']; ?></p>
                                </div>
                            </div>
                        </div> <!-- 主貼文結束 -->
                        <?php if (!empty($val['articleContent'])) { ?>
                            <div class="col-xs-12 col-sm-12 col-md-12 articles">
                                <!-- 第一則回文開始 -->
                                <div class="col-xs-12 col-sm-12 col-md-2 noPadding">
                                    <div class="postUser">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-6 col-sm-6"><img src="images/bbs/exampleAvator.jpg" class="img-responsive avator" /></div>
                                            <div class="col-md-12 col-xs-6 col-sm-6">
                                                <h3><?php echo $articleuid; ?></h3>
                                                <h4 style="font-weight: normal;"><?php echo $val['articleUserID']; ?></h4>
                                                <!--<p>等級: 100</p>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-10 noPadding">
                                    <div class="post">
                                        <div class="postControl">
                                            <span class="pull-left">#<?php echo $i + 1; ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo $val['articleTime']; ?><?php echo ($val['articleStatus'] == 0) ? "" : "&nbsp;&nbsp;|&nbsp;&nbsp;此回覆於 " . $val['articleEdittime'] . " 被編輯"; ?></span>
                                            <span><?php echo (!empty($_SESSION['uid']) && $val['articleUserID'] == $_SESSION['uid']) ? "<a class=\"post-link\" href=\"?action=editpost&type=article&id=" . $val['articleID'] . "&refbid=$refbid&refpage=$refpage&refpostid=" . $_GET['postid'] . "\">編輯</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class=\"post-link\" href=\"?action=delpost&type=article&id=" . $val['articleID'] . "&refbid=$refbid&refpage=$refpage&refpostid=" . $_GET['postid'] . "\">刪除</a>&nbsp;&nbsp;|&nbsp;&nbsp;" : ""; ?>大 中 小</span>
                                        </div>
                                        <?php echo (!empty($val['articleTitle'])) ? "<h2 class=\"postTitle\">" . $val['articleTitle'] . "</h2><hr class=\"postHR\" />" : ""; ?><p class="postContent"><?php echo $val['articleContent']; ?></p>
                                    </div>
                                </div>
                            </div> <!-- 第一則回文結束 -->
                        <?php }
                } else {
                    foreach ($memberRow as $j => $val_mem) {
                        // 拿回文者的暱稱
                        if ($val_mem['userName'] == $val['articleUserID']) {
                            $articleuid = $val_mem['userNickname'];
                        }
                    } ?>
                        <div class="col-xs-12 col-sm-12 col-md-12 articles">
                            <!-- 其它則回文開始 -->
                            <div class="col-xs-12 col-sm-12 col-md-2 noPadding">
                                <div class="postUser">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-6 col-sm-6"><img src="images/bbs/exampleAvator.jpg" class="img-responsive avator" /></div>
                                        <div class="col-md-12 col-xs-6 col-sm-6">
                                            <h3><?php echo $articleuid; ?></h3>
                                            <h4 style="font-weight: normal;"><?php echo $val['articleUserID']; ?></h4>
                                            <!--<p>等級: 100</p>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-10 noPadding">
                                <div class="post">
                                    <div class="postControl">
                                        <span class="pull-left">#<?php echo $i + 1; ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo $val['articleTime']; ?><?php echo ($val['articleStatus'] == 0) ? "" : "&nbsp;&nbsp;|&nbsp;&nbsp;此回覆於 " . $val['articleEdittime'] . " 被編輯"; ?></span>
                                        <span><?php echo (!empty($_SESSION['uid']) && $val['articleUserID'] == $_SESSION['uid']) ? "<a class=\"post-link\" href=\"?action=editpost&type=article&id=" . $val['articleID'] . "&refbid=$refbid&refpage=$refpage&refpostid=" . $_GET['postid'] . "\">編輯</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class=\"post-link\" href=\"?action=delpost&type=article&id=" . $val['articleID'] . "&refbid=$refbid&refpage=$refpage&refpostid=" . $_GET['postid'] . "\">刪除</a>&nbsp;&nbsp;|&nbsp;&nbsp;" : ""; ?>大 中 小</span>
                                    </div>
                                    <?php echo (!empty($val['articleTitle'])) ? "<h2 class=\"postTitle\">" . $val['articleTitle'] . "</h2><hr class=\"postHR\" />" : ""; ?><p class="postContent"><?php echo $val['articleContent']; ?></p>
                                </div>
                            </div>
                        </div> <!-- 其它回文結束 -->
                    <?php }
            } ?>
                <?php
                // 如果總筆數除以 $npp 筆大於 1，意即大於一頁
                $tpg = ceil($datarows / $ppp);
                if ($tpg > 1) { ?>
                    <!-- 頁數按鈕開始 -->
                    <div class="text-center">
                        <ul class="pagination">
                            <?php $pgnot1 = "href=\"?action=viewpostcontent&postid=" . $_GET['postid'] . "&p=" . ($page - 1) . ((empty($_GET['refbid']))? "" : "&refbid=" . $_GET['refbid']) . ((empty($_GET['refpage']))? "" : "&refpage=" . $_GET['refpage']) . "\"";
                            echo ($page == 1) ? "<li class=\"disabled\">" : "<li>"; ?><a <?php echo ($page == 1) ? "" : $pgnot1; ?> aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                            <?php
                            // 目前頁數
                            $i = 1;
                            // WHILE 運算不要改到原值
                            $pg = $tpg;
                            while ($pg > 0) { 
                                /* 如果這頁就是這顆按鈕就變顏色 */
                                $link = "href=\"?action=viewpostcontent&postid=" . $_GET['postid'] . "&p=$i" . ((empty($_GET['refbid']))? "" : "&refbid=" . $_GET['refbid']) . ((empty($_GET['refpage']))? "" : "&refpage=" . $_GET['refpage']) . "\"";
                                echo ($page == $i) ? "<li class=\"active\">" : "<li>"; ?><a <?php /* 如果是這頁就不印出連結 */ echo ($page == $i) ? "" : $link; ?>><?php echo $i; ?> <?php echo ($page == $i) ? "<span class=\"sr-only\">(current)</span>" : ""; ?></a></li>
                                <?php
                                $i += 1;
                                $pg -= 1;
                            }
                            ?>
                            <?php $pgnotlast = "href=\"?action=viewpostcontent&postid=" . $_GET['postid'] . "&p=" . ($page + 1) . ((empty($_GET['refbid']))? "" : "&refbid=" . $_GET['refbid']) . ((empty($_GET['refpage']))? "" : "&refpage=" . $_GET['refpage']) . "\"";
                            echo ($page == $tpg) ? "<li class=\"disabled\">" : "<li>"; ?><a <?php echo ($page == $tpg) ? "" : $pgnotlast; ?> aria-label="Next"><span aria-hidden="true">»</span></a></li>
                        </ul>
                    </div>
                    <!-- 頁數按鈕結束 -->
                <?php } ?>
            </div>
        </div>
    <?php }
} ?>