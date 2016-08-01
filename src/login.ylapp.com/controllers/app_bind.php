<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * app第三方登录绑定处理
 * 
 * @property User_model	$user_model
 * @property User_login_bind_model	$user_login_bind_model
 * @property Auth	 $auth
 *  @author yangjiguang
 */
class App_bind extends CI_Controller
{
	private $third_party_user = array();
	
	private $openuserkey = NULL;
	
	private $_to_url	= '';
	
	private $_error_meesage = array();
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('auth');
		$this->load->library('authUser');
		$this->load->library('Cache_memcached', NULL, 'cache');
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

		$this->openuserkey = $this->input->post('open_id');

		$this->third_party_user = $this->openuserkey ? $this->cache->get('app_' . $this->openuserkey) : array();

		if ( ! $this->third_party_user) {
			
			$this->_show_message_json('331', '授权已过期，请重新申请授权');
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
			$this->_show_message_json('332', Auth::$login_error_message[Auth::LOGIN_STATUS_NOT_FOUND]);
		}
	
		if ($uc_user['password'] != $this->user_model->hash_password(md5($password), $uc_user['salt'])) {
			$this->_show_message_json('333', Auth::$login_error_message[Auth::LOGIN_STATUS_WRONG_PASSWORD]);
		}
	
		if ($uc_user['type'] != User_model::USER_TYPE_BUYER) {
			$this->_show_message_json('334', '抱歉，商家帐号暂时不支持QQ登录功能');
		}
	
		if ($this->user_login_bind_model->has_bind($uc_user['uid'], $this->third_party_user['type'])) {
			$this->_show_message_json('335', '该众划算帐号已绑定其他QQ');
		}
	
		//检测帐号弱密码
		if( ! check_strong_passwd($account,$password)) {
			$this->_show_message_json('336', '密码过于简单，请立即修改');
		}
	
		// 检测UC状态
		if ( ! $this->auth->check_uc_user($uc_user)) {
			$error = $this->auth->get_error();
			$this->_show_message_json('337', Auth::$login_error_message[$error]);
		}
	
		// 获取本地用户
		$local_user = $this->user_model->find_local_user($uc_user['uid']);
		if ($local_user) {
			// 检测众划算本地用户状态
			if ( ! $this->auth->check_local_user($local_user)) {
				$error = $this->auth->get_error();
				$this->_show_message_json('338', Auth::$login_error_message[$error]);
			}
		}elseif ( ! $this->auth->sync_user($uc_user)) { // 同步用户到众划算
			// 同步不成功将不执行登录
			log_message('error', '无法从用户中心同步用户信息，UID:'.$uc_user['uid']);
			$this->_show_message_json('339', '服务器错误:无法从用户中心同步你的信息');
		}
	
		// 绑定账号
		$this->user_login_bind_model->fresh_bind($uc_user['uid'], $uc_user['username'], $this->third_party_user['type'], $this->third_party_user);
		// 更新登录状态并保持登录信息
		$login_sign = $this->_save_login($uc_user['uid']);
		if( ! $login_sign)
		{
			$this->_show_message_json('340', '系统错误：保存登录状态失败');
		}
		$user_data = $this->auth->app_user_data($uc_user['uid']);
		// 清除session
		$this->_cleanup_auth();
	
		$this->_show_message_json('201', '绑定成功', $user_data);
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
		$this->captcha->set_store_adapter(Captcha::STORE_ADAPTER_CACHE);
	
		$mobile	= trim($this->input->post('mobile'));
		$code	= trim($this->input->post('code'));
	
		if ( ! $mobile || ! $this->_is_mobile($mobile)) {
			$this->_show_message_json('332', '手机号码格式不正确，请重新输入');
		}
	
		// 设置缓存KEY
		$this->captcha->set_store_key(md5($mobile));
		if ( ! $code || ! $this->captcha->check($code)) {
			$this->_show_message_json('333', '验证码错误');
		}
	
		if ($this->user_model->uc_mobile_exists($mobile)) {
			$this->_show_message_json('334', '你填写的手机号码已被认证');
		}
	
		//推广跟踪记录
		// 注册绑定用户
		$user = $this->_save_new_user('mobile', $mobile);
		if ( ! $user) {	
			// 输出错误信息
			$this->_show_message_json('335', $this->_error_meesage['msg']);
		}
		
		// 清除验证码
		$this->captcha->cleanup();
		// 输出
		$this->_show_message_json('201','绑定成功',$user);
	}

	/**
	 * 发送语音验证码
	 * @return void
	 */
	public function send_mobile_captcha()
	{
		$this->load->driver('captcha');
		$this->captcha->set_store_adapter(Captcha::STORE_ADAPTER_CACHE);
		
		$mobile = trim($this->input->post('mobile'));
	
		if ( ! $mobile || ! $this->_is_mobile($mobile)) {
			$this->_show_message_json('331', '手机号码格式不正确，请重新输入');
		}
			
		if ($this->user_model->uc_mobile_exists($mobile)) {
			$this->_show_message_json('332', '您填写的手机号码已被认证');
		}

		// 判断互联支付手机号是否重复
		$this->load->library('hlpay_v2');
		if($this->hlpay_v2->hlpay_user_exixts($mobile,'mobile'))
		{
			$this->_show_message_json('333','该手机号已在互联支付使用，请更换其他的手机号。');
		}

		// 设置缓存KEY
		$this->captcha->set_store_key(md5($mobile));
		// 发送语音验证码send_msg_captcha  如需改为短信则用send_mobile_msg_captcha
		if ( ! $this->captcha->send_mobile_msg_captcha($mobile)) {
			$error = $this->captcha->get_error();
			$this->_show_message_json('334', $error['msg']);
		}
		$this->_show_message_json('201', '请求成功');
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
		$username	= trim($this->input->post('user_name'));
		$password	= $this->input->post('password'); // 密码
	
		if ($this->user_model->check_keyword_username($username)) {
			$this->_set_error('用户名包含非法字符');
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
		$local_data = array('reg_from' => $reg_from, 'reg_source' => 3, 'reg_from_url' => '');
	
		// 写入用户数据
		$uid = $this->user_model->insert($uc_data, $local_data);
		if ( ! $uid ) {
			log_message('用户写入失败:注册一个新账号绑定');
			
			$this->_set_error('注册失败');
			return FALSE;
		}
		
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
		$login_sign = $this->_save_login($uid);
		if( ! $login_sign)
		{
			$this->_show_message_json('340', '保存登录状态失败');
		}
		// 清除第三方授权信息
		$this->_cleanup_auth();
		
		return  $user_data = $this->auth->app_user_data($uid);
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
	 * @param string $msg
	 * @param string $code
	 * @return void
	 */
	private function _set_error($msg, $code = '')
	{
		$this->_error_meesage = array(
			'msg' => $msg,
			'code' => $code
		);
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

	//------------------------------------------------------------------------------
	private function _show_message_json($code , $msg = '', $data = NULL, $output = TRUE) {
		$code = intval($code);
		$ret = array (
			'code' => $code,
			'msg' => $msg
		);
		$data !== NULL && ($ret ['data'] = $data);
		$json_str = json_encode ( $ret);
	
		if ($output) {
			// 输出后停止程序
			die ( $json_str );
		}else{
			// 返回json字符串
			return $json_str;
		}
	}

	//------------------------------------------------------------------------------
	/**
	 * 保存登录信息
	 * 
	 * @param int $uid 用户id
	 * 
	 * @author 杜嘉杰
	 * @version 2015年7月20日 下午5:39:24
	 */
	private function _save_login($uid)
	{
		$this->load->helper('string');
		$login_sign = random_string('unique', 4);
		$this->load->model('YL_user_model');
		$re = $this->YL_user_model->update($uid, array('login_sign'=>$login_sign));
		if( ! $re)
		{
			return FALSE;
		}
		
		return $login_sign;
	}
	
}
