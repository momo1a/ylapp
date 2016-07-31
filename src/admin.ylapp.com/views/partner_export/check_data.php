<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 remindData">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<div class="ui-box-tit">审核数据统计</div>
			</div>
            	<div class="ui-box-head">
				<form class="clearfix" rel="div#main-wrap" action="<?php echo site_url($this->router->class.'/'.$this->router->method);?>" method="get">
					<input id="startTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly name="startTime" data-datefmt="yyyy-MM-dd HH:mm:ss" value="<?php echo  date('Y-m-d H:i:s',$startTime); ?>">
					<input id="endTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly name="endTime" data-datefmt="yyyy-MM-dd HH:mm:ss" value="<?php echo  date('Y-m-d H:i:s',$endTime); ?>">
					<select class="ui-select" name="search_key">
						<option value="uname">用户名称</option>
						<option value="uid">用户ID</option>
					</select>
					<input class="ui-form-text ui-form-textRed" type="text" name="search_val" value="<?php echo $search_val;?> " />
					<button type="submit" class="ui-form-btnSearch">搜 索</button>
					<input class="ui-form-button ui-form-buttonBlue" style="float:right; margin: 5px 15px 0 0;" type="button" name="export" value="导出"  onclick="dosubmit.call(this)"/>
					<input type="hidden" name="listonly" value="yes" />
				</form>
			</div>
			<div class="ui-box-body">
				<table class="ui-table">
					<thead>
						<tr>
							<th>用户ID</th>
							<th>管理员</th>
							<th>审核次数</th>
							<th>审核活动个数</th>
                            <th>已审核-已上线</th>
                            <th>已审核-待上线</th>
						</tr>
					</thead>
					<tbody> 
                  <?php   foreach ($checknum as $k=>$v){  ?>
						<tr>
							<td><?php echo $v['uid'] ?></td>
							<td><?php echo $v['uname'] ?></td>
                            <td><?php echo $v['sumnum'] ?></td>
							<td><?php echo $v['chenknum'] ?></td>
							<td><?php echo $v['onlinenum'] ?></td>
							<td><?php echo $v['notonlinenum'] ?></td>
						</tr>
                    <?php } ?>
						<tr>
							<td>合计</td>
                            <td>全部管理员</td>
                            <td><?php echo $countsum; ?></td>
							<td><?php echo $countchenk; ?></td>
							<td><?php echo $countonline; ?></td>
							<td><?php echo $counnotonline; ?></td>
						</tr>
					</tbody>
				</table>
			</div>	
		</div>
	</div>
</div>
<script language="javascript">
function dosubmit(){
	 var parent = $(this).parent();
	 if(!parent.find(".ui-form-textDatetime").eq(0).val() || !parent.find(".ui-form-textDatetime").eq(1).val()){
		art.dialog({
			icon: "error",
			title: "温馨提示",
			content:"由于数据太多，必须选择起止时间！"
		});
		return;
	}
	 location.href='<?php echo site_url('partner_export/check_data').'?export=true&'; ?>' +$(this).parent().serialize();
}
</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>