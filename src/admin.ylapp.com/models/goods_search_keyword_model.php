<?php
/**
 * 搜索关键字管理modle
 * @author 张桂赏
 * @version 2014-7-22
 */

class Goods_search_keyword_model extends CI_Model
{
	public static $table_name = 'goods_search_keyword';
	
	public function __construct()
	{
		parent::__construct();
	}	
	
	/**
	 * 查询关键字
	 * @param string $like	关键字
	 * @param int $offset	分页偏移量
	 * @param int $size		分页每页条数
	 * @return array		查询结果
	 */
	public function get_keyword($like="",$size=10,$offset=0)
	{
		$this->db->select('*');
		$this->get_keyword_where($like);	
	  	$data['list']=$this->db->limit($size,$offset)->order_by('sort_val desc,sort asc')->get()->result_array();	  	
	  	//查询条数
	  	$this->db->select('*');
	  	$this->get_keyword_where($like);
	  	$data['count']=$this->db->get()->num_rows();  	
		return $data;
	}
	private function get_keyword_where($like="")
	{
		$this->db->from($this::$table_name);
		if($like!=="")
		{
			$this->db->like('keyword',$like);
		}
	}
	
	/**
	 * 新增关键字
	 * @param string $keyword 关键字
	 * @param int $sort_val   排序值
	 * @return boolean
	 */
	public function add($keyword,$sort_val,$sort)
	{
		$this->db->set('keyword',$keyword);
		$this->db->set('sort_val',$sort_val);
		$this->db->set('sort',$sort);
		return $this->db->insert($this::$table_name);
	}
		
	/**
	 * 检查关键字是否存在
	 * @param string $keyword 关键字
	 * @return int  		    结果条数
	 */
	public function check($keyword)
	{
		$this->db->select('*')->from($this::$table_name)->where('keyword',$keyword);
		return $this->db->get()->num_rows();
	}
	/**
	 * 找出当前最大排序
	 * @return array
	 */
	public function max_sort()
	{
		$this->db->select_max('sort')->from($this::$table_name);
		return $this->db->get()->row_array();
	}
	
	/** 手动更新排序
	 * @param array $data 包含活动ID和要更新的排序数值
	 */
	public function update_sort_val($data) {
		
		if (!is_array($data)) {
			return FALSE;
		}
		$update = array();
		
		foreach ($data as $id => $sort) {
			list($id, $sort) = explode('_', $sort);
			$update[] = array('id' =>$id, 'sort_val' =>(int)$sort);
		}
		if ($update) {
			$this->db->update_batch($this::$table_name,$update,'id');
		}		
	}
	
	/**
	 * 查询导出数据
	 * @param string $like	关键字
	 * @return array
	 */
	public function export($like)
	{
		$this->db->select('*');
		$this->get_keyword_where($like);
		return $this->db->order_by('sort_val desc,sort asc')->get()->result_array();
	}
}