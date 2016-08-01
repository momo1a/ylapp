<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 账号验证
 * 
 * 账号登录业务的相关处理类
 * 
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 *
 */
class Auth
{
	const LOGIN_STATUS_NOT_FOUND		= 'NOT_FOUND';
	const LOGIN_STATUS_ISOLD 			= 'ISOLD';
	const LOGIN_STATUS_SEAL 			= 'SEAL';
	const LOGIN_STATUS_NO_ACTIVATE 		= 'NO_ACTIVATE';
	const LOGIN_STATUS_SHS_SEAL 		= 'SHS_SEAL';
	const LOGIN_STATUS_WRONG_PASSWORD	= 'WRONG_PASSWORD';
	const LOGIN_STATUS_SIMPLE_PASSWORD	= 'WRONG_SIMPLE_PASSWORD';
	
	public static $login_error_message = array(
			self::LOGIN_STATUS_NOT_FOUND		=> '账号不存在',
			self::LOGIN_STATUS_WRONG_PASSWORD	=> '账户名和密码不匹配',
			self::LOGIN_STATUS_NO_ACTIVATE		=> '帐号未激活',
			self::LOGIN_STATUS_ISOLD 			=> '旧账户未验证',
			self::LOGIN_STATUS_SEAL 			=> '帐号已封号',
			self::LOGIN_STATUS_SHS_SEAL 		=> '帐号已封号（禁止在众划算登录）',
			self::LOGIN_STATUS_SIMPLE_PASSWORD 	=> '帐号弱密码',
	);
	
	private $CI;
	
	private $_error = '';
	
	/**
	 * ip_address放入内存中,方便重复使用而不多次调用ip_address()方法
	 * 
	 * @var string
	 */
	public $ip_address;
	
	//----------------------------------------------------------------------------
	
	public function __construct()
	{
		$this->CI =& get_instance();
		
		$this->ip_address = $this->CI->input->ip_address();

		log_message('debug', 'Auth class initialized.');
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 获取一个错误信息
	 * 
	 * @return string
	 */
	public function get_error()
	{
		return $this->_error;
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 通过账号、密码进行登录
	 * 
	 * @param string $account	要登录的账号(username、email、mobile)
	 * @param string $password	密码
	 * @param boolean $remember	是否需要记住账号
	 * 
	 * @return boolean|integer	登录成功则返回一个登录用户ID，否则返回FALSE
	 */
	public function login($account, $password, $remember = FALSE)
	{
		// 从UC中获取用户
		$uc_user = $this->CI->user_model->find_ucuser_by_account($account);
		if ( ! $uc_user)
		{
			$this->_error = self::LOGIN_STATUS_NOT_FOUND;
			return FALSE;
		}
		// 检测密码
		if ( ! $this->_check_password(md5($password), $uc_user['password'], $uc_user['salt']))
		{
			$this->_error = self::LOGIN_STATUS_WRONG_PASSWORD;
			return FALSE;
		}
		//检测弱密码
		if( ! check_strong_passwd($account,$password) )
		{
			$this->_error = self::LOGIN_STATUS_SIMPLE_PASSWORD;
			return FALSE;
		}
		// 检测UC状态
		if ( ! $this->check_uc_user($uc_user)) return FALSE;

		// 获取众划算本地用户
		$user = $this->CI->user_model->find_local_user($uc_user['uid']);
		if ($user)
		{
			// 检测众划算本地用户状态
			if ( ! $this->check_local_user($user)) return FALSE;
		}
		else
		{
			// 同步用户到众划算,同步不成功将不执行登录
			if ( ! $this->sync_user($uc_user)) return FALSE;
		}
		
		$this->_login_local2uc($uc_user, $remember);
		return $uc_user['uid'];
	}
	
	//----------------------------------------------------------------------------
	/**
	 * app登录
	 * @param string $account:要登录的账号(username、email、mobile)
	 * @param unknown $password密码
	 * @return boolean|unknown
	 * @author 杜嘉杰
	 * @version 2014-10-10
	 */
	public function app_login($account, $password){
		// 从UC中获取用户
		$uc_user = $this->CI->user_model->find_ucuser_by_account($account);
		if ( ! $uc_user)
		{
			$this->_error = self::LOGIN_STATUS_NOT_FOUND;
			return FALSE;
		}
		// 检测密码
		if ( ! $this->_check_password($password, $uc_user['password'], $uc_user['salt']))
		{
			$this->_error = self::LOGIN_STATUS_WRONG_PASSWORD;
			return FALSE;
		}
		// 检测UC状态
		if ( ! $this->check_uc_user($uc_user)) return FALSE;

		// 获取众划算本地用户
		$user = $this->CI->user_model->find_local_user($uc_user['uid']);
		if ($user)
		{
			// 检测众划算本地用户状态
			if ( ! $this->check_local_user($user)) return FALSE;
		}
		else
		{
			// 同步用户到众划算,同步不成功将不执行登录
			if ( ! $this->sync_user($uc_user)) return FALSE;
		}
		
		// 更新众划算本地登录信息(被动更新email、mobile)
		$this->CI->user_model->update($uc_user['uid'], array(
				'last_time' => TIMESTAMP,
				'last_ip'	=> bindec(decbin(ip2long($this->ip_address))),
		        'regip'  =>bindec(decbin(ip2long($uc_user['regip']))),
				'email'		=> $uc_user['email'],
				'mobile'	=> $uc_user['mobile'],
				'mobile_valid' => $uc_user['mobile_valid']
		));
		
		return $uc_user['uid'];
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 通过用户ID进行登录
	 * 
	 * 如果用户的来源reg_from不是local,同时email为空,
	 * 那说明用户是通过快速登录系统注册的,只能在众划算登录,
	 * 否则走正常登录流程.
	 * 
	 * @param int $uid			用户ID
	 * 
	 * @return boolean|integer	登录成功则返回一个登录用户ID，否则返回FALSE
	 */
	public function login_by_uid($uid)
	{ 
		$user = $this->CI->user_model->find_local_user($uid); // 获取用户
		if ( !$user)
		{
			$this->_error = self::LOGIN_STATUS_NOT_FOUND;
			return FALSE;
		}

		$uc_user = $this->CI->user_model->find_ucuser_by_uid($uid);
		// 使用第三方登陆,在快捷登录时是没有email和mobile的,允许其在众划算登录,不能在联盟和互联支付登录
		if ($user['reg_from'] > 0 AND ($uc_user['email'] == '' && $uc_user['mobile'] == ''))
		{
			if ($this->check_local_user($user))
			{
				// 更新众划算本地登录信息
				$this->CI->user_model->update($user['uid'], array(
						'last_time' => time(),
						'last_ip'	=> bindec(decbin(ip2long($this->ip_address))),
				));
				AuthUser::save_login($user['uid'], $user['username'], $user['type'], 0,FALSE);
				
				return $user['uid'];
			}
		}
		else
		{
			// 检测用户是否适用于登录
			if ($this->check_uc_user($uc_user) AND $this->check_local_user($user))
			{
				$this->_login_local2uc($uc_user,FALSE);
				return $user['uid'];
			}
		}
		return FALSE;
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 保存登录信息
	 *
	 * 同步到UC
	 *
	 * @param array $uc_user
	 *
	 * @return void
	 */
	public function _login_local2uc($uc_user, $remember = FALSE)
	{
		// 更新UC信息
		$this->CI->user_model->update_uc_login_info($uc_user['uid'],
				$uc_user['username'], $uc_user['password'], $uc_user['type']);
	
		// 更新保存本地
		$this->_login_local($uc_user, $remember);
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 保存登录信息
	 *
	 * 更新用户登录信息并且保持用户状态
	 * @update 2014-11-26 登录时不从UC更新手机号码
	 *
	 * @param array $user			UC用户
	 * @param boolean $remember	是否需要记住用户名
	 *
	 * @return void
	 */
	public function _login_local($uc_user, $remember = FALSE)
	{
		// 更新众划算本地登录信息(被动更新email、mobile)
		$this->CI->user_model->update($uc_user['uid'], array(
				'last_time' => time(),
				'last_ip'	=> bindec(decbin(ip2long($this->ip_address))),
				'email'		=> $uc_user['email'],
				'mobile'	=> $uc_user['mobile'],
				'mobile_valid' => $uc_user['mobile_valid'],
		        'regip'       => bindec(decbin(ip2long($uc_user['regip']))),
		));
		// 保存登录状态到cookie
		AuthUser::save_login($uc_user['uid'],$uc_user['username'],$uc_user['type'],1,$remember);
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 检测秘钥是否正确
	 * 
	 * @param int $uid		用户ID
	 * @param string $sign	签名
	 * 
	 * @return boolean
	 */
	public function check_sign($uid, $sign)
	{
		$online_user = $this->CI->user_model->find_uc_onlineuser($uid);

		if ($online_user AND isset($online_user['code']))
		{
			return $sign == md5($_SERVER['HTTP_USER_AGENT'] . $this->ip_address . $online_user['code']);
		}
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 检测UC账号状态是否适用登陆
	 *
	 * @param array $uc_user UC用户信息
	 * @return boolean
	 */
	public function check_uc_user($uc_user)
	{
		// 手机号码为空并且用户没激活邮箱返回错误信息
		if( $uc_user['isLock'] == YL_user_model::UC_STATUS_NOT_ACTIVE )
		{
			$this->_error = self::LOGIN_STATUS_NO_ACTIVATE;
			$this->user = $uc_user;
			return FALSE;
		}
		
		if (trim($uc_user['mobile']) == '' AND trim($uc_user['email']) == '')
		{
			$this->_error = self::LOGIN_STATUS_ISOLD;
			$this->user = $uc_user;
			return FALSE;
		}
		
		if ($uc_user['isLock'] == YL_user_model::UC_STATUS_LOCKED)
		{
			$this->_error = self::LOGIN_STATUS_SEAL;
			return FALSE;
		}
		
		return TRUE;
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 检测众划算本地账号状态是否适用登陆
	 * 
	 * @param array $user
	 * @return boolean
	 */
	public function check_local_user($user)
	{
		if ($user['is_lock'] == YL_user_model::LOCK_STATE_FREEZE)
		{
			$this->_error = self::LOGIN_STATUS_SHS_SEAL;
			return FALSE;
		}
		
		return TRUE;
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 获取未注册完成的链接
	 *
	 * @param int $uid 用户ID
	 *
	 * @return string 未注册完成的链接
	 */
	public function finish_reg_url($uid)
	{
    	$this->CI->load->library('session');
    	$this->CI->session->set_userdata('session_reg_key_uid', $uid);
    	
		return $this->CI->config->item('domain_reg').'active/';
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 获取试客联盟同步登录连接
	 *
	 * @return string 试客联盟同步登录连接
	 */
	public function sync_login_shikee_url($uid)
	{
		$online_user = $this->CI->user_model->find_uc_onlineuser($uid);
		
		if ($online_user AND isset($online_user['code']))
		{
			$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
			$sign		= md5($user_agent.$this->ip_address.$online_user['code']);
			
			return config_item('domain_shikee_login').'login/sign_on?uid='.$uid.'&sign='.$sign;
		}
		
		return '';
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 从UC同步账户到众划算
	 * 
	 * @param array $uc_user
	 * 
	 * @return void
	 */
	public function sync_user($uc_user)
	{
		return $this->CI->user_model->insert_local_user(array(
				'uid'		=> $uc_user['uid'],
				'is_lock'	=> YL_user_model::LOCK_STATE_NORMAL,
				'uname'		=> $uc_user['username'],
				'utype'		=> $uc_user['type'],
				'reg_source'=> $uc_user['reg_source'],
				'dateline'	=> $uc_user['regdate'],
		        'regip'      =>bindec(decbin(ip2long($uc_user['regip']))),
				'last_time'	=> $uc_user['lastlogintime'],
				'last_ip'	=> $uc_user['lastloginip'],
				'email'		=> $uc_user['email'],
				'mobile'	=> $uc_user['mobile'],
				'mobile_valid' =>empty($uc_user['mobile'])?'2':'1',
				'login_sign' =>  md5(md5(rand()) . rand()) // 给个默认值。防止从网页登录不生成app的密钥
		));
	}

	//----------------------------------------------------------------------------
	
	/**
	 * 验证密码
	 *
	 * @param string $password 	密码明文
	 * @param string $hash		密码加密的串
	 * @param string $salt		加密的盐值
	 *
	 * @return boolean 验证结果
	 */
	private function _check_password($password, $hash, $salt)
	{
		return $hash == $this->CI->user_model->hash_password($password, $salt);
	}
	
	/**
	 * 整理app登录后用户的基本信息
	 * 
	 * @author 杜嘉杰
	 * @version 2014-7-11
	 */
	public function app_user_data($uid)
	{
		// 返回的数据
		$data = NULL;
		
		$data['uid'] = $uid;
		$user = $this->CI->user_model->select('uname')->find($uid);
		$data['uname'] = $user['uname'];
		// 获取绑定淘宝账号
		$this->CI->load->model('user_bind_model');
		$bind_user = $this->CI->user_bind_model->get($uid,1);
		foreach ($bind_user as $item){
			if( ! empty($item['bind_name'])){
				$data['bind_taobao'][] = $item['bind_name'];
			}
		}
		
		// 获取头像
		$this->CI->load->helper('application');
		$data['avatar'] = avatar($uid, 'big','img');
		
		// 设置签名
		$this->CI->load->library('YL_user_service');
		$this->CI->YL_user_service->set_app_sign($uid);
		$sing_data = $this->CI->YL_user_service->flag_data();
		$data['sign'] = $sing_data['login_sign'];
		
		return $data;
	}
	

	/**
	 * app清除登录状态
	 * @param int $uid:用户id
	 * @param string $sign:签名
	 * @return boolean
	 * @author 杜嘉杰
	 * @version 2014-7-7
	 */
	public function app_logout($uid,$sign)
	{
		// 获取用户
		$user = $this->CI->user_model->find($uid);
		if (!$user) {
			return FALSE;
		}
		if ($user['login_sign'] != $sign) {
			return FALSE;
		}
	
		// 重新生成login_sign
		$this->set_login_sign($user['uid']);
	
		return  TRUE;
	}
}
/* End of file Auth.php */
/* Location: ./application/libraries/Auth.php */