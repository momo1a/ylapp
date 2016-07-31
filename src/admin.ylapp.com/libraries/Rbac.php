<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 基于角色的权限控制类
 * @author minch
 */
class Rbac
{
	private $_CI;

	/**
	 * 构造函数
	 */
	public function __construct()
	{
		$this->_CI = &get_instance();
		$this->_CI->load->config('rbac'); //加载rbac配置文件
		$this->_CI->load->model("rbac_model"); //加载rbac数据库模型
	}

	/**
	 * 创建角色
	 * @param string $name 角色名称
	 * @param string $description 角色说明
	 * @param int $pid 父级角色ID
	 */
	public function create_role($name, $title, $description = '', $pid = 0) 
	{
		return $this->_CI->rbac_model->create_role($name, $title, $description, $pid);
	}
	
	/**
	 * 创建权限
	 * @param string $action
	 * @param string $description
	 */
	public function create_action($action, $description = '')
	{
		return $this->_CI->rbac_model->create_action($action, $description);
	}
	
	/**
	 * 查询所有的角色
	 */
	public function get_roles()
	{
		return $this->_CI->rbac_model->get_roles();
	}

	/**
	 * 查询所有的操作
	 */
	public function get_actions()
	{
		return $this->_CI->rbac_model->get_actions();
	}
	
	/**
	 * 验证角色权限
	 * @access public
	 * @param int $role_id 角色ID
	 * @param int $action 操作(ID或名称)
	 * @param boolean $byid ($action为操作ID为true,操作名称为false)
	 * @return boolean
	 */
	public function check_role_privilege($role_id, $action, $byid = true)
	{
		return $this->_CI->rbac_model->check_role_privilege($role_id, $action, $byid);
	}
	
	/**
	 * 验证用户权限
	 * @access public
	 * @param int $user_id 用户ID
	 * @param int $action 操作
	 * @param boolean $byid ($action为操作ID为true,操作名称为false)
	 * @return boolean
	 */
	public function check_user_privilege($user_id, $action, $byid = true)
	{
		return $this->_CI->rbac_model->check_user_privilege($user_id, $action, $byid);
	}
	
	/**
	 * 查询用户的所有操作
	 * @param integer $user_id 用户ID
	 */
	public function get_user_actions($user_id)
	{
		return $this->_CI->rbac_model->get_user_actions($user_id);
	}
	
	/**
	 * 查询角色的所有权限
	 * @param integer $role_id 角色ID
	 */
	public function get_role_actions($role_id)
	{
		return $this->_CI->rbac_model->get_role_actions($role_id);
	}
}


/**
 * 获取当前管理员的角色名称
 */
if ( ! function_exists('user_role_name')) {
	
	function user_role_name() {
		
		$CI =& get_instance();
		
		$user = get_user();
		$uid = $user['id'];
		
		if(in_array($uid, $CI->config->item('super_admin_uids'))){
			return '超级管理员';
		}
		
		if (! class_exists('Rbac_model')) {
			$CI->load->model('rbac_model');
		}
		$CI->db->select('role.name');
		$CI->db->join('system_privilege_role role', 'role.id=system_privilege_role_user.role_id', 'left');
		$roles = $CI->rbac_model->get_user_roles($uid);
		
		if ($roles) {
			$role_name = '';
			// 可能拥有多个角色
			foreach ($roles as $role) {
				$role_name .= $role['name'] . '|';
			}
			return trim($role_name, '|');
		}
		return '普通管理员';
	}
}


if ( ! function_exists('menu_show2hidden'))
{
	/**
	 * 用于判断是否是当前菜单
	 * 只有是当前菜单才会显示
	 * 
	 * 2014.2.8
	 * @author 韦明磊
	 * @param string $router	当前操作的控制器controller
	 * @param string $module	模块
	 * @param int $position		菜单所属的位置
	 * @return string			菜单样式
	 */
	function menu_show2hidden($module, $position)
	{
		$CI = &get_instance();
			
		if ($position == 1 AND $CI->router->class == 'welcome') {
			return TRUE;
		}
		// 这个方法目前在页面调用两次,使用静态变量,减少数据库读取次数
		static $module_id = NULL;
		
		if ($module_id === NULL) {
			$module_id = $CI->rbac_model->find_module_by_uri($CI->router->class.'/'.$CI->router->method);
		}
		return $module_id == $module ? TRUE : FALSE;
	}
}