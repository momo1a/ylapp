
<form rel="div#appeal_records_<?php echo $type_id;?>" action="<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$utype_str);?>" method="get">
			<select id="key" name="key">
				<option selected="selected" value="oid">抢购编号</option>
				<option value="id">申诉编号</option>
				<option value="trade_no">填写的订单号</option>
				<option value="gid">活动编号</option>
				<option value="title">活动标题</option>
				<option value="seller">商家名称</option>
				<option value="buyer">买家名称</option>
			</select>
			<input class="ui-form-text ui-form-textRed" id="val" name="val" type="text" value="" />
			<input id="SaveSearchCondition" class="ui-form-btnSearch" type="submit" value="搜 索" />
			<input type="hidden" name="type_id" value="<?php echo $type_id;?>" />
			<input type="hidden" name="listonly" value="yes"/>
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
				<th>返现金额</th>
				<th style="width: 10em;">填写的订单号</th>
				<th style="width: 4em;">淘宝客</th>
				<th>进度状态</th>
				<th style="width: 6em;">申诉时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<?php if(is_array($list) && count($list)):?>
		<tbody>
			<?php foreach ($list as $k=>$v):
                        $goods_link=  create_fuzz_link($v['gid'], $v['goods_state'], $v['seller_uid']);
                ?>
			<tr>
				<td><?php echo $v['id'];?></td>
			<td><?php echo $v['oid'];?><br /><span style="color:#0066CC;">已申诉：<?php echo $v['appeal_count'];?>次</span></td>
			<td ><a href="<?php echo $goods_link;?>" target="_blank" style="color:#0066CC;"><?php echo $v['title'];?></a><br />活动编号：<?php echo $v['gid'];?></td>
			<td><?php echo $v['seller_uname'];?></td>
			<td><?php echo $v['buyer_uname'];?></td>
			<td><?php echo '<em style="color:#009900; font-weight:bold";>￥'.$v['cost_price'].'</em><br />￥'.$v['price'];?>元
				<?php if($v['price_type']==Goods_model::PRICE_TYPE_MOBILE):?>
				<p style="color:#0099CC;">手机专享：<br/>￥<?php echo $v['mobile_price']; ?></p>
				<?php endif;?>
			</td>
			<td>￥<?php echo $v['real_rebate'];?><?php if( isset($v['search_reward']) && $v['search_reward']>0 ){?><br />￥<em style="color:#009900;"><?php echo $v['search_reward'];?></em><?php }?>
			<?php if( $v['adjust_rebate'] !=0 ):?>
				<a class="simpletooltip pastelblue top" href="/appeal/show_adjust_tips/<?php echo $v['oid'];?>" type="tips">&nbsp;</a>
			<?php endif;?>
			</td>
			<td><?php echo $v['trade_no'];?>
				<?php if(in_array($v['site_type'], array(2,3,4)) AND in_array($v['fill_site_type'], array(2,3,4))):?>
				<p class="apptip">手机客户端</p>
				<?php endif;?>
			</td>
			<td><?php echo $v['is_taoke'] ? '<em style="color:red;">是</em>' : '否';?></td>
			<td><?php if($v['state']==='4'): echo '<em style="color:#009900">已处理</em>'; endif;?></td>
			<td><?php echo date("Y-m-d H:i:s", $v['dateline']);?></td>
				<td class="ui-table-operate">
					<p>
						<a href="<?php echo site_url('appeal/handle')?>" type="form" callback="$('tr#appeal_row_<?php echo $v['id'];?>').remove();" width="670" height="250" data-id="<?php echo $v['id'];?>">查看申诉</a>
					</p>
					<a href="<?php echo site_url('goods/order_flow');?>" type="dialog" width="500" height="260" data-oid="<?php echo $v['oid'];?>">抢购记录</a>
				</td>
			</tr>
			<?php endforeach;?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="12" class="ui-paging"><?php echo $pager;?></td>
			</tr>
		</tfoot>
		<?php else:?>
		<tbody>
			<tr><td colspan="12">无此申诉信息</td></tr>
		</tbody>
		<tfoot>
		</tfoot>
		<?php endif;?>
	</table>

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
	<script>
	$(".ui-table-operate a[data-type=dialog]").click(function(){
		var $this = $(this);
		$.get($this.attr('href'), $this.data(), function(rs){
			if(!AjaxFilter(rs)){
				return;
			}
			var title = $this.attr('title')||$this.text();
			var width = $this.attr("width"), height = $this.attr("height");
			artDialog({
				id: 'dialog',
				title: title,
				content: '<div>'+rs+'</div>',
				width: width,
				height: height,
				padding: 0,
				background: '#000',
				opacity: 0.35,
				lock: true,
				cancel:true,
				cancelVal:"关闭"
			}).show();
		});
		return false;
	});
	</script>