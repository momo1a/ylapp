<!-- 帮助中心-分类添加 -->
<form action="<?php echo site_url('help/category_'.$action.'/'.$type.'/'.$pid)?>" method="post" class="window_form">
	<div class="h">
		<span>名称：</span>
		<p class="pingzhen clearfix">
			<input type="text" name="cate_name" size="30" data-rule="required" data-msg="请填写类目名称" />
			<span style="width:auto;" id="for_cate_name"></span>
		</p>
	</div>
	<div class="h">
		<span>排序：</span>
		<p class="pingzhen clearfix">
			<input type="text" name="cate_sort" prefix="noempty" data-rule="range(1,99999)" data-msg="只能输入数字"/>
			<span style="width:auto;" id="for_cate_sort"></span>
		</p>
	</div>
</form>
