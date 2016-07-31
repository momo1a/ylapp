<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 发送邮件-队列驱动
 *
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 * @link http://www.zhonghuasuan.com
 */
class Email_queue extends CI_Driver implements Email_driver
{
	private $_stomp = NULL;
	
	private $_queue = '';
	
	private $_is_html = '';
	
	private $_attachments = array();

	/**
	 * 发送邮件
	 * @see Email_driver::send()
	 */
	public function send()
	{
		$return = TRUE;
		foreach ($this->_address as $address)
		{
			$arr_message = array(
					'to'		=> $address,
					'title'		=> $this->_subject,
					'content'	=> $this->_body,
					'html'		=> $this->_is_html
			);
			if ($this->_attachments) {
				$arr_message['attachments'] = $this->_attachments;
			}
			$message = json_encode($arr_message);

			if ( ! $this->_stomp->send($this->_queue, $message)) {
				$return = FALSE;
				log_message('error', "邮件:{$address}发送失败,错误原因:".$this->_stomp->error());
			}
		}
		
		return $return;
	}
	
	/**
     * 添加一个附件.
	 * 文件可以包括图像/声音或者任何其他类型的文件.
     * @param string $path		附件路径.
     * @param string $embedded	是否是嵌入式附件（内联）.
     * @param string $cid Content ID of the attachment; Use this to reference
     *        the content when using an embedded image in HTML.
     * @return boolean
     * @see Email_driver::add_attachment()
     */
	public function add_attachment($path, $embedded = FALSE, $cid = NULL)
	{
		$str_file = @file_get_contents($path);
		$attachment = array('file' => base64_encode($str_file));
		
		if ($embedded) {
			$attachment['name'] = $cid;
			$attachment['disposition'] = 'inline';
		}else {
			$arr_path = explode(DIRECTORY_SEPARATOR, $path);
			$attachment['name'] = end($arr_path);
			$attachment['disposition'] = 'attachment';
		}
		
		$this->_attachments[] = $attachment;
		
		return TRUE;
	}

	/**
	 * 设置消息的类型HTML或纯文本
	 * @param boolean $isHtml 模式.
	 * @see Email_driver::is_html()
	 */
	public function is_html($is_html)
	{
		$this->_is_html = $is_html ? 'true' : 'false';
	}

	/**
	 * 是否支持
	 * @see Email_driver::is_supported()
	 */
	public function is_supported()
	{
		if ( ! extension_loaded('Stomp')) {
			log_message('error', 'Stomp扩展没有安装.');
			return FALSE;
		}
		
		return $this->_setup_stomp();
	}
	
	/**
	 * Setup stomp.
	 */
	private function _setup_stomp()
	{
		// 加载配置文件.
		$CI =& get_instance();
		if ($CI->config->load('stomp', TRUE, TRUE)) {
			$config = $CI->config->item('stomp');
			// Stomp:destination
			$this->_queue = $config['email_queue'];
			
			try {
			    $this->_stomp = new Stomp($config['broker'], $config['name'], $config['password']);
			    return TRUE;
			} catch(StompException $e) {
			    log_message('error', 'Stomp开启失败:'.$e->getMessage());
				return FALSE;
			}
		}else {
			log_message('error', 'Stomp配置文件没有找到.');
			return FALSE;
		}
	}
}