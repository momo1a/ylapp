<div style="width:400px;">
 <form action="<?php echo site_url('user/unbind_mobile'); ?>" method="post">
	<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:10px 0;">
		<tr>
			<td style="width: 60px;text-align:right;font-size:16px;font-weight:bold;">原因：</td>
			<td style="text-align:center">
				<textarea style="width: 320px;height: 80px;" name="reason"  data-rule="maxlength(100)|required" data-msg="*内容必须限定在100字符内，否则无法保存|请填写解绑原因"  ></textarea>
				<p><span id="for_reason" class="ui-table-statusR" style="font-weight: normal;"></span></p>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:center;padding:0px;">
				<input type="hidden" value="<?php echo intval($this->input->get('uid'));?>" name="uid" />
				<input type="hidden" value="yes" name="is_post" />
			</td>
		</tr>
	</table>
</form>
</div>