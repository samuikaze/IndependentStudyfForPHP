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
    $sql = mysqli_query($connect, "SELECT `bbspost`.*, `bbsarticle`.* FROM `bbspost` LEFT OUTER JOIN `bbsarticle` ON `bbsarticle`.`articlePost`=`bbspost`.`postID` WHERE `bbspost`.`postID`=$postid;");
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
<?php }else{ 
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
    foreach($row as $i => $val){
        // 第一次跑迴圈要處理主貼文和第一則回覆貼文
        if($i == 0){
            // 主貼文者帳號
            $sqlcondition .= "'" . $val['postUserID'] . "'";
            array_push($temp, $val['postUserID']);
            // 第一則回覆者帳號
            if(!in_array($val['articleUserID'], $temp)){
                $sqlcondition .= ", '" . $val['articleUserID'] . "'";
                array_push($temp, $val['articleUserID']);
            }
        // 不是第一次
        }else{
            // 注意會有空值的問題（程式不知道為何會多做一次迴圈）
            if(!in_array($val['articleUserID'], $temp) && !empty($val['articleUserID'])){
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
    while($memberRow[$mem_i] = mysqli_fetch_array($sql, MYSQLI_ASSOC)){
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
    <?php } ?>
        <div class="dropdown pull-right">
            <a href="?action=replypost&id=<?php echo $postid; ?>" class="btn btn-success">回覆此文章</a>
        </div>
        <?php
        // 開始正式處理貼文，下面這行改用 foreach 實作，然後會員暱稱用迴圈取，當帳號相符就取Nickname
        // 注意不用在去資料庫撈資料了，上面都撈完了，剩下就是 PHP 程式處理就好
        foreach($row as $i => $val){
            if(empty($val)){
                break;
            }
            if($i == 0){
                // 取得這篇主貼文的使用者暱稱
                foreach($memberRow as $j => $val_mem){
                    // 第一次才需要拿主貼文者的暱稱
                    if($j == 0){
                        if($val_mem['userName'] == $val['postUserID']){
                            $postuid = $val_mem['userNickname'];
                        }else{
                            continue;
                        }
                        if($val_mem['userName'] == $val['articleUserID']){
                            $articleuid = $val_mem['userNickname'];
                        }else{
                            continue;
                        }
                    // 第二次開始只要找回文者的暱稱就好
                    }else{
                        if($val_mem['userName'] == $val['articleUserID']){
                            $articleuid = $val_mem['userNickname'];
                        }else{
                            continue;
                        }
                    }
                    
                } ?>
            <div class="col-xs-12 col-sm-12 col-md-12 articles">
                <!-- 主貼文開始 -->
                <div class="col-xs-12 col-sm-12 col-md-2 noPadding">
                    <div class="postUser">
                        <div class="row">
                            <div class="col-md-12 col-xs-6 col-sm-6"><img src="images/bbs/exampleAvator.jpg" class="img-responsive avator" /></div>
                            <div class="col-md-12 col-xs-6 col-sm-6">
                                <h3><?php echo $postuid; ?></h3>
                                <!--<p>等級: 100</p>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-10 noPadding">
                    <div class="post">
                        <div class="postControl">
                            <span class="pull-left">#0&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo $val['postTime']; ?></span>
                            <span><?php echo (!empty($_SESSION['uid']) && $val['postUserID'] == $_SESSION['uid'])? "<a class=\"post-link\" href=\"?action=editpost&type=post&id=" . $val['postID'] . "\">編輯</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class=\"post-link\" href=\"?action=delpost&type=post&id=" . $val['postID'] . "&refbid=$refbid&refpage=$refpage&refpostid=" . $_GET['postid'] . "\">刪除</a>&nbsp;&nbsp;|&nbsp;&nbsp;" : "";?>大 中 小</span>
                        </div>
                        <?php echo (!empty($val['postTitle']))? "<h2 class=\"postTitle\">" . $val['postTitle'] . "</h2><hr class=\"postHR\" />" : ""; ?><p class="postContent"><?php echo $val['postContent']; ?></p>
                    </div>
                </div>
            </div> <!-- 主貼文結束 -->
            <?php if(!empty($val['articleContent'])){ ?>
            <div class="col-xs-12 col-sm-12 col-md-12 articles">
                <!-- 第一則回文開始 -->
                <div class="col-xs-12 col-sm-12 col-md-2 noPadding">
                    <div class="postUser">
                        <div class="row">
                            <div class="col-md-12 col-xs-6 col-sm-6"><img src="images/bbs/exampleAvator.jpg" class="img-responsive avator" /></div>
                            <div class="col-md-12 col-xs-6 col-sm-6">
                                <h3><?php echo $articleuid; ?></h3>
                                <!--<p>等級: 100</p>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-10 noPadding">
                    <div class="post">
                        <div class="postControl">
                            <span class="pull-left">#<?php echo $i + 1; ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo $val['articleTime']; ?></span>
                            <span><?php echo (!empty($_SESSION['uid']) && $val['articleUserID'] == $_SESSION['uid'])? "<a class=\"post-link\" href=\"?action=editpost&type=article&id=" . $val['articleID'] . "\">編輯</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class=\"post-link\" href=\"?action=delpost&type=article&id=" . $val['articleID'] . "&refbid=$refbid&refpage=$refpage&refpostid=" . $_GET['postid'] . "\">刪除</a>&nbsp;&nbsp;|&nbsp;&nbsp;" : "";?>大 中 小</span>
                        </div>
                        <?php echo (!empty($val['articleTitle']))? "<h2 class=\"postTitle\">" . $val['articleTitle'] . "</h2><hr class=\"postHR\" />" : ""; ?><p class="postContent"><?php echo $val['articleContent']; ?></p>
                    </div>
                </div>
            </div> <!-- 第一則回文結束 -->
            <?php }
         }else{ 
            foreach($memberRow as $j => $val_mem){
                // 拿回文者的暱稱
                if($val_mem['userName'] == $val['articleUserID']){
                    $articleuid = $val_mem['userNickname'];
                }else{
                    continue;
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
                            <!--<p>等級: 100</p>-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-10 noPadding">
                <div class="post">
                    <div class="postControl">
                        <span class="pull-left">#<?php echo $i + 1; ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo $val['articleTime']; ?></span>
                        <span><?php echo (!empty($_SESSION['uid']) && $val['articleUserID'] == $_SESSION['uid'])? "<a class=\"post-link\" href=\"?action=editpost&type=article&id=" . $val['articleID'] . "\">編輯</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class=\"post-link\" href=\"?action=delpost&type=article&id=" . $val['articleID'] . "&refbid=$refbid&refpage=$refpage&refpostid=" . $_GET['postid'] . "\">刪除</a>&nbsp;&nbsp;|&nbsp;&nbsp;" : "";?>大 中 小</span>
                    </div>
                    <?php echo (!empty($val['articleTitle']))? "<h2 class=\"postTitle\">" . $val['articleTitle'] . "</h2><hr class=\"postHR\" />" : ""; ?><p class="postContent"><?php echo $val['articleContent']; ?></p>
                </div>
            </div>
        </div> <!-- 其它回文結束 -->
        <?php }
     } ?>
    </div>
</div>
    <?php } 
} ?>