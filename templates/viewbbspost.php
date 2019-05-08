<?php
if (empty($_GET['bid'])) { ?>
    <h2 class="news-warn">找不到該則公告！<br /><a href="?action=viewboard" class="btn btn-lg btn-info">按此返回討論板一覽</a></h2>
    <?php mysqli_close($connect);
    exit;
} else {
    $bid = $_GET['bid'];
}
if(empty($_GET['pid'])){
    $pid = 1;
}else{
    $pid = $_GET['pid'];
}
$sql = mysqli_query($connect, "SELECT * FROM `bbspost` WHERE `postBoard`=$bid ORDER BY `postTime` DESC;");
$datarows = mysqli_num_rows($sql);
?>
<div class="container">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="container-fluid" style="margin: 5px 0;">
                <div class="dropdown pull-right">
                    <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">全部主題 <span class="caret"></span></button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="#">綜合討論</a></li>
                        <li><a href="#">板務公告</a></li>
                        <li><a href="#">攻略心得</a></li>
                        <li><a href="#">同人創作</a></li>
                    </ul>
                    <a href="?action=addnewpost" class="btn btn-success">張貼文章</a>
                </div>
            </div>
            <table class="table table-hover" style="vertical-align: middle;">
                <thead>
                    <tr class="info">
                        <th class="post-nums">文章數</th>
                        <th class="post-title">文章標題</th>
                        <th class="post-time">貼文時間</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){ 
                        $refer = urlencode("&bid=$bid&pid=$pid");?>
                        <tr>
                            <td class="post-nums text-left"><?php echo $datarows; ?></td>
                            <td class="post-title"><a href="?action=viewpostcontent&postid=<?php echo $row['postID']; ?>&refbid=<?php echo $bid; ?>&refpage=<?php echo $pid; ?>"><span class="badge badge-warning"><?php echo $row['postType']; ?></span> <?php echo $row['postTitle']; ?></a></td>
                            <td class="post-time"><?php echo $row['postUserID']; ?><br /><?php echo $row['postTime']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>