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
    // 加入購物車
    if(!empty($_GET['action']) && $_GET['action'] == 'joincart'){
        // 商品識別碼為空
        if(empty($_POST['goodid'])){
            $result = 'errornogid';
            mysqli_close($connect);
            echo $result;
            exit;
        }elseif(!empty($_SESSION['cart']['checkoutstatus']) && $_SESSION['cart']['checkoutstatus'] == 'notcomplete'){
            $result = 'errorincheck';
            mysqli_close($connect);
            echo $result;
            exit;
        }else{
            if(empty($_SESSION['auth'])){
                mysqli_close($connect);
                exit;
            }
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
                    $order = "ORDER BY CASE `goodsOrder` WHEN $val THEN " . ($j + 1);
                }else{
                    $sqlStr .= " OR `goodsOrder`=$val";
                    $order .= " WHEN $val THEN " . ($j + 1);
                }
            }
            $order .= " END";
            // 取出目前購物車內項目的價格
            $prices = mysqli_query($connect, "SELECT `goodsOrder`, `goodsPrice` FROM `goodslist` WHERE $sqlStr $order");
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

    // 清除購物車
    }elseif(!empty($_GET['action']) && $_GET['action'] == 'clearcart'){
        unset($_SESSION['cart']);
        mysqli_close($connect);
        // 如果是從表單送出的話要重導向回原頁面
        if(!empty($_POST['identify']) && $_POST['identify'] == 'form'){
            header("Location: userorder.php?action=viewcart");
        // 如果是 AJAX 異步處理則只需要返回 0 就好
        }else{
            echo 0;
        }
        exit;

    // 變更購物車裡商品的數量
    }elseif(!empty($_GET['action']) && $_GET['action'] == 'changeGQty'){
        // 若商品編號為空
        if(empty($_POST['gid'])){
            // 檢查 SESSION 變數的值，這個值只要進購物車頁面就會重置為 false
            if(empty($_SESSION['cart']['view']['nogid']) || $_SESSION['cart']['view']['nogid'] != true){
                $_SESSION['cart']['view']['nogid'] = true;
                $result = json_encode(array('msg'=>'errornogid', 'erred'=>'false'));
            // 已經錯誤過就不要再 echo 錯誤訊息了
            }else{
                $result = json_encode(array('msg'=>'errornogid', 'erred'=>'true'));
            }
            mysqli_close($connect);
            echo $result;
            exit;
        }else{
            $_SESSION['cart']['view']['nogid'] = false;
            $gid = $_POST['gid'];
        }
        // 若數量為空或為負
        if(empty($_POST['qty']) || $_POST['qty'] < 0){
            $result = json_encode(array('msg'=>'errorqty'));
            mysqli_close($connect);
            echo $result;
            exit;
        }else{
            $qty = $_POST['qty'];
        }
        // 都正確就開始修改數量
        // 先找出目標商品 ID 在陣列的第幾項
        foreach($_SESSION['cart'][0] as $i => $val){
            // 若找到目標商品編號就直接修改數量並跳出迴圈
            if($val == $gid){
                $_SESSION['cart'][1][$i] = $qty;
                break;
            }
        }
        // 商品小計
        $rGoodsTotal = "小計：NT$ " . $_SESSION['cart'][1][$i] * $_POST['gPrice'];
        // 總額小計（先更新SESSION再指定給返回值）
        foreach($_SESSION['cart'][0] as $j => $val){
            if($j == 0){
                $sqlStr = "`goodsOrder`=$val";
                $order = "ORDER BY CASE `goodsOrder` WHEN $val THEN " . ($j + 1);
            }else{
                $sqlStr .= " OR `goodsOrder`=$val";
                $order .= " WHEN $val THEN " . ($j + 1);
            }
        }
        $order .= " END";
        // 取出目前購物車內項目的價格
        $prices = mysqli_query($connect, "SELECT `goodsOrder`, `goodsPrice` FROM `goodslist` WHERE $sqlStr $order");
        $total = 0;
        $k = 0;
        while($price = mysqli_fetch_array($prices, MYSQLI_ASSOC)){
            // 計算總價格
            $total += ($price['goodsPrice'] * $_SESSION['cart'][1][$k]);
            $k += 1;
        }
        // 將值存進 SESSION 變數方便之後 echo
        $_SESSION['cartTotal'] = $total;
        $rAjaxTotal = $_SESSION['cartTotal'];
        echo json_encode(array('msg'=>'success','gid'=>$gid, 'nTotal'=>$rGoodsTotal, 'ajaxTotal'=>$rAjaxTotal));
        mysqli_close($connect);
        exit;

    // 移除購物車項目
    }elseif(!empty($_GET['action']) && $_GET['action'] == 'removeitem'){
        // 若商品編號為空
        if(empty($_POST['gid'])){
            // 檢查 SESSION 變數的值，這個值只要進購物車頁面就會重置為 false
            if(empty($_SESSION['cart']['view']['rnogid']) || $_SESSION['cart']['view']['rnogid'] != true){
                $_SESSION['cart']['view']['rnogid'] = true;
                $result = json_encode(array('msg'=>'errornogid', 'erred'=>'false'));
            // 已經錯誤過就不要再 echo 錯誤訊息了
            }else{
                $result = json_encode(array('msg'=>'errornogid', 'erred'=>'true'));
            }
            mysqli_close($connect);
            echo $result;
            exit;
        }
        $gid = $_POST['gid'];
        // 找出要移除的商品在陣列的第幾個
        foreach($_SESSION['cart'][0] as $i => $val){
            // 如果找到了就從 SESSION 中移除並跳出迴圈
            if($val == $gid){
                $itemQty = $_SESSION['cart'][1][$i];
                unset($_SESSION['cart'][0][$i]);
                unset($_SESSION['cart'][1][$i]);
                // 移除值後讓陣列依舊能連續
                $_SESSION['cart'][0] = array_values($_SESSION['cart'][0]);
                $_SESSION['cart'][1] = array_values($_SESSION['cart'][1]);
                break;
            }
        }
        // 先取得該商品的單價
        $gPrice = mysqli_fetch_array(mysqli_query($connect, "SELECT `goodsPrice` FROM `goodslist` WHERE `goodsOrder`=$gid;"), MYSQLI_ASSOC);
        // 算出要移除的商品小計是多少
        $origPrice = $gPrice['goodsPrice'] * $itemQty;
        // 更新總額
        $_SESSION['cartTotal'] -= $origPrice;
        // 檢查購物車還有沒有東西，若刪完為空則執行重置購物車以避免出錯
        if(sizeof($_SESSION['cart'][0]) == 0){
            unset($_SESSION['cart']);
        }
        // 把要返回的值指定給變數
        $cartTotal = $_SESSION['cartTotal'];
        $cartitemsqty = (empty($_SESSION['cart'][0])) ? 0 : sizeof($_SESSION['cart'][0]);
        // 返回值
        echo json_encode(array('msg'=>'removesuccess', 'gid'=>$gid, 'cartTotal'=>$cartTotal, 'itemsqty'=>$cartitemsqty));
        mysqli_close($connect);
        exit;

    // 已讀通知
    }elseif(!empty($_GET['action']) && $_GET['action'] == 'readnotify'){
        if(empty($_POST['notifyid'])){
            mysqli_close($connect);
            $result = json_encode(array('msg'=>'errornonotifyid'));
            echo $result;
            exit;
        }else{
            $notifyID = $_POST['notifyid'];
            mysqli_query($connect, "UPDATE `notifications` SET `notifyStatus`='r' WHERE `notifyID`=$notifyID;");
            // 取得通知數量
            $username = $_SESSION['uid'];
            $notifyQty = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `notifications` WHERE `notifyTarget`='$username' AND `notifyStatus`='u';"));
            mysqli_close($connect);
            if(!empty($_POST['isgoto']) && $_POST['isgoto'] == 'true'){
                $result = json_encode(array('msg'=>'updatesuccess'));
                echo $result;
            }else{
                $result = json_encode(array('msg'=>'noisgoto', 'nqty'=>$notifyQty));
                echo $result;
            }
            exit;
        }

    // 刪除通知
    }elseif(!empty($_GET['action']) && $_GET['action'] == 'removenotify'){
        // 通知 ID 為空
        if(empty($_POST['nid'])){
            $result = json_encode(array('msg'=>'errornonotifyid'));
            echo $result;
        }else{
            $nid = $_POST['nid'];
            // 刪除資料
            mysqli_query($connect, "DELETE FROM `notifications` WHERE `notifyID`=$nid;");
            // 取得剩餘通知筆數
            $username = $_SESSION['uid'];
            $notifyRows = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `notifications` WHERE `notifyTarget`='$username';"));
            // 取得未讀通知筆數
            $notifyNotReadRows = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `notifications` WHERE `notifyTarget`='$username' AND `notifyStatus`='u';"));
            mysqli_close($connect);
            $result = array('msg'=>'deletesuccess', 'notifynrqty'=>$notifyNotReadRows, 'notifyqty'=>$notifyRows);
            echo json_encode($result);
            exit;
        }

    // 已讀所有通知
    }elseif(!empty($_GET['action']) && $_GET['action'] == 'readallnotify'){
        // 若 POST 傳值為空
        if(empty($_POST['readallnotify']) || $_POST['readallnotify'] != 'true'){
            mysqli_close($connect);
            $result = array('msg'=>'forbidden');
            echo json_encode($result);
            exit;
        }else{
            $username = $_SESSION['uid'];
            mysqli_query($connect, "UPDATE `notifications` SET `notifyStatus`='r' WHERE `notifyTarget`='$username';");
            mysqli_close($connect);
            $result = array('msg'=>'readallsuccess', 'nqty'=>0);
            echo json_encode($result);
            exit;
        }

    // 移除所有通知
    }elseif(!empty($_GET['action']) && $_GET['action'] == 'removeallnotify'){
        // 若 POST 傳值為空
        if(empty($_POST['removeallnotify']) || $_POST['removeallnotify'] != 'true'){
            mysqli_close($connect);
            $result = array('msg'=>'forbidden');
            echo json_encode($result);
            exit;
        }else{
            $username = $_SESSION['uid'];
            mysqli_query($connect, "DELETE FROM `notifications`WHERE `notifyTarget`='$username';");
            mysqli_close($connect);
            $result = array('msg'=>'readallsuccess', 'nqty'=>0);
            echo json_encode($result);
            exit;
        }
    }
}
?>