<?php

/**
 * 订单模型
 * @author minch <yeah@minch.me>
 */
class Admin_order_model extends Order_Model
{

	/**
	 * 当前模型对应表名
	 * @var string
	 */
	private $_table;
	
	private $_error;

	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->_table = 'order';
	}
	
	public  function error(){
		return $this->_error;
	}

	/**
	 * 根据商品查询获取订单
	 * @param number $gid
	 * @param number $limit
	 * @param number $offset
	 */
	public function get_by_gid($gid, $limit = 10, $offset = 0)
	{
		$this->db->where('gid', $gid);
		$this->db->limit($limit, $offset);
		return $this->db->from($this->_table)->get()->result('array');
	}

	/**
	 * 根据商品ID获取订单数量
	 * @param number $gid
	 */
	public function count_by_gid($gid)
	{
		$this->db->where('gid', $gid);
		return $this->db->from($this->_table)->get()->num_rows();
	}

	/**
	 * 取消资格
	 * @author nty
	 * @version add by nty 2014-2-11 11:16:49
	 * @param array $params 存储过程参数数组array(oid,reason)
	 * @return array 不成功array(Code!=1,Message=错误提示)，成功返回array(Code=1,Message='')
	 */
	public function set_cancel($params=array()){
		$back = array('Code'=>-1000, 'Message'=>'取消资格存储过程未执行或执行错误');
		$_param = $inparams = array();
		if(is_array($params)){
			foreach ($params as $k=>$value) {
				$inparams[$k] = $value;
			}
			$_param = array_pad(array(), count($inparams), '?');
			$call = 'CALL proc_admin_cancel_goods_join('.implode(', ', $_param).');';//以?填充参数
			$back = $this->db->query($call, $inparams)->row_array();
		}
		return $back;
	}

	/**
	 * 扣除返现金额
	 * @author minch
	 * @version add by minch 2013-06-17
	 * @param int $oid 订单ID
	 * @param float $amount 金额
	 * @param string $reason 原因说明
	 * @param string $voucher 扣除凭证
	 */
	public function deduct_money($oid, $amount, $reason, $voucher)
	{
		$flag = TRUE;
		$this->db->trans_begin();
		$order = $this->getby_oid($oid);
		if($amount >= $order['due_money']){
			// 大于应该金额操作失败
			return FALSE;
		}else{
			// 调整返现金额
			$this->db->set('adjust_money', $amount);
			$this->db->set('paid_money', $order['due_money'] - $amount);
			$rs = $this->db->where('oid', $oid)->update('order');
			// TODO 加入扣款处理事务
		}
		if($rs){
			// 记录扣款操作
			$data = array();
			$data['oid'] = $oid;
			$data['gid'] = $order['gid'];
			$data['uid'] = $order['uid'];
			;
			$data['uname'] = $order['uname'];
			;
			$data['dateline'] = time();
			$data['reason'] = $reason;
			$data['voucher'] = $voucher;
			$data['amount'] = $amount;
			$data['state'] = 0;
			// 待扣款状态
			$rs = $this->db->insert('order_deduct_money', $data);
			if(!$rs){
				$flag = FALSE;
			}
			// TODO 其它关联操作？
		}else{
			$flag = FALSE;
		}
		if($flag){
			$this->db->trans_commit();
		}else{
			$this->db->trans_rollback();
		}
		return $flag;
	}

	/**
	 * 搜索订单
	 *
	 * @param string $key 搜索类型
	 * @param string $val 搜索关键字
	 * @param string $status 订单状态
	 * @param string $field 字段
	 * @param number $buyer_uid 买家uid
	 * @param string $where 其它条件
	 * @param number $limit 每页大小
	 * @param number $offset 偏移量
	 * @param string $order_by 排序
	 * @return array
	 */
	public function search($key = '', $val = '', $status = '', $field = '', $where = '',$limit = 0, $offset = 0, $order_by = ''){
		if('' !==$field){
			$this->db->select($field);
		}
		
		$this-> _search_where($key, $val, $status , $where);
		
		if($order_by !=='' ){
			$this->db->order_by($order_by);
		}
		if($limit){
			$this->db->limit($limit, $offset);
		}
		$this->db->from($this->_table);
		
		$order = $this->db->get()->result_array();
		//echo $this->db->last_query();
		return $order;
	}
	
	/**
	 * 行数
	 * @param string $key 搜索类型
	 * @param string $val 搜索关键字
	 * @param string $status 订单状态
	 * @param string $where 其它条件
	 * @return array
	 */
	public function search_count($key = '', $val = '', $status = '',  $where = ''){
		$this-> _search_where($key, $val, $status , $where);
		$this->db->from($this->_table);
		$count = $this->db->count_all_results();
		return $count;
	}
	
	/**
	* 查询的条件
	 * @param string $key 搜索类型
	 * @param string $val 搜索关键字
	 * @param string $status 订单状态
	 * @param string $where 其它条件
	*/
	private function _search_where($key = '', $val = '', $status = '', $where = ''){
		if(''!==$key && ''!==$val){
			switch ($key){
				case 'oid':
					$this->db->where('order.oid', $val);
					break;
				case 'trade_no':
					$this->db->where('order.trade_no', $val);
					break;
				case 'gid':
					$this->db->where('order.gid', $val);
					break;
				case 'title':
					$this->db->like('order.title', $val);
					break;
				case 'buyer_uname':
					$this->db->like('order.buyer_uname', $val);
					break;
				default:
					break;
			}
		}
		if('' !== $status){
			$this->db->where('order.state', $status);
		}	
		if('' !== $where){
			if (is_array($where)) {
				foreach ($where as $k=>$v){
					if(is_numeric($k)){
						$this->db->where($v);
						continue;
					}
					if(is_array($v)){
						$this->db->where($k, array_shift($v), (boolean)array_shift($v));
					}elseif (is_string($v) || is_numeric($v)){
						$this->db->where($k, $v);
					}
				}
			}elseif (is_string($where)){
				$this->db->where($where);
			}
		}
	}
	
}
