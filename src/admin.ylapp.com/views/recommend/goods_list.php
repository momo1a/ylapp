		<style>
		.item-sort input{text-align: center;display:none;}
		</style>
		<div class="ui-box ui-box2">
			<div class="ui-box-head">
				<p style="float:right;margin-right:15px;display:inline;">
					<?php if(isset($list[0]['id'])){?><a class="ui-form-btnSearch" href="<?php echo site_url('recommend/delete_all/');?>" type="confirm" title="确定要清空列表吗？" callback="reload" data-id="<?php echo $list[0]['id'];?>" data-type="<?php echo $list[0]['type']?>" data-category="<?php echo $list[0]['category_id']?>">清空列表</a><?php }?>
				</p>
			</div>
			<div class="ui-box-body">
				<table class="ui-table">
					<thead>
						<tr>
							<th>编号</th>
							<th>活动标题</th>
							<th>商家名称<br /></th>
							<th>发布时间</th>
							<th>数量\剩余</th>
							<th>网购价<br />/折扣</th>
		                    <th>活动状态</th>
							<th>排序 [&nbsp;
								<a href="javascript:;" class="ui-operate-button ui-operate-buttonEdit" onclick="editsort(this);return false;">&nbsp;</a>
								<a style="display: none;" href="javascript:;" class="ui-operate-button ui-operate-buttonSave" onclick="recommend_sort();return false;">&nbsp;</a>
							]</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<?php if(is_array($list)): foreach ($list as $k=>$v):?>
						<tr id="row_<?php echo $v['id'];?>">
							<td><?php echo $v['gid'];?></td>
							<td><a href="<?php echo $this->config->item('domain_detail').$v['gid'].'.html'?>" target="_blank"><?php echo $v['title'];?></a></td>
							<td><?php echo $v['uname'];?><br /></td>
							<td><?php echo date("Y-m-d H:i:s", $v['dateline']);?></td>
							<td><em><?php echo $v['quantity'];?></em> 份\<em><?php echo intval($v['remain_quantity']);?></em>份</td>
							<td><em><?php echo $v['price'];?></em> 元<br /><em><?php echo $v['discount'];?></em> 折</td>
		                    <td> <?php echo $goods_status[$v['state']]?> </td>
							<td class="item-sort">
								<span><?php echo $v['sort'];?></span>
								<input size="2" class="ui-form-text ui-form-textRed sort_order" type="text" name="id_<?php echo $v['id'];?>" value="<?php echo $v['sort'];?>" />
							</td>
							<td><a class="ui-operate-button ui-operate-buttonDel" href="<?php echo site_url('recommend/delete');?>" type="confirm" title="你确认要取消当前推荐吗？" data-id="<?php echo $v['id'];?>" callback="$('#RecommendList tr#row_<?php echo $v['id'];?>').remove();">取消推荐</a></td>
						</tr>
						<?php endforeach; endif;?>
					</tbody>
				</table>
			</div>
		</div>
		<script>
			function editsort(o){
				$(o).hide();
				$('.item-sort span').hide();
				$(o).siblings().show();
				$('.item-sort input').show();
			}
		</script>