<?php
//-------配置
$AppID = 'wx1a13456d65204e33';
$AppSecret = '5b269d029775647975de2e96339a1d29';
$callback  =  'http://123.207.87.83:8080/wxlogin/oauth.php'; //回调地址

//微信登录
session_start();
//-------生成唯一随机串防CSRF攻击
$state  = md5(uniqid(rand(), TRUE));
$_SESSION["wx_state"]    =   $state; //存到SESSION
$callback = urlencode($callback);
$wxurl = "https://open.weixin.qq.com/connect/qrconnect?appid=".$AppID."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";
header("Location: $wxurl");

