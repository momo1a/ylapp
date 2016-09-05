<?php

/*
 *---------------------------------------------------------------
 * 应用程序文件夹名称
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * folder then the default one you can set its name here. The folder
 * can also be renamed or relocated anywhere on your server.  If
 * you do, use a full server path. For more info please see the user guide:
 * http://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 *
 */
	$application_folder = dirname(__DIR__);

/*
 * --------------------------------------------------------------------
 * 加载公共文件
 * --------------------------------------------------------------------
 *
 */
require_once dirname($application_folder). '/common/common_index.php';
	
/*
 * -------------------------------------------------------------------
 *  设置常量
 * -------------------------------------------------------------------
 */
	// The name of THIS file
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	// The PHP file extension
	// this global constant is deprecated.
	define('EXT', '.php');

	// Path to the front controller (this file)
	define('FCPATH', str_replace(SELF, '', __FILE__));

	// Name of the "system folder"
	define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));

	// The path to the "application" folder
	define('APPPATH', $application_folder.'/');

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 *
 */

/**
 * 开启session
 */
session_start();


require_once BASEPATH.'core/CodeIgniter.php';

/* End of file index.php */
/* Location: ./root/index.php */