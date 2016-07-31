<div style="width: 600px;">
	<table class="ui-table" cellpadding="3" cellspacing="1">
		<thead>
			<tr>
				<td>淘宝帐号</td>
				<td>备注</td>
				<td>操作人</td>
				<td>操作内容</td>
				<td>时间</td>
			</tr>
		</thead>
		<tbody>
		<?php if(is_array($logs) && count($logs)): foreach ($logs as  $k=>$v):?>
			<tr>
				<td><?php echo $v['operation']=='重置' ? '-' : $v['bind_name']?></td>
				<td><?php echo str_replace(',', '<br />', $v['bind_note']);?></td>
				<td><?php echo $v['operate_uname'];?></td>
				<td><?php echo $v['operation'];?></td>
				<td><?php echo date('Y-m-d H:i:s',$v['dateline'])?></td>
			</tr>
		<?php endforeach; endif;?>
		</tbody>
	</table>
</div>