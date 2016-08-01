<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>登录成功</title>
    <style>
        body, dl, dt, dd, ul, li, input, img, h1, h2, form, p { margin: 0; padding: 0;}
        img { border: none; }
        body { font: 12px/1.5 tahoma,\5b8b\4f53,arial; color: #999; background-color: #F8F8F8; }
        ul,ol{ list-style: none;}
        a { color: #999; text-decoration: none; }
        a:hover { color: #BD0A01; text-decoration: underline; }
        .w980{ width: 980px; margin: 0 auto;}
        em { font-style: normal; font-weight: 700;}
        .pull-left{ float: left;}
        .pull-right{ float: right;}
        .clearfix:after { content: '\20'; display: block; height: 0; clear: both; }
        .clearfix{ *zoom: 1; }
        .fz14{font-size: 14px;}
        #warnning { color:#f00;}

        .headerWrapper{ box-shadow:0 0 10px rgba(0,0,0,.2); z-index: 1000; position: relative;}
        #logo { width:250px; height:45px; overflow: hidden; *height: 0; *padding-top: 45px; *background: url(<?php echo config_item('domain_static'); ?>images/login/logo.png) no-repeat 0 0;}
        #logo:before { content: url(<?php echo config_item('domain_static'); ?>images/login/logo.png);}
        .header { padding-top:50px; overflow: hidden; padding-bottom: 20px; }
        .header .link{ padding-top: 2em; padding-right: 10px;}

        .main{ height: 380px; overflow: hidden; background-color: #fcfcfc;}
        h2{ width: 115px; padding:5px 0 0 20px; margin:60px auto 40px; text-align: center; color:#57B909; font-family: \9ED1\4F53; font-size: 24px; font-weight: normal;}
        .links{ text-align: center; padding-top: 100px;}
        .links a { display: inline-block; width: 110px; padding-top: 45px; }
        h2, .links a { background: url(<?php echo config_item('domain_static'); ?>images/login/sprite.png) no-repeat; _background: url(<?php echo config_item('domain_static'); ?>images/login/sprite_png8.png) no-repeat; }
        h2 { background-position: 0 -350px; }
        .links .icon-back { background-position: 50% 14px;  }
        .links .icon-home { background-position: 50% -42px; }
        .links .icon-user { background-position: 50% -155px; }
        .links .icon-pay { background-position: 50% -97px; }
        .links .icon-zone { background-position: 50% -216px; }
        .links .icon-order { background-position: 50% -276px; }

        .footer { border-top: 1px solid #ccc; background-color: #fafafa; text-align: center;padding-top: 35px; }
        .footer a, #footer span {margin-right: 10px;}
    </style>
    <script src="<?php echo config_item('domain_static'); ?>javascript/common/jquery/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script>
        $(function(){

            /*5秒后跳转*/
            (function(){
                var num = 5;
                var showtime = $("<p style='text-align:center'><em style='font-size:14px;margin-right:8px;'>"+num+"</em>秒后自动返回</p>");
                var showtimeNum = showtime.find("em");
                showtime.insertBefore(".links");
                num-=1;
                var countDown = setInterval(function(){
                    if(num){
                        showtimeNum.html(num);
                        num-=1;
                    }else{
                        countDown && clearInterval(countDown);
                        document.location.href = document.getElementById("JS-goback").getAttribute("href");
                    }
                },1000);
            })();

        });
    </script>
</head>
<body>
<div class="headerWrapper">
   <div class="header w980 clearfix">
        <a href="<?php echo config_item('domain_www'); ?>">
            <h1 id="logo" class="pull-left">登陆-众划算</h1>
        </a>
        <span class="link pull-right">
            <a href="<?php echo config_item('domain_www'); ?>">首页</a> - 
            <a href="<?php echo config_item('url_reg'); ?>" target="_blank">注册</a> | 
            <a href="<?php echo config_item('url_help'); ?>" target="_blank">帮助中心</a>
        </span>
    </div> 
</div>

    <div class="main">
        <h2>登录成功</h2>
        <div class="links">
            <a class="icon-back" id="JS-goback" href="<?php echo urldecode($to_url);?>">返回</a>
            <a class="icon-home" href="<?php echo config_item('domain_www'); ?>">众划算首页</a>
            <a class="icon-user" href="<?php echo $my_YL_url; ?>" >我的众划算</a>
            <a class="icon-pay" href="<?php echo config_item('domain_hlpay_www'); ?>">我的互联支付</a>
            <a class="icon-zone" href="<?php echo config_item('domain_shikee_bbs'); ?>">我的社区空间</a>
            <a class="icon-order" href="<?php echo config_item('domain_www'); ?>show/">买家晒单</a>
        </div>
    </div>
    <iframe width=0 height=0 frameborder=0 src="<?php echo $sync_login_shikee;?>"></iframe>
    <div class="footer">
        <a href="<?php echo config_item('url_help'); ?>" target="_blank">帮助</a>
        <a href="http://www.zhonghuasuan.com/guide" target="_blank">新手引导</a>
        <a href="http://help.zhonghuasuan.com/feedback" target="_blank">意见反馈</a>
        Copyright © 2006-<?php echo date('Y');?>
        <a target="_blank" href="http://www.zhonghuasuan.com/">zhonghuasuan.com</a>
        <a href="<?php echo config_item('legal_url');?>" target="_blank">法律声明</a>
        <span>版权所有</span>
        <a href="http://www.miibeian.gov.cn/" target="_blank">桂ICP备07009935号</a>
        <script src="http://s15.cnzz.com/stat.php?id=905537&web_id=905537" language="JavaScript"></script>
        <div style="display:none;"><script src="http://s96.cnzz.com/stat.php?id=5713574&web_id=5713574&show=pic" language="JavaScript"></script></div>
    </div>
    
</body>
</html>
