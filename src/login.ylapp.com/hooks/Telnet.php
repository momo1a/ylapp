<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 登录钩子
 * 防止远程表单提交登录
 * 
 * @author "韦明磊<nicolaslei@163.com>"
 *
 */
class Telnet {
	
	/**
	 * 令牌过期时间(秒)
	 * 
	 * @var int
	 */
	private static $token_time_out = 300;
		
	public function ban()
	{
		$CI =& get_instance();
		
		// 可能还需要判断router->directory
		if ($CI->router->class != 'home') return;
			
		$token_key_name = 'login_token_' . sprintf("%u\n", ip2long($CI->auth->ip_address));
			
		if ($CI->router->method == 'index')
		{
			/*
			 * 设置令牌
			 */
			$CI->load->helper('string');
		
			$token = random_string('md5');
		
			$CI->cache->save($token_key_name, $token, self::$token_time_out);
			$CI->data['token'] = $token;
			Template::set('token', $token);
		}
		elseif ($CI->router->method == 'login')
		{
			if ($CI->input->get('login') != 'local')
			{
				return;
			}
			/*
			 * 登录前置判断
			 * 如果服务端的令牌过期，则需要刷新页面
			 * 如果没有令牌提交或者令牌不比配，那就终止程序(没有走正常的登录流程)
			 */
			
			$_cache_token = $CI->cache->get($token_key_name);
			if (!$_cache_token)
			{
				$result = json_encode(array(
						'state' => 'TOKEN_TIMEOUT',
						'message' => '请刷新页面后重新提交'
				));
			
				header('Content-Type:application/json; charset=utf-8');
				exit($result);
			}
			
			$token = $CI->input->post('token');
			if (!$token || $_cache_token != $token)
			{
				// @todo 记录这个非法操作
				exit; // 终止程序
			}
		}
	}
}