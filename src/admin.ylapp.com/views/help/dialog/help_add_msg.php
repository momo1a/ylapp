<!-- 帮助中心-分类添加 -->
<form action="" method="post" class="window_form">
	<h1><?php if($rs){
				echo '发布成功';
			}else{
				echo '发布失败';
			}?></h1>
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
			<input type="text" name="cate_sort" prefix="noempty" data-rule="range(1.0,99999.9)" data-msg="只能输入数字(最多一位小数)"/>
			<span style="width:auto;" id="for_cate_sort"></span>
		</p>
	</div>
</form>
