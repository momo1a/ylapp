<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 钩子
 * 
 * 重写CI_Hooks的_run_hook方法,使其可以加载common/hooks下的钩子文件
 * 
 * @author 韦明磊<nicolaslei@163.com>
 *
 */
class ZHS_Hooks extends CI_Hooks
{
	/**
	 * Run Hook
	 *
	 * Runs a particular hook
	 *
	 * @access	private
	 * @param	array	the hook details
	 * @return	bool
	 */
	function _run_hook($data)
	{
		if ( ! is_array($data))
		{
			return FALSE;
		}
	
		// -----------------------------------
		// Safety - Prevents run-away loops
		// -----------------------------------
	
		// If the script being called happens to have the same
		// hook call within it a loop can happen
	
		if ($this->in_progress == TRUE)
		{
			return;
		}
	
		// -----------------------------------
		// Set file path
		// -----------------------------------
	
		if ( ! isset($data['filepath']) OR ! isset($data['filename']))
		{
			return FALSE;
		}
		
		// 先从公用包中读取
		$filepath = COMPATH.$data['filepath'].'/'.$data['filename'];
	
		if ( ! file_exists($filepath))
		{
			// 从当前站点中读取
			$filepath = APPPATH.$data['filepath'].'/'.$data['filename'];
			
			if ( ! file_exists($filepath))
			{
				return FALSE;
			}
		}

		// -----------------------------------
		// Set class/function name
		// -----------------------------------
	
		$class		= FALSE;
		$function	= FALSE;
		$params		= '';
	
		if (isset($data['class']) AND $data['class'] != '')
		{
			$class = $data['class'];
		}
	
		if (isset($data['function']))
		{
			$function = $data['function'];
		}
	
		if (isset($data['params']))
		{
			$params = $data['params'];
		}
	
		if ($class === FALSE AND $function === FALSE)
		{
			return FALSE;
		}
	
		// -----------------------------------
		// Set the in_progress flag
		// -----------------------------------
	
		$this->in_progress = TRUE;
	
		// -----------------------------------
		// Call the requested class and/or function
		// -----------------------------------
	
		if ($class !== FALSE)
		{
			if ( ! class_exists($class))
			{
				require($filepath);
			}
	
			$HOOK = new $class;
			$HOOK->$function($params);
		}
		else
		{
			if ( ! function_exists($function))
			{
				require($filepath);
			}
	
			$function($params);
		}
	
		$this->in_progress = FALSE;
		return TRUE;
	}
}