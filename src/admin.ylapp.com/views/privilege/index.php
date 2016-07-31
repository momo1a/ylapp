<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>

<div class="ui-box ui-box2 permissionsSet"><div class="ui-box-outer"><div class="ui-box-inner">

	<div class="ui-box-head permissionsSet-head">
		<span>用户组</span>
		<select onchange="load(SITE_URL+'privilege/index/'+$(this).val(), 'div#main-wrap');">
			<?php if(is_array($roles)): foreach ($roles as $k=>$v):?>
			<option value="<?php echo $v['id']?>" <?php if($role_id == $v['id']):?>selected="selected"<?php endif;?>><?php echo $v['name']?></option>
			<?php endforeach; endif;?>
		</select>
		<a href="<?php echo site_url('privilege/role_form')?>" type="form" width="400" height="200" callback="reload" class="ui-operate-button ui-operate-buttonAdd">添加用户组</a>
		<a href="<?php echo site_url('privilege/role_form')?>" type="form" width="400" height="200" callback="reload" data-role_id="<?php echo $role_id; ?>" class="ui-operate-button ui-operate-buttonEdit">编辑</a>
		<a href="<?php echo site_url('privilege/delete_role')?>" type="confirm" data-id="<?php echo $role_id; ?>" title="确定要删除当前用户组吗？" callback="load('<?php echo site_url('privilege/index')?>')" class="ui-operate-button ui-operate-buttonDel">删除</a>
		<a href="<?php echo site_url('privilege/set_role_action/'.$role_id)?>" type="load" rel="div#main-wrap" class="ui-operate-button ui-operate-buttonAccredit">授权</a>
	</div>
	<div id="RoleUserList" class="ui-box-body">
		<?php echo $role_user_list;?>
	</div>
	
	<div class="ui-box-head permissionsSet-head">
		<form rel="div#main-wrap" method="get" action="<?php echo site_url('privilege/index/'.$segment)?>">
				<span>用户搜索:</span>
				<select name="key">
					<option value="uname">用户昵称</option>
					<option value="email">用户邮箱</option>
					<option value="uid">用户编号</option>
				</select>
				<input type="text" name="val" class="ui-form-text ui-form-textRed"  />
				<input type="submit" value="搜 索" class="ui-form-btnSearch" />
				<input type="hidden" name="role_id" value="<?php echo $role_id; ?>">
		</form>
	</div>
	<div id="searchList" class="ui-box-body">
		<?php echo $search_user_list;?>
	</div>

</div></div></div>

<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>