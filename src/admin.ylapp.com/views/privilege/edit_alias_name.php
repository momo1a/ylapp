<form id="role_form" action="<?php echo site_url('privilege/edit_alias_name')?>" method="post" class="window_form">
	<div class="h" >
		<h3>修改真实姓名：</h3>
		<input type="text" name="alias_name" value="<?php echo $role['alias_name']; ?>"/>
	</div>
	<input type="hidden" name="user_id" value="<?php echo $role['user_id']; ?>"/>
	<input type="hidden" value="save" name="show_type" />
</form>