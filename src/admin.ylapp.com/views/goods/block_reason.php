<table class="ui-table">
	<thead>
		<tr>
			<th width="100">操作</th>
			<th>原因</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($reasons as $k=>$v):?>
		<tr>
			<td><?php switch ($v['type']){
				case 2:
					echo '解除屏蔽';
					break;
				case 1:
					echo '屏蔽';
					break;
			} ?></td>
			<td><?php echo $v['reason']?></td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>