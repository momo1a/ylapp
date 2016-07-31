<div style="width:350px">
	<form action="<?php echo $url ?>" method="post">
		<input type="hidden" value="is_post" name="is_post" />
		<input name="cid" type="hidden" value="<?php echo isset($cid) ? $cid : '';?>" />
		<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:10px 0 10px 0;">
			<tr>
				<th>输入现金券操作码：</th>
				<td>
					<input name="op_password" type="password" class="ui-form-text ui-form-textRed" data-rule="required|minlength(6)|maxlength(15)" data-msg="*请输入现金券操作码|*操作码错误，请重新输入|*操作码错误，请重新输入">
					<?php if(isset($params) && is_array($params)) foreach ($params as $k=>$v) :?>
					<input type="hidden" name="<?php echo $k;?>" value="<?php echo $v;?>" />
					<?php endforeach;?>
					<p><span id="for_op_password" class="ui-table-statusR" style="font-weight: normal;"></span></p>
				</td>
			</tr>
		</table>
	</form>
</div>