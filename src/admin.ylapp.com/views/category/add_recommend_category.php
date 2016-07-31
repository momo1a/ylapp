	<form action="<?php echo site_url('category/add_recommend_category')?>" method="post" class="window_form">
		<div class="h">
			<span>分类名称：</span>
			<div class="pingzhen clearfix">
			<input name="name" size="30" value="<?php echo $cate['name'];?>" data-rule="required|maxlength(10)" data-msg="请填写类目名称|最多10个字符"/>
			<span style="width:auto;" id="for_name"></span>
			</div>
		</div>
		<div class="h">
			<span>排序：</span>
			<div class="pingzhen clearfix">
			<input name="sort_order" size="10" value="<?php echo $cate['sort_order'];?>"/>
			</div>
		</div>
		<div class="h" id="errmsg" style="font-size:14px;font-weight:bold;text-align:center;color:red;"></div>
		<input type="hidden" name="id" value="<?php echo $cate['id'];?>" />
		<input type="hidden" name="parent_id" value="<?php echo $cate['parent_id'];?>" />
		<input type="hidden" name="dosave" value="yes" />
	</form>