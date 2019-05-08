<?php
if($_GET['action'] == 'debug'){
    $sql = mysqli_query($connect, "SELECT * FROM `member` ORDER BY `uid` ASC;");
    $mem_i = 0;
    $memberRow = array();
    while($memberRow[$mem_i] = mysqli_fetch_array($sql, MYSQLI_ASSOC)){
        $mem_i += 1;
    }
    foreach($memberRow as $i => $val){
        //echo "$i, " . print_r($val) . "<br />\n\r";
    }
    echo $memberRow[0]['userNickname'];
}else{
// 上面是DEBUG用，用完可以刪除，包含此檔案最下方的 } 及 bbs.php 中的 action 條件式

if (empty($_GET['refpage'])) {
    $refpage = 1;
} else {
    $refpage = $_GET['refpage'];
}
if (empty($_GET['postid'])) { ?>
    <h2 class="news-warn">找不到這則文章！<br /><br />
        <div class="btn-group" role="group">
            <a class="btn btn-lg btn-info" onClick="javascript:history.back();">返回上一頁</a>
            <a href="bbs.php?action=viewboard" class="btn btn-lg btn-success">返回討論板列表</a>
        </div>
    </h2>
    <?php
} else {
    $postid = $_GET['postid'];
    $sql = mysqli_query($connect, "SELECT `bbspost`.*, `bbsarticle`.* FROM `bbspost` RIGHT OUTER JOIN `bbsarticle` ON `bbsarticle`.`articlePost`=`bbspost`.`postID` WHERE `bbspost`.`postID`=$postid;");
    $datarows = mysqli_num_rows($sql);
    if ($datarows == 0) { ?>
        <h2 class="news-warn">找不到這則文章！<br /><br />
            <div class="btn-group" role="group">
                <a class="btn btn-lg btn-info" onClick="javascript:history.back();">返回上一頁</a>
                <a href="bbs.php?action=viewboard" class="btn btn-lg btn-success">返回討論板列表</a>
            </div>
        </h2>
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
    echo $sqlcondition;
    $sql = mysqli_query($connect, "SELECT * FROM `member` WHERE `userName` IN ($sqlcondition) ORDER BY `uid` ASC;");
    $mem_i = 0;
    $memberRow = array();
    // 處裡貼文暱稱問題
    while($memberRow[$mem_i] = mysqli_fetch_array($sql, MYSQLI_ASSOC)){
        $mem_i += 1;
    }
    ?>
<div class="container-fluid">
    <div class="row">
        <?php
        // 開始正式處理貼文，下面這行改用 foreach 實作，然後會員暱稱用迴圈取，當帳號相符就取Nickname
        // 注意不用在去資料庫撈資料了，上面都撈完了，剩下就是 PHP 程式處理就好
        while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) { ?>
            <div class="col-xs-12 col-sm-12 col-md-12 articles">
                <!-- 一個貼文 -->
                <div class="col-xs-12 col-sm-12 col-md-2 noPadding">
                    <div class="postUser">
                        <div class="row">
                            <div class="col-md-12 col-xs-6 col-sm-6"><img src="images/bbs/exampleAvator.jpg" class="img-responsive avator" /></div>
                            <div class="col-md-12 col-xs-6 col-sm-6">
                                <h3>使用者暱稱</h3>
                                <p>等級: 100</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-10 noPadding">
                    <div class="post">
                        <div class="postControl">
                            <span class="pull-left">2019/04/08 02:57</span>
                            <span>編輯 | 刪除 | 大 中 小</span>
                        </div>
                        <p>貼文內容貼文內容貼文內容貼文內容貼文內容<br />貼文內容貼文內容貼文內容貼文內容貼文內容<br />貼文內容貼文內容貼文內容貼文內容貼文內容<br />貼文內容貼文內容貼文內容貼文內容貼文內容</p>
                    </div>
                </div>
            </div> <!-- /一個貼文 -->
        <?php } ?>
        <div class="col-xs-12 col-sm-12 col-md-12 articles">
            <!-- 一個貼文 -->
            <div class="col-xs-12 col-sm-12 col-md-2 noPadding">
                <div class="postUser">
                    <div class="row">
                        <div class="col-md-12 col-xs-6 col-sm-6"><img src="images/bbs/exampleAvator.jpg" class="img-responsive avator" /></div>
                        <div class="col-md-12 col-xs-6 col-sm-6">
                            <h3>使用者暱稱</h3>
                            <p>等級: 100</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-10 noPadding">
                <div class="post">
                    <div class="postControl">
                        <span class="pull-left">2019/04/08 02:57</span>
                        <span>編輯 | 刪除 | 大 中 小</span>
                    </div>
                    <p>貼文內容貼文內容貼文內容貼文內容貼文內容<br />貼文內容貼文內容貼文內容貼文內容貼文內容<br />貼文內容貼文內容貼文內容貼文內容貼文內容<br />貼文內容貼文內容貼文內容貼文內容貼文內容</p>
                </div>
            </div>
        </div> <!-- /一個貼文 -->
    </div>
</div>
    <?php } 
} 
}?>