<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户绑定
 * 
 * @author "韦明磊<nicolaslei@163.com>"
 */
class User_login_bind_model extends Common_user_login_bind_model
{
	
	public function find_user_binds($uid)
	{
		$ret_array = self::$_types;
		
		$this->db->select('id,uid,type,nickname,gender,open_id,access_token,expires_in,refresh_token,bind_time');
		$this->db->from($this->table);
		$this->db->where('uid', $uid);
		
		if ($binds = $this->db->get()->result_array())
		{
			foreach ($binds as $bind)
			{
				$ret_array[self::type_int2string($bind['type'])] = $bind;
			}
		}
		
		return $ret_array;
	}
	
	public function un_bind($uid, $bind_type, $operate_uid, $operate_uname, $account_nickname, $content)
	{
		$this->load->model('user_login_bind_log_model');
		
		// 删除绑定
		$this->db->delete($this->table, array('uid'=>$uid, 'type'=>$bind_type));
		
		// 更新用户绑定信息
		$this->db->set('bind', 'bind-'.$bind_type, FALSE);
		$this->db->where('uid', $uid);
		$this->db->update('user');
		
		// 添加日志
		$log = array(
				'uid'			=> $uid,
				'operate_uid'	=> $operate_uid,
				'operate_uname'	=> $operate_uname,
				'account_nickname' => $account_nickname,
				'bind_type' 	=> $bind_type,
				'operate_type'	=> User_login_bind_log_model::TYPE_UNBIND,
				'content'		=> $content,
				'dateline'		=> time()
		);
		$this->db->insert($this->user_login_bind_log_model->table, $log);
		
		return TRUE;
	}
}
/* End of file user_bind_model.php */
/* Location: ./application/models/user_bind_model.php */