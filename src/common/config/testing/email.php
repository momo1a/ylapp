<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//在注册帐号和QQ登录绑定时使用该邮箱发送激活码

$config['smtp_host']    = 'smtp.exmail.qq.com';  //邮箱协议地址
$config['smtp_user']    = ''; //邮箱账户
$config['smtp_pass']    = '';	    //邮箱密码
$config['smtp_crypto']  = '';               //验证类型
$config['smtp_port']    = '25';             //SMTP 端口
$config['protocol']     = 'smtp';           //邮件发送协议
$config['smtp_timeout'] = '5';              //SMTP 超时设置(单位：秒)。
$config['mailtype']     = 'html';           //邮件类型
$config['validate']     = true;             //是否验证邮件地址
$config['priority']     = 3;                //Email 优先级. 1 = 最高. 5 = 最低. 3 = 正常.
$config['crlf']         = "\r\n";           //换行符
$config['newline']      = "\r\n";            //换行符
$config['wordwrap']     = TRUE;             //开启自动换行