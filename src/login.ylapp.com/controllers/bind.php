<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 第三方登录绑定处理
 * 
 * @property User_model	$user_model
 * @property User_login_bind_model	$user_login_bind_model
 * @property Auth				$auth
 * 
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 * 
 */
class Bind extends MY_Controller
{
	private $third_party_user = array();
	
	private $openuserkey = NULL;
	
	private $_to_url	= '';
	
	private $_error_meesage = array();
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model(array('user_model','user_login_bind_model','uc_onlineusers_model'));
	}
	
	/**
	 * 映射方法
	 *
	 * 用于缓存的调用
	 *
	 * @param string $method		请求的方法
	 * @param array $method_param	请求的参数
	 *
	 * @return void
	 */
	public function _remap($method, $method_param)
	{
		$this->_to_url = $this->input->post('to_url');
		
		$this->openuserkey = $this->input->post('openuserkey');
		$this->third_party_user = $this->openuserkey ? $this->cache->get($this->openuserkey) : array();
		if ( ! $this->third_party_user) {
			$this->_show_message_json(FALSE, '授权已过期，请重新申请授权');
		}
		
		// 执行请求的方法.
		call_user_func_array(array(&$this, $method), $method_param);
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 绑定到一个现有账户
	 * 
	 * 能正常登录的用户才可以绑定,
	 * 同种登录方式一个众划算账号只能绑定一个.
	 * 
	 * @return void
	 */
	public function account()
	{
		$account	= trim($this->input->post('account'));
		$password	= $this->input->post('password'); // 密码

		// 从UC中获取用户
		$uc_user = $this->user_model->find_ucuser_by_account($account);
		
		if ( ! $uc_user) {
			$this->_show_message_json(FALSE, Auth::$login_error_message[Auth::LOGIN_STATUS_NOT_FOUND]);
		}
		
		if ($uc_user['password'] != $this->user_model->hash_password(md5($password), $uc_user['salt'])) {
			$this->_show_message_json(FALSE, Auth::$login_error_message[Auth::LOGIN_STATUS_WRONG_PASSWORD]);
		}
		
		if ($uc_user['type'] != User_model::USER_TYPE_BUYER) {
			$this->_show_message_json(FALSE, '抱歉，商家帐号暂时不支持QQ登录功能');
		}
		
		if ($this->user_login_bind_model->has_bind($uc_user['uid'], $this->third_party_user['type'])) {
			$this->_show_message_json(FALSE, '该众划算帐号已绑定其他QQ');
		}
		
		//检测帐号弱密码
		if( ! check_strong_passwd($account,$password)) {
			$this->_show_message_json(FALSE, '密码过于简单，请立即修改', 'WRONG_SIMPLE_PASSWORD');
		}
		
		// 检测UC状态
		if ( ! $this->auth->check_uc_user($uc_user)) {
			$error = $this->auth->get_error();
			$this->_show_message_json(FALSE, Auth::$login_error_message[$error]);
		}
		
		// 获取本地用户
		if ($local_user = $this->user_model->find_local_user($uc_user['uid'])) {
			// 检测众划算本地用户状态
			if ( ! $this->auth->check_local_user($local_user)) {
				$error = $this->auth->get_error();
				$this->_show_message_json(FALSE, Auth::$login_error_message[$error]);
			}
		}elseif ( ! $this->auth->sync_user($uc_user)) { // 同步用户到众划算
			// 同步不成功将不执行登录
			log_message('error', '无法从用户中心同步用户信息，UID:'.$uc_user['uid']);
			$this->_show_message_json(FALSE, '服务器错误:无法从用户中心同步你的信息');
		}
		
		// 绑定账号
		$this->user_login_bind_model->fresh_bind($uc_user['uid'], $uc_user['username'], $this->third_party_user['type'], $this->third_party_user);
		// 更新登录状态并保持登录信息
		AuthUser::save_login($uc_user['uid'], $account, $uc_user['type'] , 1, FALSE);
		// 清除session
		$this->_cleanup_auth();
		
		$this->_show_message_json(TRUE);
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 注册一个新账号绑定
	 * 
	 * @return void
	 */
	public function register()
	{
		$email	= trim($this->input->post('email'));
		$code	= trim($this->input->post('code'));
		
		// 验证邮件验证码
		if ( ! $code || ! check_email_code($email, $code, 'login')) {
			$this->_show_message_json(FALSE, '验证码错误');
		}
		
		if ( ! $email || ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) {
			$this->_show_message_json(FALSE, '不是正确的邮箱地址');
		}
		
		if ($this->user_model->uc_email_exists($email)) {
			$this->_show_message_json(FALSE, '邮箱已被使用');
		}

		// 注册绑定用户
		if ($this->_save_new_user('email', $email)) {
			// 清除邮件验证码
			cleanup_email_code($email, 'login');
			// 输出
			$this->_show_message_json(TRUE);
		}else {
			// 输出错误信息
			$this->_show_message_json();
		}
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 手机注册绑定众划算用户
	 * 不再判断图形验证码,只对语音验证码进行验证
	 * @access public
	 * @return void
	 */
	public function mobile_register()
	{
		$this->load->driver('captcha');
		
		$mobile	= trim($this->input->post('mobile'));
		$code	= trim($this->input->post('code'));
		
		if ( ! $mobile || ! $this->_is_mobile($mobile)) {
			$this->_show_message_json(FALSE, '手机号码格式不正确，请重新输入');
		}
		
		// 设置缓存KEY
		$this->captcha->set_store_key(md5($mobile));
		if ( ! $code || ! $this->captcha->check($code)) {
			$this->_show_message_json(FALSE, '语音验证码错误');
		}

		if ($this->user_model->uc_mobile_exists($mobile)) {
			$this->_show_message_json(FALSE, '你填写的手机号码已被认证');
		}
		
			//推广跟踪记录
		// 注册绑定用户
		if ($this->_save_new_user('mobile', $mobile)) {
			// 清除语音验证码
			$this->captcha->cleanup();
			// 输出
			$this->_show_message_json(TRUE);
		}else {
			// 输出错误信息
			$this->_show_message_json();
		}
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 快速登录
	 *
	 * 在第三方登录账号完成授权后,
	 * 如果用户没有绑定已有账号或者新注册一个账号绑定,
	 * 那可以通过该方法让系统自动生成一个众划算账号,默认的账号名是YL来源__时间戳,密码随机
	 * 
	 * @return void
	 */
	public function fast()
	{
		$this->load->helper('string');
		$username	= 'YL' . $this->third_party_user['type'] . '_' . TIMESTAMP . random_string('numeric', 1);
		
		// 在正常的情况不会出现用户名已存在的问题,不排除数据出错的遗留问题
		if ($this->user_model->uc_username_exists($username)) {
			exit(json_encode(array('message' => '服务器错误', 'state' => FALSE)));
		}
		
		// 随机密码
		$salt 		= random_string('alnum', 6);
		$password 	= md5($salt);
		$password	= $this->user_model->hash_password($password, $salt);
		
		// 要写入UC的用户数据
		$uc_data	= array(
				'username' 		=> $username,
				'password' 		=> $password,
				'email'			=> '',
				'mobile'		=> '',
				'mobile_valid'	=> YL_uc_user_model::MOBILE_AUTH_NOT,
				'salt'			=> $salt,
				'VnetPayPswd' 	=> md5($salt),
				'uTypeid'		=> YL_user_model::USER_TYPE_BUYER, // 快速登录只开放给普通用户
				'reg_source' 	=> 3 // 用户中心注册来源为众划算（3）
		);
		$this->load->helper('cookie');
		// 本地用户附加数据
		$local_data = array('reg_from' => $this->third_party_user['type'], 'reg_source' => 3, 'reg_from_url' =>get_cookie('referrer'));

		if ($uid = $this->user_model->insert($uc_data, $local_data)) { // 写入用户数据

			$this->user_login_bind_model->fresh_bind($uid, $username, $this->third_party_user['type'], $this->third_party_user);
			
			// 保存登录,由于邮箱没有激活,不更新联盟、支付的登录状态
			// 前台用户是通过cookie获取的，这里的用户名就只能以【请完善资料】写入cookie
			AuthUser::save_login($uid, $username, YL_user_model::USER_TYPE_BUYER, 0);
			// 清除第三方用户授权信息
			$this->_cleanup_auth();
			
			//推广跟踪记录
			try {
				$this->load->model('analytics_model');
				$this->analytics_model->record($uid,$username,User_model::USER_TYPE_BUYER,$this->input->ip_address(),TIMESTAMP,'',$this->third_party_user['type']);
			}catch(Exception $e){}
			
			$this->_show_message_json(TRUE);
		}else {
			log_message('用户写入失败:快速登录');
			
			$this->_show_message_json(FALSE, '注册失败');
		}
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 发送语音验证码
	 * @return void
	 */
	public function send_sound_captcha()
	{
		$this->load->driver('captcha');
		
		$srt_captcha = trim($this->input->post('captcha'));
		$mobile = trim($this->input->post('mobile'));

		// 验证码图形验证码
		if ($srt_captcha && $this->captcha->check($srt_captcha)) {
			// 清除图形验证码
			$this->captcha->cleanup();
			
			if ( ! $mobile || ! $this->_is_mobile($mobile)) {
				$this->_show_message_json(FALSE, '手机号码格式不正确，请重新输入');
			}
			
			if ($this->user_model->uc_mobile_exists($mobile)) {
				$this->_show_message_json(FALSE, '你填写的手机号码已被认证');
			}
			
			// 判断互联支付手机号是否重复
			$this->load->library('hlpay_v2');
			if($this->hlpay_v2->hlpay_user_exixts($mobile,'mobile'))
			{
				$this->_show_message_json(FALSE,'该手机号已在互联支付使用，请更换其他的手机号。');
			}

			// 设置缓存KEY
			$this->captcha->set_store_key(md5($mobile));
			// 发送语音验证码
			if ($this->captcha->send_sound_captcha($mobile)) {
				$this->_show_message_json(TRUE);
			} else {
				$error = $this->captcha->get_error();
				$this->_show_message_json(FALSE, $error['msg'], $error['code'], $error['data']);
			}
		} else {
			$this->_show_message_json(FALSE, '图形验证码错误或者已过期', 'IMGCAPTCHA_ERROR');
		}
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 显示验证码
	 */
	public function captcha()
	{
		$this->load->driver('captcha');
		$this->captcha->show_captcha_image();
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 注册绑定新账号
	 * @param sting $act_type
	 * @param sting $val
	 * @return boolean
	 */
	private function _save_new_user($act_type, $val)
	{
		$username	= trim($this->input->post('username'));
		$password	= $this->input->post('password'); // 密码
		
		if ($this->user_model->check_keyword_username($username)) {
			$this->_set_error('您输入的用户名不允许使用，请重新输入');
			return FALSE;
		}
		
		if ( ! $username || $this->user_model->uc_username_exists($username)) {
			$this->_set_error('用户名已被使用');
			return FALSE;
		}
		
		if( ! preg_match( '/^[_\w]{6,20}$/' , $password)) {
			$this->_set_error('密码为6-20个字符，请使用字母加数字或下划线组合密码');
			return FALSE;
		}
		
		// 检测帐号弱密码
		if( ! check_strong_passwd( $username,$password )) {
			$this->_set_error('密码不能包含账户名，您设置的密码过于简单，请设置复杂的密码');
			return FALSE;
		}
		
		// 密码设置
		$this->load->helper('string');
		
		$salt 				= random_string('alnum', 6);
		$vnetpay_password	= md5($password); // 互联支付密码
		$password			= $this->user_model->hash_password(md5($password), $salt);
		$reg_from			= $this->third_party_user['type'];
		
		// 要写入UC的用户数据
		$uc_data = array(
				'username'		=> $username,
				'password' 		=> $password,
				'salt'			=> $salt,
				'VnetPayPswd'	=> $vnetpay_password,
				'uTypeid'		=> YL_user_model::USER_TYPE_BUYER, // 快速登录只开放给普通用户
				'reg_source' 	=> 3, // 用户中心注册来源为众划算（3）
				'email'			=> '',
				'mobile'		=> '',
				'mobile_valid'	=> YL_uc_user_model::MOBILE_AUTH_NOT,
		);
		
		/*
		 * 注册认证类型判断
		 */
		if ($act_type == 'email') {
			// 邮箱认证
			$uc_data['email'] = $val;
		}elseif ($act_type == 'mobile') {
			// 手机认证
			$uc_data['mobile'] = $val;
			$uc_data['mobile_valid'] = YL_uc_user_model::MOBILE_AUTH_YES; // 已认证
		}

		// 本地用户附加数据
		$local_data = array('reg_from' => $reg_from, 'reg_source' => 3, 'reg_from_url' => $this->input->cookie('referrer'));
		
		// 写入用户数据
		if ($uid = $this->user_model->insert($uc_data, $local_data)) {
			// 绑定
			$this->user_login_bind_model->fresh_bind($uid, $username, $reg_from, $this->third_party_user);
			// 更新UC中心的用户在线状态
			$this->uc_onlineusers_model->update($uid, $username, $password, YL_user_model::USER_TYPE_BUYER);
		
			//推广跟踪记录
			try {
				$this->load->model('analytics_model');
				$this->analytics_model->record($uid,$username,User_model::USER_TYPE_BUYER,$this->input->ip_address(),TIMESTAMP,$reg_from);
			}
			catch(Exception $e){}
			
			// 保存登录
			AuthUser::save_login($uid, $username, YL_user_model::USER_TYPE_BUYER);
			
			// 清除第三方授权信息
			$this->_cleanup_auth();

			return TRUE;
		}else {
			log_message('用户写入失败:注册一个新账号绑定');
			
			$this->_set_error('注册失败');
			return FALSE;
		}
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 手机号码验证
	 * @param string $mobile 手机号码
	 * @return boolean 如果是正确的手机号码则返回TRUE
	 */
	private function _is_mobile($mobile)
	{
		return preg_match("/^1[34578]\d{9}$/", $mobile) ? TRUE : FALSE;
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 写入一个错误信息
	 * @param string $message
	 * @param string $code
	 * @return void
	 */
	private function _set_error($message, $code = '')
	{
		$this->_error_meesage = array(
				'state' => FALSE,
				'message' => $message,
				'code' => $code
		);
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 显示一个JSON信息
	 * @param string $ret		结果标识(成功true/失败false)
	 * @param string $message	提示信息
	 * @param string $code		提示码
	 * @param array	$data		额外返回的数据
	 * 
	 * @return void
	 */
	private function _show_message_json($ret = NULL, $message = '', $code = '', $data = array())
	{
		if ($ret === NULL) {
			$ret = $this->_error_meesage;
		}elseif ( ! is_array($ret)) {
			$ret = array(
					'state'		=> $ret,
					'message'	=> $message,
					'code'		=> $code,
					'data'		=> $data,
			);
		}
		// #TODO
		header('Content-type:application/json');
		exit(json_encode($ret));
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 清除保存开放用户的cache
	 * @return void
	 */
	private function _cleanup_auth()
	{
		$this->cache->delete($this->openuserkey);
	}

}
/* End of file bind.php */
/* Location: ./application/controllers/bind.php */