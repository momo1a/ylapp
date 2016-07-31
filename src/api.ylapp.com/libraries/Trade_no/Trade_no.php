<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 订单号存在性验证适配器
 *
 * @author 宁天友
 * @version 2015-7-16 15:12:01
 * @link http://www.zhonghuasuan.com
 */
class Trade_no extends CI_Driver_Library
{
	private $CI;
	protected $error = NULL;
	protected $param = array();
	protected $valid_drivers = array (
		'trade_no_default'
	);
	protected $_adapter = 'default';
	
	/**
	 * Constructor
	 *
	 * @param array
	 */
	public function __construct($config = array())
	{
		$this->CI =& get_instance();
		if ( ! empty($config))
		{
			$this->_initialize($config);
		}

		$gets = $this->CI->input->get(NULL, TRUE);
		$posts = $this->CI->input->post(NULL, TRUE);
		$this->param = array_merge(($posts == FALSE ? array() : $posts), ($gets == FALSE ? array() : $gets));
	}
	
	/**
	 * 方法执行入口
	 * @param string $method 方法名
	 * @param mixed $result 方法成功执行结果，方法执行出错$result值不变(传址参数)
	 * @return boolean 是否成功执行，true成功，false方法执行出错
	 */
	public function call($method, &$result)
	{
		$res = $this->{$method}();
		if($res === FALSE){
			return FALSE;
		}
		$result = $res;
		return TRUE;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Initialize
	 *
	 * Initialize class properties based on the configuration array.
	 *
	 * @param	array
	 * @return 	void
	 */
	private function _initialize($config)
	{
		$default_config = array(
			'adapter',
		);
	
		foreach ($default_config as $key)
		{
			if (isset($config[$key]))
			{
				$param = '_'.$key;
	
				$this->{$param} = $config[$key];
			}
		}
	}
	
	// ------------------------------------------------------------------------
	/**
	 * 验证订单号是否存在
	 * @author 宁天友
	 * @version 2015-7-17 10:23:15
	 * @return mixed 1存在，-1不存在，false错误
	 */
	private function is_exist()
	{
		if($this->{$this->_adapter}->check_param($this->param) === FALSE)
		{
			$this->error = $this->{$this->_adapter}->error();
			return FALSE;
		}
		elseif($this->{$this->_adapter}->check_sign() === FALSE)
		{
			$this->error = $this->{$this->_adapter}->error();
			return FALSE;
		}
		$is_exist = $this->{$this->_adapter}->is_exist();
		if($is_exist === FALSE)
		{
			$this->error = $this->{$this->_adapter}->error();
			return FALSE;
		}
		return $is_exist;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * 是否开放
	 *
	 * @return boolean
	 */
	public function is_open()
	{
		return $this->{$this->_adapter}->is_open();
	}
	
	// ------------------------------------------------------------------------

	/**
	 * 获取错误
	 */
	public function error()
	{
		return $this->error;
	}
	
	// ------------------------------------------------------------------------
}

?>