<table cellpadding="3" cellspacing="1" class="ui-table">
	<thead>
		<tr>
		    <th width="25%">操作人员</th>
			<th>操作内容</th>
			<th width="30%">操作时间</th>
		</tr>
	</thead>
	<tbody>
	<?php if( is_array($cash_log) ){?>
		<?php foreach ($cash_log as $item) {?>
		<tr>
		    <td><?php echo $item['uname']?></td>
			<td><?php echo $item['content']?></td>
			<td><?php echo date('Y-m-d G:i:s',$item['dateline'])?></td>
		</tr>
		<?php }?>
	<?php }?>
	</tbody>
</table>