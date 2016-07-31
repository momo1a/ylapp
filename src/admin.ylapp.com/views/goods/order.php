<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<style>
	.simpletooltip{
		display: inline-block;
		*display: inline;
		*zoom: 1;
		width: 15px;
		height: 15px;
		background-image: url(<?php echo $this->config->item('domain_static')?>images/admin/question.png);
		vertical-align: middle;
		cursor:pointer;
	}
	.apptip{
		background-color: #f1273a;
		color: #fff;
		width: 65px;
		margin: 5px auto 0;
	}
</style>
<?php change_to_minify("/javascript/common/jquery/simpleToolTip/style-simpletooltip-min.css"); ?>
<div class="ui-box ui-box2 userList">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<span>当前位置：<a href="<?php echo site_url('goods/deduct_money');?>">活动管理</a> &gt; 
					<a href="<?php echo site_url('goods/index/all');?>">所有活动</a> &gt; 
					<a href="<?php echo site_url('goods/index/all?goods_type=-1&type=0&search_key=gid&search_value='.$goods['gid']);?>" title="<?php echo $goods['title'];?>"><?php echo cutstr($goods['title'], 20);?></a> &gt;  进入活动
				</span>
			</div>
			<div class="ui-box-body">
				<?php $this->load->view('goods/goods_base_info');?>
				<form rel="div#main-wrap" action="<?php echo site_url('goods/order/'.$goods['gid'])?>">
					<select name="search_key">
						<option value="oid"  <?php if($search_key=='oid') echo 'selected="selected"';?> >抢购编号</option>
						<option value="trade_no" <?php if($search_key=='trade_no') echo 'selected="selected"';?>>订单编号</option>
                        <option value="buyer_uname" <?php if($search_key=='buyer_uname') echo 'selected="selected"';?>>买家名称</option>
					</select>
					<input name="search_val" class="ui-form-text ui-form-textRed" value="<?php echo $search_val; ?>" />
					<button type="submit" class="ui-form-btnSearch">搜 索</button>
				</form>
				
                    <?php 
                    // 判断是否为搜索下单，有追加奖励金则为搜索下单
                     if( $goods['type'] == Goods_model::TYPE_SEARCH_BUY ) :
                    ?>
				<table class="ui-table">
					<thead>
						<tr>
							<th style="width: 14%;">抢购编号</th>
							<th style="width: 14%;">买家名称</th>
							<th style="width: 14%;">活动价/网购价</th>
							<th style="width: 10%;">返现金额</th>
                            <th style="width: 10%;">搜索奖励金</th>
							<th style="width: 18%;">填写的订单号</th>
							<th style="width: 10%;">抢购状态</th>
							<th style="width: 10%;">操作</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($orders as $k=>$v):?>
						<tr>
							<td><?php echo $v['oid'];?></td>
							<td><?php echo $v['buyer_uname'];?></td>
							<td>￥<?php echo $v['cost_price'];?>/￥<?php echo $v['price'];?></td>
							<td>￥<?php echo $v['real_rebate'];?><?php if($v['adjust_rebate'] != 0){?> <a class="simpletooltip pastelblue top" href="/appeal/show_adjust_tips/<?php echo $v['oid'];?>" type="tips"> &nbsp; </a><?php }?></td>
                            <td>￥<?php echo $v['search_reward'];?></td>
                            <td><?php echo $v['trade_no'];?><p class="apptip">手机客户端</p></td>
							<td><?php echo $order_util->get_status($v['state'], array(4=>'订单号正确'));?></td>
							<td class="ui-table-operate">
								<a href="<?php echo site_url('goods/order_flow')?>" type="dialog" width="500" height="220" data-oid="<?php echo $v['oid'];?>" title="查看抢购记录">抢购记录</a><br />
								<?php echo $order_util->get_action($v);?>
							</td>
						</tr>
					<?php endforeach;?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="6" class="ui-paging"><?php echo $pager?></th>
						</tr>
					</tfoot>
				</table>
          
                    <?php else :?>
          
                    <table class="ui-table">
					<thead>
						<tr>
							<th style="width: 14%;">抢购编号</th>
							<th style="width: 14%;">买家名称</th>
							<th style="width: 22%;">活动价/网购价</th>
							<th style="width: 10%;">返现金额</th>
							<th style="width: 20%;">填写的订单号</th>
							<th style="width: 10%;">抢购状态</th>
							<th style="width: 10%;">操作</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($orders as $k=>$v):?>
						<tr>
							<td><?php echo $v['oid'];?></td>
							<td><?php echo $v['buyer_uname'];?></td>
							<td>￥<?php echo $v['cost_price'];?>/￥<?php echo $v['price'];?></td>
							<td>￥<?php echo $v['real_rebate'];?><?php if($v['adjust_rebate'] != 0){?> <a class="simpletooltip pastelblue top" href="/appeal/show_adjust_tips/<?php echo $v['oid'];?>" type="tips"> &nbsp; </a><?php }?></td>
							<td><?php echo $v['trade_no'];?></td>
							<td><?php echo $order_util->get_status($v['state'], array(4=>'订单号正确'));?></td>
							<td class="ui-table-operate">
								<a href="<?php echo site_url('goods/order_flow')?>" type="dialog" width="500" height="220" data-oid="<?php echo $v['oid'];?>" title="查看抢购记录">抢购记录</a><br />
								<?php echo $order_util->get_action($v);?>
							</td>
						</tr>
					<?php endforeach;?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="7" class="ui-paging"><?php echo $pager?></th>
						</tr>
					</tfoot>
				</table>
                     <?php endif;?>
          
			</div>
		</div>
	</div>
</div>

<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>