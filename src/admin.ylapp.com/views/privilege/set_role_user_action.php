<form id="role_form" action="<?php echo site_url('privilege/set_role_user_action')?>" method="post" class="window_form">
	<div class="h">
		<h3>分组权限：</h3>
	</div>
	<div class="h" style="width:800px;">
		<?php if(is_array($actions)): foreach ($actions as $k=>$v): if(!in_array($v['id'], $role_action_ids)): continue; endif;?>
		<label style="float: left;width:auto;" title="<?php echo $v['description'];?>"><input type="checkbox" name="role_actions[]" value="<?php echo $v['id'];?>" <?php if(!in_array($v['id'], $user['exclude_actions'])):?> checked="checked"<?php endif;?> /><?php echo $v['title']?></label>
		<?php endforeach; endif;?>
	</div>
	<div class="h">
		<h3>其它权限：</h3>
	</div>
	<div class="h">
		<?php if(is_array($actions)): foreach ($actions as $k=>$v): if(in_array($v['id'], $role_action_ids)): continue; endif;?>
		<label style="display:inline-block; margin-right: 20px; line-height: 2;" title="<?php echo $v['description'];?>"><input type="checkbox" style="margin-right: 3px;" name="extra_actions[]" value="<?php echo $v['id'];?>" <?php if(in_array($v['id'], $user['extra_actions'])):?> checked="checked"<?php endif;?> /><?php echo $v['title']?></label>
		<?php endforeach; endif;?>
	</div>
	<input type="hidden" value="yes" name="dosave" />
	<input type="hidden" value="<?php echo $role_id;?>" name="role_id">
	<input type="hidden" value="<?php echo $user_id;?>" name="user_id">
</form>