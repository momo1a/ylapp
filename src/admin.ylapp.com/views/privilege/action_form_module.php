<form action="<?php echo site_url('privilege/action_form_module')?>" method="post" class="window_form">
	<div class="h">
		<span>模块名称：</span>
		<div class="pingzhen clearfix">
			<input name="name" style="width:250px;" value="<?php echo $model['name'];?>" data-rule="required" data-msg="请输入操作名称" />
			<span id="for_name" style="width:auto;text-align:left;"></span>
		</div>
	</div>
	<div class="h">
		<span>模块标识：</span>
		<div class="pingzhen clearfix">
			<input name="code" value="<?php echo $model['code'];?>" data-rule="required|action_code" data-msg="模块标识\只能输入数字,字母,_" />
			<span id="for_code" style="width:auto;text-align:left;"></span>
		</div>
	</div>
	<div class="h">
		<span>模块样式：</span>
		<div class="pingzhen clearfix">
			<input name="css" value="<?php echo $model['css'];?>" />
			<span id="for_css" style="width:auto;text-align:left;"></span>
		</div>
	</div>
	<div class="h">
		<span>排序：</span>
		<div class="pingzhen clearfix">
			<input name="sort_order" value="<?php echo $model['sort_order'];?>" />
			<span id="for_sort_order" style="width:auto;text-align:left;"></span>
		</div>
	</div>
	<div class="h" id="errmsg" style="font-size:14px;font-weight:bold;text-align:center;color:red;"></div>
	<input type="hidden" value="yes" name="dosave" />
	<input type="hidden" value="<?php echo $model['id'];?>" name="id">
</form>
<script type="text/javascript">
MyRule.action_code = /^[a-z0-9]+$/i;
</script>