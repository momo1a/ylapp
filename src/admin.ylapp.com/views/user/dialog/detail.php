<table cellpadding="3" cellspacing="1" class="ui-table">
	<thead>
		<tr>
			<th>时间</th>
			<th>状态</th>
			<th>保证金额（元）</th>
			<th>操作人员</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($user_log as $item) {?>
		<tr>
			<td><?php echo date('Y-m-d G:i:s',$item['dateline'])?></td>
			<td><?php echo $item['content']?></td>
			<td><?php echo intval($item['after_money'])?></td>
			<td><?php echo $item['admin_uid']?></td>
		</tr>
		<?php }?>
	</tbody>
</table>