<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('__autoload'))
{
	function __autoload($class)
	{
		$ci =& get_instance();
		if(strtolower(substr($class, -5))=='model') {
			if('CI_Model' == $class){
				// CI_Model 类的判断
				$class = 'Model';
			}
			foreach ($ci->config->_config_paths as $path){
				if(file_exists($path.'models/'.$class.'.php')){
					require_once($path.'models/'.$class.'.php');
					return;
				}elseif(file_exists($path.'models/'.strtolower($class).'.php')){
					require_once($path.'models/'.strtolower($class).'.php');
					return;
				}
			}
		}
		// TODO 其它需要自动加载的请在后面追加
	}
}

/* End of file autoload_helper.php */
/* Location: ./application/helpers/autoload_helper.php */
