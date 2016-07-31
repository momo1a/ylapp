<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>

<div class="ui-box ui-box2 advertisement">
  <div class="ui-box-outer">
    <div class="ui-box-inner">
      <ul class="ui-tab-nav" id="tabs">
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/cash_send');?>">手动发放</a></li>
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/bath_send');?>">批量发放</a></li>
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/code_send');?>">兑换码发放</a></li>
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/detail_send');?>">发放记录</a></li>
         <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/cash_type');?>">现金券类型</a></li>
         <li class="ui-tab-item ui-tab-itemCurrent"><a href="<?php echo site_url('cash/index/cash_cdkey');?>">兑换码记录</a></li>
      </ul>
      <div class="ui-box ui-box2 advertisement-add">
      			<div class="ui-box-head">
				<form style="padding: 0px;" action="<?php echo site_url('cash/index/cash_cdkey/'); ?>" method="get" rel="div#main-wrap">
					
					<select name="search_key">
						<option value="cash.cname" <?php if('cash.cname'==$this->input->get_post('search_key')):?>selected="selected"<?php endif;?>>现金券类型</option>
						<option value="cash_cdkey.cdkey" <?php if('cash_cdkey.cdkey'==$this->input->get_post('search_key')):?>selected="selected"<?php endif;?>>兑换码</option>
					</select>
					<input class="ui-form-text ui-form-textRed" name="search_value" value="<?php echo $this->input->get_post('search_value');?>" >
					<input id="submit" class="ui-form-btnSearch" type="submit" value="搜索">
					<!--<input id="export" class="ui-form-btnSearch" type="submit" style="margin-left:50px;" value="导出兑换码">-->
				</form>
			    </div>
				<table class="ui-table">
					<thead>
						<tr>
						    <th style="width:5%;">现金券ID</th>
							<th style="width:15%;">现金券类型</th>
							<th style="width:10%;">面额</th>
							<th style="width:20%;">兑换码</th>
							<th style="width:10%;">所属UID</th>
							<th style="width:20%;">用户名</th>
							<th style="width:20%;">使用时间</th>
						</tr>
					</thead>
					<tbody>
						<?php if (is_array($cash_info)):?>
						<?php foreach ($cash_info as $k=>$v):?>
						<tr>
						    <td><?php echo $v['cid'];?></td>
							<td><?php echo $v['cname'];?></td>
							<td><?php echo $v['cprice'];?></td>
							<td><?php echo $v['cdkey'];?></td>
							<td><?php echo $v['uid']?$v['uid']:'无';?></td>
							<td><?php echo $v['uname']?$v['uname']:'无';?></td>
							<td><?php echo $v['dateline']?date('Y-m-d H:i:s',$v['dateline']):'';?></td>
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
//导出兑换码
$("#export").click(function(){
	$("form:eq(0)").removeAttr('rel'); 
	$("form:eq(0)").attr("action", "<?php echo site_url('cash/export_cdkey/')?>").submit();
});
//搜索现金券类型
$("#submit").click(function(){
	$("form:eq(0)").attr('rel="div#main-wrap"'); 
	$("form:eq(0)").attr("action", "<?php echo site_url('cash/index/cash_cdkey/')?>").submit();
});
</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>