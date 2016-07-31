<?php
// 用户cookie配置
$config['cookie_name'] = 'zhs';
$config['cookie_domain'] = '.fqf.com';
$config['cookie_account'] = 'account'; // 保存登录账号的cookie键

// Session配置
$config['sess_use_database'] = TRUE; // Session使用数据库
$config['sess_table_name'] = 'shs_common_session'; // 保存Session数据库表名
$config['sess_cookie_name'] = 'zhonghs_sess'; // Session使用的Cookie名称
$config['encryption_key'] = '111111'; // 统一密钥,Session加密用到


// 上传图片
$config['upload_image_save_path'] = '/mnt/myweb/images/';
$config['upload_image_thumb_size'] = array('goods' => array(60, 80, 160, 180, 230, 280, 350), 'show' => array('215x0', '105x140'));
$config['upload_image_quality'] = 95;

// 上传修改头像
$config['upload_avatar_save_path'] = '/mnt/';
$config['upload_avatar_thumb_size'] = array('small'=>48, 'middle'=>120, 'big'=>175);
$config['upload_avatar_quality'] = 95;

// 图片服务器
$config['image_servers'] = array('http://img.fqf.com/');

// 各站点域名
//$config['domain_www'] = 'http://www.fqf.com/';


require dirname(__FILE__).DIRECTORY_SEPARATOR.'shs_system.php';
require dirname(__FILE__).DIRECTORY_SEPARATOR.'static_url.php';  //加载众划算静态URL

?>
