<form rel="div#user_list_<?php echo $type_id;?>" id="div_user_list_<?php echo $type_id;?>" action="<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$uid);?>" method="get" class="from">
	<select name="search_type">
		<option value="oid"  <?php echo $search_type=='order' ? 'selected="selected"':''?>>抢购编号</option>
		<option value="trade_no"  <?php echo $search_type=='trade_no' ? 'selected="selected"':''?>>订单编号</option>
		<option value="gid"  <?php echo $search_type=='gid' ? 'selected="selected"':''?>>活动编号</option>
		<option value="title"  <?php echo $search_type=='title' ? 'selected="selected"':''?>>活动标题</option>
	</select>
	<input class="ui-form-text ui-form-textRed" name="search_value" value="<?php echo $search_value;?>" >
	<input class="ui-form-btnSearch" type="submit" value="搜索">
	<input type="hidden" name="type_id"  id="type_id" value="<?php echo $type_id?>"/>
	<input type="hidden" name="listonly"  value="yes"/>
	<input type="hidden" id="doexport_<?php echo $type_id?>" name="doexport"  value="no"/>
	<input type="button" class="ui-form-btnSearch doexport"   onclick="export_order()" value="导出" />
</form>
<table class="ui-table">
	<thead>
		<tr>
			<th>抢购编号</th>
				<th width="20%">活动信息</th>
				<th>商家</th>
				<th>买家</th>
				<th>填写订单编号</th>
				<th>活动价/网购价</th>
				<th>返现</th>
				<th>抢购状态</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php  $states = array('0' => '全部', '1' => '待填写订单号', '3' => '待审核订单号', '4' => '待返现', '5' => '订单号有误', '6' => '申诉中', '7' => '已关闭', '8' => '返现中', '9' => '已完成');?>
			<?php if(is_array($order)): foreach ($order as $k=>$v):
                        $goods_link = create_fuzz_link($v['gid'], 32, $v['seller_uid']);
            ?>
			<tr>
				<td><?php echo $v['oid']?></td>
				<td><a href="<?php echo $goods_link; ?>" target="_blank"><span style="float:left; width:30%;"><img src="<?php echo $v['img']  ?>" width="50" /></span><span style="float:left; width:70%;"><font color="#0066CC"><?php echo $v['title'];?></font></span></a><br />活动编号:<?php echo $v['gid'] ; ?></td>
				<td><?php echo $v['seller_uname']?></td>
				<td><?php echo $v['buyer_uname']?></td>
				<td><?php echo $v['trade_no'] ? $v['trade_no'] : '-';?>
				<?php if( Order_model::is_mobile_order($v['site_type'],$v['fill_site_type']) ){?>
	            <p class="apptip">手机客户端</p>
	            <?php }?>
				</td>
				<td><font color="green">￥<?php echo $v['cost_price'];?>/</font><br />￥<?php echo $v['price'];?></td>
				<td>
					<div style="position:relative;"> ￥<?php echo $v['real_rebate'];?><?php if( isset($v['search_reward']) && $v['search_reward']>0 ){?><font color="green"><br /> ￥<?php echo $v['search_reward'];?></font><?php }?>
						<?php if($v['adjust_rebate']!=0):?>
						<a class="simpletooltip pastelblue top" href="/appeal/show_adjust_tips/<?php echo $v['oid'];?>" type="tips">&nbsp;</a>
						<?php endif; ?>
                        </div>
				</td>
				<td><p><?php echo isset($states[$v['state']]) ? $states[$v['state']] : '未知状态'?></p>
				<?php 
 					if($v['state']==1){
                        echo '<p class="orderForm-statusTip">自动清除倒计时：<br /><em time="'.$v['auto_timeout_time'].'">'.$v['count_down_default'].'</em><p>';
                    }elseif($v['state']==3 || $v['state']==4){
                        echo '<p class="orderForm-statusTip">自动返现倒计时：<br /><em time="'.$v['auto_checkout_time'].'">'.$v['count_down_default'].'</em><p>';
                   }?>
				</td>
				<td class="ui-table-operate">
					<?php if(!in_array($v['state'],array(5,7,8,9)) ):?><p><a data-oid="<?php echo $v['oid']?>" height="150" width="520" type="form" href="<?php echo site_url('order/handle')?>" callback="reload">取消资格</a></p><?php endif;?>
					<?php if($v['show_id']>0) echo '<p><a target="_blank" href="',$domain_shikee_bbs, 'yesvalue.php?mod=showshop&uid=', $v['buyer_uid'], '&showshopid=', $v['show_id'], '">查看晒单</a></p>'; ?>
					<a data-oid="<?php echo $v['oid']?>" height="320" width="500" type="dialog" href="<?php echo site_url('goods/order_flow')?>">抢购记录</a>
				</td>
			
			</tr>
			<?php endforeach; endif;?>
		</tbody>
		<tfoot>
			<tr><td colspan="9"><div class="ui-paging"><?php echo $pager; ?></div></td></tr>
		</tfoot>
</table>
<script language="javascript">
function export_order(){
	$("#doexport_<?php echo $type_id?>").val("yes");
	var data = $("#div_user_list_<?php echo $type_id;?>").serialize();
	var url =  "<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$uid);?>?"+data;
	$("#doexport_<?php echo $type_id?>").val("no");
	window.open(url);
	$("#doexport").val("no");
}


//订单倒计时
$(function() {
	function count_down(sec) {
		if(sec<=0) return '-';
		var s = sec;
		var left_s = s % 60;
		var m = Math.floor(s / 60);
		var left_m = m % 60;
		var h = Math.floor(m / 60);
		var left_h = h % 24;
		var d = Math.floor(h / 24);

		var ret = [];
		d && ret.push('<span class="d">', d, '</span>天');
		left_h && ret.push('<span class="h">', left_h, '</span>时');
		left_m && ret.push('<span class="m">', left_m, '</span>分');
		left_s && ret.push('<span class="s">', left_s, '</span>秒');

		return ret.join('');
	}

	var now = <?php echo time()?> + 3;
	$('.orderForm-statusTip em').each(function() {
		this.sec = parseInt($(this).attr('time'), 10);
		this.innerHTML = count_down(this.sec - now);
	});
	setInterval(function() {++now;
		$('.orderForm-statusTip em').each(function() {
			this.innerHTML = count_down(this.sec - now);
		});
	}, 1000);
});
</script>
<style>
.from{margin-bottom: 15px;}
.doexport{float:right}
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
