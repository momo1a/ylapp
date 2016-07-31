<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 验证码适配器
 *
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 * @link http://www.zhonghuasuan.com
 */
class Captcha extends CI_Driver_Library
{
	const STORE_ADAPTER_CACHE = 'cache';
	const STORE_ADAPTER_SESSION = 'session';

	/**
	 * 有效的驱动
	 * @var array
	 */
	public $valid_drivers = array (
			'captcha_default',
	);
	
	/**
	 * 默认的驱动
	 * @var string
	 */
	protected $_adapter = 'default';
	
	/**
	 * 存储适配器，提供两种方式(cache/session)(默认使用session)
	 * @var string
	 */
	protected $_store_adapter = 'session';
	
	/**
	 * 存储验证码的KEY
	 * @var string
	 */
	protected $_store_key = 'captcha_sess';
	
	/**
	 * 存储验证码时间的KEY(使用session时有效)
	 * @var string
	 */
	protected $_session_time_var = 'captcha_sess_time';
	
	/**
	 * 过期时间(秒)
	 * @var int
	 */
	protected $_store_expiration = 1200;
	
	/**
	 * 发送频率限制数据的缓存KEY
	 * @var string
	 */
	protected $_frequency_cache_key = '';
	
	/**
	 * 发送频率限制数据
	 * @var array
	 */
	protected $_frequency_cache_data = array();
	
	/**
	 * 发送限制的冻结时间(秒)
	 * @var int
	 */
	private $_frequency_freeze_time = 3600;

	protected $CI = NULL;
	
	/**
	 * 验证码文本
	 * @var string
	 */
	private $captcha_text = '';
	
	/**
	 * 错误信息
	 * @var array
	 */
	private $_error_message = array();
	
	//------------------------------------------------------
	
	public function __construct($config = array())
	{
		$this->CI = &get_instance();
		
		if (count($config)>0) {
			$this->initialize($config);
		}
		
		// 加载缓存
		$this->CI->load->library('cache_memcached', NULL, 'cache');
		
		// 使用Session
		if ($this->_store_adapter == self::STORE_ADAPTER_SESSION) {
			// 加载Session
			$this->CI->load->library('session');
			$this->_session_time_var = $this->_store_key.'_exp_time';
		}
	}
	
	//------------------------------------------------------
	
	/**
	 * Initialize
	 *
	 * 将配置信息初始化为类的属性.
	 *
	 * @param array
	 * @return void
	 */
	public function initialize($config)
	{
		$default_config = array (
				'adapter',
				'store_key',
				'store_expiration',
				'store_adapter'
		);
	
		foreach ($default_config as $key)
		{
			if (isset($config[$key])) {
				$param = '_' . $key;
				$this->{$param} = $config[$key];
			}
		}
	}
	
	//------------------------------------------------------
	
	/**
	 * 发送语音验证码
	 * @param string $mobile 手机号码
	 */
	public function send_sound_captcha($mobile)
	{
		$this->_frequency_cache_key = md5($this->_store_key.'_sound_captcha_frequency');
		// 发送频率检测
		if ( ! $this->check_frequency()) {
			return FALSE;
		}
		
		$this->CI->load->library('mobile');
		$this->CI->load->helper('string');
		
		$code = random_string('numeric', 4);
		
		// 发送验证码
		if ($this->CI->mobile->voice_verify($mobile, $code)) {
			// 保存1个小时
			$this->_store_expiration = 3600;
			// 保存验证码
			$this->_store_captcha($code);
			// 更新设置发送频率
			$this->update_frequency();
			
			return TRUE;
		}else {
			$this->set_error('SEND_FAILURE', '服务器错误，发送失败');
			return FALSE;
		}
	}
	//------------------------------------------------------
	/**
	 * 发送短信验证码
	 * @param string $mobile 手机号码
	 */
	public function send_mobile_msg_captcha($mobile)
	{
		$this->_frequency_cache_key = md5($this->_store_key.'_mobile_msg__captcha_frequency');
		// 发送频率检测
		if ( ! $this->check_frequency()) {
			return FALSE;
		}
	
		$this->CI->load->library('mobile');
		$this->CI->load->helper('string');
	
		$code = random_string('numeric', 4);
		// 发送验证码
		if ($this->CI->mobile->send_msg($mobile, $code, 60)) {
			// 保存1个小时
			$this->_store_expiration = 3600;
			// 保存验证码
			$this->_store_captcha($code);
			// 更新设置发送频率
			$this->update_frequency();
				
			return TRUE;
		}else {
			$this->set_error('SEND_FAILURE', '服务器错误，发送失败');
			return FALSE;
		}
	}
	//------------------------------------------------------
	
	/**
	 * 发送语音验证码
	 * @param string $mobile 手机号码
	 */
	public function send_msg_captcha($mobile)
	{
		
		$this->_frequency_cache_key = md5($this->_store_key.'_msg_captcha_frequency');

		// 发送频率检测
		if ( ! $this->check_frequency()) {
			return FALSE;
		}
	
		$this->CI->load->library('mobile');
		$this->CI->load->helper('string');
	
		$code = random_string('numeric', 4);

		// 发送验证码
		//if ($this->CI->mobile->send_msg($mobile,$code,60)) {
		if (TRUE) {
			// 保存1个小时
			$this->_store_expiration = 3600;
			// 保存验证码
			$this->_store_captcha($code);
			// 更新设置发送频率
			$this->update_frequency();
				
			return TRUE;
		}else {
			$this->set_error('SEND_FAILURE', '服务器错误，发送失败');
			return FALSE;
		}
	}
	
	//------------------------------------------------------
	
	/**
	 * 显示一个验证码图片
	 * @return void
	 */
	public function show_captcha_image()
	{
		$this->{$this->_adapter}->show_captcha_image();
		
		$text = $this->captcha_text = $this->{$this->_adapter}->get_captcha_text();
		$this->_store_captcha($text);
	}
	
	//------------------------------------------------------
	
	/**
	 * 发送一个邮箱验证码
	 * @param string $email 要验证码的邮箱
	 * @param string $send_adapter 邮件发送驱动(可选),默认使用队列发送
	 * @return boolean
	 */
	public function send_email_captcha($email, $send_adapter = 'queue')
	{
		// 加载驱动
		$this->CI->load->driver('email', array(
				'adapter' => $send_adapter // 使用队列发送
		));
		$this->CI->load->helper('string');
		
		$code = $this->captcha_text = strtolower(random_string('alnum', 4));
		
		$this->CI->email->add_address($email);  // 收件人邮箱
		$this->CI->email->set_subject('众划算-邮箱认证(系统邮件，请勿回复)'); // 邮件主题
		$this->CI->email->set_body_tempate_var('code', $code);
		$this->CI->email->set_body_template('email');
		$this->CI->email->is_html();  // send as HTML
		
		if ( ! $this->CI->email->send()) {
			return FALSE;
		}
		
		// 保存验证码到缓存中1200秒(20分钟)
		$this->_store_captcha($code);
		return TRUE;
	}
	
	//------------------------------------------------------
	
	/**
	 * 保存验证码图片到文件
	 * @param string $filename 文件名
	 */
	public function save_captcha_for_file($filename)
	{
		$this->{$this->_adapter}->save_captcha_for_file($filename);
		
		$text = $this->captcha_text = $this->{$this->_adapter}->get_captcha_text();
		$this->_store_captcha($text);
	}
	
	//------------------------------------------------------
	
	/**
	 * 设置缓存的KEY
	 * @param string $store_key
	 * @return void
	 */
	public function set_store_key($store_key)
	{
		$this->_store_key = $store_key;
		$this->_session_time_var = $this->_store_key.'_exp_time';
	}
	
	//------------------------------------------------------
	
	/**
	 * 设置存储的驱动
	 * @param string $store_adapter
	 * @return void
	 */
	public function set_store_adapter($store_adapter)
	{
		if (in_array($store_adapter, array(self::STORE_ADAPTER_CACHE, self::STORE_ADAPTER_SESSION))) {
			$this->_store_adapter = $store_adapter;
		}
	}
	
	//------------------------------------------------------
	
	/**
	 * 获取验证码文本
	 * @return string
	 */
	public function get_captcha_text()
	{
		return $this->captcha_text;
	}
	
	//------------------------------------------------------
	
	/**
	 * 存储验证码用于校验
	 * @param $text 要保存的验证码字符
	 * @return boolean
	 */
	private function _store_captcha($text)
	{
		if ($this->_store_adapter == self::STORE_ADAPTER_CACHE) {
			$this->CI->cache->save($this->_store_key, $text, $this->_store_expiration);
		}else {
			$code = array(
					$this->_store_key => $text,
					$this->_session_time_var => TIMESTAMP
			);
			$this->CI->session->set_userdata($code);
		}
	}
	
	//------------------------------------------------------
	
	/**
	 * 校验验证码是否正确
	 * @param $code 要校验的验证码
	 * @return boolean
	 */
	public function check($code)
	{
		if ($this->_store_adapter == self::STORE_ADAPTER_CACHE) {
			$cache_data = $this->CI->cache->get($this->_store_key);
			if ( $cache_data !== FALSE AND strval($cache_data) === strval($code)) {
				return TRUE;
			}
		}else {
			// 验证码保存的时间
			$session_settime = $this->CI->session->userdata($this->_session_time_var);
			// 验证码存在或者过期?
			if ($session_settime === FALSE OR (TIMESTAMP-$session_settime) > $this->_store_expiration) {
				$this->cleanup();
				return FALSE;
			}
			
			// 对比验证码
			if ($this->CI->session->userdata($this->_store_key) == $code) {
				return TRUE;
			}
		}

		return FALSE;
	}
	
	//------------------------------------------------------
	
	/**
	 * 清除验证码session(缓存)
	 * @return void
	 */
	public function cleanup()
	{
		if ($this->_store_adapter == self::STORE_ADAPTER_CACHE) {
			$this->CI->cache->delete($this->_store_key);
		}else {
			$this->CI->session->unset_userdata(array(
					$this->_store_key => '',
					$this->_session_time_var => '',
			));
		}
	}
	
	//------------------------------------------------------
	
	/**
	 * 被请求的驱动是否已开启
	 *
	 * @param string 要测试的驱动.
	 * @return bool
	 */
	public function is_open($driver)
	{
		static $support = array();
	
		if ( ! isset($support[$driver]))
		{
			$support[$driver] = $this->{$driver}->is_open();
		}
	
		return $support[$driver];
	}
	
	//------------------------------------------------------
	
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
			show_error("{$child} captcha does not open");
		}
	
		return $obj;
	}
	
	//------------------------------------------------------
	
	/**
	 * 发送频率检测
	 * @return boolean
	 */
	private function check_frequency()
	{
		// 发送频率检测
		$this->_frequency_cache_data = $this->CI->cache->get($this->_frequency_cache_key);

		if ($this->_frequency_cache_data) {
			// 更新发送次数
			isset($this->_frequency_cache_data['sendnum']) && $this->_frequency_cache_data['sendnum'] += 1;
			// 检测是否被限制发送
			if (isset($this->_frequency_cache_data['LIMIT_START_TIME']) && $this->_frequency_cache_data['LIMIT_START_TIME'] > 0) {
				// 限制的剩余时间
				$surplus_time = 3600 - (TIMESTAMP - intval($this->_frequency_cache_data['LIMIT_START_TIME']));
				$message = '获取语音激活码过于频繁，请稍后再试！';
				$this->set_error('SEND_NUM_EXCEED', $message, array('SURPLUS_TIME'=>$surplus_time));
				
				return FALSE;
			}
			
			// 已经发送了5次(60分钟内)
			if ($this->_frequency_cache_data['sendnum'] > 5) {
				/*
				 * 判断是否是在60分钟内连续发的
				 */
				if ((TIMESTAMP - $this->_frequency_cache_data['sendstarttime']) <= $this->_store_expiration) {
					// 错误提示
					$this->set_error('SEND_NUM_EXCEED', '获取语音激活码过于频繁，请稍后再试！', array('SURPLUS_TIME'=>$this->_frequency_freeze_time));
					// 添加限制发送
					$this->CI->cache->save($this->_frequency_cache_key, array('LIMIT_START_TIME'=>TIMESTAMP), $this->_frequency_freeze_time);
					
					return FALSE;
				} else {
					/*
					 * 清空数据，重新设置发送频率
					 */
					$this->_frequency_cache_data = array();
					$this->CI->cache->delete($this->_frequency_cache_key);
					
					return TRUE;
				}
				
			} elseif ((TIMESTAMP - $this->_frequency_cache_data['sendlasttime']) < 60) {
				// 发送间隔是60秒
				$this->set_error('SEND_FREQUENCY_TOO_FAST', '获取语音激活码频率过快，1分钟内只能发送一次');
				
				return FALSE;
			}
		}
		
		return TRUE;
	}
	
	//------------------------------------------------------
	
	/**
	 * 更新设置发送频率
	 * @return void
	 */
	private function update_frequency()
	{
		if ( ! $this->_frequency_cache_data) {
			$this->_frequency_cache_data['sendstarttime'] = TIMESTAMP; // 第一次发送时间
			$this->_frequency_cache_data['sendnum'] = 1;
		}
		
		$this->_frequency_cache_data['sendlasttime'] = TIMESTAMP; // 更新最后发送时间

		$this->CI->cache->save($this->_frequency_cache_key, $this->_frequency_cache_data, $this->_store_expiration);
	}
	
	//------------------------------------------------------
	
	/**
	 * 获取错误信息
	 * <code>
	 * array(
	 * 		'code' 	=> 'ERROR_CODE',
	 * 		'msg'	=> '错误信息'
	 * )
	 * </code>
	 * @return aray()
	 */
	public function get_error()
	{
		return $this->_error_message;
	}
	
	//------------------------------------------------------
	
	/**
	 * 设置一个错误信息
	 * @param string $code		错误代码(必须全大写)
	 * @param string $message	错误信息
	 * @param array	$data		额外返回的数据
	 * @return void
	 */
	private function set_error($code, $message, $data = array())
	{
		$this->_error_message = array(
				'code'	=> $code,
				'msg'	=> $message,
				'data'	=> $data,
		);
	}
}
// End Captcha Class

// ------------------------------------------------------------------------

/**
 * 适配器接口
 *
 * 要求每个适配器必须实现这个接口定义的办法
 *
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 *
 */
interface Captcha_driver
{
	/**
	 * 显示验证码
	 * @return void
	 */
	public function show_captcha_image();
	
	//--------------------------------------------
	
	/**
	 * 保存验证码到图片文件
	 * @return void
	 */
	public function save_captcha_for_file($filename);

	//---------------------------------------------
	
	/**
	 * 获取验证码文本
	 * @return string;
	 */
	public function get_captcha_text();
	
	//---------------------------------------------
	
	/**
	 * 是否开放
	 * @return boolean
	*/
	public function is_open();
}