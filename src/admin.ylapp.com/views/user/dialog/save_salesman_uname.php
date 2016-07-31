<form id="role_form" action="<?php echo site_url('user/save_salesman_uname')?>" method="post" class="window_form">
	<div class="h" >
		<h3>所属伙伴：</h3>
		<input type="text" name="salesman_uname" value="<?php echo $user_seller['salesman_uname']; ?>"/>
	</div>
	<input type="hidden" name="uid" value="<?php echo $uid; ?>"/>
	<input type="hidden" value="save" name="show_type" />
</form>