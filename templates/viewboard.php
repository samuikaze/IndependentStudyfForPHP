<!-- 討論版塊放置區 -->
<div class="row" style="margin-top: 0px; padding-top: 0px;">
    <?php 
    $sql = mysqli_query($connect, "SELECT * FROM `bbsboard` WHERE `boardHide`!=1 ORDER BY `boardID` ASC;");
    while($row = mysqli_fetch_array($sql, MYSQLI_BOTH)){
    ?>
    <div class="col-md-4 courses-info">
        <div class="thumbnail">
            <a href="#"><img src="images/bbs/board/<?php echo $row['boardImage']; ?>" style="width: 640px; height: 310px"></a>
            <div class="caption">
                <!--<p class="numbers fRight">文章數 <span>99,999</span></p>-->
                <h3 class="pull-left"><?php echo $row['boardName']; ?></h3>
                <p class="fLeft"><?php echo $row['boardDescript']; ?></p>
                <div class="clearfix"></div>
                <p class="text-center">
                    <div class="text-center" style="margin-bottom: 15px;">
                        <a href="bbs.php?action=viewbbspost&bid=<?php echo $row['boardID']; ?>" class="btn btn-block btn-warning">進入討論板</a>
                    </div>
                </p>
            </div>
        </div>
    </div>
    <?php } ?>
</div>