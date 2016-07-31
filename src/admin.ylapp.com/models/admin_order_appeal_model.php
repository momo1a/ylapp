<?php

/**
 * 申诉操作模型类
 * @author minch <yeah@minch.me>
 * @version 20130605
 */
class Admin_order_appeal_model extends Order_appeal_model
{

	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 根据ID获取指定申诉
	 * @param number $id 指定编号
	 */
	public function getby_id($id)
	{
		return $this->db->where('id', $id)->from($this->_table)->get()->first_row('array');
	}
	
	/**
	 * 根据ID获取指定申诉
	 * @param number $id 指定编号
	 */
	public function getby_utype($utype)
	{
		return $this->db->where('utype', $utype)->from($this->_table.'_type')->get()->result_array();
	}
	
	/**
	 * 判定单号有误
	 * @param number $appeal_id 申诉ID
	 * @param string $content 处理结果说明
	 * @param number $uid 管理员ID
	 * @param string $uname 管理员用户名
	 * @return array('Code'=>,'Message'=>) Code和Message为空则成功
	 */
	public function tradeno_error($appeal_id, $content, $uid, $uname)
	{
		$result = $this->db->query('CALL proc_order_appeal_tradeno_error(?,?,?,?,?)', array($appeal_id, $content, $uid, $uname, bindec(decbin(ip2long($this->input->ip_address())))));
		$rs = $result->first_row('array');
		$result->free_result();
		$this->db->close();
		return $rs;
	}

	/**
	 * 判定单号正确
	 * @param number $appeal_id 申诉ID
	 * @param string $content 处理结果说明
	 * @param number $uid 管理员ID
	 * @param string $uname 管理员用户名
	 * @return array('Code'=>,'Message'=>) Code和Message为空则成功
	 */
	public function tradeno_correct($appeal_id, $content, $uid, $uname)
	{
		$result = $this->db->query('CALL proc_order_appeal_tradeno_correct(?,?,?,?,?)', array($appeal_id, $content, $uid, $uname, bindec(decbin(ip2long($this->input->ip_address())))));
		$rs = $result->first_row('array');
		$result->free_result();
		$this->db->close();
		return $rs;
	}
	
	/**
	 * 修改单号
	 * @param number $appeal_id 申诉ID
	 * @param string $trade_no 新单号
	 * @param string $content 处理结果说明
	 * @param number $uid 管理员ID
	 * @param string $uname 管理员用户名
	 * @return array('Code'=>,'Message'=>) Code和Message为空则成功
	 */
	public function adjust_tradeno($appeal_id, $content, $trade_no, $uid, $uname)
	{
		$result = $this->db->query('CALL proc_order_appeal_adjust_tradeno(?,?,?,?,?,?)', array($appeal_id, $content, $trade_no, $uid, $uname, bindec(decbin(ip2long($this->input->ip_address())))));
		$rs = $result->first_row('array');
		$result->free_result();
		$this->db->close();
		return $rs;
	}

	/**
	 * 取消资格
	 * @param number $appeal_id 申诉ID
	 * @param string $content 处理结果说明
	 * @param number $uid 管理员ID
	 * @param string $uname 管理员用户名
	 * @return array('Code'=>,'Message'=>) Code和Message为空则成功
	 */
	public function disqualification($appeal_id,$content,$uid,$uname)
	{
		$result = $this->db->query('CALL proc_order_appeal_disqualification(?,?,?,?,?)', array($appeal_id, $content, $uid, $uname,  bindec(decbin(ip2long($this->input->ip_address()))) ));
		$rs = $result->first_row('array');
		$result->free_result();
		$this->db->close();
		return $rs;
	}

	/**
	 * 关闭申诉
	 * @param number $appeal_id 申诉ID
	 * @param string $content 处理结果说明
	 * @param number $uid 管理员ID
	 * @param string $uname 管理员用户名
	 * @return array('Code'=>,'Message'=>) Code和Message为空则成功
	 */
	public function close($appeal_id,$content,$uid,$uname)
	{
		$result = $this->db->query('CALL proc_order_appeal_close(?,?,?,?,?)', array($appeal_id, $content, $uid, $uname,  bindec(decbin(ip2long($this->input->ip_address()))) ));
		$rs = $result->first_row('array');
		$result->free_result();
		$this->db->close();
		return $rs;
	}

	/**
	 * 增加返现时间
	 * @param number $appeal_id 申诉ID
	 * @param string $content 处理结果说明
	 * @param number $days 延长返现天数
	 * @param number $uid 管理员ID
	 * @param string $uname 管理员用户名
	 * @return array('Code'=>,'Message'=>) Code和Message为空则成功
	 */
	public function increase_deadline($appeal_id,$content,$days,$uid,$uname)
	{
		$result = $this->db->query('CALL proc_order_appeal_increase_deadline(?,?,?,?,?,?)', array($appeal_id, $content, $days, $uid, $uname,  bindec(decbin(ip2long($this->input->ip_address()))) ));
		$rs = $result->first_row('array');
		$result->free_result();
		$this->db->close();
		return $rs;
	}

	/**
	 * 调整返现金额
	 * @param number $appeal_id 申诉ID
	 * @param string $content 处理结果说明
	 * @param number $amount 调整金额
	 * @param number $uid 管理员ID
	 * @param string $uname 管理员用户名
	 * @return array('Code'=>,'Message'=>) Code和Message为空则成功
	 */
	public function adjust_rebate($appeal_id,$content,$amount,$uid,$uname)
	{
		$result = $this->db->query('CALL proc_order_appeal_adjust_rebate(?,?,?,?,?,?)', array($appeal_id, $amount, $content, $uid, $uname,  bindec(decbin(ip2long($this->input->ip_address()))) ));
		$rs = $result->first_row('array');
		$result->free_result();
		$this->db->close();
		return $rs;
	}

	/**
	 * 申诉直接返现
	 * @param number $appeal_id 申诉ID
	 * @param string $content 处理结果说明
	 * @param number $uid 管理员ID
	 * @param string $uname 管理员用户名
	 */
	public function checkout($appeal_id,$content,$uid,$uname)
	{
		$result = $this->db->query('CALL proc_order_appeal_checkout_prepare(?,?,?,?,?)', array($appeal_id, $content, $uid, $uname,  bindec(decbin(ip2long($this->input->ip_address()))) ));
		$rs = $result->first_row('array');
		$result->free_result();
		$this->db->close();
		return $rs;
	}
	
	/**
	 * 获取管理员处理申诉统计
	 * @param string $where
	 * @param string $sort
	 * @param number $limit
	 * @param number $offset
	 * @return array
	 */
	public function get_appeal_data($where='',$sort='admin_uid ASC', $limit=10, $offset=0){
	
		if($where !=''){
			$this->db->where($where, NULL, FALSE);
		}
		if($limit){
			$this->db->limit($limit, $offset);
		}
		$this->db->select('admin_uid,admin_uname,count(if(utype=2,true,null)) seller,count(if(utype=1,true,null)) buyer,count(id) num',false);
		$this->db->where('state',4);
		$this->db->group_by('admin_uid');
		$this->db->order_by($sort);
		$data=$this->db->from('order_appeal')->get()->result_array();
		return $data;
	}
	
	/**
	 * 获取管理员处理申诉统计
	 * @param string $where
	 * @param string $sort
	 * @param number $limit
	 * @param number $offset
	 * @return int
	 */
	public function get_appeal_data_count($where=''){
	
		if($where !=''){
			$this->db->where($where, NULL, FALSE);
		}
		$this->db->select('admin_uid');
		$this->db->where('state',4);
		$this->db->group_by('admin_uid');
		$data=$this->db->from('order_appeal')->get()->result_array();
		return count($data);
	}
	
}