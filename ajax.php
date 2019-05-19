<?php
require "sessionCheck.php";
//檢查字串
function inputCheck($data){   //輸入字元安全性處理
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = nl2br($data);
    $data = preg_replace( "/\r|\n/", "", $data );
    $data = trim($data);
    return $data;
}
// 若沒有 GET 值就跳回首頁
if(empty($_SERVER['QUERY_STRING'])){
    mysqli_close($connect);
    header("Location: ./");
    exit;
}else{
    if(!empty($_GET['action']) && $_GET['action'] == 'joincart'){
        // 商品識別碼為空
        if(empty($_POST['goodid'])){
            $result = 'errornogid';
            mysqli_close($connect);
            echo $result;
            exit;
        }else{
            $gid = $_POST['goodid'];
            // 先判斷有沒有該項商品
            $ifgoods = mysqli_query($connect, "SELECT * FROM `goodslist` WHERE `goodsOrder`=$gid;");
            $ifgoodsRows = mysqli_num_rows($ifgoods);
            // 若沒有該項商品
            if($ifgoodsRows == 0){
                $result = 'errorgid';
                mysqli_close($connect);
                echo $result;
                // 要返回陣列的話要作 json_encode()
                exit;
            }
            // 先判斷之前有沒有購物車的資料
            $chk = (empty($_SESSION['cart']))? "" : $_SESSION['cart'];
            // 若購物車不為空
            if(!empty($chk)){
                // 購物車商品名
                $chkGoods = $chk[0];
                // 各商品數量
                $chkQty = $chk[1];
                // 若該商品已經存在於購物車內
                if(in_array($gid, $chkGoods)){
                    // 找出它在哪個 index
                    $i = array_search($gid, $chkGoods);
                    // 用上面的 index 更新數量
                    $chkQty[$i] += 1;
                    // 然後併回一個陣列後更新 SESSION 變數值
                    $_SESSION['cart'] = array(0=>$chkGoods, 1=>$chkQty);
                // 若不在購物車中
                }else{
                    // 將商品放入品項陣列
                    array_push($chkGoods, "$gid");
                    // 數量
                    array_push($chkQty, 1);
                    // 併回一個陣列後更新 SESSION 變數值
                    $_SESSION['cart'] = array(0=>$chkGoods, 1=>$chkQty);
                }
            // 若購物車為空
            }else{
                // 將商品放入品項陣列
                $cGoods = array(0=>$gid);
                // 數量
                $cQty = array(0=>1);
                // 併回一個陣列後更新 SESSION 變數值
                $_SESSION['cart'] = array(0=>$cGoods, 1=>$cQty);
            }
            // 處理取資料的 SQL 條件式
            foreach($_SESSION['cart'][0] as $j => $val){
                if($j == 0){
                    $sqlStr = "`goodsOrder`=$val";
                }else{
                    $sqlStr .= " OR `goodsOrder`=$val";
                }
            }
            // 取出目前購物車內項目的價格
            $prices = mysqli_query($connect, "SELECT `goodsOrder`, `goodsPrice` FROM `goodslist` WHERE $sqlStr ORDER BY `goodsOrder` ASC");
            //$temp = "SELECT `goodsOrder`, `goodsPrice` FROM `goodslist` WHERE $sqlStr ORDER BY `goodsOrder` ASC";
            $total = 0;
            $k = 0;
            while($price = mysqli_fetch_array($prices, MYSQLI_ASSOC)){
                // 計算總價格
                $total += ($price['goodsPrice'] * $_SESSION['cart'][1][$k]);
                $k += 1;
            }
            // 將值存進 SESSION 變數方便之後 echo
            $_SESSION['cartTotal'] = $total;
            // 向 AJAX 返回總價格
            echo $_SESSION['cartTotal'];
        }
        mysqli_close($connect);
        exit;
    }elseif(!empty($_GET['action']) && $_GET['action'] == 'clearcart'){
        unset($_SESSION['cart']);
        echo 0;
    }
}
?>