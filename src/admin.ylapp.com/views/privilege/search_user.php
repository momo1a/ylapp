		<table class="ui-table">
			<thead>
				<tr>
					<th>用户编号</th>
					<th>用户邮箱</th>
					<th>用户昵称</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($list)): foreach ($list as $k=>$v):?>
				<tr id="row_uid_<?php echo $v['uid'];?>">
					<td><?php echo $v['uid'];?></td>
					<td><?php echo $v['email'];?></td>
					<td><?php echo $v['uname'];?></td>
					<td class="ctrlCol">
						<a href="<?php echo site_url('privilege/add_role_user')?>" type="post" data-user_id="<?php echo $v['uid'];?>" data-role_id="<?php echo $role_id;?>" callback="reload()" class="ui-operate-button ui-operate-buttonAdd">添加管理员用户</a>
					</td>
				</tr>
				<?php endforeach; else:?>
				<tr>
					<td colspan="4">暂无相关数据</td>
				</tr>
				<?php endif;?>
			</tbody>
			<tfoot>
				<tr><td colspan="4" class="ui-paging"><?php echo $pager;?></th></tr>
			</tfoot>
		</table>