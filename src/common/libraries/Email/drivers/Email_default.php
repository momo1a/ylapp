<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 发送邮件默认驱动
 *
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 * @link http://www.zhonghuasuan.com
 */
class Email_default extends CI_Driver implements Email_driver
{
	private $CI;
	
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library('phpmailer');
	}
	
	/**
	 * 发送邮件
	 * @see Email_driver::send()
	 */
	public function send()
	{
		$this->CI->phpmailer->Subject	= $this->_subject;
		$this->CI->phpmailer->Body		= $this->_body;
		
		foreach ($this->_address as $address)
		{
			$this->CI->phpmailer->AddAddress($address);  // 收件人邮箱
		}
		
		return $this->CI->phpmailer->Send();
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
		if ($embedded && $cid) {
			return $this->CI->phpmailer->AddEmbeddedImage($path, $cid, 'attachment', 'base64', 'image/jpeg');
		}else {
			return $this->CI->phpmailer->AddAttachment($path);
		}
	}

	/**
	 * 设置消息的类型HTML或纯文本
	 * @param boolean $isHtml 模式.
	 * @see Email_driver::is_html()
	 */
	public function is_html($is_html)
	{
		$this->CI->phpmailer->IsHTML($is_html);
	}

	/**
	 * 是否支持
	 * @see Email_driver::is_supported()
	 */
	public function is_supported()
	{
		return TRUE;
	}	
}