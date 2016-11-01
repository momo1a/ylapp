<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>香港医疗</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="<?php echo config_item('domain_static')?>api/css/mui.min.css">
    <style>
        body {
            background-color: #fff;
        }
        .mui-bar{
            background: #fff;
            box-shadow:none;
            border-bottom: 1px solid #DEDEDE;
        }
        .mui-bar h1{
            color: #3b88d5;
            font-size: 18px;
        }
        .mui-bar a{
            font-size: 18px;
            color: #000;
        }
        .mui-content{
            background: #fff;
        }
        .succ_img{
            width: 70px;
            height: 70px;
            margin: 0 auto;
            margin-top: 135px;
        }
        .succ_img img{
            width: 100%;
            height: 100%;
        }
        .pic{
            width: 215px;
            overflow: hidden;
            margin: 0 auto;

        }
        .pic img{
            width: 69px;
            height: 11px;
        }
        .pic,.pic a{
            font-size: 15px;
            color: #999999;
        }
        .pic a:hover{
            color: #3b88d5;
        }
        h4{
            font-size: 15px;
            color: #333333;
            margin: 15px auto 10px auto;
            text-align: center;
        }
    </style>
</head>
<body>
<header class="mui-bar mui-bar-nav">
    <h1 id="title" class="mui-title">充值成功</h1>
</header>
<div class="mui-content">
    <div class="succ_img">
        <img src="<?php echo config_item('domain_static')?>api/images/icon_ylcg.png"/>
    </div>
    <h4>充值成功</h4>
    <div class="pic">
        <img src="<?php echo config_item('domain_static')?>api/images/pic_1.png" />
        <a href="#" id="strolls">点击返回</a>
        <img src="<?php echo config_item('domain_static')?>api/images/pic_2.png" />
    </div>
</div>
<script src="<?php echo config_item('domain_static')?>api/js/mui.min.js"></script>
<script>
    mui.init({
        swipeBack:true //启用右滑关闭功能
    });
    mui.plusReady(function(){
        document.getElementById("strolls").addEventListener('tap',function(){
            plus.webview.currentWebview().close();
        });
    });
</script>
</body>
</html>