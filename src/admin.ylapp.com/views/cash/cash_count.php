<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 userList">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
	      	<div class="ui-box-head">
			<form style="padding: 0px;" action="<?php echo site_url('cash/count/'); ?>" method="get" rel="div#main-wrap">
				
				<select name="search_key">
					<option value="cash.cname" <?php if('cash.cname'==$this->input->get_post('search_key') ):?>selected="selected"<?php endif;?>>现金券类型</option>
				</select>
				<input class="ui-form-text ui-form-textRed" name="search_value" value="<?php echo $this->input->get_post('search_value');?>" >
				<input class="ui-form-btnSearch" type="submit" value="搜索">
			</form>
		    </div>
			<div class="ui-box-body">
				<table class="ui-table">
					<thead>
						<tr>
							<th width="10%">现金券类型</th>
							<th width="10%">总价值（元）</th>
							<th width="8%">面额（元）</th>
							<th width="8%">总数量</th>
							<th width="8%">未激活</th>
							<th width="8%">未兑现</th>
							<th width="8%">待打款</th>
							<th width="8%">已兑换</th>
							<th width="8%">已过期</th>
							<th width="8%">已作废</th>
							<th width="8%">已结算数量</th>
							<th width="8%">已结算总额</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$all_money = $all_quantity = $left = $all_left = $all_state1 = $all_state2 = $all_state3 = $all_state4 = $all_state5
						=$all_fre_quantity=$all_fre_money=0;
						if(is_array($cash_count)):
							foreach ($cash_count as $k=>$v):
						?>
						<tr>
							<td><?php echo $v['cname']; ?></td>
							<td><?php echo $v['money']; $all_money += $v['money']; ?></td>
							<td><?php echo $v['cprice']; ?></td>
							<td><?php echo $v['quantity']; $all_quantity +=$v['quantity']?></td>
							<td><?php $left = $v['quantity']-$v['state1']-$v['state2']-$v['state3']-$v['state4']-$v['state5'];echo $left; $all_left +=$left; ?></td>
							<td><?php echo $v['state1']; $all_state1 +=$v['state1'] ?></td>
							<td><?php echo $v['state2']; $all_state2 +=$v['state2']?></td>
							<td><?php echo $v['state3']; $all_state3 +=$v['state3']?></td>
							<td><?php echo $v['state4']; $all_state4 +=$v['state4']?></td>
							<td><?php echo $v['state5']; $all_state5 +=$v['state5']?></td>
							
							<td><?php echo $v['fre_quantity']; $all_fre_quantity +=$v['fre_quantity'];?></td>
							<td><?php echo $v['fre_money']; $all_fre_money +=$v['fre_money']; ?></td>
						</tr>
						<?php endforeach; endif;?>
						
						<tr>
							<td>总数合计</td>
							<td><?php echo sprintf("%01.2f", $all_money); ?></td>
							<td>--</td>
							<td><?php echo $all_quantity; ?></td>
							<td><?php echo $all_left; ?></td>
							<td><?php echo $all_state1; ?></td>
							<td><?php echo $all_state2; ?></td>
							<td><?php echo $all_state3; ?></td>
							<td><?php echo $all_state4; ?></td>
							<td><?php echo $all_state5; ?></td>
							
							<td><?php echo $all_fre_quantity; ?></td>
							<td><?php echo sprintf("%01.2f", $all_fre_money); ?></td>
						</tr>
					</tbody>
				</table>
				<div class="ui-paging-center" style="margin-top:20px;">
					<div class="ui-paging"><?php echo $pager;?></div>
				</div>
			</div>
			<!-- /userList-body -->
		</div>
	</div>
</div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>