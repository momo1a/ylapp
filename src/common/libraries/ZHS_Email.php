<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 邮件发送
 * 
 * 修正中文邮件标题的乱码问题
 * 
 * @author "韦明磊<nicolaslei@163.com>"
 *
 */
class YL_Email extends CI_Email
{
	public function subject($subject)
	{
		$subject = '=?'. $this->charset .'?B?'. base64_encode($subject) .'?=';
		$this->_set_header('Subject', $subject);
		return $this;
	}
}