<?php 
// 判断是否为搜索下单
if ($goods['type'] == Goods_model::TYPE_SEARCH_BUY) :
?>
<table cellpadding="3" cellspacing="1" class="ui-table">
	<thead>
		<tr>
			<th>商品编号</th>
			<th>期号</th>
			<th>发布|追加时间</th>
			<th>发布|追加份数</th>
			<th>存入担保金</th>
			<th>存入损耗费</th>
			<th>存入搜索奖励金</th>
			<th>状态</th>
		</tr>
	</thead>
	<tbody>
		<?php $i=1; foreach ($logs as $k=>$v):?>
		<tr>
			<td><?php echo $v['gid'];?></td>
			<td><?php echo $i;?></td>
			<td><?php echo date("Y-m-d H:i:s", $v['dateline']);?></td>
			<td><?php echo $v['add_num'];?></td>
			<td><?php echo $v['add_guaranty'];?></td>
			<td><?php echo $v['add_fee'];?></td>
                <td><?php echo $v['add_search_reward'];?></td>
			<td><?php
				$state_str = '';
				switch ($v['state']){
					case 1:
						$state_str .= '没有上线';
						break;
					case 2:
						$state_str .= '准备上线';
						$state_str .= $v['online_time'] ? '<br />'.date("Y-m-d", $v['online_time']).'<br />'.date("H:i:s", $v['online_time']) : '';
						break;
					case 3:
						$state_str .= '已经上线';
						$state_str .= $v['online_time'] ? '<br />'.date("Y-m-d", $v['online_time']).'<br />'.date("H:i:s", $v['online_time']) : '';
						break;
				} 
				echo $state_str;
			?></td>
		</tr>
		<?php $i+=1; endforeach;?>
	</tbody>
</table>
<?php elseif ($goods['type'] == Goods_model::TYPE_YZCM) :?>
<table cellpadding="3" cellspacing="1" class="ui-table">
	<thead>
		<tr>
			<th>商品编号</th>
			<th>序号</th>
			<th>发布|追加时间</th>
			<th>发布|追加份数</th>
			<th>存入担保金</th>
			<th>存入损耗费</th>
			<th>状态</th>
		</tr>
	</thead>
	<tbody>
		<?php $i=1; foreach ($logs as $log):?>
		<?php $count = isset($batch[$log['pid']]) ? count($batch[$log['pid']]) : 0;?>
		<?php $rowspan[$log['pid']] = isset($rowspan[$log['pid']]) && $rowspan[$log['pid']] === true ? true : false;?>		
		<tr>
			<?php if ($count > 1):?>
			<?php if ($rowspan[$log['pid']] === false):?>
			<td<?php echo $count > 1 ? (' rowspan="'.$count.'"') : '';?>><?php echo $goods['gid'];?></td>
			<td<?php echo $count > 1 ? (' rowspan="'.$count.'"') : '';?>><?php echo $i;?></td>
			<td<?php echo $count > 1 ? (' rowspan="'.$count.'"') : '';?>><?php echo date("Y-m-d H:i:s", $log['dateline']);?></td>
			<?php $i += 1;$rowspan[$log['pid']] = true;?>
			<?php endif;?>
			<?php else:?>
			<td><?php echo $goods['gid'];?></td>
			<td><?php echo $i;?></td>
			<td><?php echo date("Y-m-d H:i:s", $log['dateline']);?></td>
			<?php $i += 1;endif;?>
			<td><?php echo $log['add_num'];?></td>
			<td><?php echo $log['add_guaranty'];?></td>
			<td><?php echo $log['add_fee'];?></td>
			<td><?php
				$state_str = '';
				switch ($log['state']){
					case 0:
						$state_str .= '未付款';
						break;
					case 1:
						$state_str .= '已付款';
						break;
					case 2:
						$state_str .= '准备上线';
						$state_str .= $log['online_time'] ? '<br />'.date("Y-m-d", $log['online_time']).'<br />'.date("H:i:s", $log['online_time']) : '';
						break;
					case 3:
						$state_str .= '已经上线';
						$state_str .= $log['online_time'] ? '<br />'.date("Y-m-d", $log['online_time']).'<br />'.date("H:i:s", $log['online_time']) : '';
						break;
				} 
				echo $state_str;
			?></td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
<?php else :?>
<table cellpadding="3" cellspacing="1" class="ui-table">
	<thead>
		<tr>
			<th>商品编号</th>
			<th>期号</th>
			<th>发布|追加时间</th>
			<th>发布|追加份数</th>
			<th>存入担保金</th>
			<th>存入损耗费</th>
			<th>状态</th>
		</tr>
	</thead>
	<tbody>
		<?php $i=1; foreach ($logs as $k=>$v):?>
		<tr>
			<td><?php echo $v['gid'];?></td>
			<td><?php echo $i;?></td>
			<td><?php echo date("Y-m-d H:i:s", $v['dateline']);?></td>
			<td><?php echo $v['add_num'];?></td>
			<td><?php echo $v['add_guaranty'];?></td>
			<td><?php echo $v['add_fee'];?></td>
			<td><?php
				$state_str = '';
				switch ($v['state']){
					case 1:
						$state_str .= '没有上线';
						break;
					case 2:
						$state_str .= '准备上线';
						$state_str .= $v['online_time'] ? '<br />'.date("Y-m-d", $v['online_time']).'<br />'.date("H:i:s", $v['online_time']) : '';
						break;
					case 3:
						$state_str .= '已经上线';
						$state_str .= $v['online_time'] ? '<br />'.date("Y-m-d", $v['online_time']).'<br />'.date("H:i:s", $v['online_time']) : '';
						break;
				} 
				echo $state_str;
			?></td>
		</tr>
		<?php $i+=1; endforeach;?>
	</tbody>
</table>
<?php endif;?>