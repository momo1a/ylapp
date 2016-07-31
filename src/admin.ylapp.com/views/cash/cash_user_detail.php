<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<?php 
function show_state( $v ){
	if( $v['state']==1 ){
		echo '<td>未兑现</td>';
	}
	if( $v['state']==2 ){
		echo '<td style="color:#CC3300;">待打款</td>';
	}
	if( $v['state']==3 ){
		echo '<td style="color:#009966;">已兑换</td>';
	}
	if( $v['state']==4 ){
		echo '<td style="color:#FF6600;">已过期</td>';
	}
	if( $v['state']==5 ){
		echo '<td style="color:#FF3300;">已作废</td>';
	}
}
?>
<div class="ui-box ui-box2 userList">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<form action="<?php echo site_url('cash/detail/'.$segment); ?>" method="get" rel="div#main-wrap" id="from">
				    <input id="startTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly  value="<?php echo $this->input->get_post('startTime');?>" name="startTime" data-datefmt="yyyy-MM-dd HH:mm:ss" title="开始日期">
					<input id="endTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly value="<?php echo $this->input->get_post('endTime');?>"  name="endTime" data-datefmt="yyyy-MM-dd HH:mm:ss" title="截止日期">
				    
				    <select name="cash_user_state">
				        <option value="choose">现金券状态</option>
						<option value="1" <?php if('1'==$this->input->get_post('cash_user_state') ):?>selected="selected"<?php endif;?>>未兑现</option>
						<option value="2" <?php if('2'==$this->input->get_post('cash_user_state') ):?>selected="selected"<?php endif;?>>待打款</option>
						<option value="3" <?php if('3'==$this->input->get_post('cash_user_state') ):?>selected="selected"<?php endif;?>>已兑现</option>
						<option value="4" <?php if('4'==$this->input->get_post('cash_user_state') ):?>selected="selected"<?php endif;?>>已过期</option>
						<option value="5" <?php if('5'==$this->input->get_post('cash_user_state') ):?>selected="selected"<?php endif;?>>已作废</option>
					</select>
					
					<select name="search_key">
						<option value="cash_user.uname" <?php if('cash_user.uname'==$this->input->get_post('search_key')):?>selected="selected"<?php endif;?>>用户名称</option>
						<option value="cash.cname" <?php if('cash.cname'==$this->input->get_post('search_key')):?>selected="selected"<?php endif;?>>现金券类型</option>
						<option value="cash_cdkey.cdkey" <?php if('cash_cdkey.cdkey'==$this->input->get_post('search_key')):?>selected="selected"<?php endif;?>>兑换码</option>
					</select>
					<input class="ui-form-text ui-form-textRed" name="search_value" value="<?php echo $this->input->get_post('search_value');?>" >
					<input id="submit" class="ui-form-btnSearch" type="submit" value="搜索">
					<input id="export" class="ui-form-btnSearch" type="submit" style="margin-left:50px;" value="导出列表">
					
					<a id="pay_all" class="ui-form-btnSearch"  type="form"  style="margin-left:50px;" href="javascript:;"  >一键打款</a>
					
					<a style="margin: 6px 15px 0 0;float: right;" type="form" href="<?php echo site_url('cash/password');?>">设置操作码</a>
				</form>
			</div>
			<div class="ui-box-body">
			<?php if($this->input->get_post('search_key')!='cash_cdkey.cdkey'){?>
			<div style="background-color: #eee;margin-bottom: 9px;padding:15px;">
				<table style="width:100%">
					<tr>
						<td>总数量：<em><?php echo $items_count;?></em></td>
						<td>未兑换：<em class="ui-table-statusR"><?php echo intval($cash_count_state['state1']);?></em></td>
						<td>已兑现：<em class="ui-table-statusG" style="margin-right:20px;"><?php echo intval($cash_count_state['state3']);?></em></td>
						<td>待打款：<em class="ui-table-statusG" style="margin-right:20px;"><?php echo intval($cash_count_state['state2']);?></em></td>
						<td>已过期：<em style="margin-right:40px;"><?php echo intval($cash_count_state['state4']);?></em>已经作废：<em><?php echo intval($cash_count_state['state5']);?></em></td>
					</tr>
					<tr>
						<td>总面额：<em><?php echo sprintf("%01.2f",$cash_count_money['state1']+$cash_count_money['state2']+$cash_count_money['state3']+$cash_count_money['state4']+$cash_count_money['state5']);?> 元</em></td>
						<td>冻结金额（未兑现）：<em class="ui-table-statusR"><?php echo $cash_count_money['state1']==''?'0.00':sprintf("%01.2f", $cash_count_money['state1']);?> 元</em></td>
						<td>已兑现（已兑现）：<em class="ui-table-statusG"><?php echo $cash_count_money['state3']==''?'0.00':sprintf("%01.2f", $cash_count_money['state3']);?> 元</em></td>
						<td>待打款（待打款）：<em class="ui-table-statusG"><?php echo $cash_count_money['state2']==''?'0.00':sprintf("%01.2f", $cash_count_money['state2']);?> 元</em></td>
						<td>应解除冻结（已过期+已作废）：<em><?php echo $cash_count_money['state4']+$cash_count_money['state5']==''?'0.00':sprintf("%01.2f", $cash_count_money['state4']+$cash_count_money['state5']);?> 元</em></td>
					</tr>
				</table>
			</div>
			<?php }?>
				<table class="ui-table">
					<thead>
						<tr>
							<th width="5%"><input class="checkAll" type="checkbox" name="ids[]">全选</th>
							<th width="10%">劵编号</th>
							<th width="20%">现金券类型</th>
							<th width="15%">兑换码</th>
							<th width="15%">所属用户</th>
							<th width="10%">面额</th>
							<th width="10%">现金券状态</th>
							<th width="15%">操作</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if(is_array($user_cash)):
							foreach ($user_cash as $k=>$v):
						?>
						<tr>
							<td><input type="checkbox" name="ids[]" value="<?php echo $v['id']; ?>" /></td>
							<td><?php echo $v['cid']; ?></td>
							<td><a type="dialog" width="500px" height="300px" title="现金券类型-<?php echo $v['cname'];?>" href="<?php echo site_url('cash/show_cash').'/'.$v['cid']?>"><?php echo $v['cname']; ?></a></td>
							<td><?php echo $v['cdkey']; ?></td>
							<td><?php echo $v['uname']; ?></td>
							<td><?php echo $v['cprice']; ?></td>
							<?php echo show_state($v); ?>
							<td class="ui-table-operate">
							<?php if(in_array($v['state'], array('1','2'))){?>
								<a href="<?php echo site_url('cash/destroy');?>"  callback="reload" data-ids="<?php echo $v['id'];?>" width="200" type="form">作废</a><br/>
							<?php }?>
							<?php if(in_array($v['state'], array('2'))){?>
								<a href="<?php echo site_url('cash/pay_user');?>" callback="reload" data-ids="<?php echo $v['id'];?>" width="200" type="form">打款</a><br/>
							<?php }?>
								<a href="<?php echo site_url('cash/log')?>" data-cid="<?php echo $v['cid']; ?>" data-uid="<?php echo $v['uid']; ?>" data-pay_id="<?php echo $v['pay_id']; ?>" height="420" width="600" type="dialog">操作记录</a>
							</td>
						</tr>
						<?php endforeach; endif;?>
					</tbody>
					<tfoot>
						<tr>
						<th width="5%"><input class="checkAll" type="checkbox" name="ids[]">全选</th>
							<td colspan="9">
								<a onclick="pay_user(this)" callback="reload" class="ui-form-button ui-form-buttonBlue" href="javascript:;">打款</a>
								<a onclick="destroy(this)" callback="reload" class="ui-form-button ui-form-buttonBlue" href="javascript:;">作废</a>
							</td>
						</tr>
					</tfoot>
				</table>
				<div class="ui-paging-center" style="margin-top:20px;">
					<div class="ui-paging"><?php echo $pager;?></div>
				</div>
			</div>
			<!-- /userList-body -->
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$("input[type='checkbox'].checkAll").click(function(){
		var $this = $(this);
		$("input[type='checkbox'][name='"+$this.attr('name')+"']").attr('checked', $this.is(':checked'));
	});
});
//批量作废
function destroy(obj){
	var $obj = $(obj);
	var ids = $("input[type='checkbox'][class!='checkAll'][name^='ids']:checked").map(function(){
		return $(this).val();
	}).get().join(",");
	
	if(ids === '') {
		alert('请至少选择一条记录');
		return false
	}
	var url = '<?php echo site_url('cash/destroy');?>'+'?ids='+ids;
	url = encodeURI(url);
	$obj.attr('href', url);
	$obj.attr('type', 'form');
	return true;
}
//批量打款
function pay_user(obj){
	var $obj = $(obj);
	var ids = $("input[type='checkbox'][class!='checkAll'][name^='ids']:checked").map(function(){
		return $(this).val();
	}).get().join(",");

	if(ids === '') {
		alert('请至少选择一条记录');
		return false
	}
	var url = '<?php echo site_url('cash/pay_user');?>'+'?ids='+ids;
	url = encodeURI(url);
	$obj.attr('href', url);
	$obj.attr('type', 'form');
	return true;
}
//导出用户
$("#export").click(function(){
	$("form:eq(0)").removeAttr('rel'); 
	$("form:eq(0)").attr("action", "<?php echo site_url('cash/export_user_detail/')?>").submit();
});
//搜索用户
$("#submit").click(function(){
	$("form:eq(0)").attr('rel="div#main-wrap"'); 
	$("form:eq(0)").attr("action", "<?php echo site_url('cash/detail/')?>").submit();
});

//一键打款
$("#pay_all").click(function(){
	if( ! confirm('确定要一键给搜索条件下“待打款”的现金券打款吗？')){
		return false;
	}
	$obj = $('#pay_all');
	$from = $('#from'); 
	 
	var url = '<?php echo site_url('cash/pay_all?')?>' + $from.serialize();
	
	$obj.attr('href', url);
	$obj.attr('type', 'form');
	 
	return true;
});


</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>