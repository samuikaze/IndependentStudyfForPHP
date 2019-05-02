<?php
// 若沒有 GET 值就跳回後台首頁
if(empty($_SERVER['QUERY_STRING'])){
    header("Location: index.php?action=index");
    exit;
}else{
    if($_GET[''] == ''){
        
    }elseif($_POST[''] == ''){

    // 如果上述條件都不符合跳回後台首頁
    }else{
        header("Location: index.php?action=index");
        exit;
    }
}

?>