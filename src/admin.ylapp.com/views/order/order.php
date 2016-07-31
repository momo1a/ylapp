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

<div class="ui-box ui-box2 userList">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<form rel="div#main-wrap" action="<?php echo site_url('order/all')?>">
					<select name="search_key">
						<option value="oid"  <?php if($search_key=='oid') echo 'selected="selected"';?> >抢购编号</option>
						<option value="no" <?php if($search_key=='no') echo 'selected="selected"';?>>订单编号</option>
                        <option value="gid" <?php if($search_key=='gid') echo 'selected="selected"';?>>活动编号</option>
                        <option value="title" <?php if($search_key=='title') echo 'selected="selected"';?>>活动标题</option>
					</select>
					<input name="search_val" class="ui-form-text ui-form-textRed" value="<?php echo $search_val; ?>" /> 
                <select name="type_key">
                        <option value="buyer" <?php if($type_key=='buyer') echo 'selected="selected"';?>>买家名称</option>
                        <option value="seller" <?php if($type_key=='seller') echo 'selected="selected"';?>>商家名称</option>
					</select>
					<input name="type_val" class="ui-form-text ui-form-textRed" value="<?php echo $type_val ?>" /> 
					<button type="submit" class="ui-form-btnSearch">搜 索</button>
				</form>
			</div>
			<div class="ui-box-body">
				<table class="ui-table">
				<thead>
					<tr>
						<th>抢购编号</th>
						<th width="20%">商品标题</th>
						<th>商家名称</th>
						<th>买家名称</th>
						<th>填写的订单号</th>
						<th>活动价/网购价</th>
						<th>返现金额</th>
                        <th>抢购状态</th>
                        <th>操作</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($rows as $k=>$v):
                                $goods_link=  create_fuzz_link($v['gid'], 32, $v['seller_uid']);
                        ?>
					<tr>
						<td><input type="checkbox" name='check_oid' id="check_oid" value="<?php echo $v['oid'];?>"/>&nbsp;<?php echo $v['oid'];?></td>
						<td><a href="<?php echo $goods_link; ?>" target="_blank"><span style="float:left; width:30%;"><img src="<?php echo $v['img']  ?>" width="50" /></span><span style="float:left; width:70%;"><font color="#0066CC"><?php echo $v['title'];?></font></span></a><br />活动编号:<?php echo $v['gid'] ; ?></td>
						<td><?php echo $v['seller_uname'];?></td>
						<td>
								<p><?php echo $v['buyer_uname'];?></p>
								<p style="<?php  echo 'color:' . user_stat_coror($v['buyer_user']['is_lock']) ?>"><?php echo user_stat_str($v['buyer_user']['is_lock'], $v['buyer_user']['lock_day']);?></p>
						</td>
                        <td>
                        	<p><?php if($v['state']>=3) echo $v['trade_no']; else echo '-';?></p>
                        	<?php if( Order_model::is_mobile_order($v['site_type'],$v['fill_site_type']) ){?>
                        	<p class="apptip">手机客户端</p>
                        	<?php }?>
                        </td>
                        <td><font color="green">￥<?php echo $v['cost_price'];?>/</font><br />￥<?php echo $v['price'];?></td>
						<td>
                        <div style="position:relative;"> ￥<?php echo $v['real_rebate'];
						 if($v['adjust_rebate']!=0):?>
                            <a class="simpletooltip" href="/appeal/show_adjust_tips/<?php echo $v['oid'];?>" type="tips">&nbsp;</a>
                            <?php endif; ?>
                             <span title="" style="display: block; color: green;"><?php if($v['search_reward']>0) echo '+￥'.$v['search_reward'];?></span>
                        </div>
                        </td>
						<td>
						    <?php
                    // 待填单号、已填单号、审核通过、需显示倒计时
                    if($v['state']==1){
                        echo '<p class="orderForm-statusTip">自动清除倒计时：<br /><em time="'.$v['auto_timeout_time'].'">'.$v['count_down_default'].'</em><p>';
                    }elseif($v['state']==3 || $v['state']==4){
                        echo '<p class="orderForm-statusTip">自动返现倒计时：<br /><em time="'.$v['auto_checkout_time'].'">'.$v['count_down_default'].'</em><p>';
                   }elseif($v['state']==6){
						$typeurl= $v['appeal_utype']==1 ? 'buyer' :'seller';
					?>	
					<a href="<?php echo site_url('appeal/index/'.$typeurl)?>?key=trade_no&type_id=<?php echo $v['type_id']; ?>&val=<?php echo $v['trade_no']; ?>" target="_blank"><?php echo $statelist[$v['state']]; ?></a>
				 <?php 		
					}else{
						echo $statelist[$v['state']];
						}
                    ?>
                        </td>
						<td class="ui-table-operate">
                        <?php  if($v['show_id']>0): ?>
                        <a target="_blank" href="<?php echo $this->config->item('domain_shikee_bbs').'yesvalue.php?mod=showshop&uid='.$v['buyer_uid'].'&showshopid='.$v['show_id'];  ?>">查看晒单</a><br/>
                        <?php elseif(in_array($v['state'],array(3,4,5,6,8))): ?>
                        <a data-oid="<?php echo $v['oid'];?>" height="150" width="520" type="form" callback="reload" href="<?php echo site_url('order/handle')?>">取消资格</a><br/>
                        <?php endif;?>
                        <a data-oid="<?php echo $v['oid'];?>"  height="220" width="500" type="dialog" href="<?php echo site_url('goods/order_flow')?>">抢购记录</a>
                        </td>
					</tr>
					<?php endforeach;?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="1" >
							<input type="checkbox" id="checkAll" name="checkAll"/>&nbsp;全选/取消
						</td>
						<td colspan="8" >
							<a onclick="batchCloseOrder(this)" callback="reload" class="ui-form-button ui-form-buttonBlue" href="javascript:;">批量取消资格</a>
							&nbsp;&nbsp;
							<a onclick="batchUserLock(this)" callback="reload" class="ui-form-button ui-form-buttonBlue" href="javascript:;">批量封号/屏蔽</a>
						</td>
					</tr>
					<tr>
						<td colspan="9" class="ui-paging"><?php echo $pager;?></td>
					</tr>
				</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<script language="javascript">

$(function(){
	$("#checkAll").click(function(){
		var $this = $(this);
		$("input[type='checkbox'][name='check_oid']").attr('checked', $this.is(':checked'));
	});
});

// 批量取消资格
function batchUserLock(obj){
	var $obj = $(obj);
	var oids = $("input[type='checkbox'][name='check_oid']:checked").map(function(){
		return $(this).val();
	}).get().join(",");
	
	if(oids === '') {
		alert('请至少选择一条记录');
		return false
	}
	var url = '<?php echo site_url('order/batch_lock_user?oids=')?>'+oids;
	url = encodeURI(url);
	$obj.attr('href', url);
	$obj.attr('type', 'form');
	return true;
}

// 批量取消资格
function batchCloseOrder(obj){
	var $obj = $(obj);
	var oids = $("input[type='checkbox'][name='check_oid']:checked").map(function(){
		return $(this).val();
	}).get().join(",");
	
	if(oids === '') {
		alert('请至少选择一条记录');
		return false
	}
	var url = '<?php echo site_url('order/handle?oid=')?>'+oids;
	url = encodeURI(url);
	$obj.attr('href', url);
	$obj.attr('type', 'form');
	return true;
}
</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>