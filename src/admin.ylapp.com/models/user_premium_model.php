<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 优质会员
 *
 * @author 宁天友
 * @version 2015-11-30 15:17:13
 * @link http://www.zhonghuasuan.com/
 *
 */
class User_premium_model extends Zhs_user_premium_model
{
	/**
	 * 更新优质会员信息
	 *
	 * @param array $where 条件
	 * @param array $data 更新数据
	 *
	 * @return bool
	 */
	public function update_premium_user($where, $data=array())
	{
		$flag = true;
		if( ! empty($where) &&  ! empty($data)){
			$flag = $this->update($where, $data);
		}
		return $flag;
	}
}