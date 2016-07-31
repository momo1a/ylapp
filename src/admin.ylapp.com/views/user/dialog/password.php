<div style="width:450px">
	<form action="<?php echo site_url('user/password') ?>" method="post">
		<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:20px 0 20px 0;">
			<tr>
				<th width="150px">当前诚信金操作码：</th>
				<td>
					<input type="password" class="ui-form-text ui-form-textRed" data-rule="required|minlength(6)|maxlength(15)" data-msg="*请输入操作码|*至少输入6个字符|*最多能输入15个字符"  name="old_pwd">
					<span id="for_reason" class="ui-table-statusR" style="font-weight: normal;"></span>
				</td>
			</tr>
			<tr>
				<th>新诚信金操作码：</th>
				<td>
					<input type="password" class="ui-form-text ui-form-textRed"  data-rule="required|minlength(6)|maxlength(15)" data-msg="*请输入操作码|*至少输入6个字符|*最多能输入15个字符" name="new_pwd" id="new_pwd">
					<span id="for_new_pwd" class="ui-table-statusR" style="font-weight: normal;"></span>
				</td>
			</tr>
			<tr>
				<th>确认新操作码：</th>
				<td>
					<input type="password" class="ui-form-text ui-form-textRed"  data-rule="required|minlength(6)|maxlength(15)|equalto('new_pwd')" data-msg="*请输入操作码|*至少输入6个字符|*最多能输入15个字符|两个操作码不一致"  name="cmp_pwd">
					<span id="for_cmp_pwd" class="ui-table-statusR" style="font-weight: normal;"></span>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:center;padding-top:20px;">
					<input type="hidden" value="is_post" name="is_post" />
				</td>
			</tr>
		</table>
	</form>
</div>