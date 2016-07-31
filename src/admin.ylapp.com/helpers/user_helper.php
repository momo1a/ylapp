<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('user_has_binds'))
{
	/**
	 * 获取一个用户绑定了几种登录方式
	 *
	 * 绑定数值与绑定方式对应的数值相与,
	 * 如果为真,那说明该用户绑定了该登录方式
	 *
	 * @param int $type
	 *
	 * @return array 如:array('qq'=>TRUE,'weibo'=>TRUE,...)
	 */
	function user_has_binds($type)
	{
		$type = (int)$type;
		if ( !$type) return array();

		if ( !class_exists('User_login_bind_model'))
		{
			$CI =& get_instance();
			$CI->load->model('user_login_bind_model');
		}

		$arr = array();

		foreach (User_login_bind_model::types() as $v)
		{
			if ($type&$v)
			{
				$arr[User_login_bind_model::type_int2string($v)] = TRUE;
			}
		}

		return $arr;
	}
}

if ( ! function_exists('gender_int2string'))
{
	function gender_int2string($gender_int)
	{
		if ($gender_int == 0)
		{
			return '男';
		}
		elseif ($gender_int == 1)
		{
			return '女';
		}
		else
		{
			return '未知';
		}
	}
}


if ( ! function_exists('login_bind_log_type'))
{
	function login_bind_log_type($type)
	{
		if ( !class_exists('User_login_bind_log_model'))
		{
			$CI =& get_instance();
			$CI->load->model('user_login_bind_log_model');
		}
		
		if ($type == User_login_bind_log_model::TYPE_BIND)
		{
			return '绑定账号';
		}
		elseif ($type == User_login_bind_log_model::TYPE_UNBIND)
		{
			return '解除绑定';
		}
		else
		{
			return '更换绑定';
		}
	}
}

// 返回用户状态的颜色
if ( ! function_exists('user_stat_coror')){
	function user_stat_coror($lock){
		$color = '';
		switch ($lock){
			case 0:
				$color = '#009900';
				break;
			case 1:
				$color = '#C5AA20';
				break;
			case 2:
				$color = '#FF8705';
				break;
			case 3:
				$color = '#CA6901';
				break;
			case 4:
				$color = '#8D4C04';
				break;
			case  5:
				$color = '#FF0000';
				break;
				//自动屏蔽
			case  6:
			case  7:
			case  8:
				$color = '#FF6600';
				break;
				//
			default:
				$color = '#000000';
		}
		return $color;
	}
}

// 返回用户状态文字描述
if (! function_exists('user_stat_str')){
	function user_stat_str($state, $lock_day){
		$state_map = array(0=>'正常', 1=>'调查',2=>'屏蔽',5=>'封号',6=>'自动屏蔽',7=>'自动屏蔽',8=>'自动屏蔽');
		
		$text = '';
		// 屏蔽
		if($state == 2){
			$day_str = $lock_day==0 ? '永久' : $lock_day.'天';
			$text = isset($state_map[$state]) ? $state_map[$state] . '(' . $day_str . ')': '未知状态'; 
		}else{
			$text = isset($state_map[$state]) ? $state_map[$state] : '未知状态';
		}
		
		return $text;
	}
}
