<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 remindData">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<div class="ui-box-tit">申诉数据统计</div>
			</div>
            	<div class="ui-box-head">
				<form class="clearfix" rel="div#main-wrap" action="<?php echo site_url($this->router->class.'/'.$this->router->method);?>" method="get">
					<input id="startTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly name="startTime" data-datefmt="yyyy-MM-dd HH:mm:ss" value="<?php echo  date('Y-m-d H:i:s',$startTime); ?>">
					<input id="endTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly name="endTime" data-datefmt="yyyy-MM-dd HH:mm:ss" value="<?php echo  date('Y-m-d H:i:s',$endTime); ?>">
					<select class="ui-select" name="search_key">
						<option value="uname" <?php if($search_key=='uname')echo 'selected="selected"' ?> >用户名称</option>
						<option value="uid" <?php if($search_key=='uid')echo 'selected="selected"' ?>>用户ID</option>
					</select>
					<input class="ui-form-text ui-form-textRed" type="text" name="search_val" value="<?php echo $search_val;?> " />
					<button type="submit" class="ui-form-btnSearch">搜 索</button>
					<input class="ui-form-button ui-form-buttonBlue" style="float:right; margin: 5px 15px 0 0;" type="button" name="export" value="导出"  onclick="location.href='<?php echo site_url('partner_export/export_appeal_data').'?'; ?>' +$(this).parent().serialize()"/>
					<input type="hidden" name="listonly" value="yes" />
				</form>
			</div>
			<div class="ui-box-body">
				<table class="ui-table">
					<thead>
						<tr>
							<th>用户ID</th>
							<th>管理员</th>
							<th>已处理买家申诉</th>
							<th>已处理商家申诉</th>
                            <th>处理申诉总量</th>
						</tr>
					</thead>
					<tbody>
                  <?php   foreach ($appeal_data as $k=>$v){  ?>
						<tr>
							<td><?php echo $v['admin_uid'] ?></td>
							<td><?php echo $v['admin_uname'] ?></td>
                            <td><?php echo $v['buyer'] ?></td>
							<td><?php echo $v['seller'] ?></td>
							<td><?php echo $v['num'] ?></td>
						</tr>
                    <?php } ?>
						<tr>
							<td>合计</td>
                            <td>全部管理员</td>
							<td><?php echo $total_buyer; ?></td>
							<td><?php echo $total_seller;  ?></td>
							<td><?php echo $total_num; ?></td>
						</tr>
                        <tr><td colspan="5"><div class="ui-paging"><?php echo $pager; ?></div></td></tr>
					</tbody>
				</table>
			</div>	
		</div>
	</div>
</div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>