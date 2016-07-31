<div style="width:180px;">
	<form action="<?php echo site_url('user/refund')?>" method="post"  onsubmit="return false">
		<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:20px 0 20px 0;">
			<tr>
				<th width="150px">请输入诚信金操作码:</th>
				<td></td>
			</tr>
			<tr>
				<th>
					<input type="password" class="ui-form-text ui-form-textRed" name="password"  data-rule="required|minlength(6)|maxlength(15)"  data-msg="*请输入操作码|*至少输入6个字符|*最多能输入15个字符">
					<p><span id="for_password" class="ui-table-statusR" style="font-weight: normal;"></span></p>
				</th>
				<td style="text-align:center;padding-top:20px;">
					<input type="hidden" value="<?php echo $seller_uid ?>" name="uid">
                    <input type="hidden" value="<?php echo $deposit_type ?>" name="deposit_type">
					<input type="hidden" value="is_post" name="is_post" />
				</td>
			</tr>
		</table>
	</form>
</div>