<div style="width: 600px;">
	<table class="ui-table" cellpadding="3" cellspacing="1">
		<thead>
			<tr>
				<td>操作人</td>
				<td>操作内容</td>
				<td>时间</td>
				<td>备注</td>
			</tr>
		</thead>
		<tbody>
		<?php  foreach ($logs as  $k=>$v):?>
			<tr>
				<td><?php echo $v['operate_uname'];?></td>
				<td><?php echo $v['operation'];?></td>
				<td><?php echo date('Y-m-d H:i:s',$v['dateline'])?></td>
				<td><?php 
				//用户操作则输出-,否则输出正常的备注
				if($v['uid']==$v['operate_uid'])
				{
					echo '-';
				}else{
					echo $v['bind_note'];
				}
				?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>