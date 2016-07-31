<div style="width:450px">
	<form action="<?php echo site_url('cash/password') ?>" method="post">
		<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:20px 0 20px 0;">
			<tr>
				<th width="150px">当前现金券操作码：</th>
				<td>
					<input type="password" class="ui-form-text ui-form-textRed" data-rule="required|minlength(6)|maxlength(15)" data-msg="*请输入操作码|*至少输入6个字符|*最多能输入15个字符"  name="old_pwd">
				</td>
			</tr>
			<tr>
				<th>新现金券操作码：</th>
				<td>
					<input type="password" class="ui-form-text ui-form-textRed"  data-rule="required|minlength(6)|maxlength(15)" data-msg="*请输入操作码|*至少输入6个字符|*最多能输入15个字符" name="new_pwd" id="new_pwd">
				</td>
			</tr>
			<tr>
				<th>确认新操作码：</th>
				<td>
					<input type="password" class="ui-form-text ui-form-textRed"  data-rule="required|minlength(6)|maxlength(15)|equalto('new_pwd')" data-msg="*请输入操作码|*至少输入6个字符|*最多能输入15个字符|两个操作码不一致"  name="cmp_pwd">
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