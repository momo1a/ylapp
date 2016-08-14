<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Exceptions Class
 * 
 * 将错误显示模板文件放到/common/errors中,
 * 同时通过不同的环境(ENVIRONMENT)调用相应的错误提示页
 * 
 * 注意：
 * 1、系统将默认首先调用应用文件夹(application/errors)下相应的错误提示页,
 * 如果不存在才会调用公用的提示页.
 * 2、应用文件夹(application/errors)下的提示页不区分环境(ENVIRONMENT).
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Exceptions
 * @author		momo1a@qq.com
 * @link		http://codeigniter.com/user_guide/libraries/exceptions.html
 */
class YL_Exceptions extends CI_Exceptions {
	/**
	 * General Error Page
	 *
	 * This function takes an error message as input
	 * (either as a string or an array) and displays
	 * it using the specified template.
	 *
	 * @access	private
	 * @param	string	the heading
	 * @param	string	the message
	 * @param	string	the template name
	 * @param 	int		the status code
	 * @return	string
	 */
	function show_error($heading, $message, $template = 'error_general', $status_code = 500)
	{
		set_status_header($status_code);

		$message = '<p>'.implode('</p><p>', ( ! is_array($message)) ? array($message) : $message).'</p>';

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		
		//$file = APPPATH.'errors/'.$template.'.php';
		//if ( ! file_exists($file))
		//{
			// 测试环境(testing)和正式环境(production)调用的是production文件包下的文件
			$folder = ENVIRONMENT == 'development' ? 'development' : 'production';
			$file = COMPATH.'errors/'.$folder.'/'.$template.'.php';
		//}
		
		ob_start();
		include($file);
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	// --------------------------------------------------------------------

	/**
	 * Native PHP error handler
	 *
	 * @access	private
	 * @param	string	the error severity
	 * @param	string	the error string
	 * @param	string	the error filepath
	 * @param	string	the error line number
	 * @return	string
	 */
	function show_php_error($severity, $message, $filepath, $line)
	{
		$severity = ( ! isset($this->levels[$severity])) ? $severity : $this->levels[$severity];

		$filepath = str_replace("\\", "/", $filepath);

		// For safety reasons we do not show the full file path
		if (FALSE !== strpos($filepath, '/'))
		{
			$x = explode('/', $filepath);
			$filepath = $x[count($x)-2].'/'.end($x);
		}

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		
		//$file = APPPATH.'errors/error_php.php';
		//if ( ! file_exists($file))
		//{
			// 测试环境(testing)和正式环境(production)调用的是production文件包下的文件
			$folder = ENVIRONMENT == 'development' ? 'development' : 'production';
			$file = COMPATH.'errors/'.$folder.'/error_php.php';
		//}
		ob_start();
		include($file);
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
	}


}
// END Exceptions Class

/* End of file Exceptions.php */
/* Location: ./system/core/Exceptions.php */