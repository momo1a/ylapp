		<table class="ui-table">
			<thead>
				<tr>
					<th>买家账号</th>
					<th>买家名称</th>
					<th>活动标题</th>
					<th>晒单图片</th>
					<th>排序 [<a class="ui-operate-button ui-operate-buttonEdit" onclick="recommend_sort();return false;">修改排序</a>]</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($list)): foreach ($list as $k=>$v):?>
				<tr id="row_<?php echo $v['recommend_id'];?>">
					<td><?php echo $v['uid'];?></td>
					<td><a href="<?php echo config_item('domain_shikee_bbs').'space-uid-'.$v['uid'].'.html'?>" target="_blank"><?php echo $v['uname'];?></a></td>
					<td><?php echo $v['title'];?></td>
					<td><a href="<?php echo config_item('domain_shikee_bbs').'space-uid-'.$v['uid'].'.html'?>" target="_blank"><img width="50" src="<?php echo image_url($v['id'], $v['img_url']);?>"/></a></td>
					<td>
						<span><?php echo $v['recommend_sort'];?></span>
						<input size="2" class="ui-form-text ui-form-textRed sort_order"  name="id_<?php echo $v['recommend_id'];?>" value="<?php echo $v['recommend_sort'];?>" />
					</td>
					<td>
						<a class="ui-operate-button ui-operate-buttonDel" href="<?php echo site_url('recommend/delete');?>" type="confirm" title="你确认要取消当前推荐吗？" data-id="<?php echo $v['recommend_id'];?>" callback="$('#RecommendList tr#row_<?php echo $v['recommend_id'];?>').remove();">取消推荐</a>
					</td>
				</tr>
				<?php endforeach; endif;?>
			</tbody>
		</table>