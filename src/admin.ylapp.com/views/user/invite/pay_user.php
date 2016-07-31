<div style="width:460px">
<div style="font-size:12px;margin:5px 0px 5px 15px;">系统已过滤“被封号邀请人”的待打款记录。</div>
<div style="font-size:12px;margin:5px 0px 5px 15px;">符合要求的待打款记录有<?php echo $wait_pay_user_total;?>条，本次处理前<?php echo $need_money_count;?>条（每次最大只能处理<?php echo $limit;?>条）。</div>
	<form action="<?php echo site_url('user_invite/pay') ?>" method="post">
		<input type="hidden" value="is_post" name="is_post" />
		<input type="hidden" value="<?php echo $need_money;?>" name="need_money" />
		<table cellpadding="4" cellspacing="1" class="ui-table2" style="margin:0px 0 0px 0;">
			<tr><th style="width: 102px;">待付款奖励金：</th><td><span style="font-family:微软雅黑;font-size:13px;font-weight:bold;font-style:normal;text-decoration:none;color:#FF0000;">￥<?php echo sprintf('%.2f', $need_money);?></span>（<?php echo $need_money_count;?>条）</td></tr>
			<tr><th>互联支付余额：</th><td><span style="font-family:微软雅黑;font-size:13px;font-weight:bold;font-style:normal;text-decoration:none;color:#006633;">￥<?php echo sprintf('%.2f', $last_money);?></span></td></tr>
			<tr>
				<th>操作码：</th>
				<td>
					<input name="op_password" type="password" class="ui-form-text ui-form-textRed" data-rule="required|minlength(6)|maxlength(15)" data-msg="*请输入操作码|*操作码错误，请重新输入|*操作码错误，请重新输入">
					<p><span id="for_op_password" class="ui-table-statusR" style="font-weight: normal;"></span></p>
				</td>
			</tr>
		</table>
	</form>
</div>