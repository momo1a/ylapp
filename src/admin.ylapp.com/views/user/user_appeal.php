
<form id="form_user_list_<?php echo $type_id;?>" rel="div#user_list_<?php echo $type_id;?>" action="<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$uid);?>" method="get" class="from">
	<select id="key" name="key">
		<option <?php if($key=='oid') echo 'selected="selected"'?> value="oid">抢购编号</option>
		<option <?php if($key=='id') echo 'selected="selected"'?> value="id">申诉编号</option>
		<option <?php if($key=='title') echo 'selected="selected"'?> value="title">活动标题</option>
		<option <?php if($key=='gid') echo 'selected="selected"'?> value="gid">活动编号</option>
		<option <?php if($key=='seller') echo 'selected="selected"'?> value="seller">商家名称</option>
		<option <?php if($key=='buyer') echo 'selected="selected"'?> value="buyer">买家名称</option>
		<option <?php if($key=='trade_no') echo 'selected="selected"'?> value="trade_no">订单号</option>
	</select>
	<input class="ui-form-text ui-form-textRed" id="val" name="val" type="text" value="<?php echo $val;?>" />
	<input id="SaveSearchCondition" class="ui-form-btnSearch" type="submit" value="搜 索" />
	<input type="hidden" name="type_id" value="<?php echo $type_id;?>" />
	<input type="hidden" name="listonly" value="yes"/>
	<input type="hidden" id="doexport_<?php echo $type_id?>" name="doexport"  value="no"/>
	<input type="button" class="ui-form-btnSearch doexport"   onclick="export_<?php echo $type_id?>()" value="导出" />
	</form>
	<table class="ui-table">
		<thead>
			<tr>
				<th>申诉编号</th>
				<th>抢购编号</th>
				<th style="width: 12em;">活动标题</th>
				<th>商家名称</th>
				<th>买家名称</th>
				<th>活动价/网购价</th>
				<th>返现</th>
				<th style="width: 10em;">订单号</th>
				<th style="width: 4em;">淘宝客</th>
				<th>进度状态</th>
				<th style="width: 6em;">申诉时间</th>
				<th>操作</th>
			</tr>
	</thead>
	<tbody>
		<?php  
			//当前页面url，供刷新使用
			$this_url =  $this->router->uri->uri_string().'?'.http_build_query($_GET);
			$this_url = site_url($this_url);
		?>
		<?php if(is_array($list)): foreach ($list as $k=>$v):
                    $goods_link=  create_fuzz_link($v['gid'], $v['goods_state'], $v['seller_uid']);
        ?>
		<tr id="appeal_row_<?php echo $v['id'];?>">
			<td><?php echo $v['id'];?></td>
			<td><?php echo $v['oid'];?></td>
			<td ><a href="<?php echo $goods_link;?>" target="_blank" style="color:#0066CC;"><?php echo $v['title'];?></a><br />活动编号：<?php echo $v['gid'];?></td>
			<td><?php echo $v['seller_uname'];?></td>
			<td><?php echo $v['buyer_uname'];?></td>
			<td><?php echo '<em style="color:#009900; font-weight:bold";>￥'.$v['cost_price'].'</em><br />￥'.$v['price'];?>元
			<?php if($v['price_type']==Goods_model::PRICE_TYPE_MOBILE){?>
				<p style="color: #0099cc">手机专享：<br/>￥<?php echo $v['mobile_price'];?>元</p>
			<?php }?>
			</td>
			<td>￥<?php echo $v['real_rebate'];?>
			<?php if( $v['adjust_rebate'] !=0 ){?>
			<a class="simpletooltip pastelblue top" href="/appeal/show_adjust_tips/<?php echo $v['oid'];?>" type="tips">&nbsp;</a>
			<?php }?>
			</td>
			<td><?php echo $v['trade_no'];?>
			<?php if( Order_model::is_mobile_order($v['site_type'],$v['fill_site_type']) ){?>
            <p class="apptip">手机客户端</p>
            <?php }?>
			</td>
			<td><?php echo $v['is_taoke'] ? '<em style="color:red;">是</em>' : '否';?></td>
			<td><?php 	if($v['state']==1){
									echo '<em style="color:#FF6600">待处理</em><br />(等待回应)'; 
								}elseif($v['state']==2){
									 echo '<em style="color:#FF6600">待处理</em><br />(已回应)'; 
								}elseif($v['state']==3){
									echo '<em style="color:#FF6600">待处理</em><br />(无需回应)'; 
								}elseif($v['state']==4){
									 echo '<em style="color:#009900">申诉关闭</em>'; 
								}elseif($v['state']==5){
									echo '<em style="color:#FF6600">处理中</em>'; 
								}elseif($v['state']==6){
									echo '<em style="color:#009900">已撤销</em>'; 
								}?>
			</td>
			<td><?php echo date("Y-m-d H:i:s", $v['dateline']);?></td>
			<td class="ui-table-operate">
				<?php if (in_array($v['state'],array(1,2,3)) ):
				
				?>
				<p>
					<a href="<?php echo site_url('appeal/handle?handle=1')?>" type="form" width="520" height="250" data-id="<?php echo $v['id'];?>" callback="load('<?php echo $this_url?>','div#user_list_<?php echo $type_id;?>')">处理申诉</a>
				</p>
				<?php endif;?>
				<p>
					<a href="<?php echo site_url('goods/order_flow');?>" type="dialog" width="500" height="260" data-oid="<?php echo $v['oid'];?>">抢购记录</a>
				</p>
			</td>
		</tr>
		<?php endforeach; endif;?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="12" class="ui-paging"><?php echo $pager;?></td>
			</tr>
		</tfoot>
	</table>
<script>
function export_<?php echo $type_id?>(){
	$("#doexport_<?php echo $type_id?>").val("yes");
	var data = $("#form_user_list_<?php echo $type_id;?>").serialize();
	var url =  "<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$uid);?>?"+data;
	$("#doexport_<?php echo $type_id?>").val("no");
	window.open(url);
}
function reload(){
	$('#div_user_list_<?php echo $type_id;?>').submit();
}

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