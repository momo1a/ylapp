<?php
/**
 * 扣除返现金额记录模型类
 * @author minch <yeah@minch.me>
 * @version 20130717
 */
class Order_adjust_rebate_model extends Zhs_order_adjust_rebate_model 
{
	/**
	 * 通过申述获取一条返现金额调整记录
	 * @param int $appeal_id
	 */
	public function getby_appeal($appeal_id)
	{
		return $this->db->select('id,gid,oid,uid,uname,appeal_id,dateline,adjust_rebate,paid_time,state,
						reason,seller_uid,seller_uname,admin_uid,admin_uname')
					->from(self::$table_name)
					->where('appeal_id', $appeal_id)
					->get()
					->row_array();
	}
	
	/**
	 * 添加记录
	 * @param array|object $data 数组array('field1'=>'value1','field2'=>'value2',...)<br />或对象{field1:value1,field2:value2}
	 */
	public function insert($data){
		if(!is_array($data) OR !is_object($data)){
			return false;
		}
		$fields = $this->db->list_fields(self::$table_name);
		foreach ($data as $k=>$v){
			if (in_array($k, $fields)) {
				$this->set($k, $v);
			}
		}
		return $this->db->insert(self::$table_name);
	}

	/**
	 * 查询扣除返现金额记录
	 * @param string $search_key 搜索类型
	 * @param string $search_val 搜索关键字
	 * @param number $amount 搜索金额
	 * @param number $startTime 起始时间
	 * @param number $endTime 结束时间
	 * @param number $limit
	 * @param number $offset
	 */
	public function get($search_key = '', $search_val = '', $amount = 0, $startTime = 0, $endTime = 0, $limit = 20, $offset = 0){
		if($search_key && $search_val){
			switch ($search_key){
				case 'gid':
					$this->db->where('gid', intval($search_val));
					break;
				case 'oid':
					$this->db->where('oid', intval($search_val));
					break;
				case 'buyer_uname':
					$this->db->where('uname', trim(strval($search_val)));
					break;
				case 'seller_uname':
					$this->db->where('seller_uname', trim(strval($search_val)));
					break;
				default:
					// XXXX do nothing
			}
		}
		if($amount){
			$this->db->where('adjust_rebate', $amount);
		}
		if($startTime && $endTime){
			$this->db->where('dateline >=', $startTime)->where('dateline <=', $endTime);
		}
		$this->db->order_by('id DESC');
		if($limit){
			$this->db->limit($limit, $offset);
		}
		return $this->db->select()->from(self::$table_name)->get()->result_array();
	}
	
	/**
	 * 查询扣除返现金额记录数
	 * @param string $search_key 搜索类型
	 * @param string $search_val 搜索关键字
	 * @param number $amount 搜索金额
	 * @param number $startTime 起始时间
	 * @param number $endTime 结束时间
	 */
	public function count($search_key = '', $search_val = '', $amount = 0, $startTime = 0, $endTime = 0){
		if($search_key && $search_val){
			switch ($search_key){
				case 'gid':
					$this->db->where('gid', intval($search_val));
					break;
				case 'oid':
					$this->db->where('oid', intval($search_val));
					break;
				case 'buyer_uname':
					$this->db->where('uname', trim(strval($search_val)));
					break;
				case 'seller_uname':
					$this->db->where('seller_uname', trim(strval($search_val)));
					break;
				default:
					// XXXX do nothing
			}
		}
		if($amount){
			$this->db->where('adjust_rebate', $amount);
		}
		if($startTime && $endTime){
			$this->db->where('dateline >=', $startTime)->where('dateline <=', $endTime);
		}
		return $this->db->from(self::$table_name)->get()->num_rows();
	}

} //end class