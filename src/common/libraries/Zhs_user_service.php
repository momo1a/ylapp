<?php

/**
 * 通用用户类库，主要用于注册、修改密码、找回密码等
 *
 */
class Zhs_user_service
{
	/**
	 * 保存状态
	 * @var array
	 */
	protected $flag;
	
	protected $CI;
	
	public function __construct()
	{
		$this->CI = &get_instance();
	}
	/**
	 * 获取上次操作的状态及数据
	 *
	 * @return array
	 * @author 杜嘉杰
	 * @version 2015年6月16日 上午10:22:19
	 */
	public function flag()
	{
		return $this->flag;
	}
	
	/**
	 * 获取上次操作的状态
	 * 
	 * @return string/int:
	 * 
	 * @author 杜嘉杰
	 * @version 2015年7月16日 上午10:21:29
	 */
	public function flag_code()
	{
		return $this->flag['code'];
	}
	
	/**
	 * 获取上次操作的状态文字描述
	 * 
	 * @return string:
	 * 
	 * @author 杜嘉杰
	 * @version 2015年7月16日 上午10:21:46
	 */
	public function flag_msg()
	{
		return $this->flag['msg'];
	}
	
	/**
	 * 获取上次操作的数据
	 * 
	 * @return array
	 * 
	 * @author 杜嘉杰
	 * @version 2015年7月16日 上午10:22:42
	 */
	public function flag_data()
	{
		return $this->flag['data'];
	}
	
	/**
	 * 设置操作的状态及数据
	 *
	 * @param int/string $code 状态码
	 * @param unknown $msg 文字描述
	 * @param string $data 附加数据
	 *
	 * @author 杜嘉杰
	 * @version 2015年6月16日 上午10:21:11
	 */
	protected function set_flag($code, $msg, $data = NULL)
	{
		$this->flag = array('code'=>$code, 'msg'=>$msg, 'data'=>$data);
	}
	
	/**
	 * 检测密码
	 * 
	 * 返回错误码：
	 * PWD_NOT_SAME：您输入的密码不一致，请重新输入。
	 * PWD_ALL_NUMBER：密码不能为纯数字。
	 * PWD_ALL_LETTER：密码不能为纯字母。
	 * PWD_FORMAT_WRONG：密码为6-20个字符，请使用字母加数字或下划线组合密码。
	 * PWD_NOT_STRONG：密码不能包含账户名，您设置的密码过于简单，请设置复杂的密码
	 * 
	 * @return boolean
	 */
	public function check_password($username, $password, $confirm_password){
		if( $password != $confirm_password ){
			$this->set_flag('PWD_NOT_SAME', '您输入的密码不一致，请重新输入。');
			return FALSE;
		}
		if( preg_match ( '/^\d*$/', $password ) ){
			$this->set_flag('PWD_ALL_NUMBER', '密码不能为纯数字。');
			return FALSE;
		}
		if( preg_match ( '/^[a-zA-Z]+$/', $password ) ){
			$this->set_flag('PWD_ALL_LETTER', '密码不能为纯字母。');
			return FALSE;
		}
		if( !preg_match( '/^[_\w]{6,20}$/' , $password) ){
			$this->set_flag('PWD_FORMAT_WRONG', '密码为6-20个字符，请使用字母加数字或下划线组合密码。');
			return FALSE;
		}
	
		//为弱密码，则不通过
		if( ! check_strong_passwd( $username, $password ) ){
			$this->set_flag('PWD_NOT_STRONG', '密码不能包含账户名，您设置的密码过于简单，请设置复杂的密码');
			return FALSE;
		}
	
		return TRUE;
	}
	
	/**
	 * 修改app登录sign
	 * 
	 * @param int $uid 用户uid
	 * @return boolean
	 * 
	 * @author 杜嘉杰
	 * @version 2015年7月16日 上午10:20:58
	 */
	public function set_app_sign($uid)
	{
		$this->CI->load->helper('string');
		$login_sign = random_string('md5');
		
		$this->CI->load->model('zhs_user_model');
		$re =  $this->CI->zhs_user_model->update($uid, array('login_sign'=>$login_sign) );
		if( ! $re)
		{
			$this->set_flag('REFRESH_SIGN_ERROR', '更新签名失败');
			return FALSE;
		}
		
		$this->CI->load->library('zhs_http');

		// 请求app站点更新用户的缓存
		$url = config_item('domain_appsystem');
		$key = KEY_APP_SERVER;
		if( !$url || !$key)
		{
			log_message('error', '缺少配置：domain_appsystem或KEY_APP_SERVER');
			$this->set_flag('CONDIG_ONT_FIND', '系统错误，请稍后重试');
			return FALSE;
		}
		$params = array(
			'uid'=>$uid,
			'sign' => md5($uid.$key)
		);
		$this->CI->zhs_http->post($url.'app/flush_user', $params);

		$this->set_flag('SUCCESS', '新的签名', array('login_sign'=>$login_sign));
		return TRUE;
	}
	
}
