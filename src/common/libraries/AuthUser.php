<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 登录用户处理类
 * 
 * 该类提供一个已登录用户的各种状态判断
 * 用户的账户状态作单独判断(用些操作不需要到用户状态判断,减少对UC的读取压力)
 * 
 * @author "韦明磊<nicolaslei@163.com>"
 *
 */
class AuthUser
{
	static $CI = NULL;
	
	static $user = NULL;
	
	static $message = '';

	static $_user_key_prefix = 'user_info_';
	
	//----------------------------------------------------------------------------
	
	public function __construct()
	{
		self::init();
	}
	
	public static function init()
	{
		if (self::$CI === NULL)
		{
			self::$CI = &get_instance();
		}
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 登录的用户是不是买家
	 * 
	 * @return boolean
	 */
	public static function is_buyer()
	{
		return self::type() == YL_user_model::USER_TYPE_BUYER;
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 登录的用户是不是商家
	 * 
	 * @return boolean
	 */
	public static function is_seller()
	{
		return (bool)(self::type() == YL_user_model::USER_TYPE_SELLER);
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 已登录用户的用户ID
	 * 
	 * @return number
	 */
	public static function id()
	{
		return self::is_logged_in() ? self::$user['id'] : 0;
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 已登录用户的用户名
	 *
	 * @return number
	 */
	public static function account()
	{
		return self::is_logged_in() ? self::$user['account'] : '';
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 已登录用户的类型
	 * 
	 * @return number
	 */
	public static function type()
	{
		return self::is_logged_in() ? self::$user['type'] : 0;
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 是否登录
	 * 
	 * @return boolean
	 */
	public static function is_logged_in()
	{
		return (bool) self::info();
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 是否需要完善资料(第三方登录快速注册没有激活邮箱的情况)
	 * @param int $reg_from
	 * @return boolean   false不需要完善，true 需要去完善
	 */
	public static function has_perfect_info()
	{
		$status = self::is_logged_in() ? self::$user['status'] : 0;
		return $status ? FALSE : TRUE;
	}

	//----------------------------------------------------------------------------
	
	/**
	 * 判断一个登录用户的状态是否正常(用于检测用户是否可以正常操作的基础)(带缓存)
	 * 
	 * 第三登录(QQ)【系统自动生成账号】(邮箱为空),出于业务的需要,我们默认这个状态的用户是正常的
	 * 
	 * 除非用操作,一般情况下不需要判断时时用户的状态,这会增加UC的压力
	 * 用户状态将保存在缓存中30分钟
	 * 
	 * @return boolean 用户的状态,正常返回TRUE,不正常返回FALSE,未登录直接返回FALSE
	 */
	public static function is_normal()
	{
		if (self::is_logged_in())
		{
			// TODO 未完成
			return;
			$cache_key = self::_user_cache_key();
			// 先从缓存读取
			if ($status = self::$CI->cache->get($cache_key))
			{
				self::$message = $status['message'];
				return $status['flag'];
			}
			
			self::$CI->load->model('YL_user_model');
			
			$status = self::$CI->YL_user_model->status();
			// 第三登录(QQ)【系统自动生成账号】(邮箱为空),出于业务的需要,我们默认这个状态的用户是正常的
			$flag	= ($status == 0 OR $status == 300) ? TRUE : FALSE;
			$cache 	= array(
				'flag' => $flag,
				'message' => $flag ? '' : self::$CI->YL_user_model->error
			);
			self::$message = $cache['message'];
			// 将状态保存30分钟
			self::$CI->cache->set($cache_key, $cache, 1800);
			
			return $status;
		}
		
		self::$message = '没有登录用户';
		return FALSE;
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 获取已登录的用户信息
	 * 
	 * @return array/null 返回NULL或者一个存放在cookie中的用户数据array('id'=>,'name'=>,'type'=>);
	 */
	public static function info()
	{
		if (self::$user !== NULL)
		{
			return self::$user;
		}

		$cookie = self::$CI->input->cookie(config_item('cookie_name'));

		if ($cookie === FALSE)
		{
			return NULL;
		}
			
		$cookie = explode('|', $cookie);

		if (count($cookie) >= 3 AND ($auth = self::_crypt($cookie[2], 'decode')))
		{
			$auths		= explode('|', $auth);
			$user_id	= (int)$auths[0];

			// 存在用户ID并且保存的用户类型一致
			if ($user_id > 0 AND $cookie[1] == $auths[1])
			{
				// TODO 应该装更多的信息？
				// status 用户资料是否已完善(0、未完善，1、已完善)
				return self::$user = array(
						'id' => $user_id,
						'account' => $cookie[0],
						'type' => (int)$cookie[1],
						'status' => (int)$cookie[3],
				);
			}
		}
		
		return NULL;
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 保存用户登录信息到cookie
	 * 
	 * @param int $id			用户编号
	 * @param string $account	登录账号
	 * @param int $type			用户类型
	 * @param int $status		用户资料是否已完善(0、未完善，1、已完善)
	 * @param bool $remember	保存登录账号(默认不保存)
	 * 
	 * @return void
	 */
	public static function save_login($id,$account,$type,$status = 1,$remember = FALSE)
	{
		$time = time();
		// cookie的作用域
		$domain = self::$CI->config->item('cookie_domain');
		
		// 保存到cookie的数据
		$cookie = "{$account}|{$type}|".self::_crypt("{$id}|{$type}")."|".$status;
		
		// 是否在cookie中保存用户名(用户名的保存时间)
		$remember_cookie_time = $remember ? ($time+86400*30) : $time-360;
		
		// 记住账号
		setcookie(self::$CI->config->item('cookie_account'), $account, $remember_cookie_time, '/', $domain);
		
		// 登录的加密信息(浏览器关闭就清除)
		setcookie(self::$CI->config->item('cookie_name'), $cookie, 0, '/', $domain);
		
		// 将用户信息放到内存中
		if (self::$user !== NULL)
		{
			self::$user = array_merge(self::$user, array('id'=>$id, 'account'=>$account, 'type'=>$type));
		}
		else
		{
			self::$user = array('id'=>$id, 'account'=>$account, 'type'=>$type);
		}
		
		// 清除上一次登录保存的状态缓存
		self::$CI->cache->delete(self::_user_cache_key());
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 清除登录信息
	 * 
	 * @return void
	 */
	public static function clean()
	{
		if (self::is_logged_in())
		{
			$uc_db = self::$CI->load->database('uc', TRUE); // UC
			$uc_db->where('uid', self::id())->delete('onlineusers');
			$uc_db->close(); // 关闭连接

			// 清除用户缓存
			self::$CI->cache->delete(self::_user_cache_key());
			
			// 清除内存
			self::$user = NULL;

			// 清除cookie
			setcookie(self::$CI->config->item('cookie_name'), '', time()-3600, '/', self::$CI->config->item('cookie_domain'));
		}
	}
	
	//----------------------------------------------------------------------------
	
	/**
	 * 加密或者解密字符串
	 * 
	 * @param string $string 字符串
	 * @param string $operation DECODE/ENCODE
	 */
	private static function _crypt($string, $operation = 'encode')
	{
		$config = array('key' => KEY_COOKIE_CRYPT, 'iv' => KEY_COOKIE_CRYPT_IV);
		self::$CI->load->library('crypt', $config);

		return strtolower($operation) == 'decode' ? self::$CI->crypt->decode($string) : self::$CI->crypt->encode($string);
	}
	
	/**
	 * 生成状态缓存的KEY值
	 * @return string
	 */
	private static function _user_cache_key()
	{
		return self::$_user_key_prefix.self::id();
	}
}
