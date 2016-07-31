<div style="width:450px">
	<form action="<?php echo site_url('prompts/add');?>" method="post">
		<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:20px 0 20px 0;">
			<tr>
				<th width="100px">类型：</th>
				<td>
					<select name="type" id="type">
					  <option value="1">普通</option>
					  <option value="2">可填写</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>标题：</th>
				<td>
					<input type="text" class="ui-form-text ui-form-textRed"  name="title"  size="40" maxlength="50"   data-rule="required" data-msg="请输入标题">
                       <span id="for_title" style="width:auto;"></span>
				</td>
			</tr>
			<tr id="prompts" style="display:none;">
				<th>提示语：</th>
				<td>
					<input type="text" class="ui-form-text ui-form-textRed"  name="prompts" size="40"  maxlength="25">
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:center;padding-top:20px;">
					<input type="hidden" value="is_post" name="is_post" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="goods_type" value="<?php echo $goods_type;?>"/>
	</form>
</div>
<script>
$('#type').on('change',function(){
	if($(this).find('option:selected').val() == '1'){
		$('#prompts').hide();
	}else{
		$('#prompts').show();
		}
	});

</script>