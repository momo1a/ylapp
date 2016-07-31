<!doctype html>
<!--[if lt IE 7]><html class="ie ie6"><![endif]-->
<!--[if IE 7]><html class="ie ie7"><![endif]-->
<!--[if IE 8]><html class="ie ie8"><![endif]-->
<!--[if IE 9]><html class="ie ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html><!--<![endif]-->
<?php if($dialog == 1){/*详细页登录ifrane*/ ?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>登录众划算</title>
    <script>!function(e){if(!/\bie\b/i.test(e.className)){var a=navigator.userAgent.match(/\bMSIE (d+)/i);e.className+=a?" ie ie"+a[1]:" noie"}}(document.getElementsByTagName("html")[0]);</script>
    <base href="<?php echo config_item('domain_static'); ?>">
    <base target="_top">
	<?php change_to_minify("common/css/reset.css,common/css/function.css"); ?>
    <style>
        html{overflow-y: auto;}
        .m-login{ font: 12px/1.5 Tahoma, Helvetica, Arial, "\5FAE\8F6F\96C5\9ED1", sans-serif; padding: 10px 25px; color: #000; width: 250px; }
        .m-login a{ color: #666; text-decoration: none; }
        .m-login a:hover{ color: #d11414; text-decoration: underline; }
        .m-login .inp{ height: 190px; padding-top: 1px; }
        .m-login dt,.m-login dd{line-height: 30px;}
        .u-ipt{ width: 236px; height: 16px; line-height: 16px; padding: 6px; border: 1px solid #CDCDCD; outline: 0; color: #666; }
        .u-ipt:focus{ border-color: #FF818E; box-shadow: 0 0 4px rgba(204,0,0,.6); }
        .u-btn{ width: 250px; height: 30px; line-height: 16px; padding: 7px 0; border: 0; outline: 0; background-color: #D11414; color: #fff; font-size: 14px; cursor: pointer; border-radius: 3px; }
        .u-btn:hover{ background-color: #D94242; }
        .z-dis,.z-dis:hover{ background-color: #CDCDCD; cursor: default; }
        .u-icon{ vertical-align: middle; }
        .m-tip{ display: none; height: 16px; line-height: 16px; margin: 3px 0; padding: 3px; padding-left: 20px; overflow: hidden; border: 1px solid #FFAD77; background: #FFFFD0 url(common/img/icon/15x15_warnning.png) 3px 3px no-repeat; color: #FF0000;}
    </style>
</head>
<body>
    <div class="m-login">
        <form id="J_login" method="post" action="<?php echo site_url('tologin');?>" onsubmit="return false;" data-dialog="true">
            <div class="inp">
                <p class="m-tip J_tip"></p>
                <dl>
                    <dt>账号：</dt>
                    <dd><input type="text" class="u-ipt" name="account"></dd>
                </dl>
                <dl>
                    <dt>密码：</dt>
                    <dd><input type="password" class="u-ipt" name="password"></dd>
                </dl>
                <dl>
                    <dd>
                        <p class="f-fl">
                            <a href="<?php echo config_item('url_qq_login'); ?>"><img class="u-icon" src="common/img/icon/16x16_qq.png" width="16" height="16" alt="QQ">QQ登录</a>
                            <a href="<?php echo config_item('url_reg'); ?>" target="_blank" style="margin-left:5px">立即注册</a>
                        </p>
                        <a href="<?php echo config_item('domain_shikee_usercenter') ;?>findpwd/" target="_blank" class="f-fr">忘记密码？</a>
                    </dd>
                </dl>
            </div>
            <p><input type="submit" value="登 录" class="u-btn J_submit"></p>
        </form>
    </div>
    <?php change_to_minify("common/js/jquery.js,common/js/shs.js,login/home/js/iframe.js"); ?>
</body>
<?php }else{/*侧边栏登录ifrane*/?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>登录众划算</title>
    <script>!function(e){if(!/\bie\b/i.test(e.className)){var a=navigator.userAgent.match(/\bMSIE (d+)/i);e.className+=a?" ie ie"+a[1]:" noie"}}(document.getElementsByTagName("html")[0]);</script>
    <base href="<?php echo config_item('domain_static'); ?>">
	<?php change_to_minify("common/css/reset.css,common/css/function.css"); ?>
    <style>
        html{overflow-y: auto;}
        .m-login{ font: 12px/1.5 Tahoma, Helvetica, Arial, "\5FAE\8F6F\96C5\9ED1", sans-serif; padding: 10px 25px; color: #666; width: 230px; }
        .m-login .hd{ height: 35px; border-bottom: 2px solid #CDCDCD; }
        .m-login .hd span,.m-login .hd a{ font-size: 14px; display: block; float: left; height: 15px; line-height: 15px; padding: 10px 20px; color: #141414; border-bottom: 2px solid #F32C44; }
        .m-login .hd a{ color: #9B9999; padding: 0 17px; margin: 10px 0; border-bottom: 0; border-left: 1px solid #141414; }
        .m-login .hd a:hover{text-decoration: none;color: #d11414;}
        .m-login .bd{ padding-top: 15px; }
        .m-login .bd a{color: #00B1CB;}
        .m-login .bd a:hover{color: #00B1CB;text-decoration: underline;}
        .m-login .bd dl{margin-top: 20px;}
        .m-login .bd dt{ width: 50px; height: 16px; line-height: 16px; padding: 7px 0; float: left; }
        .m-login .bd dd{width: 180px;height: 30px;float: right;line-height: 30px;}
        .u-ipt{ width: 166px; height: 16px; line-height: 16px; padding: 6px; border: 1px solid #CDCDCD; outline: 0; color: #666; }
        .u-ipt:focus{ border-color: #FF818E; box-shadow: 0 0 4px rgba(204,0,0,.6); }
        .u-btn{ border: 0; display: block; width: 180px; height: 30px; background-color: #D11414; color: #fff; text-align: center; border-radius: 3px; outline: 0; cursor: pointer; }
        .u-btn:hover{background-color: #D94242;}
        .u-icon{ vertical-align: middle; }
        .z-dis,.z-dis:hover{ background-color: #CDCDCD; cursor: default; }
        .m-link{ text-align: right; margin-top: 10px; margin-bottom: -10px; }
        .m-tip{ display: none; height: 16px; line-height: 16px; padding: 3px; padding-left: 20px; overflow: hidden; border: 1px solid #FFAD77; background: #FFFFD0 url(common/img/icon/15x15_warnning.png) 3px 3px no-repeat; color: #FF0000; margin-top: -5px; margin-bottom: -10px; }
    </style>
</head>
<body>
    <div class="m-login">
        <div class="hd">
            <span class="f-fwb">登录</span>
            <a href="<?php echo config_item('url_reg'); ?>" target="_blank">注册</a>
            <a href="<?php echo config_item('url_qq_login'); ?>" target="_blank"><img class="u-icon" src="common/img/icon/16x16_qq.png" width="16" height="16" alt="QQ">QQ登录</a>
        </div>
        <div class="bd">
            <form id="J_login" method="post" action="<?php echo site_url('tologin');?>" onsubmit="return false;" data-dialog="false">
                <p class="m-tip J_tip">密码错误</p>
                <dl class="f-cb">
                    <dt>账　号：</dt>
                    <dd><input type="text" class="u-ipt" name="account"></dd>
                </dl>
                <dl class="f-cb">
                    <dt>密　码：</dt>
                    <dd><input type="password" class="u-ipt" name="password"></dd>
                </dl>
                <p class="m-link"><a href="<?php echo config_item('domain_shikee_usercenter') ;?>findpwd/" target="_blank">忘记密码？</a></p>
                <dl class="f-cb">
                    <dd><input type="submit" value="登录" class="u-btn J_submit"></dd>
                </dl>
            </form>
        </div>
    </div>
    <?php change_to_minify("common/js/jquery.js,common/js/shs.js,login/home/js/iframe.js"); ?>
</body>
<?php }?>
</html>
