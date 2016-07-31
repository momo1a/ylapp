<div style="width: 600px;">
	<table class="ui-table" cellpadding="3" cellspacing="1">
		<thead>
			<tr>
				<td>登录方式</td>
				<td>操作人</td>
				<td>操作类型</td>
				<td>操作时间</td>
				<td>备注</td>
			</tr>
		</thead>
		<tbody>
		<?php if(isset($logs) && count($logs)): ?>
		<?php foreach ($logs as  $log):?>
			<tr>
				<td><?php echo strtoupper(User_login_bind_model::type_int2string($log['bind_type'])), '<br>('.$log['account_nickname'].')';?></td>
				<td><?php echo $log['uid'] == $log['operate_uid'] ? '自己' : $log['operate_uname'];?></td>
				<td><?php echo login_bind_log_type($log['operate_type']);?></td>
				<td><?php echo date('Y-m-d H:i:s',$log['dateline']);?></td>
				<td><?php echo empty($log['content']) ? '-' : $log['content'];?></td>
			</tr>
		<?php endforeach; ?>
		<?php else:?>
			<tr>
				<td colspan="5">没有记录</td>
			</tr>
		<?php endif;?>
		</tbody>
	</table>
</div>