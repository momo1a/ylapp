<?php
/**
 * 管理员操作记录模型类
 * @author minch <yeah@minch.me>
 * @version 20130608
 */
class Admin_log_model extends CI_Model
{
	/**
	 * 数据库表名
	 * @var string
	 */
	private $_table;
	
	public function __construct()
	{
		parent::__construct();
		$this->_table = 'sysadmin_admin_log';
	}
	
	/**
	 * 查询管理员操作记录
	 * @param string $key 搜索关键字
	 * @param int $starttime 起始时间
	 * @param int $endtime 结束时间
	 * @param int $limit
	 * @param int $offset
	 */
	public function get($key = '', $starttime = 0, $endtime = 0, $limit = 20, $offset = 0)
	{
		if ('' !== $key) {
			$this->db->like('content', $key);
		}
		if($starttime){
			$this->db->where('dateline >=', $starttime);
		}
		if($endtime){
			$this->db->where('dateline <=', $endtime);
		}
		$this->db->order_by('id', 'DESC');
		$this->db->limit($limit, $offset);
		return $this->db->from($this->_table)->get()->result_array();
	}
	
	/**
	 * 查询管理员操作记录数
	 * @param string $key 搜索关键字
	 * @param number $starttime 起始时间
	 * @param number $endtime 结束时间
	 */
	public function count($key = '', $starttime = 0, $endtime = 0){
		if ('' !== $key) {
			$this->db->like('content', $key);
		}
		if($starttime){
			$this->db->where('dateline >=', $starttime);
		}
		if($endtime){
			$this->db->where('dateline <=', $endtime);
		}
		return $this->db->from($this->_table)->get()->num_rows();
	}
	
	/**
	 * 添加管理操作记录
	 * @param int $user_id
	 * @param string $username
	 * @param string $content
	 * @param string $param
	 * @param unknown $type
	 */
	public function save($user_id, $username, $content = '', $param = '', $type = 1)
	{
		$data = array();
		$data['dateline'] = time();
		$data['type'] = $type;
		$data['uid'] = $user_id;
		$data['uname'] = $username;
		$data['url'] = current_url();
		$data['param'] = serialize($param);
		$data['content'] = $content;
		$this->db->set('clientip', "INET_ATON('{$this->input->ip_address()}')", false);
		$this->db->set('serverip', "INET_ATON('{$_SERVER['SERVER_ADDR']}')", false);
		return $this->db->insert($this->_table, $data);
	}
	
	/**
	 * 删除记录
	 * @param int $id 操作记录编号
	 */
	public function delete($id)
	{
		return $this->db->delete($this->_table, array('id'=>$id));
	}
}