<form id="role_form" action="<?php echo site_url('privilege/role_form')?>" method="post" class="window_form">
	<div class="h">
		<span>组名称：</span>
		<div class="pingzhen clearfix">
		<input name="name" size="40" value="<?php echo $vo['name'];?>" data-rule="required" data-msg="请输入组名称" />
		</div>
	</div>
	<div class="h">
		<span>说明：</span>
		<div class="pingzhen clearfix">
			<textarea name="description" style="width:250px;height:50px;"><?php echo $vo['description'];?></textarea>
		</div>
	</div>
	<div class="h" id="errmsg" style="font-size:14px;font-weight:bold;text-align:center;color:red;"></div>
	<input type="hidden" value="yes" name="dosave" />
	<input type="hidden" value="<?php echo $vo['id'];?>" name="id">
</form>