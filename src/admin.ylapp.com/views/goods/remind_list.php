				<table class="ui-table">
					<thead>
						<tr>
							<th>状态</th>
							<th>会员名</th>
							<th>活动编号</th>
							<th>邮箱</th>
							<th>手机</th>
							<th>开团/追加提醒</th>
							<th>时间</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($list as $k=>$v):?>
						<tr>
							<td><?php switch ($v['state']) {
								case 3:
									echo '×';
								break;
								case 2:
									echo '√';
								break;
								case 1:
									echo '!';
								break;
							}?></td>
							<td><?php echo $v['uname']; ?></td>
							<td><?php echo $v['gid']; ?></td>
							<td><?php echo $v['email'] ? $v['email'] : '-'; ?></td>
							<td><?php echo $v['mobile'] ? $v['mobile'] : '-'; ?></td>
							<td><?php echo 2==$v['type'] ? '追加提醒' : '开团提醒'; ?></td>
							<td><?php echo date("Y-m-d", $v['dateline']); ?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="7">
								<p class="ui-paging"><?php echo $pager;?></p>
							</td>
						</tr>
					</tfoot>
				</table>