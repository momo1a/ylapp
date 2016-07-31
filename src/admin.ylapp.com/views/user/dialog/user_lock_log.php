<div style="width: 480px; height:300px;">
	<table class="ui-table" cellpadding="3" cellspacing="1">
		<thead>
			<tr>
				<td>屏蔽原因</td>
				<td>管理员账号</td>
				<td>操作内容</td>
				<td>时间</td>
			</tr>
		</thead>
		<tbody>
		<?php if(count($lock_log)): foreach ($lock_log as  $k=>$v):?>
			<tr>
				<td><?php echo $v['content']?></td>
				<td><?php echo $v['admin_uname']?></td>
				<td>
				<?php
					$state =  $v['utype']==1 ? $buyer_state[$v['after_state']] : $seller_state[$v['after_state']];
					if (in_array($v['after_state'], array(2,3,4)) && $v['day'] <>100 ) {
						 $state .= $v['day'].'天';
					}else if (in_array($v['after_state'], array(2,3,4)) && $v['day'] == 100 ){
					 	 $state .= '永久';
					}
					echo $state;
				?>
				</td>
				<td><?php echo date('Y-m-d G:i:s',$v['dateline'])?></td>
			</tr>
		<?php endforeach; endif;?>
		</tbody>
	</table>
</div>