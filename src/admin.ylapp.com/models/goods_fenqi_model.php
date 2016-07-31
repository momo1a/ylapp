<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 分期购的商品数据处理model
 */
class Goods_fenqi_model extends MY_Model
{
	/**
	 * 活动表名
	 * @var string
	 */
	public static $table_name = 'goods';
	
	/**
	 * 主键
	 * 
	 * @var string
	 */
	protected $key = 'gid';
	
	/**
	 * 分期购商品的标识
	 * @var int
	 */
	static $type_fenqi = 4;
	
	/**
	 * 数据分页-单页数据量限制
	 * @var int
	 */
	public $limit = 10;
	
	/**
	 * 数据分页-数据偏移量
	 * @var int
	 */
	public $offset = 0;
	
	/**
	 * 数据分页-总数据量
	 * @var int
	 */
	public $total_count = 0;
	
	// ---------------------------------------------------------------

	/**
	 * 获取分期购商品数据(带获取数据限制)
	 * 
	 * @access public
	 * 
	 * @param string $get 获取方式：1、complete已经结算的,2、ongoing还在进行中的,3、herald新品预告
	 * @param array $wheres 其他条件
	 * 
	 * @return array
	 */
	public function find_fenqi_limit($get = 'complete', $wheres = NULL) {
		
		$this->db->from(self::$table_name);
		
		// 处理额外的条件
		$this->_handle_search_where($wheres);
		
		if ($get == 'complete') { // 已完成
			// 结算中(31)和已结算(32)的活动
			$this->db->where_in('state', array(31,32));
		}elseif ($get=='herald') { //新品预告
			$this->db->where('state', 5);
		}else { // 正在进行
			// 获取当天要上线或者正在进行(20)、已下架(22)、已屏蔽(21)的一站成名活动
			$this->db->where_in('state', array(20,21,22,24));
		}
		// 一站成名的活动
		$this->db->where('type', self::$type_fenqi);
		
		
		// 保留这些条件在count_all_results()之后不被清除掉
		$this->db->ar_store_array = array('ar_from', 'ar_join', 'ar_where', 'ar_wherein', 'ar_like');
		
		// 获取总数量
		$this->total_count = $this->db->count_all_results();
		
		// 如果没有数据,就不去查询数据了
		if ($this->total_count) {

			$select .= 'gid,title,state,img,price,cost_price,discount,quantity';
			$select .= ',endtime,uname,remain_quantity,wait_fill_num,manual_sort';
			
			$this->db->select($select);
			
			if ($get == 'complete') {
				$this->db->order_by('sort DESC,gid DESC');
			}elseif ($get=='herald') {
				$this->db->order_by('manual_sort DESC,expect_online_time DESC,gid DESC');
			}else {
				$this->db->order_by('sort DESC,manual_sort DESC,gid DESC');
			}
			
			
			$this->db->limit($this->limit, $this->offset);
			
			// 不再设置要保留的数据,在get()之后直接清除掉
			$this->db->ar_store_array = array();
			
			return $this->db->get()->result_array();
		}
		// 不再设置要保留的数据
		$this->db->ar_store_array = array();
		return array();
		
	}// end find_yzcm_limit()
	
	// ---------------------------------------------------------------
	
	/**
	 * 更新一战成名商品排序
	 * 只更新手动排序项
	 * 
	 * @param array $data 包含活动ID和要更新的排序数值,格式：array(活动编号[gid]=>排序数值,...)
	 * @return void
	 */
	public function update_manual_sort($data) {
		
		if (!is_array($data)) {
			return FALSE;
		}
		$update = array();
		
		foreach ($data as $id => $sort) {
			list($id, $sort) = explode('_', $sort);
			$update[] = array($this->key => (int)$id, 'manual_sort' => (int)$sort);
		}
		
		if ($update) {
			$this->update_batch($update, $this->key);
		}		
	}
	
	// ---------------------------------------------------------------
	
	/**
	 * 处理数据操作的条件
	 * 
	 * @param array $wheres
	 * @return void
	 */
	private function _handle_search_where($wheres) {

		if (empty($wheres) || !is_array($wheres)) {
			return;
		}
		
		foreach ($wheres as $f=>$v) {
			$value = trim($v);
			if ($f == 'gid') {
				$this->db->where('goods.gid', $value);
			}elseif ($f == 'uname') {
				$this->db->where('goods.uname', $value);
			}elseif ($f == 'title') {
				$this->db->like('goods.title', $value);
			}
		}
	}
}