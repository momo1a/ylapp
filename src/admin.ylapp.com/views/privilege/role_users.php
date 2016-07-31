	<table class="ui-table">
		<thead>
			<tr>
				<th>会员名称/会员账号</th>
				<th>操作</th>
			</tr>
		</thead>
		<?php if(is_array($role_users)): foreach ($role_users as $k=>$v):?>
		<tr>
			<td>
				<?php echo $v['uname'].'/'.$v['uid']; ?>
				<?php if($v['alias_name']):?>
				(<?php echo $v['alias_name'];?>)
				<?php endif;?>
			</td>
			<td class="ctrlCol">
				<a href="<?php echo site_url('privilege/set_role_user_action/'.$v['uid'].'/'.$v['role_id'])?>" type="load" rel="div#main-wrap" class="ui-operate-button ui-operate-buttonEdit">编辑权限</a>&nbsp;&nbsp;
				<a href="<?php echo site_url('privilege/edit_alias_name'); ?>" type="form" callback="reload" class="ui-operate-button ui-operate-buttonEdit" data-user_id="<?php echo $v['user_id']; ?>">真实姓名</a>&nbsp;&nbsp;
				<a href="<?php echo site_url('privilege/delete_role_user');?>" type="confirm" title="您确定要删除当前用户吗？" callback="reload()" data-user_id="<?php echo $v['uid']?>" data-role_id="<?php echo $v['role_id'];?>" class="ui-operate-button ui-operate-buttonEdit">删除</a>
			</td>
		</tr>
		<?php endforeach; endif;?>
	</table>