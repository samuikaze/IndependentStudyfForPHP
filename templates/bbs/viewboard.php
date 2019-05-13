<?php if(!empty($_GET['msg']) && $_GET['msg'] == 'delpostsuccessnobid'){ ?>
<div class="alert alert-warning alert-dismissible fade in" role="alert" style="margin-top: 1em;">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4><strong>文章刪除成功，但因為無法識別討論板 ID ，故無法執行頁面跳轉。</strong></h4>
</div>
<?php } ?>
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