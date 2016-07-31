<div id="option_list_div">
<table  class="ui-table">
	<col span="1"/>
	<col style="width:60%" />
	<col span="1" />
	<?php if( ! empty($logs)){?>
		<thead>
			<tr>
				<th>操作用户</th>
				<th>操作内容</th>
				<th>操作时间</th>
			</tr>
		</thead>
		<tbody>
				<?php foreach ($logs as $k=>$log){?>
				<tr>
					<td>
						<?php echo $log['uname'];?>
					</td>
					<td>
						<?php echo $log['content'];?>
					</td>
					<td>
						<?php echo date('Y-m-d H:i:s', $log['dateline']);?>
					</td>
				</tr>
				<?php }?>
		</tbody>
	<?php }else{?>
		<tbody>
			<tr>
				<td></td>
				<td>没有找到任何记录</td>
				<td></td>
			</tr>
		</tbody>
	<?php }?>
	<tfoot>
		<tr>
			<td colspan="3">
				<div class="ui-paging" id="option_page">
				<?php if($pageString != ''){?>
					<?php echo $pageString;?>
				<?php }?>
				</div>
			</td>
		</tr>
	</tfoot>
</table>
</div>