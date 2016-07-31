<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 订单号存在性验证接口驱动
 *
 * @author 宁天友
 * @version 2015-7-16 14:57:45
 * @link http://www.zhonghuasuan.com
 */
require APPPATH.'libraries/ZHS_api_driver.php';

class Trade_no_default extends CI_Driver implements ZHS_api_driver
{
	private $CI;
	protected $error = array();
	/**
	 * 参数
	 * @var array
	 */
	protected $params = array();
	/**
	 * 接受的参数
	 * @var array
	 */
	protected $params_key = array();

	/**
	 * 接口版本
	 * @var int
	 */
	const VERSION = 1;
	/**
	 * 接口是否可用
	 * @var int
	 */
	const IS_OPEN = TRUE;
	
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->params_key = array(
			'no'=>array('type'=>'string', 'preg'=>"/^[a-zA-Z0-9_\-]{1,}$/"),
			'sign'=>array('type'=>'string', 'preg'=>"/^[a-zA-Z0-9]{32}$/"),
			'site'=>array('type'=>'string', 'preg'=>"/^[1-9]{1}$/"),
		);
	}
	
	/**
	 * 验证订单号是否存在
	 * @author 宁天友
	 * @version 2015-7-17 10:23:15
	 * @return mixed 1存在，-1不存在，false错误
	 */
	public function is_exist()
	{
		$this->CI->load->model('Zhs_order_model');
		$this->CI->load->model('Order_model', 'order');
		$count = $this->CI->order->count_by('trade_no', $this->params['no']);

		if($count === FALSE){
			$this->error = array('code'=>9002, 'msg'=>'接口内部错误');
			return FALSE;
		}
		return $count > 0 ? 1 : -1;
	}
	
	/**
	 * 获取错误
	 */
	public function error()
	{
		return $this->error;
	}
	
	/**
	 * 检测签名，验证请求是否合法
	 * @return bool
	 */
	public function check_sign()
	{
		$local_sign = $this->sign();
		$request_sign = $this->params['sign'];
		if($local_sign === $request_sign && $request_sign != '')
		{
			return TRUE;
		}
		$this->error = array('code'=>1004, 'msg'=>'签名错误');
		return FALSE;
	}
	
	/**
	 * 检测参数是否正确
	 * @param array $params 请求参数数组
	 * @return bool
	 */
	public function check_param($params=array())
	{
		$miss_params = $params_error = array();
		foreach ($this->params_key as $key=>$init) {
			if( ! isset($params[$key]))
			{
				$miss_params[] = $key;
			}
			else
			{
				if($init['type'] == 'string')
				{
					$this->params[$key] = trim($params[$key]);
				}
				elseif($init['type'] == 'int')
				{
					$this->params[$key] = intval($params[$key]);
				}
				else
				{
					$this->params[$key] = $params[$key];
				}
				if($init['preg'] && ! preg_match($init['preg'], $this->params[$key]))
				{
					$params_error[] = $key;
				}
			}
		}

		if(count($miss_params) || count($params_error))
		{
			$this->params = array();
			count($miss_params) AND $msg[] = '缺少参数['.implode(',', $miss_params).']';
			count($params_error) AND $msg[] = '参数错误['.implode(',', $params_error).']';
			$this->error = array('code'=>1003, 'msg'=>implode(',', $msg));
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * 生成接口sign
	 * @return string
	 */
	public function sign()
	{
		$site = intval($this->params['site']);
		$no = trim($this->params['no']);
		return md5(md5($site.$no).KEY_SHIKEE);
	}
	
	public function version(){
		return self::VERSION;
	}
	
	/**
	 * 是否开放
	 *
	 * @return boolean
	 */
	public function is_open()
	{
		return self::IS_OPEN;
	}
}