<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户登录绑定
 * 
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 */
class User_login_bind_model extends YL_user_login_bind_model
{
	/**
	 * 插入前置操作
	 * @see MY_Model::_before_insert()
	 */
	protected function _before_insert($data)
	{
		$bind_data = array_merge(array('bind_time' => TIMESTAMP), $data);
		// 返回新插入的数据自动ID
		$this->return_insert_id = TRUE;
		
		return $bind_data;
	}
	
	//---------------------------------------------------------------------------------------
	
	/**
	 * 新的绑定
	 * 
	 * @param int $uid					绑定的用户ID
	 * @param string $uname				绑定的用户名
	 * @param int $bind_type			绑定的类型
	 * @param array $third_party_user	绑定的第三方账号数据
	 */
	public function fresh_bind($uid, $uname, $bind_type, $third_party_user)
	{
		// 开始事务
		$this->db->trans_start();
		
		// 用户绑定标识更新
		$this->db->set('bind', 'bind+'.$bind_type, FALSE)->where('uid', $uid)->update(YL_user_model::$table_name);
		$third_party_user['uid'] = $uid;
		$third_party_user['type'] = $bind_type;
		// 写入绑定信息
		parent::insert($third_party_user);
		$log = array(
				'uid'			=> $uid,
				'bind_type'			=> $bind_type,
				'operate_uid'	=> $uid, // 自身操作
				'operate_uname'	=> $uname,
				'operate_type'	=> YL_user_login_bind_log_model::TYPE_BIND,
				'account_nickname' => $third_party_user['nickname'],
				'dateline'		=> TIMESTAMP,
				'content'		=> '',
		);
		// 写入绑定日志
		$this->db->insert(YL_user_login_bind_log_model::$table_name, $log);
		
		$this->db->trans_complete();
		
		return $this->db->trans_status();
	}
	
	//---------------------------------------------------------------------------------------
	
	/**
	 * 新的绑定
	 *
	 * @param int $uid					绑定的用户ID
	 * @param string $uname				绑定的用户名
	 * @param int $bind_type			绑定的类型
	 * @param array $third_party_user	绑定的第三方账号数据
	 */
	public function replace_bind($uid, $uname, $bind_type, $third_party_user)
	{	
		// 开始事务
		$this->db->trans_start();
		
		$third_party_user['bind_time'] = TIMESTAMP;
		// 写入绑定信息
		parent::update(array('type'=>$bind_type, 'uid'=>$uid), $third_party_user);
		$log = array(
				'uid'			=> $uid,
				'bind_type' 			=> $bind_type,
				'operate_uid'	=> $uid, // 自身操作
				'operate_uname'	=> $uname,
				'operate_type'	=> YL_user_login_bind_log_model::TYPE_UPBIND,
				'account_nickname' => $third_party_user['nickname'],
				'dateline'		=> TIMESTAMP,
				'content'		=> '',
		);
		// 写入绑定日志
		$this->db->insert(YL_user_login_bind_log_model::$table_name, $log);
		
		$flag = $this->db->trans_status();
		$this->db->trans_complete();
		
		return $this->db->trans_status();
	}
	
	//---------------------------------------------------------------------------------------
	
	/**
	 * 判断第三方账号在众划算是否已经有绑定
	 * 
	 * @param string $type		第三方账户登录类型/来源
	 * @param string $open_id	第三方账户开放的用户ID
	 * 
	 * @return boolean/int 如果已经授权登录,返回用户ID,否则返回FALSE
	 */
	public function has_authorize($type, $open_id)
	{
		return $this->select('id,uid,type,nickname,gender,avatar,open_id,access_token,expires_in,refresh_token,bind_time')
						->find_by(array('type'=>$type, 'open_id'=>$open_id));
	}
	
	//---------------------------------------------------------------------------------------
	
	/**
	 * 去除用户一个绑定
	 * 
	 * @param int $uid	用户ID
	 * @param int $type	登录类型
	 */
	public function unbind($uid, $type)
	{
		return parent::delete_where(array('uid'=>$uid, 'type'=>$type));
	}
}
/* End of file user_login_bind_model.php */
/* Location: ./application/models/user_login_bind_model.php */