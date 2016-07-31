  <div class="ui-box-head" >
			<form rel="div#main-wrap" method="get" action="<?php echo site_url('recommend/goods/'.$segment)?>">
				<span>活动搜索：</span>
				<select id="type" name="mpg_key">
				<option value="gid" <?php if($so['key']=='' ||$so['key']=='gid' ) echo 'selected="selected"'; ?> >活动编号</option>
				<option value="title"  <?php if($so['key']=='title' ) echo 'selected="selected"'; ?> >活动标题</option>
				<option value="uname"  <?php if($so['key']=='uname' ) echo 'selected="selected"'; ?> >商家名称</option>
				</select>
				<input class="ui-form-text ui-form-textRed" name="mpg_val"  value="<?php echo $so['val']; ?>"/>
				<input class="ui-form-btnSearch" type="submit" value="搜 索" />
				<input type="hidden" name="uri_string" value="<?php echo uri_string()?>" />
			</form>
		</div>
        <table class="ui-table">
			<thead>
				<tr>
					<th>活动编号</th>
					<th>活动标题</th>
					<th>商家名称<br /></th>
					<th>发布时间</th>
					<th>数量\剩余</th>
					<th>网购价<br />/折扣</th>
                    <th>上线时间</th>
                    <th>活动状态</th>
					<th>排序 [<a href="javascript:;" class="ui-operate-button ui-operate-buttonEdit" onclick="recommend_sort();return false;">修改排序</a>]</th>
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
                    <td><?php echo $v['expect_online_time']?date("Y-m-d H:i:s", $v['expect_online_time']):'-';?> </td>
                    <td>
                       <?php echo $goods_status[$v['state']]?>
                    </td>
					<td>
						<span><?php echo $v['sort'];?></span>
						<input size="2" class="ui-form-text ui-form-textRed sort_order" type="text" name="id_<?php echo $v['id'];?>" value="<?php echo $v['sort'];?>" />
					</td>
					<td><a class="ui-operate-button ui-operate-buttonDel" href="<?php echo site_url('recommend/delete');?>" type="confirm" title="你确认要取消当前推荐吗？" data-id="<?php echo $v['id'];?>" callback="$('#RecommendList tr#row_<?php echo $v['id'];?>').remove();">取消推荐</a></td>
				</tr>
				<?php endforeach; endif;?>
            <!--名品馆分页-->
               <tr><td colspan="10"><div class="ui-paging"><?php echo $pager_mpg;?></div></td></tr> 
            <!--名品馆分页结束-->
			</tbody>
		</table>