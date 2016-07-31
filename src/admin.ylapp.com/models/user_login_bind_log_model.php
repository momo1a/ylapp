<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户绑定登录记录
 * 
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 */
class User_login_bind_log_model extends CI_Model
{
	public $table = 'user_login_bind_log';
	
	const TYPE_BIND = 1; // 日志类型：绑定
	
	const TYPE_UNBIND = 2;	// 日志类型：解除绑定
	
	const TYPE_UPBIND = 3; // 日志类型：更新绑定
	
	public function find_logs($uid, $bind_type = NULL)
	{
		$this->db->select('id,uid,operate_uid,operate_uname,operate_type,bind_type,content,account_nickname,dateline');
		$this->db->from($this->table);
		$this->db->where('uid', $uid);
	
		if ($bind_type)
		{
			$this->db->where('bind_type', $bind_type);
		}
		
		$this->db->order_by('dateline DESC');
		
		return $this->db->get()->result_array();
	}
}