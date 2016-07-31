<form id="explain_setting_form" action="<?php echo site_url('appeal/type_form')?>" method="post" class="window_form">
	<div class="h">
		<span>申诉名称：</span>
		<div class="pingzhen clearfix">
		<input name="name" data-rule="required" data-msg="请输入申诉类型名称" size="40" value="<?php echo $vo['name'];?>"/>
		</div>
	</div>
	<div class="h">
		<span>需要对方回复：</span>
		<div class="pingzhen clearfix">
			<select name="need_reply">
				<option value="0" <?php if(!$vo['need_reply']):?>selected="selected"<?php endif;?>>不需要</option>
				<option value="1" <?php if($vo['need_reply']):?>selected="selected"<?php endif;?>>需要</option>
			</select>
		</div>
	</div>
	<div class="h">
		<span>排序：</span>
		<div class="pingzhen clearfix">
			<input name="sort" data-rule="sort" data-msg="排序只能输入整数" value="<?php echo $vo['sort'];?>" size="5">
		</div>
	</div>
	<input type="hidden" value="yes" name="dosave" />
	<input type="hidden" value="<?php echo $vo['utype'];?>" name="utype" />
	<input type="hidden" value="<?php echo $vo['id'];?>" name="id">
</form>
<script type="text/javascript">
MyRule.sort = /^\d*$/;
</script>