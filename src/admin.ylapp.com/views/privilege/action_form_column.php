<form action="<?php echo site_url('privilege/action_form_column')?>" method="post" class="window_form">
	<div class="h">
		<span>所属模块：</span>
		<div class="pingzhen clearfix">
			<select name="parent_id" data-rule="required" data-msg="请选择模块">
				<option value="">选择模块</option>
				<?php foreach ($modules as $module):?>				
				<option value="<?php echo $module['id'];?>"<?php if ($model['parent_id']==$module['id']): echo 'selected="selected"';endif;?>><?php echo $module['name'];?></option>
				<?php endforeach;?>
			</select>
			<span id="for_parent_id" style="width:auto;text-align:left;"></span>
		</div>
	</div>
	<div class="h">
		<span>栏目名称：</span>
		<div class="pingzhen clearfix">
			<input name="name" style="width:250px;" value="<?php echo $model['name'];?>" data-rule="required" data-msg="请输入栏目名称" />
			<span id="for_name" style="width:auto;text-align:left;"></span>
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
	<p></p>
</form>