<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 remindData">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<div class="ui-box-tit">开团提醒数据</div>
			</div>
			<div class="ui-box-body">
				<table class="ui-table">
					<thead>
						<tr>
							<th>申请提醒类型</th>
							<th>总次数</th>
							<th>已提醒</th>
							<th>待提醒</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>开团提醒</td>
							<td><?php echo intval($system_stat['goods_online_remind_yes'] + $system_stat['goods_online_remind_no']);?></td>
							<td style="color: #1BB974;"><?php echo intval($system_stat['goods_online_remind_yes']);?></td>
							<td style="color: #c00;"><?php echo intval($system_stat['goods_online_remind_no']);?></td>
						</tr>
						<tr>
							<td>追加提醒</td>
							<td><?php echo intval($system_stat['goods_addition_remind_yes'] + $system_stat['goods_addition_remind_no']);?></td>
							<td style="color: #1BB974;"><?php echo intval($system_stat['goods_addition_remind_yes']);?></td>
							<td style="color: #c00;"><?php echo intval($system_stat['goods_addition_remind_no']);?></td>
						</tr>
					</tbody>
				</table>
			</div>	
			<div class="ui-box-head">
				<form class="clearfix" rel="div#remind_list" action="<?php echo site_url($this->router->class.'/'.$this->router->method);?>" method="get">
					<input id="startTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly name="startTime">
					<input id="endTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly name="endTime">
					<select class="ui-select" name="search_key">
						<option value="uname">会员名</option>
						<option value="email">邮箱</option>
						<option value="mobile">手机</option>
						<option value="gid">活动编号</option>
					</select>
					<input class="ui-form-text ui-form-textRed" type="text" name="search_val" value="" />
					<button type="submit" class="ui-form-btnSearch">搜 索</button>
					<input class="ui-form-button ui-form-buttonBlue" style="float:right; margin: 5px 15px 0 0;" type="button" name="export" value="导出"  onclick="dosubmit.call(this)"/>
					<input type="hidden" name="listonly" value="yes" />
				</form>
			</div>
			<div class="ui-box-body" id="remind_list">
				<?php $this->load->view('goods/remind_list');?>
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
	  location.href='<?php echo site_url('export/goods_remind').'?'; ?>' +parent.serialize();
}
</script>

<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>