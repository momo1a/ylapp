<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 advertisement">
  <div class="ui-box-outer">
    <div class="ui-box-inner">
      <ul class="ui-tab-nav" id="tabs">
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/cash_send');?>">手动发放</a></li>
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/bath_send');?>">批量发放</a></li>
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/code_send');?>">兑换码发放</a></li>
        <li class="ui-tab-item ui-tab-itemCurrent"><a href="<?php echo site_url('cash/index/detail_send');?>">发放记录</a></li>
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/cash_type');?>">现金券类型</a></li>
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/cash_cdkey');?>">兑换码记录</a></li>
      </ul>
      <div class="ui-box ui-box2 advertisement-add">
      			<div class="ui-box-head">
				<form style="padding: 0px;" action="<?php echo site_url('cash/index/detail_send/'); ?>" method="get" rel="div#main-wrap">
					
					<select name="search_key">
						<option value="cash.cname" <?php if('cash.cname'== $this->input->get_post('search_key')):?>selected="selected"<?php endif;?>>现金券类型</option>
					</select>
					<input class="ui-form-text ui-form-textRed" name="search_value" value="<?php echo $this->input->get_post('search_value');?>" >
					<input class="ui-form-btnSearch" type="button" onclick="btnSubmit();" value="搜索">
				</form>
			    </div>
				<table class="ui-table">
					<thead>
						<tr>
						    <th style="width:8%;">发放方式</th>
							<th style="width:15%;">现金券类型</th>
							<th style="width:20%;">发放份数</th>
							<th style="width:10%;">面额</th>
							<th style="width:10%;">总金额</th>
							<th style="width:10%;">发放状态</th>
							<th style="width:12%;">发放时间</th>
							<th style="width:15%;">操作</th>
						</tr>
					</thead>
					<tbody>
						<?php if (is_array($cash_data)):?>
						<?php foreach ($cash_data as $k=>$v):?>
						<tr>
						    <td><?php if($v['send_type']==1){echo '手动发放';}elseif($v['send_type']==2){echo '批量发放';}elseif($v['send_type']==3){echo '兑换码发放';}else{echo '未知';};?></td>
						    <td><?php echo $v['cname'];?></td>
						    <td><?php echo $v['quantity'];?>份 
						    
						    <?php if( in_array($v['state'],array(3,4) ) ){?>
						    (<a href="<?php echo site_url('cash/export_cash_send').'?pay_id='.$v['id'].'&cid='.$v['cid'].'&send_type='.$v['send_type'];?>">点击导出</a>)
						    <?php }?>
						    
						    </td>
						    <td><?php echo $v['cprice'];?></td>
						    <td><?php echo $v['money'];?></td>
						    <td><?php if($v['state']==1){echo '未付款';}elseif($v['state']==2){echo '付款中';}elseif($v['state']==3){echo '已付款';}elseif($v['state']==4){echo '已结算';}else{echo '未知';};?>
						    <td><?php echo date('Y/m/d H:i',$v['dateline']);?></td>
						    <td class="ui-table-operate">
						    <?php if($v['state']==1){?>
								<a <?php echo $v['valid_end_time'] < time() ? 'type="form"' : ''?> href="<?php echo site_url('cash/pay').'?id='.$v['id'];?>">去付款</a><br/>
							<?php }elseif($v['state']==2){?>
							    <a <?php echo $v['valid_end_time'] < time() ? 'type="form"' : ''?> href="<?php echo site_url('cash/pay').'?id='.$v['id'];?>">去付款</a><br/>
								<a href="<?php echo site_url('cash/cancel_pay');?>" callback="reload" data-id="<?php echo $v['id'];?>" type="form">撤销付款</a>
							<?php }else{?>
							   -
							<?php }?>
							</td>
							
						</tr>
						<?php endforeach;?>
						<?php endif;?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="13">
								<div class="ui-paging"><?php echo $pager;?></div></td>
						</tr>
					</tfoot>
				</table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function btnSubmit(){
	$('form').get(0).submit();
}
</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>