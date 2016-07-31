				<table class="ui-table">
					<thead>
						<tr>
							<th>编号</th>
							<th class="ui-table-colActivityName">活动标题</th>
							<th>商品类目</th>
							<th>商家名称</th>
							<th class="ui-table-colDatetime">活动时间</th>
							<th>剩余/数量</th>
							<th>网购价/折扣</th>
							<th>活动状态</th>
							<th style="width: 125px;">排序 [&nbsp;<a href="javascript:;" class="ui-operate-button ui-operate-buttonEdit" onclick="special_sort('<?php echo site_url($this->router->class.'/special/'.$segment);?>', 'div#spacial_list_<?php echo $segment.'_'.$cate_id;?>',<?php echo $cate_id;?>);return false;">修改排序</a>]</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<?php if(is_array($list)): foreach ($list as $k=>$v):?>
						<tr id="row_<?php echo $v['id']; ?>">
							<td><?php echo $v['gid']; ?></td>
							<td class="ui-table-activityName"><a href="<?php echo $this->config->item('domain_detail').$v['gid'].'.html'?>" target="_blank"><?php echo $v['title'];?></a></td>
							<td><?php echo $goods_cates[$v['pid']]['name'].'<br />'.$goods_cates[$v['cid']]['name']; ?></td>
							<td><?php echo $v['uname']; ?></td>
							<td><?php echo date("Y-m-d H:i:s", $v['dateline']);?></td>
							<td><em><?php echo $v['quantity'];?></em> 份\<em><?php echo intval($v['remain_quantity']);?></em>份</td>
							<td><em><?php echo $v['price'];?></em> 元<br /><em><?php echo $v['discount'];?></em> 折</td>
							<td><?php  echo isset($goods_status[$v['state']]) ? $goods_status[$v['state']] : '未知状态'?></td>
							<td>
								<span><?php echo $v['sort'];?></span>
								<input size="2" class="ui-form-text ui-form-textRed sort_order" type="text" name="id_<?php echo $v['id'];?>" value="<?php echo $v['sort'];?>" />
							</td>
							<td class="ui-table-operate">
								<p><a href="<?php echo site_url('recommend/delete');?>" type="confirm" title="你确认要取消当前推荐吗？" data-id="<?php echo $v['id'];?>" callback="$('#spacial_list_<?php echo $segment.'_'.$cate_id;?> tr#row_<?php echo $v['id'];?>').remove();">取消推荐</a>
							</td>
						</tr>
						<?php endforeach; endif;?>
					</tbody>
				</table>