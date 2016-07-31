<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 输出类
 * 
 * 重写CI_Output的_display_cache方法,使其可以使用?fc跳过缓存重新生成
 * 
 * @author 韦明磊<nicolaslei@163.com>
 *
 */
class ZHS_Output extends CI_Output
{
	/**
	 * Update/serve a cached file
	 *
	 * @access	public
	 * @param 	object	config class
	 * @param 	object	uri class
	 * @return	void
	 */
	function _display_cache(&$CFG, &$URI)
	{
		if (isset($_GET['fc']))
		{
			return FALSE;;
		}

		return parent::_display_cache($CFG, $URI);
	}
}