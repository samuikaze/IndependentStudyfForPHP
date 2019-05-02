<?php if (isset($_COOKIE['sid']) == False) { ?>
    <a id="loginForm" class="btn btn-info pull-right">登入</a>
<?php } else { ?>
    <div class="dropdown pull-right" style="display: inline-block; ">
        <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <?php echo $_SESSION['user']; ?>
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu pull-right" aria-labelledby="dropdownMenu1">
            <li class="dropdown-header">使用者選單</li>
            <li><a>使用者設定（尚未完成）</a></li>
            <li><a href="member.php?action=logout&refer=<?php echo urlencode($self); ?>">登出</a></li>
            <?php echo ($_SESSION['priv'] == 99) ? "<li class=\"dropdown-header\">管理者選單</li>" : "";
            echo ($_SESSION['priv'] == 99) ? "<li><a href=\"admin/index.php\">後台管理</a>" : "";?>
        </ul>
    </div>
<?php } ?>