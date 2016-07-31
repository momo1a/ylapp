<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 邮件发送适配器
 *
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 * @link http://www.zhonghuasuan.com
 */
class Email extends CI_Driver_Library
{
	protected $valid_drivers 		= array(
			'email_queue', 'email_ci', 'email_default'
	);
	
	protected $_adapter				= 'default';
	protected $_backup_driver;
	
	protected $_email_template_path = 'template';
	
	public $_subject;
	public $_body;
	public $_address				= array();
	
	private $_body_template;
	private $_use_template			= FALSE;
	private $_body_template_var		= array();
	
	// ------------------------------------------------------------------------
	
	/**
	 * Constructor
	 *
	 * @param array
	 */
	public function __construct($config = array())
	{
		if ( ! empty($config))
		{
			$this->_initialize($config);
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * 发送邮件
	 * @param string $subject	邮件主题(可选)注意：覆盖之前的设置
	 * @param string $body		邮件内容(可选)注意：覆盖之前的设置
	 * @param array/string $address	邮件发送地址(可选)注意：覆盖之前的设置
	 * @return boolean
	 */
	public function send($subject = NULL, $body = NULL, $address = NULL)
	{
		if ( ! is_null($subject)) {
			$this->_subject = $subject;
		}
		
		if ( ! is_null($body)) {
			// 直接覆盖之前设置的邮件内容
			$this->_body = $body;
			
		}elseif ($this->_use_template) {
			
			// 完整的模板路径
			$template = $this->_email_template_path.'/'.$this->_body_template;
			
			$CI =& get_instance();
			$this->_body = $CI->load->view($template, $this->_body_template_var, TRUE);
		}
		
		if ( ! is_null($address)) {
			if ( ! is_array($address)) {
				$address = array($address);
			}
			// 直接覆盖
			$this->_address = $address;
		}

		return $this->{$this->_adapter}->send();
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * 设置邮件主题
	 * @param string $subject 邮件主题
	 * @return Email
	 */
	public function set_subject($subject)
	{
		$this->_subject = $subject;
		return $this;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * 直接设置邮件内容
	 * 不使用模板
	 * @param string $body 邮件内容
	 * @return Email
	 */
	public function set_body($body)
	{
		$this->_body = $body;
		return $this;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * 设置邮件内容模板
	 * @param string $template 内容模板
	 * @return Email
	 */
	public function set_body_template($template)
	{
		$this->_body_template = $template;
		$this->_use_template = TRUE;
		return $this;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * 添加一个邮件地址
	 * @param array $address 邮件地址
	 * @return Email
	 */
	public function add_address($address)
	{
		if (is_string($address)) {
			$address = array($address);
		}
		
		foreach ($address as $val) {
			$this->_address[] = $val;
		}
		
		return $this;
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 添加一个附件.
	 * 文件可以包括图像/声音或者任何其他类型的文件.
     * @param string $path		附件路径.
     * @param string $embedded	是否是嵌入式附件（内联）.
     * @param string $cid Content ID of the attachment; Use this to reference
     *        the content when using an embedded image in HTML.
     * @return boolean
     */
	public function add_attachment($path, $embedded = FALSE, $cid = NULL)
	{
		return $this->{$this->_adapter}->add_attachment($path, $embedded, $cid);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * 设置邮件模板变量
	 * @param string $var		模板变量
	 * @param string $var_val	变量值
	 * @return Email
	 */
	public function set_body_tempate_var($var, $var_val = NULL)
	{
		if ( ! is_array($var)) {
			$var = array($var => $var_val);
		}
		
		foreach($var as $key=>$val) { 
			$this->_body_template_var[$key] = $val;
		}
		
		return $this;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * 设置消息的类型HTML或纯文本
	 * @param boolean $isHtml 默认HTML模式.
	 * @return Email
	 */
	public function is_html($is_html = TRUE)
	{
		$this->{$this->_adapter}->is_html($is_html);
		return $this;
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
	
		if (isset($config['backup']))
		{
			if (in_array('email_'.$config['backup'], $this->valid_drivers))
			{
				$this->_backup_driver = $config['backup'];
			}
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Is the requested driver supported in this environment?
	 *
	 * @param 	string	The driver to test.
	 * @return 	array
	 */
	public function is_supported($driver)
	{
		static $support = array();
	
		if ( ! isset($support[$driver]))
		{
			$support[$driver] = $this->{$driver}->is_supported();
		}
	
		return $support[$driver];
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * __get()
	 *
	 * @param 	child
	 * @return 	object
	 */
	public function __get($child)
	{
		$obj = parent::__get($child);
	
		if ( ! $this->is_supported($child))
		{
			$this->_adapter = $this->_backup_driver;
		}
	
		return $obj;
	}
	
	// ------------------------------------------------------------------------
}

// ------------------------------------------------------------------------

/**
 * 适配器接口
 *
 * 要求每个适配器必须实现这个接口定义的办法
 *
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 *
 */
interface Email_driver
{
	/**
	 * 邮件发送
	 * @return boolean
	 */
	public function send();

	//--------------------------------------------
	
	/**
     * 添加一个附件.
	 * 文件可以包括图像/声音或者任何其他类型的文件.
     * @param string $path		附件路径.
     * @param string $embedded	是否是嵌入式附件（内联）.
     * @param string $cid Content ID of the attachment; Use this to reference
     *        the content when using an embedded image in HTML.
     * @return boolean
     */
	public function add_attachment($path, $embedded = FALSE, $cid = NULL);
	
	//--------------------------------------------
	
	/**
	 * 设置消息的类型HTML或纯文本
	 * @param boolean $is_html 是否HTML
	 */
	public function is_html($is_html);
	
	//--------------------------------------------

	/**
	 * 是否支持
	 * @return boolean
	*/
	public function is_supported();
}