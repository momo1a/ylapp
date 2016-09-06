<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>用户登录</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/'?>bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/'?>dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/'?>plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
       <!-- <a href="#"><b>Admin</b>LTE</a>-->
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg"></p>
        <div class="callout callout-info" style="text-align: center;font-weight: bold;font-size: large;">移动医疗后台管理系统</div>
        <form action="" method="post" id="login">
            <div class="form-group has-feedback">
                <input type="text" class="form-control msg-hidden" placeholder="用户名" name="username" required value="<?php echo $username ? $username : '';?>">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control msg-hidden" placeholder="密码" name="password" required value="<?php echo $password ? $password : '';?>" id="pass">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="<?php if($msg){ echo 'alert alert-danger alert-dismissible';}?>" id="msg">
                <?php echo $msg;?>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember_pwd" value="1" <?php echo $remember ? "checked" : ""; ?>>&nbsp;&nbsp;&nbsp;记住密码
                        </label>
                    </div>
                </div>
                <input type="hidden" name="doAction" value="yes"/>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat" style="background: white;color: #000000;border: #808080 1px solid;border-radius: 5px">登录</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="<?php echo config_item('domain_static').'admin/'?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo config_item('domain_static').'admin/'?>bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo config_item('domain_static').'admin/'?>plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo config_item('domain_static').'admin/'?>validate/jquery.validate.min.js"></script>
<script src="<?php echo config_item('domain_static').'admin/'?>validate/messages_zh.js"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
        $('.msg-hidden').focus(function(){
            $("#msg").hide();
        });
    });
</script>
</body>
</html>
