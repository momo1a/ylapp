<div style="width:450px;">
 <form action="<?php echo site_url('user/remark'); ?>" method="post">
	<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:20px 0 20px 0;">
		<tr>
			<td style="text-align:center">
				<textarea style="margin-left: 20px;width: 410px;height: 200px;" name="remark"  data-rule="maxlength(1000)" data-msg="*内容必须限定在1000字符内，否则无法保存"  ><?php echo $data['remark']?></textarea>
				<p style="width:400px;"><span id="for_remark" class="ui-table-statusR" style="font-weight: normal;"></span></p>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:center;padding-top:10px;">
				<input type="hidden" value="<?php echo $seller_uid?>" name="uid" />
				<input type="hidden" value="<?php echo $deposit_type?>" name="deposit_type" />
				<input type="hidden" value="is_post" name="is_post" />
			</td>
		</tr>
	</table>
</form>
</div>