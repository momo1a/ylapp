<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户数据处理
 * 
 * 使用独立的model处理类，放置公用的model类过于臃肿，不便维护，同时修改时可能造成一些隐藏的BUG
 * 在类中同时也处理了UC用户的数据更新和插入
 * 
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 */
class User_model extends YL_user_model
{
	/**
	 * 新增一个本地用户
	 * 
	 * 目前新增的数据是从UC同步过来的,已经包含了uid
	 * 
	 * @param array $data 新增的用户数据,包含uid
	 */
	public function insert_local_user($data)
	{
		// 获取会员每天可以免验证码抢购的次数
		$goods_today_buy_num = $this->config->item('goods_today_buy_num');
		if ( ! $goods_today_buy_num) {
			$this->load->model('system_config_model');
			$goods_today_buy_num = $this->system_config_model->get_field('goods_today_buy_num', 'value');
			// 以防万一,如果都没有数据就默认20
			if ( ! $goods_today_buy_num) $goods_today_buy_num = 20;
		}
		// 数据写入,开启事务
		$this->db->trans_start();
		parent::insert($data);
		$this->db->insert(YL_user_stat_model::$table_name, array('uid'=>$data['uid'],'goods_today_buy_num'=>$goods_today_buy_num));
		if($data['utype']==parent::USER_TYPE_BUYER)
		{
			$this->db->insert(YL_user_guide_model::$table_name, array('uid'=>$data['uid']));
		}
		$this->db->trans_complete();

		return $this->db->trans_status() === FALSE ? FALSE : TRUE;
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 写入一个完整的用户
	 * 
	 * 先写入UC数据库中心
	 * 
	 * @param array $uc_data		要写入UC的用户信息
	 * @param array $local_data		要写入本地的用户信息
	 * 
	 * @return integer/FALSE 新插入的用户ID或者FALSE
	 */
	public function insert($uc_data, $local_data = array())
	{
		$uc_db		= $this->load->database('uc', TRUE);

		$ip			= $this->input->ip_address();
		$regip		= bindec(decbin(ip2long($ip)));
		$data		= array_merge(array(
				'regip'			=> $ip,
				'regdate'		=> TIMESTAMP,
				'lastloginip'	=> $regip,
				'lastlogintime' => TIMESTAMP,
				'isLock'		=> 0,
				'isold'			=> 0,
		), $uc_data);

		$uc_db->insert('members', $data); // 写入UC库
		
		$uid = $uc_db->affected_rows() ? $uc_db->insert_id() : FALSE;
		if ($uid) {
			$_local_data = array(
					'uid'			=> $uid,
					'is_lock'		=> self::UC_STATUS_NORMAL,
					'uname'			=> $uc_data['username'],
					'utype'			=> $uc_data['uTypeid'],
					'dateline'		=> TIMESTAMP,
			        'regip'          =>$regip,
					'last_time'		=> TIMESTAMP,
					'last_ip'		=> $regip,
					'email'			=> $uc_data['email'],
					'mobile'		=> $uc_data['mobile'],
					'mobile_valid'	=> $uc_data['mobile_valid'],
					'login_sign' 	=> md5(rand())
			);
			$local_data = array_merge($_local_data, $local_data);
			// 写入众划算本地库
			if ( ! $this->insert_local_user($local_data)) {
				// 如果SQL出错并且没有关闭数据库debug,这里将无法执行
				$uc_db->delete('members', array('uid' => $uid));
			}
		}
		$uc_db->close();
		
		return $uid;
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 向UC写入一个在线的用户
	 * 
	 * @param int $uid			用户ID
	 * @param string $username	用户名
	 * @param string $password	用户密码
	 * @param int $usertype		用户类型
	 * 
	 * @return void
	 */
	public function insert_uc_onlineuser($uid, $username, $password, $usertype)
	{
		$uc_db = $this->load->database('uc', TRUE); // UC的数据库
		
		$uc_db->insert('onlineusers', array(
				'uid'		=> $uid,
				'username'	=> $username,
				'password'	=> $password,
				'usertype'	=> $usertype,
				'logintime'	=> TIMESTAMP,
				'code'		=> $this->_rand_string(),
		));

		$uc_db->close();
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 检测用户名是否是保留关键字，是保留关键字返回TRUE，不是返回FALSE
	 * 
	 * @param $username
	 * @return boolean
	 */
	public function check_keyword_username($username)
	{
		//修改为读取UC从库数据库 update_by 关小龙 2015-08-27 10:38:00
		$uc_db = $this->load->database('ucslave', TRUE);
		$keyword = $uc_db->select('NameWord')->from('yzw_config')->get()->row_array();
		$keyword_arr = explode(",", $keyword ['NameWord']);
		
		$uc_db->close();
		
		// 无匹配结果，通过
		foreach($keyword_arr as $v)
		{
			if (isset($v) && $v != '') {
				$stat = strpos($username, $v);
				if ($stat !== FALSE) return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	 * 更新一个用户绑定
	 * 
	 * @param int $uid			用户ID
	 * @param int $bind_type	绑定类型
	 * 
	 * @return boolean 更新结果
	 */
	public function bind($uid, $bind_type)
	{
		$this->db->set('bind', 'bind+' . $bind_type, FALSE);
		
		$this->db->where('uid', $uid);
		$this->db->update(self::$table_name);
		
		return $this->db->affected_rows() ? TRUE : FALSE;
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 卸载一个用户绑定
	 *
	 * @param int $uid			用户ID
	 * @param int $bind_type	绑定类型
	 *
	 * @return boolean 更新结果
	 */
	public function un_bind($uid, $bind_type)
	{
		$this->db->set('bind', 'bind-' . $bind_type, FALSE);
		
		$this->db->where('uid', $uid);
		$this->db->update(self::$table_name);
		
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	
	//------------------------------------------------------------------------------
	
	/**
	 * 获取一个UC用户
	 *
	 * 可以通过用户名(username)、电子邮箱(email)或者手机(mobile)进行获取
	 *
	 * @param string $account 要查找的账号(email|mobile|username)
	 *
	 * @return array/boolean 用户信息/FALSE
	 */
	public function find_ucuser_by_uid($uid)
	{
		return $this->_find_ucuser(array('uid' => $uid));
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 通过用户id获取UC在线用户
	 * 
	 * @param int $uid
	 * 
	 * @return array UC在线用户或者一个空数组
	 */
	public function find_uc_onlineuser($uid)
	{
		$uc_db = $this->load->database('uc', TRUE);
		
		$onlineuser = $uc_db->select('code')
							->from('onlineusers')
							->where('uid', $uid)
							->limit(1)
							->get()
							->row_array();
		$uc_db->close();

		return $onlineuser;
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 检测用户名是否已存在
	 * 
	 * @param string $username 用户名
	 * 
	 * @return boolean 存在返回TRUE否则返回FALSE
	 */
	public function uc_username_exists($username)
	{
		return $this->_find_ucuser(array('username' => $username)) ? TRUE : FALSE;
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 检测邮箱是否已存在
	 *
	 * @param string $email 邮箱
	 * 
	 * @return boolean 存在返回TRUE否则返回FALSE
	 */
	public function uc_email_exists($email)
	{
		return $this->_find_ucuser(array('email' => $email)) ? TRUE : FALSE;
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 检测手机号码是否已存在
	 *
	 * @param string $mobile 手机号码
	 *
	 * @return boolean 存在返回TRUE否则返回FALSE
	 */
	public function uc_mobile_exists($mobile)
	{
		return $this->_find_ucuser(array('mobile' => $mobile)) ? TRUE : FALSE;
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 更新UC用户的最后登录时间和在线用户表
	 * 
	 * @param int $uid			用户ID
	 * @param string $username	用户名
	 * @param string $password	密码(加密后的)
	 * @param int $usertype		用户类型
	 * 
	 * @return void
	 */
	public function update_uc_login_info($uid, $username, $password, $usertype)
	{
		$uc_db = $this->load->database('uc', TRUE); // UC的数据库
		
		// 更新最后登录时间
		$uc_db->where('uid', $uid)->update('members', array('lastlogintime' => TIMESTAMP));
		
		// 更新在线用户表
		$online_user = array(
				'username'	=> $username,
				'password'	=> $password,
				'usertype'	=> $usertype,
				'logintime'	=> TIMESTAMP,
				'code'		=> $this->_rand_string(),
		);
		
		if ($uc_db->where('uid', $uid)->count_all_results('onlineusers')) {
			$uc_db->where('uid', $uid)->update('onlineusers', $online_user);
		}else {
			$online_user['uid'] = $uid;
			$uc_db->insert('onlineusers', $online_user);
		}
		
		$uc_db->close();
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 清除UC的在线用户表
	 * 
	 * @param int $uid
	 * @return void
	 */
	public function delete_uc_login($uid)
	{
		$uc_db = $this->load->database('uc', TRUE); // UC
		$uc_db->where('uid', $uid)->delete('onlineusers');
		
		$uc_db->close(); // 关闭连接
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 密码明文加密
	 * 
	 * @param string $password	密码,必须经过一层MD5加密(配合JS登录加密使用)
	 * @param string $salt		盐值,给密文加点盐,保证密码的复杂性
	 * 
	 * @return string 加密之后的密码,使用小写
	 */
	public function hash_password($password, $salt = '')
	{
		return strtolower(md5(strtolower($password . $salt)));
	}

	
	//------------------------------------------------------------------------------
	
	/**
	 * 生成指定随机数
	 *
	 * @param int $num 随机数数量
	 * 
	 * @return string 随机数
	 */
	private function _rand_string($num = 15)
	{
		$code = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-');
		
		$str = '';
		for ($i = 0; $i < $num; $i++)
		{
			$index = rand(0, count($code) - 1);
			$str .= $code[$index];
		}
		
		return $str;
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 设置app登录生成的签名
	 * @param int $uid:用户id
	 * @param string $sign:签名
	 * @author 杜嘉杰
	 * @version 2014-6-28
	 */
	public function set_login_sign($uid, $sign){
		$this->db->set('login_sign', $sign)->where('uid', $uid)->update(self::$table_name);
	}
}
/* End of file user_model.php */
/* Location: ./application/models/user_model.php */