<?php
// 用户cookie配置
$config['cookie_name'] = 'YL';
$config['cookie_domain'] = '.du-ms.com';
$config['cookie_account'] = 'account'; // 保存登录账号的cookie键

// Session配置
$config['sess_use_database'] = TRUE; // Session使用数据库
$config['sess_table_name'] = 'shs_common_session'; // 保存Session数据库表名
$config['sess_cookie_name'] = 'zhonghs_sess'; // Session使用的Cookie名称
$config['encryption_key'] = '111111'; // 统一密钥,Session加密用到


// 上传图片
$config['upload_image_save_path'] = 'D:/webroot/zzidcconf/nginx/html/images/';
$config['upload_image_thumb_size'] = array('illRemark' => array(30,50,80,108), 'leavingMsg' => array(30,50,100,30),'post'=>array(30,60,100,160),'avatar'=>array(30,60,90,108),'certificate'=>array(30,60,90,108),'hospital'=>array(60,98,128),'news'=>array(50,45,98,108));
$config['upload_image_quality'] = 95;

// 上传修改头像
$config['upload_avatar_save_path'] = '/mnt/';
$config['upload_avatar_thumb_size'] = array('small'=>48, 'middle'=>120, 'big'=>175);
$config['upload_avatar_quality'] = 95;

// 图片服务器
$config['image_servers'] = array('https://img.du-ms.com/');

// 各站点域名
//$config['domain_www'] = 'http://www.ylapp.com/';
$config['domain_static'] = 'https://static.du-ms.com/';
$config['domain_detail'] = 'https://detail.du-ms.com/';
$config['domain_download'] = 'https://download.du-ms.com/';  // 下载站点

// app升级包上传目录
$config['app_update_package_upload_path'] = 'D:/webroot/zzidcconf/nginx/html/upload/';

$config['super_admin'] = array(1);

require dirname(__FILE__).DIRECTORY_SEPARATOR.'shs_system.php';
require dirname(__FILE__).DIRECTORY_SEPARATOR.'static_url.php';  //加载众划算静态URL

?>

