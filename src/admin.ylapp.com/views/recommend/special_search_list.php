				<table class="ui-table" id="special_search_<?php echo $segment.'_'.$cate_id;?>">
					<thead>
						<tr>
							<th>活动编号</th>
							<th>用户邮箱</th>
							<th>商家名称</th>
							<th>用户头像</th>
							<th class="ui-table-colActivityName">活动标题</th>
							<th>商品类目</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<?php if(isset($search_goods) && is_array($search_goods)): foreach ($search_goods as $k=>$v):?>
						<tr id="search_row_<?php echo $v['gid'];?>">
							<td><?php echo $v['gid']; ?></td>
							<td><?php echo $v['email']; ?></td>
							<td><?php echo $v['uname']; ?></td>
							<td><?php echo img(avatar($v['uid'], 'small'));?></td>
							<td class="ui-table-activityName"><a target="_blank" href="<?php echo $this->config->item('domain_detail').$v['gid'].'.html'; ?>"><?php echo img(image_url(0, $v['img'], '60x60'));  echo $v['title']; ?></a></td>
							<td><?php echo $goods_cates[$v['pid']]['name'].'<br />'.$goods_cates[$v['cid']]['name']; ?></td>
							<td class="ui-table-operate">
								<?php if(in_array($v['gid'], $list_gids)):?>
								<a>已推荐</a>
								<?php else:?>
								<a href="<?php echo site_url('recommend/set_recommend');?>" type="post"  data-special_id="<?php echo $segment; ?>" data-cate_id="<?php echo $cate_id;?>" data-targetid="<?php echo $v['gid'];?>" data-type="special" callback="load('<?php echo site_url($this->router->class.'/special/'.$segment);?>', $('div#spacial_list_<?php echo $segment.'_'.$cate_id;?>'), {listonly:'item',cate_id:<?php echo $cate_id;?>}); $('table#special_search_<?php echo $segment.'_'.$cate_id;?> tr#search_row_<?php echo $v['gid'];?> td.ui-table-operate').html('<?php echo '<a>已推荐</a>';?>');">推荐</a>
								<?php endif;?>
							</td>
						</tr>
						<?php endforeach; endif;?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="7" class="ui-paging"><?php echo isset($pager)?$pager:'';?></td>
						</tr>
					</tfoot>
				</table>