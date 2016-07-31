<table class="ui-table">
	<thead>
		<tr>
			<th>商品编号</th>
			<th>操作人</th>
			<th>操作内容</th>
			<th>时间</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($logs as $k=>$v):?>
		<tr>
			<td><?php echo $order['gid'];?></td>
			<td><?php echo $v['uname'];?></td>
			<td><?php echo $v['content'];?></td>
			<td><?php echo date("Y-m-d H:i:s", $v['dateline']);?></td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>