<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户登录绑定日志处理
 * 
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 *
 */
class User_login_bind_log_model extends YL_user_login_bind_log_model
{
	/**
	 * 对要写入数据库的数据进行写入前更新
	 * 
	 * @see MY_Model::_before_insert()
	 * @param array $data 要写入的数据
	 * 
	 * @return array 要写入的数据
	 */
	protected function _before_insert($data)
	{
		$data['dateline'] = time();
		$data['content'] = '';
		
		return $data;
	}
	
	//------------------------------------------------------------------------
	
	/**
	 * 新增绑定的日志
	 * 
	 * @param int $uid					绑定的用户用户ID
	 * @param int $bind_type			绑定的第三方账号类别
	 * @param string $operate_uname		操作的用户用户名
	 * @param string $account_nickname	绑定的第三方账号昵称
	 * 
	 * @return int 新增的ID
	 */
	public function bind($uid,$bind_type,$operate_uname,$account_nickname)
	{
		return parent::insert(array(
				'uid'			=> $uid,
				'bind_type' 	=> $bind_type,
				'operate_uid'	=> $uid, // 自身操作
				'operate_uname'	=> $operate_uname,
				'operate_type'	=> self::TYPE_BIND,
				'account_nickname' => $account_nickname,
		));
	}
	
	//------------------------------------------------------------------------
	
	/**
	 * 修改绑定的日志
	 *
	 * @param int $uid					绑定的用户用户ID
	 * @param int $bind_type			绑定的第三方账号类别
	 * @param string $operate_uname		操作的用户用户名
	 * @param string $account_nickname	绑定的第三方账号昵称
	 *
	 * @return int 新增的ID
	 */
	public function update_bind($uid,$bind_type,$operate_uname,$account_nickname)
	{
		return parent::insert(array(
				'uid'			=> $uid,
				'bind_type' 	=> $bind_type,
				'operate_uid'	=> $uid, // 自身操作
				'operate_uname'	=> $operate_uname,
				'operate_type'	=> self::TYPE_UPBIND,
				'account_nickname' => $account_nickname,
		));
	}
	
	//------------------------------------------------------------------------
	
	/**
	 * 解除绑定的日志
	 *
	 * @param int $uid					绑定的用户用户ID
	 * @param int $bind_type			绑定的第三方账号类别
	 * @param string $operate_uname		操作的用户用户名
	 * @param string $account_nickname	绑定的第三方账号昵称
	 *
	 * @return int 新增的ID
	 */
	public function unset_bind($uid,$bind_type,$operate_uname,$account_nickname)
	{
		return parent::insert(array(
				'uid'			=> $uid,
				'bind_type' 	=> $bind_type,
				'operate_uid'	=> $uid, // 自身操作
				'operate_uname'	=> $operate_uname,
				'operate_type'	=> self::TYPE_UNBIND,
				'account_nickname' => $account_nickname,
		));
	}
}
/* End of file user_login_bind_log_model.php */
/* Location: ./application/models/user_login_bind_log_model.php */