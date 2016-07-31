<table class="ui-table">
	<thead>
		<tr>
			<th>买家账号</th>
			<th>买家名称</th>
			<th>活动标题</th>
			<th>晒单图片</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php if(isset($search_list) && is_array($search_list)): foreach ($search_list as $k=>$v):?>
		<tr id="search_row_<?php echo $v['uid']; ?>">
			<td><?php echo $v['uid'];?></td>
			<td><?php echo $v['uname'];?></td>
			<td><?php echo $v['title'];?></td>
			<td><img width="50" src="<?php echo image_url($v['id'], $v['img_url']);?>"/></td>
			<td>
				<a href="<?php echo site_url('recommend/set_recommend');?>" type="post" data-recommend_type="<?php echo $recommend_type?>" data-targetid="<?php echo $v['id'];?>" data-category="<?php echo isset($category) ? $category : '';?>" data-cate_id="<?php echo isset($cate_id) ? $cate_id : 0;?>" callback="load('<?php echo site_url($uri_string);?>', $('div#RecommendList'), {listonly:'yes'});$('tr#search_row_<?php echo $v['uid'];?>').remove();">推荐</a>
			</td>
		</tr>
		<?php endforeach; endif;?>
	</tbody>
	<tfoot><tr><td colspan="5" class="ui-paging"><?php echo isset($pager)?$pager:'';?></td></tr></tfoot>
</table>