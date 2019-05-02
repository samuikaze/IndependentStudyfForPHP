<?php
// 若沒有 GET 值就跳回後台首頁
if(empty($_SERVER['QUERY_STRING'])){
    header("Location: index.php?action=index");
    exit;
}else{
    if($_GET['modifynews'] == ''){
        // 若消息標題為空
        if(empty($_POST['newsTitle'])){
            $refer = $_POST['refer'];
            header("Location: index.php?$refer");
            exit;
        // 若消息內容為空
        }elseif(empty($_POST['newsContent'])){

        // 都沒問題開始寫入資料
        }else{

        }
    }elseif($_POST[''] == ''){

    // 如果上述條件都不符合跳回後台首頁
    }else{
        header("Location: index.php?action=index");
        exit;
    }
}

?>