<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<!-- 韦明磊<nicolaslei@163.com>修改于2014.1.22 -->
<div class="ui-box ui-box2">
	<div class="ui-box-outer">
		<div class="ui-box-inner"> 
			<div class="ui-box-head"><span class="ui-box-tit"><?php echo $page_title;?></span></div>
			<div class="ui-box-body">
				<form id="role_form" action="<?php echo site_url($from_url)?>" type="ajax" callback="load('<?php echo site_url('privilege/index/'.$role_id)?>');" method="post">
				<input type="hidden" value="yes" name="dosave" />
				<table width="100%" border="1" id="role_list">
					<?php if ($actions):?>
					<?php foreach ($actions as $module):?>
					<tr>
						<td width="150">
							<!-- 一级分类 -->
							<label style="display:inline-block;margin-left: 10px; margin-right: 20px; line-height: 2;" title="<?php echo $module['name'];?>">
								<input id="<?php echo $module['code'];?>" type="checkbox" style="margin-right: 3px;" name="modules[]" value="<?php echo $action['id'];?>" /><?php echo $module['name'];?></label></td>
						<td>
							<table width="100%" border="1">
								<tr><td width="150" height="40"></td><td align="center">
										<strong>独占功能</strong></td>
									<td width="250" align="center">
										<strong>通用功能</strong></td></tr>
								<!-- 二级分类 -->
								<?php if ($module['columns']):?>
								<?php foreach ($module['columns'] as $column):?>
								<tr>
									<td width="150">
										<label style="display:inline-block;margin-left: 10px; margin-right: 20px; line-height: 2;" title="<?php echo $column['name'];?>">
											<input id="<?php echo $module['code'];?>-<?php echo $column['id']?>" pid="<?php echo $module['code'];?>" type="checkbox" style="margin-right: 3px;" name="columns[]" value="<?php echo $column['id'];?>" /><?php echo $column['name'];?></label></td>
									<td>
										<table width="100%" border="1">
											<?php foreach ($column['column_actions'] as $column_action):?>
											<tr>
												<td width="200">
												<label style="display:inline-block;margin-left: 10px; margin-right: 20px; line-height: 2;" title="<?php echo $column_action['description'];?>">
													<input id="<?php echo $module['code'];?>-<?php echo $column['id'];?>-<?php echo $column_action['id'];?>" pid="<?php echo $module['code'];?>-<?php echo $column['id'];?>" tid="<?php echo $module['code'];?>"<?php if(in_array($column_action['id'], $role_action_ids)):echo ' checked="checked"';endif;?> type="checkbox" style="margin-right: 3px;" name="actions[]" value="<?php echo $column_action['id'];?>" />
													<?php echo $column_action['title']?>
												</label>
												</td>
												<td>
													<!-- 三级分类[栏目] -->
													<?php if ($column_action['actions']):?>
													<?php foreach ($column_action['actions'] as $action):?>
													<label style="display:inline-block;margin-left: 10px; margin-right: 20px; line-height: 2;" title="<?php echo $action['description'];?>">
														<input id="<?php echo $module['code'];?>-<?php echo $column['id'];?>-<?php echo $column_action['id'];?>-<?php echo $action['id'];?>" fid="<?php echo $module['code'];?>-<?php echo $column['id'];?>-<?php echo $column_action['id'];?>" pid="<?php echo $module['code'];?>-<?php echo $column['id'];?>" tid="<?php echo $module['code'];?>"<?php if(in_array($action['id'], $role_action_ids)):echo ' checked="checked"';endif;?> type="checkbox" style="margin-right: 3px;" name="actions[]" value="<?php echo $action['id'];?>" />
														<?php echo $action['title']?>
													</label>
													<?php endforeach;?>
													<?php endif;?>
												</td>
											</tr>
											<?php endforeach;?>
										</table>
									</td>
									<td>
										<?php if ($column['universal_actions']):?>
										<?php foreach ($column['universal_actions'] as $uaction):?>
											<label style="display:inline-block;margin-left: 10px; margin-right: 20px; line-height: 2;" title="<?php echo $action['description'];?>">
												<input id="<?php echo $module['code'];?>-<?php echo $column['id'];?>-<?php echo $uaction['id'];?>"<?php if(in_array($uaction['id'], $role_action_ids)):echo ' checked="checked"';endif;?> type="checkbox" style="margin-right: 3px;" name="actions[]" value="<?php echo $uaction['id'];?>" />
												<?php echo $uaction['title']?>
											</label>
										<?php endforeach;?>
										<?php endif;?>
									</td>
								</tr>
								<?php endforeach;?>
								<?php endif;?>
							</table>
						</td>
					</tr>
					<?php endforeach;?>
					<?php endif;?>
					<tr><td>
					</td><td><input class="ui-form-button ui-form-buttonBlue" type="submit" value="保存" />
					<input class="ui-form-button ui-form-buttonBlue" type="button" onclick="load('<?php echo site_url('privilege/index/'.$role_id)?>');" value="取消" /></td></tr>
				</table>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
// 将操作的上级勾选上
$('#role_list :checked').each(function(){
	select(this, $(this).attr('checked') == 'checked');
});

// 绑定点击事件
$('#role_list input:checkbox').bind('click', function(){
	var checked = $(this).attr('checked') == 'checked';
	// 设置下一级的勾选状态
	$("#role_list input[id^='"+this.id+"-']").attr('checked', checked ? 'checked' : false);
	// 设置上级的勾选状态
	select(this, checked);
});

function select(obj, checked) {
	fid = $(obj).attr('fid'),
	pid = $(obj).attr('pid'),
	tid = $(obj).attr('tid');

	// 设置前置操作的勾选状态
	selectParent('fid', fid, checked);
	// 设置二级的勾选状态
	selectParent('pid', pid, checked);
	// 设置一级的勾选状态
	selectParent('tid', tid, checked);
}

function selectParent(id,value,checked) {
	if (value && value != 'undefined') {
		if (checked) {
			$("#"+value).attr('checked', 'checked');
		}else {
			var other = $("["+id+"='"+value+"']:checked").length;
			if (other == 0 && id != 'fid') {
				$("#"+value).attr('checked', false);
			}
		}
	}
}
</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>