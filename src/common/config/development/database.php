<?php
$active_group = 'default';
$active_record = TRUE;

// 主数据库（默认）

$db['default']['hostname'] = '192.168.1.103';
$db['default']['username'] = 'root';
$db['default']['password'] = '123456';
$db['default']['database'] = 'ylapp';
$db['default']['port']     = '3306';
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = 'YL_';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = FALSE;
$db['default']['stricton'] = FALSE;

// 从数据库 - 主要用于前台读取数据

$db['slave']['hostname'] = '192.168.1.103';
$db['slave']['username'] = 'root';
$db['slave']['password'] = '123456';
$db['slave']['port']     = '3306';
$db['slave']['database'] = 'ylapp';
$db['slave']['dbdriver'] = 'mysql';
$db['slave']['dbprefix'] = 'YL_';
$db['slave']['pconnect'] = FALSE;
$db['slave']['db_debug'] = TRUE;
$db['slave']['cache_on'] = FALSE;
$db['slave']['cachedir'] = '';
$db['slave']['char_set'] = 'utf8';
$db['slave']['dbcollat'] = 'utf8_general_ci';
$db['slave']['swap_pre'] = '';
$db['slave']['autoinit'] = FALSE;
$db['slave']['stricton'] = FALSE;

?>
