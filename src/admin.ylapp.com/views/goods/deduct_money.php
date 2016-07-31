<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 userList">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<form rel="div#main-wrap" action="<?php echo site_url('goods/deduct_money')?>">
					<input class="ui-form-text ui-form-textGray ui-form-textDatetime" readonly  value="<?php echo $start_time;?>" name="startTime">
					<input class="ui-form-text ui-form-textGray ui-form-textDatetime" readonly  value="<?php echo $end_time;?>" name="endTime">
					<select name="search_key">
						<option value="">--请选择--</option>
						<option value="oid" <?php echo $search_key == 'oid' ? 'selected="selected"' : '';?>>抢购编号</option>
						<option value="gid" <?php echo $search_key == 'gid' ? 'selected="selected"' : '';?>>活动编号</option>
						<option value="buyer_uname" <?php echo $search_key == 'buyer_uname' ? 'selected="selected"' : '';?>>买家名称</option>
						<option value="seller_uname" <?php echo $search_key == 'seller_uname' ? 'selected="selected"' : '';?>>商家名称</option>
					</select>
					<input name="search_val" class="ui-form-text ui-form-textRed" value="<?php echo $search_val;?>" />
					<button type="submit" class="ui-form-btnSearch">搜 索</button>
				</form>
			</div>
			<div class="ui-box-body">
				<table class="ui-table">
				<thead>
					<tr>
						<th width="80">扣款编号</th>
						<th>买家</th>
						<th>商家</th>
						<th>活动编号</th>
						<th>抢购编号</th>
						<th>扣款金额</th>
						<th>扣除时间</th>
						<th>执行伙伴</th>
						<th>扣除状态</th>
						<th>扣除原因</th>
					</tr>
				</thead>
				<tbody>
					<?php if (is_array($list)){?>
					<?php foreach ($list as $k=>$v){?>
					<tr>
						<td><?php echo $v['id'];?></td>
						<td><?php echo $v['uname'];?></td>
						<td><?php echo $v['seller_uname'];?></td>
						<td><?php echo $v['gid'];?></td>
						<td><?php echo $v['oid'];?></td>
						<td><?php echo $v['adjust_rebate'];?>(元)</td>
						<td><?php echo date("Y-m-d H:i:s", $v['dateline']);?></td>
						<td>
								<?php if($v['admin_uid'] == 0):?>
									<span style="color: #ff0000;"><?php echo $v['admin_uname'];?></span>
								<?php else:?>
									<?php echo $v['admin_uname'];?>
								<?php endif;?>
						</td>
						<td><?php if($v['state']==1):?><p style="color:green">已扣除</p><?php else:?><p style="color:red">待扣除</p><?php endif;?></td>
						<td>
							<?php if($v['adjust_type']==2):?>
							<span style="color: #ff0000;">手机客户端</span>
							<?php else:?>
							<?php echo $v['reason'];?>
							<?php endif;?>
							<?php $v['adjust_type']==2 ? '' : $v['reason'];?>
						</td>
					</tr>
					<?php }?>
					<?php }?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="7" class="ui-paging"><?php echo $pager;?></th>
					</tr>
				</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>