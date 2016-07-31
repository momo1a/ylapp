<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * 第三方登录适配器
 *
 * QQ登录
 *
 * @author 韦明磊-众划算项目组<nicolaslei@163.com>
 * @link
 */
class Third_party_login_qq extends CI_Driver implements Third_party_login_driver
{
	private $_ci = NULL;
	
	public $authorize_url		= 'https://graph.qq.com/oauth2.0/authorize';
	public $access_token_url	= 'https://graph.qq.com/oauth2.0/token';
	public $openid_url			= 'https://graph.qq.com/oauth2.0/me';
	public $user_info_url		= 'https://graph.qq.com/user/get_user_info';
	
	public $refresh_token		= '';
	public $expires_in			= 0;
	public $access_token		= '';
	public $openid				= 0;
	
	protected $conf = array (
		'client_id' => '',
		'client_secret' => '',
		'verification' => FALSE
	);
	
	/**
	 * 第三方登录入口
	 * 
	 * 在这里拼接URL并跳转过登录授权地址
	 * 
	 * @return void
	 * @see Third_party_login_driver::login()
	 */
	public function login()
	{
		// 授权参数
		$params = array(
				'client_id' => $this->conf['client_id'],
				'redirect_uri' => $this->parent->_callback_url,
				'response_type' => 'code',
		);
		
		// 防钓鱼验证
		if ($this->has_verification())
		{
			$params['state'] = $this->parent->state();
		}
		
		$login_url = $this->authorize_url . '?' . http_build_query($params);
		// 前往获取授权
		header("Location: $login_url");
	}
	
	// ------------------------------------------------------------------------

	/**
     * 登录回调处理
     * 
     * 取出access_token
     * 
     * @param string $auth_code Authorization Code
     * 
     * @return boolean/array
     */
	public function callback($auth_code)
	{
		$access_token = $this->get_access_token($auth_code);
		
		if ($access_token !== FALSE)
		{
			$openid = $this->get_openid($access_token);
			
			if ($openid === FALSE) return FALSE;
			
			$user = $this->get_user_info();
			
			if ($user !== FALSE)
			{
				return array(
						'access_token'	=> $access_token,
						'open_id'		=> $openid,
						'refresh_token'	=> $this->refresh_token,
						'expires_in'	=> $this->expires_in,
						'nickname'		=> $user['nickname'],
						'gender'		=> $user['gender'] == '男' ? 0 : 1,
						'avatar'		=> empty($user['figureurl_qq_2']) ? $user['figureurl_qq_1'] : $user['figureurl_qq_2'],
				);
			}
		}
		
		return FALSE;
		
	}// end callback()
	
	// ------------------------------------------------------------------------

	/**
     * 取用户的OpenID
     * 
     * 在这之前首先要获取access_token
     *
     * @param string $access_token (可选)Access Token,默认使用之前的存储的Access Token
     * 
     * @return string OpenID
     */
	public function get_openid($access_token = '')
	{
		$access_token	= empty($access_token) ?  $this->access_token : trim($access_token);
		
		$response = Http::get($this->openid_url, array(
			'access_token' => $access_token
		));
		
		$user = $this->_handl_callback($response);
		
		if ($user !== FALSE)
		{
			if ($user->client_id == $this->conf['client_id'])
			{
				return $this->openid = $user->openid;
			}
			
			// 这个几乎没有可能,以防万一吧.
			$this->parent->set_error('错误数据,返回结果中的client_id和当前设置的client_id不一致');
		}
		
		log_message('error', 'OpenID获取失败，Access Token为：'.$access_token);
		return FALSE;
		
	}// end get_openid()
	
	// ------------------------------------------------------------------------
	
	/**
     * 获取登录用户信息
     * 
     * @param string $access_token	(可选)Access Token,默认使用之前的存储的$this->access_token
     * @param string $openid		(可选)Openid,默认使用之前的存储的$this->openid
     *
     * @return array 用户信息
     */
	public function get_user_info($access_token = '', $openid = '')
	{
		$access_token	= empty($access_token) ? $this->access_token : $access_token;
		$openid			= empty($openid) ? $this->openid : $openid;
		
		$response = Http::get($this->user_info_url, array (
			"oauth_consumer_key" => $this->conf['client_id'], 
			"access_token" => $access_token, 
			"openid" => $openid, 
			"format" => 'json' 
		));
		
		$user = self::_obj2arr(json_decode($response));
		
		if ($user['ret'] == 0)
		{
			return $user;
		}
		
		$this->parent->set_error("API[get_user_info](ERROR):" . $user['msg']);
		log_message('error', '获取用户信息失败，Access Token为：'.$access_token.'，OpenID为：'.$openid);
		return FALSE;
		
	}// end get_user_info()
	
	// ------------------------------------------------------------------------
	
	/**
	 * 获取access_token
	 * 
	 * @param string $auth_code
	 * 
	 * @return boolean|string FALSE OR access_token
	 */
	public function get_access_token($auth_code)
	{
		$params = array(
				'client_id'		=> $this->conf['client_id'],
				'client_secret' => $this->conf['client_secret'],
				'grant_type'	=> 'authorization_code',
				'code'			=> $auth_code,
				'redirect_uri'	=> $this->parent->_callback_url,
		);
		$response = Http::get($this->access_token_url, $params);
		
		if (strpos($response, "callback") !== FALSE)
		{
			$lpos = strpos($response, "(");
			$rpos = strrpos($response, ")");
			// JSON
			$str_msg	= substr($response, $lpos + 1, $rpos - $lpos -1);
			$msg		= json_decode($str_msg);
			if (isset($msg->error))
			{
				$error = sprintf("%s : %s", $msg->error, trim($msg->error_description));
				// 给驱动返回错误信息
				$this->parent->set_error($error);
				
				log_message('error', 'Access Token获取失败，Authorization Code为：'.$auth_code);
				return FALSE;
			}
		}

		$params = array();
		parse_str($response, $params);

		$this->refresh_token		= isset($params['refresh_token']) ? $params['refresh_token'] : '';
		$this->expires_in			= isset($params['expires_in']) ? $params['expires_in'] : 0;
		
		return $this->access_token	= isset($params['access_token']) ? $params['access_token'] : FALSE;
	}// end get_access_token();
	
	// ------------------------------------------------------------------------
	
	/**
     * Setup QQ Login
     *
     * @param array $config            
     * @return void
     */
	private function _ini($config)
	{
		$this->conf['client_id']		= $config['appid'];
		$this->conf['client_secret']	= $config['appkey'];
		$this->conf['verification']		= $config['verification'];
	}// end _ini()
	
	// ------------------------------------------------------------------------
	
	/**
	 * 是否需要验证来源
	 * @see Third_party_login_driver::has_verification()
	 */
	public function has_verification()
	{
		return (bool)$this->conf['verification'];
	}// end has_verification()
	
	// ------------------------------------------------------------------------

	/**
     * 判断是否开启QQ登录功能
     *
     * @return boolean
     */
	public function is_open()
	{
		$this->_ci =& $this->parent->CI;
		
		// 读取配置文件
		$config = $this->_ci->config->item('auth.qq');
		if (isset($config['state']) && strtolower($config['state']) != 'on')
		{
			log_message('error', 'The QQ Extension must be loaded to use QQ login.');
			return FALSE;
		}
		$this->_ini($config);
		return TRUE;
	}// end is_open();
	
	// ------------------------------------------------------------------------

	/**
     * 检测返回的结果中是否存在错误
     * 如果结果有错,就错误发送给驱动,并返回TRUE否则返回FALSE.
     * 
     * @param string $response 要检测的结果信息
     * @return bool
     */
	private function _handl_callback($response)
	{
		if (strpos($response, "callback") !== FALSE)
		{
			$lpos = strpos($response, "(");
			$rpos = strrpos($response, ")");
			
			// json
			$str = substr($response, $lpos + 1, $rpos - $lpos - 1);
			$json = json_decode($str);

			if (isset($json->error))
			{
				$error = sprintf("%s : %s", $json->error, trim($json->error_description));
				// 给驱动返回错误信息
				$this->parent->set_error($error);
				
				return FALSE;
			}
			return $json;
		}
		
		return FALSE;
		
	}// end _handl_callback();
	
	// ------------------------------------------------------------------------
	
	/**
     * 对象到数组转换
     *
     * @param object $obj            
     * @return array
     */
	private static function _obj2arr($obj)
	{
		if (!is_object($obj) && !is_array($obj))
		{
			return $obj;
		}
		$arr = array ();
		
		foreach ( $obj as $k => $v )
		{
			$arr[$k] = self::_obj2arr($v);
		}
		return $arr;
	}
}
// End Class

/* End of file Third_party_login_qq.php */
/* Location: ./application/libraries/Third_party_login/drivers/Third_party_login_qq.php */