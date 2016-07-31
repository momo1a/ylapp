<table cellpadding="3" cellspacing="1" class="ui-table">
	<thead>
		<tr>
		    <th width="25%">操作人员</th>
			<th>操作内容</th>
			<th width="30%">操作时间</th>
		</tr>
	</thead>
	<tbody>
	<?php if( is_array($logs) ){?>
		<?php foreach ($logs as $item) {?>
		<tr>
		    <td><?php echo $item['operate_uname']?></td>
			<td><?php echo $item['operation']?></td>
			<td><?php echo date('Y-m-d H:i:s',$item['dateline'])?></td>
		</tr>
		<?php }?>
	<?php }?>
	</tbody>
</table>