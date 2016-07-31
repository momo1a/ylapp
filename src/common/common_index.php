<?php

/*
 *---------------------------------------------------------------
 * 应用环境配置
 *---------------------------------------------------------------
 *
 * 不同的应用环境将加载不同的应用配置,如果想在线上做调试,
 * 可以将应用环境设置为testing,系统将自动调用testing环境下的应用配置文件(如果有的话).
 *
 * 不同的应用环境也将影响日志记录和错误报告的显示
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */
define('ENVIRONMENT', 'development');
/*
 *---------------------------------------------------------------
 * 错误报告
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */

if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'development':
			error_reporting(E_ALL^E_NOTICE);
			break;

		case 'testing':
		case 'production':
			error_reporting(0);
			break;

		default:
			exit('The application environment is not set correctly.');
	}
}

/**
 * 时间戳
 * @var int
 */
define('TIMESTAMP', time());

/*
 *---------------------------------------------------------------
 * 公共文件夹
 *---------------------------------------------------------------
 */

define('COMPATH', str_replace("\\", "/", __DIR__.'/'));

/*
 *---------------------------------------------------------------
 * 框架存放路径
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" folder.
 * Include the path if the folder is not in the same  directory
 * as this file.
 *
 */
$system_path = realpath(COMPATH . '../../libs/codeigniter/2.1.3').'/';

/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here.  For most applications, you
 * WILL NOT set your routing here, but it's an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * IMPORTANT:  If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller.  Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * Un-comment the $routing array below to use this feature
 *
 */
// The directory name, relative to the "controllers" folder.  Leave blank
// if your controller is not in a sub-folder within the "controllers" folder
// $routing['directory'] = '';

// The controller class file name.  Example:  Mycontroller
// $routing['controller'] = '';

// The controller function you wish to be called.
// $routing['function']	= '';


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the $assign_to_config array below to use this feature
 *
 */
// $assign_to_config['name_of_config_item'] = 'value of config item';



// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */

// Set the current directory correctly for CLI requests
if (defined('STDIN'))
{
	chdir(dirname(__FILE__));
}

/*
 * -------------------------------------------------------------------
 *  设置路径常量
 * -------------------------------------------------------------------
 */

// Path to the system folder
define('BASEPATH', str_replace("\\", "/", $system_path));

/* End of file common_index.php */
/* Location: ./common/common_index.php */