<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 会员转介绍 - 奖励打款记录invite_user_pay表 MODEL
 *
 * @author  关小龙
 * @version 2015.01.21
 * @link    http://www.zhonghuasuan.com/
 */

class Invite_user_pay_model extends Zhs_invite_user_pay_model 
{
	
	/**
	 * 查询待打款用户(过滤“被封号的邀请人"和“状态≠待打款的项”)
	 * 
	 */
	public function find_wait_pay_user($page_size,$offset)
	{
		$this->db->select('iv.ivid, iv.payuid, iv.ivuid,iv.ivuname,iv.pno,iv.commission, u.is_lock')->from('invite_user_pay iv');
		$this->db->limit($page_size, $offset);
		return $this->db->join('user u', 'iv.ivuid=u.uid')->where(array('iv.state !='=>self::STATUS_ALREADY_PAY,'u.is_lock !='=>'5'))->get()->result_array();
	}
	
	/**
	 * 查询待打款用户(过滤“被封号的邀请人"和“状态≠待打款的项”)总数
	 *
	 */
	public function find_wait_pay_user_count()
	{
		$this->db->select('iv.ivid, iv.payuid, iv.ivuid,iv.ivuname,iv.pno,iv.commission, u.is_lock')->from('invite_user_pay iv');
		return $this->db->join('user u', 'iv.ivuid=u.uid')->where(array('iv.state !='=>self::STATUS_ALREADY_PAY,'u.is_lock !='=>'5'))->count_all_results();;
	}
	
	/**
	 * 奖励打款回调存储过程
	 *
	 * @param int $ivuid 邀请人用户ID
	 * @param int $ivid 邀请编号
	 * @param int $operate_uid 操作人UID
	 * @param varchar $operate_uname 操作人用户名
	 * @param int $ip IP地址
	 */
	public function pay_return($ivuid,$ivid,$operate_uid,$operate_uname,$ip)
	{
		$result = $this->db->query('CALL proc_user_invite_pay_return(?,?,?,?,?);', array($ivuid,$ivid,$operate_uid,$operate_uname,$ip ))->row_array();
		$this->db->close();
		return $result;
	}
	

} //end class