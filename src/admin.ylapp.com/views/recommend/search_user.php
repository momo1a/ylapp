<table class="ui-table">
	<thead>
		<tr>
			<th>用户编号</th>
			<th>用户邮箱</th>
			<th>用户昵称</th>
			<th>用户头像</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php if(is_array($search_list)): foreach ($search_list as $k=>$v):?>
		<tr id="search_row_<?php echo $v['uid']; ?>">
			<td><?php echo $v['uid'];?></td>
			<td><?php echo $v['email'];?></td>
			<td><?php echo $v['uname'];?></td>
			<td><?php echo img(avatar($v['uid'], 'small'));?></td>
			<td>
				<a href="<?php echo site_url('recommend/set_recommend');?>" type="post" data-recommend_type="<?php echo $recommend_type?>" data-targetid="<?php echo $v['uid'];?>" data-category="<?php echo $category;?>" data-cate_id="<?php echo $cate_id;?>" callback="load('<?php echo site_url($uri_string);?>', $('div#RecommendList'), {listonly:'yes'});$('tr#search_row_<?php echo $v['uid'];?>').remove();">推荐</a>
			</td>
		</tr>
		<?php endforeach; endif;?>
	</tbody>
	<tfoot><tr><td colspan="5" class="ui-paging"><?php echo $pager;?></td></tr></tfoot>
</table>