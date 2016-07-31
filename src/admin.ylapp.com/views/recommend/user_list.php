		<table class="ui-table">
			<thead>
				<tr>
					<th>用户编号</th>
					<th>用户邮箱</th>
					<th>用户昵称</th>
					<th>用户头像</th>
					<th>排序 [<a class="ui-operate-button ui-operate-buttonEdit" onclick="recommend_sort();return false;">修改排序</a>]</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($list)): foreach ($list as $k=>$v):?>
				<tr id="row_<?php echo $v['id'];?>">
					<td><?php echo $v['uid'];?></td>
					<td><?php echo $v['email'];?></td>
					<td><?php echo $v['uname'];?></td>
					<td><?php echo img(avatar($v['uid'], 'small'));?></td>
					<td>
						<span><?php echo $v['sort'];?></span>
						<input size="2" class="ui-form-text ui-form-textRed sort_order"  name="id_<?php echo $v['id'];?>" value="<?php echo $v['sort'];?>" />
					</td>
					<td>
						<a class="ui-operate-button ui-operate-buttonDel" href="<?php echo site_url('recommend/delete');?>" type="confirm" title="你确认要取消当前推荐吗？" data-id="<?php echo $v['id'];?>" callback="$('#RecommendList tr#row_<?php echo $v['id'];?>').remove();">取消推荐</a>
					</td>
				</tr>
				<?php endforeach; endif;?>
			</tbody>
		</table>