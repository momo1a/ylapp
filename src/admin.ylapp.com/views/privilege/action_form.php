<form action="<?php echo site_url('privilege/action_form')?>" method="post" class="window_form">
	<div class="h">
		<span>所属模块[一级]：</span>
		<div class="pingzhen clearfix">
			<select name="module_id" data-rule="required" data-msg="请选择模块" onchange="selectModule(this);">
				<option value="">选择模块</option>
				<?php foreach ($modules as $module):?>
				<option value="<?php echo $module['id'];?>"<?php if (isset($vo)&&$module['id']==$vo['module_id']):echo ' selected="selected"';endif;?>>
					<?php echo $module['name'];?></option>
				<?php endforeach;?>
			</select>
			<span id="for_module_id" style="width:auto;text-align:left;"></span>
		</div>
	</div>
	<div class="h">
		<span>栏目[二级]：</span>
		<div class="pingzhen clearfix">
			<select name="column_id" data-rule="required" onchange="selectHandleFront(this);" data-msg="请选择栏目">
				<?php if (isset($columns)):?>
				<?php foreach ($columns as $column):?>
				<option value="<?php echo $column['id'];?>"<?php if ($column['id']==$vo['column_id']):echo ' selected="selected"';endif;?>>
					<?php echo $column['name'];?></option>
				<?php endforeach;?>
				<?php else:?>
				<option value="">先选择模块</option>
				<?php endif;?>
			</select>
			<span id="for_column_id" style="width:auto;text-align:left;"></span>
		</div>
	</div>
	<div class="h">
		<span>操作类型：</span>
		<div class="pingzhen clearfix">
			<label><input onclick="radioHandleFront('colunm');" name="column_show" type="radio" value="1"<?php if (isset($vo['column_show']) && $vo['column_show'] OR !isset($vo['column_show'])):echo ' checked="checked"';endif;?> /> 独占</label>
			<label><input onclick="radioHandleFront('action');" name="column_show" type="radio" value="0"<?php if (isset($vo['column_show']) && !$vo['column_show']):echo ' checked="checked"';endif;?> /> 通用</label>
		</div>
	</div>
	<div class="h" id="front_action"<?php if (isset($vo) && !$vo['front_id'] AND !$vo['column_show']): echo 'style="display:none;"';endif;?>>
		<span>前置操作：</span>
		<div class="pingzhen clearfix">
			<select name="front_id">
				<option value="0">无(直接在栏目显示)</option>
				<?php foreach ($front_actions as $action):?>
				<?php if ($action['id'] <> $vo['id']):?>
				<option value="<?php echo $action['id'];?>"<?php if ($action['id']==$vo['front_id']):echo ' selected="selected"';endif;?>>
					<?php echo $action['title'];?></option>
				<?php endif;?>	
				<?php endforeach;?>
			</select>
			<span id="for_module_id" style="width:auto;text-align:left;"></span>
		</div>
	</div>
	<div class="h">
		<span>操作URI：</span>
		<div class="pingzhen clearfix">
		<input name="uri" style="width:250px;" value="<?php echo isset($vo['uri'])?$vo['uri']:'';?>" data-rule="required|action_name" data-msg="请输入操作名称|只能输入数字,字母和'/'号" />
		<span id="for_name" style="width:auto;text-align:left;"></span>
		</div>
	</div>
	<div class="h">
		<span>操作名称：</span>
		<div class="pingzhen clearfix">
		<input name="title" size="30" value="<?php echo isset($vo['title'])?$vo['title']:'';?>" data-rule="required" data-msg="请输入操作标题" />
		<span id="for_title" style="width:auto;text-align:left;"></span>
		</div>
	</div>
	<div class="h">
		<span>说明：</span>
		<div class="pingzhen clearfix">
			<textarea name="description" style="width:250px;height:50px;"><?php echo isset($vo['description'])?$vo['description']:'';?></textarea>
		</div>
	</div>
	<div class="h">
		<span>排序：</span>
		<div class="pingzhen clearfix">
			<input name="sort_order" value="<?php echo isset($vo['sort_order'])?$vo['sort_order']:'';?>" />
			<span id="for_title" style="width:auto;text-align:left;">从小到大排序</span>
		</div>
	</div>
	
	<div class="h" id="errmsg" style="font-size:14px;font-weight:bold;text-align:center;color:red;"></div>
	<input type="hidden" value="yes" name="dosave" />
	<input type="hidden" value="<?php echo isset($vo)?$vo['id']:0	;?>" name="id">
</form>
<script type="text/javascript">
MyRule.action_name = /^[a-z0-9/\_]+$/i;

// 记录上次已经获取的ID，多次重复获取
var handledID = 0;

function selectModule(obj) {
	var moduleId = obj.options[obj.selectedIndex].value;
	$.ajax({
		type:'GET',
		url:'<?php echo site_url('privilege/get_action_columns')?>',
		data:{id:moduleId},
		dataType:'JSON',
		success:function(response) {
			var options = '<option value="">选择栏目</option>';
			$.each(response, function(i,item){
				options += '<option value="'+item.id+'">'+item.name+'</option>';
			});
			$("select[name='column_id']").html(options);
		}
	});
}

function selectHandleFront(obj) {
	if ($("input[name='column_show']:checked").val() == 1)
		_handleFrontData(obj.options[obj.selectedIndex].value);
}

function radioHandleFront(status) {
	if (status == 'colunm') {
		var column_id = $("[name='column_id'] option:selected").val();
		if (column_id>0) {
			$('#front_action').show('normal', 'linear');
			_handleFrontData(column_id);
		}else {
			alert('请先选择二级栏目');
			$("input[name='column_show']").get(1).checked = true;
			$('#front_action').hide('normal', 'linear');
		}
	}else {
		$('#front_action').hide('normal', 'linear');
		$("select[name='front_id']").get(0).selectedIndex = 0
	}
}

function _handleFrontData(id) {
	if (!id || id<1 || handledID==id)
		return;

	$.ajax({
		type:'GET',
		url:'<?php echo site_url('privilege/get_column_actions')?>',
		data:{id:id},
		dataType:'JSON',
		success:function(response) {
			var options = '<option value="">无(直接在栏目显示)</option>';
			$.each(response, function(i,item){
				options += '<option value="'+item.id+'">'+item.title+'</option>';
			});
			$("select[name='front_id']").html(options);
			handledID = id;
		}
	});
}
</script>