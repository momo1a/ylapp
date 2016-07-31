<div style="width:300px">
	<form action="<?php echo site_url('cash/pay_user') ?>" method="post">
		<input type="hidden" value="is_post" name="is_post" />
		<input name="ids" type="hidden" value="<?php echo $ids;?>" />
		<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:10px 0 10px 0;">
			<tr>
				<th>输入现金券操作码：</th>
				<td>
					<input name="op_password" type="password" class="ui-form-text ui-form-textRed" data-rule="required|minlength(6)|maxlength(15)" data-msg="*请输入现金券操作码|*操作码错误，请重新输入|*操作码错误，请重新输入">
					<p><span id="for_op_password" class="ui-table-statusR" style="font-weight: normal;"></span></p>
				</td>
			</tr>
		</table>
	</form>
</div>