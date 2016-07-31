<?php
/**
 * 定时任务系统配置模型类
 * @author minch <yeah@minch.me>
 * @version 20130725
 */
class System_tasktimer_config_model extends CI_Model
{
	/**
	 * 数据库表名
	 * @var string
	 */
	private $_table;
	
	public function __construct()
	{
		parent::__construct();
		$this->_table = 'system_tasktimer_config';
	}
	
	/**
	 * 查询设置值
	 * @param string|array $key 设置项
	 * @return Object|array 如果传入参数$key则返回对应设置项<br />否则返回所有设置项数组
	 */
	public function get($key = '')
	{
		if (is_string($key)) {
			$rs = $this->db->get_where($this->_table, array('key'=>$key))->first_row('array');
		} elseif (is_array($key)) {
			$rs = $this->db->select('key,value,title')->from($this->_table)->where_in('key', $key)->get()->result_array();
		} else {
			$rs = $this->db->get_where($this->_table)->result_array();
		}
		return $rs;
	}
	
	/**
	 * 保存系统配置项
	 * @param string $key 设置项
	 * @param string $value 设置值
	 * @param string $remark 备注
	 * @return unknown
	 */
	public function save($key, $value = '', $remark = '')
	{
		if($this->db->get_where($this->_table, array('key'=>$key))->num_rows()) {
			$data = array('value'=>$value, 'title'=>$remark);
			$rs = $this->db->update($this->_table, $data, array('key'=>$key));
		} else {
			$data = array('key'=>$key, 'value'=>$value, 'title'=>$remark);
			$rs = $this->db->insert($this->_table, $data);
		}
		return $rs;
	}
	
	/**
	 * 批量保存
	 * @param array $rows 配置项数组
	 * @return boolean
	 */
	public function save_all($rows)
	{
		$flag = true;
		$this->db->trans_begin();
		foreach ($rows as $row){
			$rs = $this->save($row['key'], $row['value'], $row['title']);
			if(!$rs){
				$flag = false;
				break;
			}
		}
		if($flag){
			$this->db->trans_commit();
		}else{
			$this->db->trans_rollback();
		}
		return $flag;
	}
	
	/**
	 * 删除设置项
	 * @param string $key
	 */
	public function delete($key)
	{
		return $this->db->delete($this->_table, array('key'=>$key));
	}
}