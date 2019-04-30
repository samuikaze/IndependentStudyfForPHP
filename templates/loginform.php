<!-- 登入與註冊開始 -->
<div class="hp_login" style="display: none;">
    <div class="login">
        <div class="container">
            <div id="login" class="col-md-4 text-center">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">登入</h3>
                    </div>
                    <div class="panel-body">
                        <form method="POST" action="authentication.php?action=login">
                            <div class="form-group"><input type="text" class="form-control" name="username" placeholder="請輸入帳號" /></div>
                            <div class="form-group"><input type="password" class="form-control" name="password" placeholder="請輸入密碼" /></div>
                            <input type="hidden" name="refer" value="<?php echo $self; ?>" />
                            <div class="text-center" style="margin: 10px auto 0 0;">
                                <input type="submit" class="btn btn-success" name="submit" value="登入" />
                                <input type="button" id="register" class="btn btn-info" name="register" value="註冊" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="hp_register" style="display: none;">
    <div class="register">
        <div class="container">
            <div id="reg" class="col-md-4 text-center">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">註冊</h3>
                    </div>
                    <div class="panel-body">
                        <form method="POST" action="authentication.php?action=register">
                            <div class="form-group"><input type="text" class="form-control" name="username" placeholder="請輸入帳號" /></div>
                            <div class="form-group"><input type="password" class="form-control" name="password" placeholder="請輸入密碼" /></div>
                            <div class="form-group"><input type="password" class="form-control" name="passwordConfirm" placeholder="請再次輸入密碼" /></div>
                            <div class="form-group"><input type="email" class="form-control" name="email" placeholder="請輸入電子信箱地址" /></div>
                            <div class="text-center" style="margin: 10px auto 0 0;">
                                <input type="submit" class="btn btn-success" name="submit" value="註冊" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 登入與註冊結束 -->