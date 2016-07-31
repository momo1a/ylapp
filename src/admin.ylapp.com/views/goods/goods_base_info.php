<?php 
$goods_link=  create_fuzz_link($goods['gid'], $goods['state'], $goods['uid']);?>
<table class="ui-table">
	<tr>
		<td><a href="<?php echo $goods_link;?>" title="<?php echo $goods['title'];?>" target="_blank"><img src="<?php echo $goods['img'];?>" alt="商品展示图" /></a></td>
		<td><a href="<?php echo $goods_link;?>" title="<?php echo $goods['title'];?>" target="_blank"><?php echo $goods['title'];?></a></td>
		<td>总份数：<?php echo $goods['quantity'];?>份<br>剩余份数：<?php $left_num = $goods['quantity'] - $goods['join_num'];echo $left_num > 0 ? $left_num : 0;?>份</td>
		<td>抢购人数：<?php echo $goods['join_num'];?>人<br>下单人数：<?php echo $goods['fill_order_num'];?>人</td>
		<td class="ui-table-operate">
			已返现金额：<?php echo $goods['rebate_num'];?>人
		</td>
	</tr>
</table>