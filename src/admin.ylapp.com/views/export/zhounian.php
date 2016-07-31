<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 syslog">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<form action="<?php echo site_url('export/zhounian');?>" target="exportifrm" method="post">
					<input class="ui-form-text ui-form-textGray ui-form-textDatetime" readonly  name="startTime" data-dateFmt="yyyy-MM-dd HH:mm:ss"> -
					<input class="ui-form-text ui-form-textGray ui-form-textDatetime" readonly  name="endTime" data-dateFmt="yyyy-MM-dd HH:mm:ss">
					<input type="hidden" name="doexport" value="yes" />
					<input class="ui-form-btnSearch" type="submit" value="导出" />
				</form>
				<iframe id="exportifrm" name="exportifrm" src="#" frameborder="0" width="0" height="0"></iframe>
			</div>
		</div>
	</div>
</div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>