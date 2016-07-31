<?php

/**
 * RBAC数据模型类
 * @author minch
 * @package models
 */
class Rbac_model extends CI_Model
{
	/**
	 * @var string 角色表名
	 */
	private $_role_table;
	
	/**
	 * @var string 角色用户关系表
	 */
	private $_role_user_table;
	
	 /**
	 * @var string 角色操作关系表
	 */
	private $_role_action_table;
	
	/**
	 * @var string 操作表名
	 */
	private $_action_table;
	
	/**
	 * @var string 用户表
	 */
	private $_user_table;
	
	/**
	 * 更新时间 2014.1.21
	 * 
 	 * @author 韦明磊<nicolaslei@163.com>
	 * @var string 操作分类表名
	 */
	private $_action_category_table;
	/**
	 * 更新时间 2014.1.22
	 *
	 * @author 韦明磊<nicolaslei@163.com>
	 * @var string 错误信息，暂时只能是string
	 */
	private $_error = '';

	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->_initialize();
		$this->load->database();
	}
	
	/**
	 * 初始化方法
	 * 设置对应的表名
	 */
	private function _initialize()
	{
		$this->_role_table = $this->config->item('role_table') ? $this->config->item('role_table') : 'roles';
		$this->_role_user_table = $this->config->item('role_user_table') ? $this->config->item('role_user_table') : 'role_users';
		$this->_role_action_table = $this->config->item('role_action_table') ? $this->config->item('role_action_table') : 'role_actions';
		$this->_action_table = $this->config->item('action_table') ? $this->config->item('action_table') : 'actions';
		$this->_user_table = $this->config->item('user_table') ? $this->config->item('user_table') : 'user';
		
		/**
		 * 更新时间 2014.1.21
		 *
		 * @author 韦明磊<nicolaslei@163.com>
		 */
		$this->_action_category_table = $this->config->item('action_category_table');
	}
	
	/**
	 * 创建新角色
	 * @param string $name 角色名称
	 * @param string $description 角色说明
	 * @param number $pid 父级角色ID
	 * @return boolean|int 成功返回角色ID,失败返回false
	 */
	public function create_role($name, $description = '', $pid = 0) 
	{
		if (!$this->role_exist($name)){
			$pids = '';
			if($pid){
				$rs = $this->db->get_where($this->_role_table, array('id'=>$pid));
				$parent_role = $rs->first_row();
				$pids = trim($parent_role->parent_ids.','.$pid, ',');
			}
			$role = array('name' => $name, 'description' => $description, 'parent_id' => $pid, 'parent_ids'=>$pids);
			$this->db->insert($this->_role_table, $role);
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}

	/**
	 * 保存角色信息
	 * @param array|object $role 角色信息
	 */
	public function save_role($role, $role_id = 0){
		if (!is_array($role) && !is_object($role)) {
			return FALSE;
		}
		$id = is_array($role) ? intval($role['id']) : intval($role->id);
		foreach ($role as $k=>$v){
			if ('id' == $k) {
				continue;
			}
			if('name' == $k && $role_id != $id && $this->role_exist($v)){
				return FALSE;
			}
			if('parent_id' == $k){
				$parent = $this->db->where('id', $k)->get()->first_row('array');
				$parent_ids = $parent['parent_ids'].','.$k;
				$this->db->set('parent_ids', $parent_ids);
			}
			$this->db->set($k, $v);
		}
		if ($id) {
			$rs = $this->db->where('id', $id)->update($this->_role_table);
		}else{
			$rs = $this->db->insert($this->_role_table);
		}
		return $rs;
	}
	
	/**
	 * 判断角色是否存在
	 * @param string $name 角色名称
	 * @return boolean
	 */
	public function role_exist($name)
	{
		return (boolean) $this->db->get_where($this->_role_table, array('name'=>$name))->num_rows();
	}
	
	/**
	 * 获取全部角色
	 */
	public function get_roles()
	{
		return $this->db->get_where($this->_role_table, array())->result_array();
	}
	
	public function get_role($role_id){
		return $this->db->from($this->_role_table)->where('id', $role_id)->get()->first_row('array');
	}
	
	public function delete_role($role_id){
		$this->db->from($this->_role_action_table)->where('role_id', $role_id)->delete();
		$this->db->from($this->_role_user_table)->where('role_id', $role_id)->delete();
		return $this->db->from($this->_role_table)->where('id', $role_id)->delete();
	}
	
	/**
	 * 搜索用户
	 * @param string $key 搜索字段
	 * @param string $val 搜索内容
	 * @param number $role_id 角色ID
	 * @param number $limit
	 * @param number $offset
	 * @return array
	 */
	public function search_user($key, $val, $role_id = 0, $limit = 10, $offset = 0){
		$role_uids = array();
		if($role_id){
			$role_users = $this->db->where('role_id', $role_id)->get($this->_role_user_table)->result_array();
			if (is_array($role_users)) {
				foreach ($role_users as $k=>$v){
					$role_uids[] = $v['user_id'];
				}
			}
		}
		if(count($role_uids)){
			$this->db->where_not_in('uid', $role_uids);
		}
		switch ($key){
			case 'uid':
				$this->db->where('uid', $val);
				break;
			case 'email':
				$this->db->like('email', $val);
				break;
			case 'uname':
				$this->db->like('uname', $val);
				break;
		}
		return $this->db->from($this->_user_table)->limit($limit, $offset)->get()->result_array();
	}
	
	/**
	 * 搜索用户数以分页
	 * @param string $key 搜索字段
	 * @param string $val 搜索内容
	 * @param number $role_id 角色ID
	 */
	public function search_user_count($key, $val, $role_id = 0){
		$role_uids = array();
		if($role_id){
			$role_users = $this->db->where('role_id', $role_id)->get($this->_role_user_table)->result_array();
			if (is_array($role_users)) {
				foreach ($role_users as $k=>$v){
					$role_uids[] = $v['user_id'];
				}
			}
		}
		
		if(count($role_uids)){
			$this->db->where_not_in('uid', $role_uids);
		}
		switch ($key){
			case 'uid':
				$this->db->where('uid', $val);
				break;
			case 'email':
				$this->db->like('email', $val);
				break;
			case 'uname':
				$this->db->like('uname', $val);
				break;
		}
		return $this->db->from($this->_user_table)->get()->num_rows();
	}
	
	/**
	 * 添加操作
	 * @param string $name 操作名称（系统）
	 * @param string $title 操作名称（显示）
	 * @param string $description 描述
	 * @return boolean|int 成功返回权限ID,失败返回false
	 */
	public function create_action($name, $title, $description = '')
	{
		if (!$this->action_exist($name)) {
			$row = array('name' => $name, 'title'=>$title, 'description' => $description);
			$this->db->insert($this->_action_table, $row);
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	/**
	 * 保存操作信息
	 * @param array|object $data 操作信息
	 */
	public function save_action($data, $act_id = 0){
		if (!is_array($data) && !is_object($data)) {
			return FALSE;
		}
		$id = is_array($data) ? intval($data['id']) : intval($data->id);
		
		$query = $this->db->get_where($this->_action_table, array('uri'=>$data['uri']));
		
		if ($action = $query->row_array()) {
			if (!$id || ($id && $id != $action['id'])) {
				$this->_error = 'URI已存在';
				return FALSE;
			}
			unset($action);
			$query->free_result();
		}
		
		
		foreach ($data as $k=>$v){
			if ('id' == $k)
				continue;

			$this->db->set($k, $v);
		}
		if ($id) {
			$rs = $this->db->where('id', $id)->update($this->_action_table);
		}else{
			$rs = $this->db->insert($this->_action_table);
		}
		return $rs;
	}
	
	/**
	 * 判断操作是否存在
	 * @param string $name 权限名称
	 * @return boolean
	 */
	public function action_exist($name)
	{
		return (boolean) $this->db->get_where($this->_action_table, array('uri'=>$name))->num_rows();
	}
	
	/**
	 * 获取全部操作
	 */
	public function get_actions($where = '', $limit = 20, $offset = 0)
	{
		if ('' !== $where) {
			$this->db->where($where);
		}
		if($limit){
			$this->db->limit($limit, $offset);
		}
		return $this->db->from($this->_action_table)->get()->result_array();
	}
	
	/**
	 * 查询操作方法
	 * @param string $where
	 */
	public function get_action_count($where = ''){
		if ('' !== $where) {
			$this->db->where($where);
		}
		return $this->db->from($this->_action_table)->get()->num_rows();
	}
	
	/**
	 * 给用户分配角色
	 * @param int $user_id 用户ID
	 * @param int|array $role_id 角色ID(可传ID数组)
	 */
	public function set_user_role($user_id, $role_id)
	{
		if(is_numeric($role_id)){
			$data = array('user_id'=>$user_id, 'role_id'=>$role_id);
			if (!$this->db->get_where($this->_role_user_table, $data)->num_rows())
			{
				$rs = $this->db->insert($this->_role_user_table, $data);
			}
		}elseif (is_array($role_id)){
			$this->db->trans_start();
			foreach ($role_id as $id){
				$data = array('user_id'=>$user_id, 'role_id'=>$id);
				if (!$this->db->get_where($this->_role_user_table, $data)->num_rows())
				{
					$rs = $this->db->insert($this->_role_user_table, $data);
				}
			}
			$this->db->trans_complete();
			$rs = $this->db->trans_status();
		}else {
			$rs = FALSE;
		}
		return $rs;
	}
	
	/**
	 * 查询用户所在的用户组
	 * @param number $user_id
	 */
	public function get_user_roles($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->get($this->_role_user_table)->result_array();
	}
	
	/**
	 * 给分组用户单独设置权限
	 * @param number $role_id 分组ID
	 * @param number $user_id 用户ID
	 * @param array $exclude 排除的分组权限ID
	 * @param array $extra 额外权限ID
	 */
	public function set_role_user_action($role_id, $user_id, $exclude, $extra){
		/*
		 * TODO 目前对三段URI(aa/index/bb)进行判断，所下面添加一段临时处理办法
		 * 只是针对额外(extra)的权限
		 * 
		 * 韦明磊 于2014.2.10
		 */
		if ($extra) {
			foreach ($extra as $id) {
				$action = $this->db->get_where($this->_action_table, array('id'=>$id))->row_array();
				if (substr_count($action['uri'], '/') >= 2) {
					$str_uri = trim($action['uri'], '/');
					$arr_uri = explode('/', $str_uri);
					if ($arr_uri[1] == 'index') {
						// 查找index操作是否存在
						$action = $this->db->get_where($this->_action_table, array('uri'=>$arr_uri[0].'/'.$arr_uri[1]))->row_array();
						if ($action) {
							// 查看该用户所属的角色是否已经拥有了这个操作
							$ra = $this->db->get_where($this->_role_action_table, array('role_id'=>$role_id, 'action_id'=>$action['id']))
												->row_array();
							// 角色还没有建立关系
							if (!$ra)
								$extra[] = $action['id'];
						}
					}
				}
			}
			$extra = array_flip(array_flip($extra));
		}
		if ($exclude) {
			$exclude = array_flip(array_flip($exclude));
		}
		/* end */
		$this->db->set('exclude_actions', implode(',', $exclude));
		$this->db->set('extra_actions', implode(',', $extra));
		$this->db->where('user_id', $user_id);
		$this->db->where('role_id', $role_id);
		return $this->db->update($this->_role_user_table);
	}
	
	/**
	 * 查询分组用户信息
	 * @param number $role_id 分组ID
	 * @param number $user_id 用户ID
	 */
	public function get_role_user($role_id, $user_id){
		$this->db->where('user_id', $user_id);
		$this->db->where('role_id', $role_id);
		$user = $this->db->from($this->_role_user_table)->get()->first_row('array');
		$user['exclude_actions'] = empty($user['exclude_actions']) ? null : explode(',', $user['exclude_actions']);
		$user['extra_actions'] = empty($user['extra_actions']) ? null : explode(',', $user['extra_actions']);
		return $user;
	}
	
	/**
	 * 给角色添加权限
	 * @param int $role_id 角色ID
	 * @param int|array $action_id 权限ID
	 * @return boolean|int
	 */
	public function set_role_action($role_id, $action_id)
	{
		if(is_numeric($action_id)){
			$data = array('role_id'=>$role_id, 'privilege_id'=>$action_id);
			if (!$this->db->get_where($this->_role_action_table, $data)->num_rows())
			{
				$rs = $this->db->insert($this->_role_action_table, $data);
				return $this->db->insert_id();
			}
		}elseif (is_array($action_id)){
			$this->db->trans_start();
			$this->db->where('role_id', $role_id)->delete($this->_role_action_table);
			foreach ($action_id as $id){
				$data = array('role_id'=>$role_id, 'action_id'=>$id);
				$this->db->insert($this->_role_action_table, $data);
			}
			$this->db->trans_complete();
			return $this->db->trans_status();
		}
	}

	/**
	 * 查询用户的所有操作
	 * @param int $user_id
	 * @return array
	 */
	public function get_user_actions($user_id)
	{
		// 查询用户角色，包括父级角色
		$this->db->select("id,name,parent_id,parent_ids,user_id,extra_actions,exclude_actions");
		$this->db->join($this->_role_user_table, "{$this->_role_table}.id={$this->_role_user_table}.role_id");
		$this->db->where('user_id', $user_id);
		$roles = $this->db->get($this->_role_table)->result_array();
		if(!count($roles)){return array();}
		$roles_ids = '';
		$exclude_actions = array();
		$extra_actions = array();
		foreach ($roles as $role){
			$roles_ids .= ','.$role['id'].','.$role['parent_ids'];
			$roles_ids = trim($roles_ids, ',');
			$exclude_actions = array_merge($exclude_actions, explode(',', $role['exclude_actions']));
			$extra_actions = array_merge($extra_actions, explode(',', $role['extra_actions']));
		}
		// 查询用户所有角色的操作
		$this->db->select("{$this->_action_table}.id,{$this->_action_table}.uri");
		$this->db->join($this->_role_action_table, "{$this->_action_table}.id={$this->_role_action_table}.action_id");
		$this->db->where_in('role_id', explode(',', $roles_ids));
		$actions = array();
		foreach ($this->db->get($this->_action_table)->result_array() as $rs){
			if(!in_array($rs['id'], $exclude_actions)){
				$actions[$rs['id']] = $rs['uri'];
			}
		}
		// 查询额外的操作权限
		$this->db->select('id,uri');
		$this->db->where_in('id', $extra_actions);
		foreach ($this->db->get($this->_action_table)->result_array() as $rs){
			$actions[$rs['id']] = $rs['uri'];
		}
		return $actions;
	}
	
	/**
	 * 查询角色所有用户
	 * @param number $role_id 角色ID
	 */
	public function get_role_users($role_id){
		$this->db->select("{$this->_role_user_table}.*,{$this->_user_table}.*");
		$this->db->from($this->_role_user_table);
		$this->db->where($this->_role_user_table.'.role_id', $role_id);
		$this->db->join($this->_user_table, $this->_user_table.".uid={$this->_role_user_table}.user_id");
		return $this->db->get()->result_array();
	}
	
	/**
	 * 查询角色所有操作
	 * @param int $role_id 角色ID
	 * @return array
	 */
	public function get_role_actions($role_id)
	{
		$role = $this->db->get_where($this->_role_table, array('id'=>$role_id))->first_row();
		$role_ids = trim($role->id.','.$role->pids, ',');
		// 查询操作
		$this->db->select("{$this->_action_table}.*");
		$this->db->join($this->_role_action_table, "{$this->_action_table}.id={$this->_role_action_table}.action_id");
		$this->db->where("role_id IN ({$role_ids})");
		$actions = array();
		foreach ($this->db->get($this->_action_table)->result() as $rs){
			$actions[$rs->id] = $rs->title;
		}
		return $actions;
	}
	
	public function get_action($id){
		return $this->db->from($this->_action_table)->where('id', $id)->get()->first_row('array');
	}
	
	/**
	 * 查检用户操作权限
	 * @param int $user_id
	 * @param int $action
	 * @param boolean $byid
	 * @return boolean
	 */
	public function check_user_privilege($user_id, $action, $byid = true)
	{
		$actions = $this->get_user_actions($user_id);
		if($byid){
			return in_array($action, array_keys($actions));
		}else{
			return in_array($action, $actions);
		}
	}
	
	/**
	 * 检查角色操作权限
	 * @param int $role_id
	 * @param int $action
	 * @param boolean $id_field
	 * @return boolean
	 */
	public function check_role_privilege($role_id, $action, $byid = true)
	{
		$actions = $this->get_role_actions($role_id);
		if($byid){
			return in_array($action, array_keys($actions));
		}else{
			return in_array($action, $actions);
		}
	}
	
	/**
	 * 删除指定角色组用户
	 * @param number $role_id 角色ID
	 * @param number $user_id 用户ID
	 */
	public function delete_role_user($role_id, $user_id){
		if(!$role_id OR !$user_id){
			return FALSE;
		}
		return $this->db->from($this->_role_user_table)->where('role_id', $role_id)->where('user_id', $user_id)->delete();
	}
	
	/**
	 * 删除操作
	 * @param number $id 操作ID
	 */
	public function delete_action($id){
		$this->db->from($this->_role_action_table)->where('action_id', $id)->delete();
		$this->db->from($this->_action_table)->where('front_id', $id)->delete();
		return $this->db->from($this->_action_table)->where('id', $id)->delete();
	}
	
	/**
	 * 删除操作分类
	 *
	 * 添加时间 ：2014.1.25
	 *
	 * @param int $id 操作分类ID
	 * @author 韦明磊<nicolaslei@163.com>
	 * @return boolean
	 */
	public function delete_action_category($id)
	{
		// 获取要删除的分类信息
		$model = $this->db->get_where($this->_action_category_table, array('id'=>$id))->row_array();
		
		if ($model) {
			$field = 'column_id';
			// 如果是一级分类，需要删除该分类下的二级分类
			if (intval($model['parent_id']) == 0) {
				// 删除下级分类
				$this->db->from($this->_action_category_table)->where('parent_id', $id)->delete();
				$field = 'module_id';
			}
			
			// 获取分类下的操作
			$actions = $this->db->get_where($this->_action_table, array($field=>$id))->result_array();
			if ($actions) {
				foreach ($actions as $action) {
					// 删除操作
					$this->delete_action($action['id']);
				}
			}
			// 删除自身
			return $this->db->from($this->_action_category_table)->where('id', $id)->delete();
		}
		return FALSE;		
	}
	
	
	/**
	 * 保存操作分类
	 *
	 * 添加时间 ：2014.1.22
	 * 
	 * @param array $data 保存的数据
	 * @author 韦明磊<nicolaslei@163.com>
	 * @return boolean
	 */
	public function save_action_category($data)
	{
		if (!is_array($data))
			return FALSE;

		$id = intval($data['id']);

		// code和name必须是唯一的
		// 二级栏目不对code做判断
		$has_id_code = $this->action_module_exist(array('code'=>$data['code'], 'parent_id'=>$data['parent_id']));
		$has_id_name = $this->action_module_exist(array('code'=>$data['code'], 'parent_id'=>$data['parent_id']));
		
		if (($data['parent_id'] === 0 && $has_id_code != 0 && $has_id_code != $id)
			|| ($has_id_name != 0 && $has_id_name != $id))
		{
			$this->_error = '标识或者名称已存在';
			return FALSE;
		}

		unset($data['id']); // ID不写入
		
		// 模块才需要样式
		if (empty($data['css']) && $data['parent_id'] === 0) {
			$data['css'] = 'nav-' . $data['code'];
		}
		
		if (empty($data['sort_order'])) {
			$data['sort_order'] = 99;
		}
		
		$this->db->set($data);
		return $id ? $this->db->where('id', $id)->update($this->_action_category_table)
					: $this->db->insert($this->_action_category_table);
	}
	
	public function action_module_exist($data)
	{
		$result = $this->db->select('id')
							->where($data)
							->get($this->_action_category_table)
							->row_array();
		
		return $result ? $result['id'] : 0;
	}
	
	/**
	 * 获取操作分类信息
	 *
	 * 添加时间 ：2014.1.24
	 * @param int $id 分类ID
	 * @author 韦明磊<nicolaslei@163.com>
	 * @return array
	 */
	public function find_category($id)
	{
		return $this->db->from($this->_action_category_table)
						->where('id', $id)
						->get()
						->row_array();
	}
	
	/**
	 * 获取权限所有模块
	 * 
	 * 添加时间 ：2014.1.21
	 * @author 韦明磊<nicolaslei@163.com>
	 * @return array
	 */
	public function find_action_categorys($parent_id = 0)
	{
		return $this->db->from($this->_action_category_table)
							->select('id,parent_id,name,code,css')
							->where('parent_id', $parent_id)
							->order_by('sort_order DESC, id ASC')
							->get()
							->result_array();
	}
	
	/**
	 * 获取栏目操作
	 *
	 * 添加时间 ：2014.1.25
	 * 
	 * @param int $column_id 栏目
	 * @author 韦明磊<nicolaslei@163.com>
	 * @return array
	 */
	public function find_column_actions($column_id)
	{
		$this->db->where('front_id', 0);
		$this->db->where('column_show', 1);
		$this->db->where('column_id', intval($column_id));

		return $this->db->order_by('id ASC')
						->get($this->_action_table)
						->result_array();
	}

	/**
	 * 获取权限栏目
	 * 
	 * 供后台左侧栏目调用
	 * 数据格式如：
	 * array(
	 * 		array(
	 * 			'code' => 'system'
	 * 			'columns' => array(
	 * 				array(
	 * 					'name' => '系统管理'
	 * 					'actions' => array(
	 * 						array(
	 * 							'title' => '设置活动上线时间',
	 * 							'uri' => 'setting/online_time',
	 * 						),
	 * 						...
	 * 					),
	 * 				),
	 * 				...
	 * 			),
	 * 		)
	 * 		..
	 * )
	 * TODO 需要添加缓存
	 * 
	 * 添加时间：2014.1.22
	 * @author 韦明磊<nicolaslei@163.com>
	 * @return array 格式后的数组
	 */
	public function create_action_columns()
	{
		// 一次性获取模块和栏目，减少数据库读取次数
		$results = $this->db->select('id,parent_id,name,code')
							->order_by('sort_order DESC')
							->get($this->_action_category_table)
							->result_array();
		$datas = array();
		// 先循环出模块
		foreach ($results as $module) {
			if (intval($module['parent_id']) === 0)
			{
				$datas[$module['id']] = $module;
			}
		}
		// 循环出左侧栏目并获取栏目下的方法,只显示column_show为1的操作
		foreach ($results as $column) {
			if (intval($column['parent_id']) !== 0)
			{
				// 获取操作
				$this->db->select('id,uri,title,column_show,front_id');
				$this->db->where('column_id', $column['id']);
				$this->db->order_by('sort_order ASC,id ASC');
				
				$actions = $this->db->get($this->_action_table)->result_array();
				
				$column_actions = array();
				$universal_actions = array();
				foreach ($actions as $action) {
					// front_id为0，column_show大于0才会在栏目显示，要不则是通用功能
					if (intval($action['front_id']) === 0) {
						if ($action['column_show']) {
							$column_actions[$action['id']] = $action;
						}else {
							$universal_actions[] = $action;
						}
					}
				}
				
				foreach ($actions as $action) {
					if (intval($action['front_id']) > 0) {
						$column_actions[$action['front_id']]['actions'][] = $action;
					}
				}
				
				/* foreach ($actions as $action) {
					if (intval($action['front_id']) > 0) {
						$column_actions[$action['front_id']]['actions'] = $action;
					}
				} */
				
				$column['column_actions'] = $column_actions;
				$column['universal_actions'] = $universal_actions;
				//$parent_id = $column['parent_id'];
				// 删除非必须字段，让数组的数据少一点
				//unset($column['id'],$column['code'],$column['parent_id']);
				// 装载数据
				$datas[$column['parent_id']]['columns'][] = $column;
			}
		}
		return $datas;
	}
	
	/**
	 * 获取用户所用户的模块
	 * 
	 * @author 韦明磊
	 * @param int $user_id
	 * @return ArrayIterator
	 */
	public function find_user_modules($user_id)
	{
		// 查询用户的角色权限信息
		$action_ids = $this->_get_user_actions($user_id);
		
		$this->db->select('cate.*');
		$this->db->from($this->_action_table.' action');
		$this->db->join($this->_action_category_table.' cate', 'action.module_id=cate.id', 'left');
		$this->db->where_in('action.id', $action_ids);
		$this->db->group_by('action.module_id');
		$this->db->order_by('cate.sort_order DESC');
		
		return $this->db->get()->result_array();
	}
	
	/**
	 * 生成用户栏目
	 * 
	 * @author 韦明磊
	 * @param int $user_id
	 * @return array 格式的栏目数据，同create_action_columns数据格式一致
	 */
	public function create_user_action_columns($user_id)
	{
		$datas = array();
		$modules = $this->find_user_modules($user_id);
		foreach ($modules as $module) {
			$datas[$module['id']] = $module;
		}
		
		/*
		 * 获取用户所有的操作权限
		 * 根据操作权限推算用所具有的栏目
		 */
		$action_ids = $this->_get_user_actions($user_id);
		
		// 获取二级栏目
		$this->db->select('cate.*');
		$this->db->from($this->_action_table.' action');
		$this->db->join($this->_action_category_table.' cate', 'action.column_id=cate.id', 'left');
		$this->db->where_in('action.id', $action_ids);
		$this->db->group_by('action.column_id');
		$this->db->order_by('cate.sort_order DESC');
		
		$columns = $this->db->get()->result_array();

		foreach ($columns as $column) {

			// 获取三级栏目
			$this->db->select('*');
			$this->db->from($this->_action_table);
			$this->db->where(array('column_id'=>$column['id'],'column_show'=>1,'front_id'=>0));
			$this->db->where_in('id', $action_ids);
			$this->db->order_by('sort_order ASC,id ASC');
			
			$actions = $this->db->get()->result_array();

			foreach ($actions as $action) {
				$column['column_actions'][$action['id']] = $action;
			}

			$datas[$column['parent_id']]['columns'][] = $column;
		}

		return $datas;
	}
	
	/**
	 * 获取用户的所有操作
	 * 去除排除的操作，包含额外的操作
	 * 
	 * @author 韦明磊
	 * @param int $user_id
	 * @return array
	 */
	private function _get_user_actions($user_id)
	{
		static $action_ids = array();
		
		if ( !$action_ids) {
			
			$this->db->select('role_id,exclude_actions,extra_actions');
			$this->db->from($this->_role_user_table);
			$this->db->where('user_id', $user_id);
			$user = $this->db->get()->row_array();
			
			if ($user) {
				// 查询角色的所有操作
				$this->db->select('action_id');
				$this->db->from($this->_role_action_table);
				$this->db->where('role_id', $user['role_id']);
				$actions = $this->db->get()->result_array();
			
				if ($actions) {

					$arr_exclude = explode(',', $user['exclude_actions']);
			
					foreach ($actions as $action) {
						// 排除掉用户不具有的操作
						if ( !in_array($action['action_id'], $arr_exclude)) {
							$action_ids[] = (int)$action['action_id'];
						}
					}
			
					// 额外的操作
					if (!empty($user['extra_actions']) && $user['extra_actions'] != '') {
						// 转换为array
						$arr_extra = explode(',', $user['extra_actions']);
						foreach ($arr_extra as $key=>$id) {
							$arr_extra[$key] = (int)$id;
						}
						$action_ids = array_merge($action_ids, $arr_extra);
					}
				}
			}
		}
		return $action_ids;
	}
	
	/**
	 * 根据action->uri获取所属模块
	 * 
	 * @param string $uri
	 * @return integer 模块IDmodule_id
	 */
	public function find_module_by_uri($uri)
	{
		$this->db->select('module_id');
		$this->db->from($this->_action_table);
		$this->db->like('uri', $uri, 'after');
		$this->db->group_by('module_id');
		
		$action = $this->db->get()->row_array();
		
		return $action ? $action['module_id'] : 0;
	}
	
	public function get_error()
	{
		return $this->_error;
	}
	
	/**
	 * 根据uid获取用户角色关系的数据
	 * @param int $uid
	 *
	 * @author 杜嘉杰
	 * @version 2015年12月10日  上午11:24:48
	 *
	 */
	public function find_role_user_by_uid($uid)
	{
		$this->db->select('*')->from($this->_role_user_table)->where('user_id',$uid);
		return $this->db->get()->row_array();
	}
	
	/**
	 * 修改别名（真实姓名）
	 * @param int $uid
	 * @param string $alias_name
	 *
	 * @author 杜嘉杰
	 * @version 2015年12月10日  上午11:25:44
	 *
	 */
	public function edit_alias_name($uid, $alias_name)
	{
		$data = array('alias_name' => $alias_name);
		return $this->db->where('user_id',$uid)->update($this->_role_user_table, $data);
	}
}