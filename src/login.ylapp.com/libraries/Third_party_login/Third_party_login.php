<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 第三方登录适配器
 * 
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 * @link http://www.zhonghuasuan.com
 */
class Third_party_login extends CI_Driver_Library
{
	/**
	 * 有效的驱动
	 * 
	 * @var array
	 */	
	public $valid_drivers = array (
		'third_party_login_qq', 
		'third_party_login_local' 
	);
	
	/**
	 * 默认的适配器
	 * 
	 * @var string
	 */
	protected $_adapter = 'local';
	
	/**
	 * 回调地址
	 * 
	 * @var string
	 */
	public $_callback_url	= '';
	public $CI				= NULL;
	
	/**
	 * 错误信息
	 * 
	 * @var string
	 */
	public $_error			= '';
	
	const STATE_ID = 'LOGIN_STATE';
	
	// ------------------------------------------------------------------------

	/**
     * Constructor
     *
     * @param array 配置文件
     */
	public function __construct($config = array())
	{
		$this->CI = &get_instance();
		
		$this->CI->load->library(array('session', 'Http'));

		// 加载配置文件
		$this->CI->config->load('login');
		
		if (!empty($config))
		{
			$this->_initialize($config);
		}
	}// end __construct()
	
	// ------------------------------------------------------------------------
	
	/**
     * 授权登录
     * 
     * 跳转到第三方授权地址
     *
     * @return void
     */
	public function login()
	{
		$this->{$this->_adapter}->login();
		
	}// end login()
	
	// ------------------------------------------------------------------------
	
	/**
	 * 回调处理
	 * 
	 * @return boolean
	 */
	public function callback()
	{
		// Authorization Code
		$auth_code = $this->CI->input->get_post('code');
		
		// 是否需要做严格验证?
		if ($this->{$this->_adapter}->has_verification())
		{
			// 获取之前设置的状态值
			$state = $this->CI->input->get('state');
			
			// 判断来源是否正确
			if (!$state || $this->_verification($state) || !$auth_code)
			{
				$this->set_error('非法来源');
				return FALSE;
			}
		}

		return $this->{$this->_adapter}->callback($auth_code);
		
	}// end callback()
	
	// ------------------------------------------------------------------------
	
	/**
     * 获取用户信息
     *
     * @return $array 用户信息
     */
	public function get_user_info()
	{
		return $this->{$this->_adapter}->get_user_info();
	}// get_user_info()
	
	// ------------------------------------------------------------------------
	
	/**
	 * 被请求的驱动是否已开启
	 *
	 * @param string 要测试的驱动.
	 * @return bool
	 */
	public function is_open($driver)
	{
		static $support = array ();
		
		if ( ! isset($support[$driver]))
		{
			$support[$driver] = $this->{$driver}->is_open();
		}
		
		return $support[$driver];
	}// end is_open()
	
	// ------------------------------------------------------------------------
	
	/**
	 * 设置错误信息
	 * 
	 * @param string $msg
	 */
	public function set_error($msg)
	{
		$this->_error = $msg;
	}// end set_error()
	
	// ------------------------------------------------------------------------
	
	/**
	 * 返回错误信息
	 * 
	 * @return string
	 */
	public function get_error()
	{
		return $this->_error;
	}// end get_error()
	
	// ------------------------------------------------------------------------
	
	/**
	 * __get()
	 *
	 * @param child
	 * @return object
	 */
	public function __get($child)
	{
		$obj = parent::__get($child);
	
		if ( ! $this->is_open($child))
		{
			show_error("{$child} Login does not open");
		}
	
		return $obj;
	}// end __get()
	
	// ------------------------------------------------------------------------
	
	/**
     * Initialize
     *
     * 将配置信息初始化为类的属性.
     *
     * @param array
     * @return void
     */
	private function _initialize($config)
	{
		$default_config = array (
			'adapter',
			'callback_url',
			'username',
			'password'
		);
		
		foreach ($default_config as $key)
		{
			if (isset($config[$key]))
			{
				$param = '_' . $key;
				$this->{$param} = $config[$key];
			}
		}
	}// end _initialize()
	
	// ------------------------------------------------------------------------
	
	/**
	 * 验证来源是否正确
	 * 
	 * @param string $state 状态值
	 * @return boolean
	 */
	private function _verification($state)
	{
		// 取出闪存的自定义数据
		$state = $this->CI->session->flashdata(self::STATE_ID);
		
		return $this->CI->input->get('state') == $state;
		
	}// end _verification()
	
	// ------------------------------------------------------------------------
	
	/**
	 * 状态值
	 * 
	 * 防止CSRF攻击,在授权后回调时会原样带回,用于判断来源是否正确
	 * 
	 * @return string
	 */
	public function state()
	{
		// 防止CSRF攻击
		$state = md5(uniqid(rand(), TRUE));
		// @TODO 暂时保存到Session中
		$this->CI->session->set_userdata(self::STATE_ID, $state);
		
		return $state;
		
	}// end _state()
}
// End Third_party_login Class

// ------------------------------------------------------------------------

/**
 * 适配器接口
 * 
 * 要求每个适配器必须实现这个接口定义的办法
 * 
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 *
 */
interface Third_party_login_driver
{

	/**
	 * 登录处理
	 * 
	 * @return void
	 */
	public function login();
	
	// ------------------------------------------------------------------------
	
	/**
	 * 回调处理
	 * 
	 * @param string $auth_code Authorization Code
	 * @return void
	 */
	public function callback($auth_code);
	
	// ------------------------------------------------------------------------

	/**
	 * 获取用户信息
	 * 
	 * @return array
	 */
	public function get_user_info();
	
	// ------------------------------------------------------------------------
	
	/**
	 * 是否需要验证来源
	 * 
	 * @return bool
	 */
	public function has_verification();
	
	// ------------------------------------------------------------------------

	/**
	 * 是否开放
	 * 
	 * @return boolean
	 */
	public function is_open();
}
//End Third_party_login_driver interface

/* End of file Third_party_login.php */
/* Location: ./application/libraries/Third_party_login/Third_party_login.php */