<?php
    // 將 BR 轉換回 New Line
    function br2nl($string){
        return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
    }

    // 判斷權限
    function priviledgeText($privNum){
        $result = "";
        if($privNum == 1){
            $result = "一般使用者";
        }elseif($privNum == 2){
            $result = "未定";
        }else{
            $result = "超級管理員";
        }
        return $result;
    }

    // 取得瀏覽器名稱
    function get_browser_name($user_agent){
        if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) {
            return 'Opera';
        } elseif (strpos($user_agent, 'Edge')) {
            return 'Microsoft Edge';
        } elseif (strpos($user_agent, 'Chrome')) {
            return 'Google Chrome';
        } elseif (strpos($user_agent, 'Safari')) {
            return 'Safari';
        } elseif (strpos($user_agent, 'Firefox')) {
            return 'Mozilla Firefox';
        } elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) {
            return 'Internet Explorer';
        } else {
            return '其它瀏覽器';
        }
        // Usage:
        //echo get_browser_name($_SERVER['HTTP_USER_AGENT']);
    }

    // 找出陣列項目，僅支援二維陣列
    /* 返回值
     * 當找到 $target 值時返回其 $index 值
     * 否則返回 false
     */
    function getItemFromArray($target, $arr){
        foreach($arr as $index => $insideArr){
            if(in_array($target, $insideArr)){
                return $index;
            }
        }
        return false;
    }

?>