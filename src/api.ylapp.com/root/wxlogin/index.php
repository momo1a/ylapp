<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/10
 * Time: 8:52
 */

$appid = 'wx1a13456d65204e33';

header('location:https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri=123.207.87.83:8080/wxlogin/oauth.php&response_type=code&scope=snsapi_userinfo&state=123&connect_redirect=1#wechat_redirect');

