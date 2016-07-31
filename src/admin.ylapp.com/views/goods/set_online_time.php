<div style="padding: 20px;">
<table>
	<tr>
		<th width="40%">活动编号</th>
		<th>原上线时间</th>
	</tr>
	<?php foreach ($goods_list as $k=>$v):?>
	<tr>
		<td><?php echo $v['gid']?> </td>
		<td><?php echo date("Y-m-d H:i:s", $v['expect_online_time'])?></td>
	</tr>
	<?php endforeach;?>
</table><br />
<form id="editForm" class="window_form" action="<?php echo site_url($this->router->class.'/'.$this->router->method)?>" method="post">
	<input style="display:none;" data-datefmt="yyyy-MM-dd HH:mm:ss" name="startTime" value="<?php echo date("Y-m-d H:i:s"); ?>" readonly class="ui-form-text ui-form-textGray ui-form-textDatetime" />
	<label>新上线时间：<input data-datefmt="yyyy-MM-dd HH:mm:ss" name="endTime" value="<?php echo date("H")<10 ? date("Y-m-d 10:00:00") :  date("Y-m-d 10:00:00", strtotime('1 days')); ?>" readonly class="ui-form-text ui-form-textGray ui-form-textDatetime" /></label>
	<input type="hidden" name="dosave" value="yes" />
	<input type="hidden" name="gids" value="<?php echo implode(',', $gids);?>" />
</form>
</div>
