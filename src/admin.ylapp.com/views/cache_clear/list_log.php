<div class="clearfix" style="margin-bottom:8px;" id="option_list_div">
	<!-- 搜索 -->

	<!-- /搜索 -->
</div>
<!--自增编号、类别名称、地址、是否启用、备注、添加时间-->
<table id="list_tb" class="ui-table">
	<thead>
	<tr>
		<th>编号</th>
		<th>类型名称</th>
		<th>缓存地址</th>
		<th>操作时间</th>
		<th>管理员</th>
		<th>操作结果</th>
	</tr>
	</thead>
	<tbody>
	<?php if($contents):foreach ($contents as $c):?>
		<tr>
			<!--<td><input type="checkbox" name="ids[]" value="<?php echo $c['id']?>" /></td>-->
			<td><?php echo $c['id']?></td>
			<td><?php echo $c['cat_name']?></td>
			<td><?php echo $c['url']?></td>
			<td><?php echo date('Y-m-d H:i:s',$c['actiontime']);?></td>
			<td><?php echo $c['username']?></td>
			<td><?php echo $c['result']?></td>
		</tr>
	<?php endforeach;?>
	<?php else:?>
		<tr>
			<td colspan="6" style="text-align:center">
					不存在日志
				</div>
			</td>
		</tr>
	</tbody>
	<?php endif;?>
	<?php if (isset($list_count)&&$contents):?>
		<tfoot>
		<tr>
			<td colspan="6" style="text-align:left;padding:12px;">
				<div class="ui-paging floatR">
					<?php echo $pager;?>
				</div>
			</td>
		</tr>
		</tfoot>
	<?php endif;?>

</table>
