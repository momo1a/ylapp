<div style="width: 380px;">
<form action="<?php echo site_url('user/un_login_bind/'.$bind_type.'/'.$uid); ?>" method="post">
<input type="hidden" name="todo" value="y" />
	<table cellpadding="3" cellspacing="1" class="ui-table">
		<tbody>
	
			<tr>
				<td width="80">解除原因：</td>
				<td width="260">
				<textarea name="content" style="width: 260px;height: 61px;" data-rule="maxlength(100)" data-msg="*内容必须限定在100字符内"></textarea>
				</td>
			</tr>
		</tbody>
	</table>
</form>
</div>
