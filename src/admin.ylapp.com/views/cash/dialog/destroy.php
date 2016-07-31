<div style="width:350px">
	<form action="<?php echo site_url('cash/destroy') ?>" method="post" style="margin:15px 0 15px 0;">
		<input type="hidden" value="is_post" name="is_post" />
		<input name="ids" type="hidden" value="<?php echo $ids;?>" />
		<table cellpadding="3" cellspacing="1" class="ui-table2">
			<tr>
				<th width="80px">作废原因：</th>
				<td>
					<input name="reason" type="text" class="ui-form-text ui-form-textRed" data-rule="required|minlength(3)|maxlength(20)" data-msg="*请输入作废理由|*至少输入3个字符|*最多能输入20个字符">
					<p></p>
				</td>
			</tr>
		</table>
	</form>
</div>