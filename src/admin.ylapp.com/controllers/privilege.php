<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 权限管理控制器类
 * @author minch <yeah@minch.me>
 * @version 2013-06-09
 */
class Privilege extends MY_Controller 
{
	public $check_access = TRUE;
	public $except_methods = array('search_user');
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 权限设置页面
	 */
	public function index(){
		$segment = $this->uri->segment(3);
		$role_id = intval($segment);
		$role_id = $role_id ? $role_id : $this->get_post('role_id');
		$roles = $this->rbac_model->get_roles();
		if(!$role_id){
			$role_id = $roles[0]['id'];
		}
		$role_users = $this->rbac_model->get_role_users($role_id);
		$role_user_list = $this->load->view('privilege/role_users', get_defined_vars(), true);
		if($this->is_ajax() && 'role_user'==$this->get_post('listonly')){
			exit($role_user_list);
		}
		$search_user_list = $this->search_user($role_id);
		
		$this->load->view('privilege/index', get_defined_vars());
	}
	
	/**
	 * 搜索用户
	 */
	private function search_user($role_id){
		$role_id = $role_id ? $role_id : intval($this->get_post('role_id'));
		$key = $this->get_post('key');
		$val = $this->get_post('val');
		if ($key && $val) {
			$limit = 10;
			$offset = intval($this->uri->segment(4));
			$list = $this->rbac_model->search_user($key, $val, $role_id, $limit, $offset);
			$total = $this->rbac_model->search_user_count($key, $val, $role_id);
			
			$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#main-wrap"');
			$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$role_id);
			$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$role_id.'/0');
			$pager = $this->pager($total, $limit, $page_conf);
			
			return $this->load->view('privilege/search_user', get_defined_vars(), TRUE);
		}
		return '';
	}
	
	/**
	 * 角色表单页面
	 */
	public function role_form(){
		if('yes'==$_POST['dosave']){
			$this->save_role();
		}
		$role_id = $this->get_post('role_id');
		$vo = $this->rbac_model->get_role($role_id);
		$this->load->view('privilege/role_form', get_defined_vars());
	}
	
	/**
	 * 保存角色信息
	 */
	private function save_role(){
		$role = array();
		$role['id'] = intval($this->get_post('id'));
		$role['name'] = $this->get_post('name');
		$role['description'] = $this->get_post('description');
		if(!$role['id'] && $this->rbac_model->role_exist($role['name'])){
			$this->success('用户分组名称已经存在');
		}
		$rs = $this->rbac_model->save_role($role, $role['id']);
		$logpre = $role['id'] ? '修改' : '添加';
		if($rs){
			$this->log($logpre.'用户分组成功');
			$this->success('保存成功');
		}else{
			$this->log($logpre.'用户分组失败');
			$this->error('保存失败');
		}
	}
	
	/**
	 * 删除用户组
	 */
	public function delete_role(){
		$id = intval($this->get_post('id', 0));
		if(!$id){
			$this->error('参数错误');
		}
		$rs = $this->rbac_model->delete_role($id);
		if($rs){
			$this->log('删除用户组成功');
			$this->success('删除成功');
		}else{
			$this->log('删除用户组失败');
			$this->error('删除失败');
		}
	}
	
	/**
	 * 操作表单页面
	 */
	public function action_form(){
		if(isset($_POST['dosave']) && 'yes'==$_POST['dosave']){
			$data = array(
					'id' => intval($this->get_post('id')),
					'column_id' => intval($this->get_post('column_id')),
					'module_id' => intval($this->get_post('module_id')),
					'column_show' => intval($this->get_post('column_show')),
					'uri' => trim(strval($this->get_post('uri'))),
					'title' => trim(strval($this->get_post('title'))),
					'front_id' => intval($this->get_post('front_id')),
					'description' => $this->get_post('description'),
					'sort_order' => (int)$this->get_post('sort_order'),
			);
			
			if(!$data['column_id']){
				$this->error('请输入选择栏目');
			}

			if(!$data['uri']){
				$this->error('请输入操作uri');
			}
			
			if(!$data['title']){
				$this->error('请输入操作标题');
			}
			
			$rs = $this->rbac_model->save_action($data, $data['id']);
			
			$logpre = $data['id'] ? '修改' : '添加';
			if($rs){
				$this->log($logpre.'操作权限成功');
				$this->success('保存成功');
			}else{
				$this->log($logpre.'操作权限失败');
				$this->error('保存失败|'.$this->rbac_model->get_error());
			}
		}
		$id = intval($this->get_post('id'));
		if($id){
			$vo = $this->rbac_model->get_action($id);
			if ($vo) {
				$columns = $this->rbac_model->find_action_categorys($vo['module_id']);
				$front_actions = $this->rbac_model->find_column_actions($vo['column_id']);
			}
		}
		$modules = $this->rbac_model->find_action_categorys();
		$this->load->view('privilege/action_form', get_defined_vars());
	}
	
	/**
	 * 分组页面
	 */
	public function role_users(){
		$role_id = $this->get_post('role_id');
		$role_users = $this->rbac_model->get_role_users($role_id);
		$this->load->view('privilege/role_users', get_defined_vars());
	}
	
	/**
	 * 添加分组用户
	 */
	public function add_role_user(){
		$role_id = intval($this->get_post('role_id'));
		$user_id = intval($this->get_post('user_id'));
		if(!$role_id OR !$user_id){
			$this->error('参数错误');
		}
		$rs = $this->rbac_model->set_user_role($user_id, $role_id);
		if($rs){
			$this->log('添加分组用户成功');
			$this->success('操作成功');
		}else{
			$this->log('添加分组用户失败');
			$this->error('操作失败');
		}
	}
	
	/**
	 * 删除分组用户
	 */
	public function delete_role_user(){
		$role_id = $this->get_post('role_id');
		$user_id = $this->get_post('user_id');
		$rs = $this->rbac_model->delete_role_user($role_id, $user_id);
		if($rs){
			$this->log('删除组用户成功');
			$this->success('删除组用户成功');
		}else{
			$this->log('删除组用户失败');
			$this->error('删除组用户失败');
		}
	}
	
	/**
	 * 操作列表页面
	 */
	public function action(){		
		$actions = $this->rbac_model->create_action_columns();
		$this->load->view('privilege/action', get_defined_vars());
	}
	
	/**
	 * 删除操作
	 */
	public function delete_action(){
		$id = $this->get_post('id');
		if(!$id){
			$this->error('非法操作');
		}
		$rs = $this->rbac_model->delete_action($id);
		if($rs){
			$this->log('删除操作权限成功');
			$this->success('删除操作成功');
		}else{
			$this->log('删除操作权限失败');
			$this->error('删除操作失败');
		}
	}

	/**
	 * 设置分组权限
	 */
	public function set_role_action($role_id)
	{
		$actions = $this->rbac_model->create_action_columns(); //获取权限栏目/操作
		$role_actions = $this->rbac_model->get_role_actions($role_id);
		$role_action_ids = array_keys($role_actions);

		if('yes' == $_POST['dosave']){
			$rs = $this->rbac_model->set_role_action($role_id, $_POST['actions']);
			if($rs){
				$this->log('对分组授权成功');
				$this->success('授权成功');
			}else{
				$this->log('对分组授权失败');
				$this->error('授权失败');
			}
		}else {
			$model = $this->rbac_model->get_role($role_id);
			
			$page_title = '角色授权-'.$model['name'];
			$from_url = 'privilege/set_role_action/'.$role_id;
		}
		$this->load->view('privilege/set_role_action', get_defined_vars());
	}
	
	/**
	 * 单独设置分组权限
	 * 
	 * 韦明磊于2014.2.8修改
	 * 
	 * @return void
	 */
	public function set_role_user_action($user_id, $role_id)
	{
		if(1 == $user_id){
			$this->error('超级管理员不需要授权');
		}
		
		$actions = $this->rbac_model->create_action_columns(); //获取权限栏目/操作
		$role_actions = $this->rbac_model->get_role_actions($role_id); //角色所拥有的权限
		
		if ('yes' == $this->input->post('dosave'))
		{
			$post_actions = $this->input->post('actions');
			$role_action_ids = array_keys($role_actions);
			
			$exclude_actions = array_diff($role_action_ids, $post_actions); // 排除的操作
			$extra_actions = array_diff($post_actions, $role_action_ids); // 额外的操作
			
			if (strlen(implode(',', $extra_actions)) > 255) {
				$this->error('授权失败！授权的数量过多');
			}
			
			if (strlen(implode(',', $exclude_actions)) > 255) {
				$this->error('授权失败！排除的授权过多');
			}
			
			// 保存
			$rs = $this->rbac_model->set_role_user_action($role_id, $user_id, $exclude_actions, $extra_actions);
			if($rs){
				$this->log('对分组用户单独授权成功');
				$this->success('授权成功');
			}else{
				$this->log('对分组用户单独授权失败');
				$this->error('授权失败');
			}
		}
		else
		{
			$user = $this->rbac_model->get_role_user($role_id, $user_id);
			// 排除的操作
			if (!empty($user['exclude_actions']) AND is_array($user['exclude_actions']))
			{
				foreach ($user['exclude_actions'] as $key) {
					unset($role_actions[$key]);
				}
			}
	
			// 额外的操作
			if (!empty($user['extra_actions']) AND is_array($user['extra_actions']))
			{
				foreach ($user['extra_actions'] as $key) {
					$role_actions[$key] = '';
				}
			}
			$role_action_ids = array_keys($role_actions);
	
			$page_title = '用户单独授权';
			$from_url = 'privilege/set_role_user_action/'.$user_id.'/'.$role_id;
			
			$this->load->view('privilege/set_role_action', get_defined_vars());
		}
	}
	
	/**
	 * 栏目编辑表单
	 *
	 * @author 韦明磊
	 * @return void
	 */
	public function action_form_column()
	{
		if('yes'==$_POST['dosave']){
			$data = array(
					'id' => intval($this->get_post('id')),
					'parent_id' => intval($this->get_post('parent_id')),
					'name' => trim(strval($this->get_post('name'))),
			);
				
			if(!$data['name']){
				$this->error('请输入栏目名称');
			}
			if(!$data['parent_id']){
				$this->error('请选择所属模块');
			}
				
			$this->_save_action_category_comm($data);
		}
		$id = $this->input->get('id', TRUE);
		if ($id) {
			$model = $this->rbac_model->find_category($id);
		}
		$modules = $this->rbac_model->find_action_categorys();
		$this->load->view('privilege/action_form_column', get_defined_vars());
	}
	
	/**
	 * 模块编辑表单
	 * 
	 * @author 韦明磊
	 * @return void
	 */
	public function action_form_module()
	{
		if('yes'==$_POST['dosave']){
			$data = array(
					'id' => intval($this->get_post('id')),
					'name' => trim(strval($this->get_post('name'))),
					'code' => trim(strval($this->get_post('code'))),
			);
			
			if(!$data['name']){
				$this->error('请输入模块名称');
			}
			
			if(!$data['code']){
				$this->error('请输入模块标识');
			}
			$data['parent_id'] = 0;
			$this->_save_action_category_comm($data);
		}
		$id = $this->input->get('id', TRUE);
		if ($id) {
			$model = $this->rbac_model->find_category($id);
		}
		$this->load->view('privilege/action_form_module', get_defined_vars());
	}
	
	/**
	 * 获取栏目
	 * 
	 * @author 韦明磊
	 * @return json
	 */
	public function get_action_columns()
	{
		$id = intval($this->input->get('id', TRUE));
		if ($id) {
			$columns = $this->rbac_model->find_action_categorys($id);
			$this->ajax_return($columns);
		}
	}
	
	/**
	 * 获取是栏目的操作(表action)
	 * 
	 * @author 韦明磊
	 * @return json
	 */
	public function get_column_actions()
	{
		$id = intval($this->input->get('id', TRUE));
		if ($id) {
			$columns = $this->rbac_model->find_column_actions($id);
			$this->ajax_return($columns);
		}
	}
	
	/**
	 * 删除操作分类
	 *
	 * 添加时间 ：2014.1.25
	 *
	 * @author 韦明磊<nicolaslei@163.com>
	 * @return void
	 */
	public function delete_action_category()
	{
		$id = intval($this->input->get('id', TRUE));
		if ($id) {
			$res = $this->rbac_model->delete_action_category($id);
			if($res){
				$this->log('删除操作分类成功');
				$this->success('删除成功');
			}else{
				$this->log('删除操作分类失败');
				$this->error('删除失败');
			}
		}else {
			$this->error('非法操作');
		}		
	}
	
	/**
	 * 生成栏目菜单新增SQL
	 * 用于更新
	 * 
	 * @param int $id
	 * @return string sql
	 */
	public function menu_sql($id) {
		
		$menu = $this->rbac_model->find_category($id);
		
		if ($menu) {
			$table = $this->config->item('action_category_table');
			if ($menu['parent_id']) {
				// module_id SQL
				$module = $this->rbac_model->find_category($menu['parent_id']);
				$module_sql = "SELECT @mid:=id FROM `{$table}` WHERE name='{$module['name']}' AND parent_id=0;";
				$sql = "INSERT INTO `{$table}` VALUES (null, @mid, '{$menu['name']}', '', '', {$menu['sort_order']});";
			}else {
				$sql = "INSERT INTO `{$table}` VALUES (null, {$menu['parent_id']}, '{$menu['name']}', '{$menu['code']}', '{$menu['css']}', {$menu['sort_order']});";
			}
			
			$html = <<<EOF
<br />
<p><textarea rows="10" style="width:99%">{$module_sql}
{$sql}</textarea></p>
EOF;
			$this->ajax_return($html);
		}
	}
	
	/**
	 * 生成权限操作新增SQL
	 * 用于更新
	 *
	 * @param int $id
	 * @return string sql
	 */
	public function action_sql($id) {
		
		$action = $this->rbac_model->get_action($id);
		
		if ($action) {
			$menu_table = $this->config->item('action_category_table');
			$action_table = $this->config->item('action_table');
			
			// module_id SQL
			$module = $this->rbac_model->find_category($action['module_id']);
			$module_sql = "SELECT @mid:=id FROM `{$menu_table}` WHERE name='{$module['name']}' AND parent_id=0 ;";
			
			// column_id SQL
			$column = $this->rbac_model->find_category($action['column_id']);
			$column_sql = "SELECT @cid:=id FROM `{$menu_table}` WHERE parent_id=@mid AND name='{$column['name']}';";
			
			$front_sql = '';
			if ($action['front_id']) {
				$front = $this->rbac_model->get_action($action['front_id']);
				$front_sql = "SELECT @fid:=id FROM `{$action_table}` WHERE uri ='{$front['uri']}';";
				$sql = "INSERT INTO `{$action_table}` VALUES (null, '{$action['uri']}', '{$action['title']}', '{$action['description']}', @cid, @mid, {$action['column_show']}, @fid, {$action['sort_order']});";
			}else {				
				$sql = "INSERT INTO `{$action_table}` VALUES (null, '{$action['uri']}', '{$action['title']}', '{$action['description']}', @cid, @mid, {$action['column_show']}, 0, {$action['sort_order']});";
			}
			
			$html = <<<EOF
<p><textarea rows="10" style="width:99%">{$module_sql}
{$column_sql}
{$front_sql}
{$sql}</textarea></p>
EOF;
			$this->ajax_return($html);
		}
	}

	/**
	 * 操作分类修改公用处理方法
	 * 
	 * @param array $data 更新的数据
	 * @author 韦明磊
	 * @return void
	 */
	private function _save_action_category_comm($data)
	{
		$data['css'] = trim(strval($this->get_post('css')));
		$data['sort_order'] = intval($this->get_post('sort_order'));
		
		$rs = $this->rbac_model->save_action_category($data);
		$logpre = $data['id'] ? '修改' : '添加';
		if($rs){
			$this->log($logpre.'操作权限成功');
			$this->success('保存成功');
		}else{
			$this->log($logpre.'操作权限失败');
			$this->error('保存失败|'.$this->rbac_model->get_error());
		}
	}
	
	/**
	 * 修改管理员的真实姓名（别名）
	 * 
	 * @author 杜嘉杰
	 * @version 2015年12月10日  上午10:35:22
	 *
	 */
	public function edit_alias_name()
	{
		// 操作类型
		$show_type = trim($this->get_post('show_type'));
		// 用户id
		$user_id = intval($this->get_post('user_id'));
		if($show_type == 'save')
		{
			// 保存
			$alias_name = trim($this->get_post('alias_name'));
			if(mb_strlen($alias_name)>50)
			{
				$this->error('别名不超过50个字符');
			}
			$re = $this->rbac_model->edit_alias_name($user_id, $alias_name);
			if( ! $re)
			{
				$this->error('修改别名失败');
			}
			$this->success('修改别名成功');
		}
		else
		{
			// 展示页面
			$role = $this->rbac_model->find_role_user_by_uid($user_id);
			$data['role'] = $role;
			$this->load->view('privilege/edit_alias_name.php', $data);
		}
	}
}
// End of class Privilege

/* End of file privilege.php */
/* Location: ./application/controllers/privilege.php */