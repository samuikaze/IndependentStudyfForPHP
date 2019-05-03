<?php
    // 將 BR 轉換回 New Line
    function br2nl($string){
        return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
    }
?>