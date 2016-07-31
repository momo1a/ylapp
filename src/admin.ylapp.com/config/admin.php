<?php
if (defined ( 'ENVIRONMENT' ) and file_exists ( COMPATH . 'config/' . ENVIRONMENT . '/constants.php' )) {
	require (COMPATH . 'config/' . ENVIRONMENT . '/constants.php');
}
if (defined ( 'ENVIRONMENT' ) and file_exists ( COMPATH . 'config/' . ENVIRONMENT . '/shs.php' )) {
	require (COMPATH . 'config/' . ENVIRONMENT . '/shs.php');
}

/**
 * 静态内容地址
 */
$config['static_url'] = trim($config['domain_static'], '/');

/**
 * 管理员登录地址
 */
$config['admin_login_url'] = $config['url_login'];

/**
 * 设置一战成名保证金修改操作码权限的管理员用户ID
 */
$config['deposit_password_uid'] = array(1);
