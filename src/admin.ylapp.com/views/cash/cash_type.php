<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
           <?php 
           /**
            * 显示现金券的使用条件
            */
           function show_condition( $v ){
           	$i = 1;
           	if( $v['not_limit'] ){
           		echo $i++.'.不需要使用条件';return;
           	}
           	if( $v['is_time_limit'] ){
           		echo $i++.'.抢购时间属于'.date('Y-m-d H:i:s',$v['time_limit_start_time']).'到'.date('Y-m-d H:i:s',$v['time_limit_end_time']).'<br/>';
           	}
           	if( $v['is_phone'] ){
           		echo $i++.'.需要使用客户端抢购<br/>';
           	}
           	if( $v['category_id'] ){
           		echo $i++.'.商品限制为'.$v['category_name'].'<br/>';
           	}
           	if( $v['sum_price'] !='0.00' ){
           		echo $i++.'.网购价总额满'.$v['sum_price'].'元（抢购状态“已完成”时统计）<br/>';
           	}
           	if( $v['sum_cost_price'] !='0.00' ){
           		echo $i++.'.活动价总额满'.$v['sum_cost_price'].'元（抢购状态“已完成”时统计）<br/>';
           	}
           	if( $v['sum_rebate'] !='0.00' ){
           		echo $i++.'.已返现总额满'.$v['sum_rebate'].'元（抢购状态“已完成”时统计）<br/>';
           	}
           }
           ?>
<div class="ui-box ui-box2 advertisement">
  <div class="ui-box-outer">
    <div class="ui-box-inner">
      <ul class="ui-tab-nav" id="tabs">
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/cash_send');?>">手动发放</a></li>
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/bath_send');?>">批量发放</a></li>
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/code_send');?>">兑换码发放</a></li>
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/detail_send');?>">发放记录</a></li>
         <li class="ui-tab-item ui-tab-itemCurrent"><a href="<?php echo site_url('cash/index/cash_type');?>">现金券类型</a></li>
         <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/cash_cdkey');?>">兑换码记录</a></li>
      </ul>
      <div class="ui-box ui-box2 advertisement-add">
      			<div class="ui-box-head">
				<form style="padding: 0px;" action="<?php echo site_url('cash/index/cash_type/'); ?>" method="get" rel="div#main-wrap">
					
					<select name="search_key">
						<option value="cash.cname" <?php if('cash.cname'==$this->input->get_post('search_key')):?>selected="selected"<?php endif;?>>现金券类型</option>
						<option value="cash.ctitle" <?php if('cash.ctitle'==$this->input->get_post('search_key')):?>selected="selected"<?php endif;?>>现金券标题</option>
					</select>
					<input class="ui-form-text ui-form-textRed" name="search_value" value="<?php echo $this->input->get_post('search_value');?>" >
					<input class="ui-form-btnSearch" type="button" onclick="btnSubmit();" value="搜索">
					<a style="margin: 6px 15px 0 0;float: right;" type="form" callback="reload"  href="<?php echo site_url('cash/add_cash_type/'); ?>">添加现金券类型</a>
				</form>
			    </div>
				<table class="ui-table">
					<thead>
						<tr>
							<th style="width:15%;">现金券类型</th>
							<th style="width:10%;">标题</th>
							<th style="width:10%;">面额</th>
							<th style="width:30%;">使用条件</th>
							<th style="width:15%;">有效期</th>
							<th style="width:20%;">操作</th>
						</tr>
					</thead>
					<tbody>
						<?php if (is_array($cash_info)):?>
						<?php foreach ($cash_info as $k=>$v):?>
						<tr>
							<td><?php echo $v['cname'];?></td>
							<td><?php echo $v['ctitle'];?></td>
							<td><?php echo $v['cprice'];?></td>
							<td><?php show_condition( $v );?></td>
							<td><?php echo date('Y/m/d',$v['valid_start_time']);?>~<?php echo date('Y/m/d',$v['valid_end_time']);?></td>
							<td class="ui-table-operate">
							<?php if( $v['valid_end_time'] > time() ){?>
							<a href="<?php echo site_url('cash/edit_bath_send');?>" callback="reload" data-cid="<?php echo $v['cid'];?>" type="form">编辑批量发放的条件</a>
							<br/>
							<?php }?>
							<a href="<?php echo site_url('cash/bath_send_detail');?>" callback="reload" data-cid="<?php echo $v['cid'];?>" type="form" title="批量发放的条件">查看发放条件</a>
							<br/>
							<?php if( $v['valid_end_time']+5*3600*24 < time() && $v['settled']==0 ){?>
							<a href="<?php echo site_url('cash/freezerecord');?>" callback="reload" data-cid="<?php echo $v['cid'];?>" type="form">结算</a>
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