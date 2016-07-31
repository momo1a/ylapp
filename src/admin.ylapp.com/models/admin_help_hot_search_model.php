<?php
/**
 * 帮助管理-热门搜索类 模型(后台)
 * @author 邓元翔
 * @version 13.12.21
 */
class Admin_help_hot_search_model extends CI_Model
{

	private $_table;	//表名

	public function __construct(){
		parent::__construct();
		$this->_table = 'help_hot_search';
	}

	/**
	 * 热门搜索-手动添加（管理员操作：可被后台和前端查询）
	 * @param array $data 要的插入字段键值数组
	 */
	public function manual_add($data){
		return $this->db->insert($this->_table, $data);
	}
	
	/**
	 * 热门搜索-被搜索自动添加（查询时自动添加：可被后台查询）
	 * @param array $data 要的插入字段键值数组
	 */
	public function auto_add($data){
		return $this->db->insert($this->_table, $data);
	}
	
	/**
	 * 返回所有记录
	 * @param int $offset 数据偏移量
	 * @param int $limit 抓取记录数
	 */
	public function get_all($limit=30, $offset=0){
		return $this->db->order_by('hit desc')->get($this->_table, $limit, $offset)->result_array();
	}
	
	/**
	 * 获取推送的关键字
	 * @return array 返回 结果集
	 */
	public function get_hot_keyword(){
		$data = array(
				'is_push' => 1	//1推送、0不推
		);
		$query = $this->db->get_where($this->_table, $data);
		return $query->result_array();
	}
	
	/**
	 * 批量撤销推送
	 * @param string $ids 撤销推送记录的ID
	 * @param int $is_push 推送状态：0未推，1已推
	 */
	public function cancel_push($ids, $is_push=0){
		if(is_string($ids)){
			$ids = explode(',', $ids);
		}
		if(!is_array($ids) || !count($ids)){
			return false;
		}
		return $this->db->where_in('id',$ids)->update($this->_table, array('is_push'=>$is_push));
	}
	
	/**
	 * 推送
	 * @param int $id 推送记录的ID
	 * @param int $is_push 推送状态：0未推，1已推
	 */
	public function push($id, $is_push=1){
		return $this->db->where('id',$id)->update($this->_table, array('is_push'=>$is_push));
	}
	
	/**
	 * 返回热门关键字记录总数
	 */
	public function list_count(){
		$rs = $this->db->select('COUNT(id) AS count')->get($this->_table)->row_array();
		return $rs['count'];
	}
	
	/**
	 * 删除搜索词汇
	 * @param int $id 编号
	 * @return bool $bool 执行结果(TURE|FALSE)
	 */
	public function delete($id){
		return  $this->db->where('id', $id)->delete($this->_table);
	}

}