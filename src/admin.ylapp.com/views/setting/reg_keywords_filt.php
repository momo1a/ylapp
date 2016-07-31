<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<br/>
<!-- 用户名关键字过滤编辑表单 -->
<div class="ui-box">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<h2 class="ui-box-tit"> 用户名关键字过滤<span>(以"逗号"隔开输入多个关键字)</span></h2>
			</div>
			<form class="onlineTime" type="ajax" action="<?php echo site_url('setting/reg_keywords_filt'); ?>" method="post">
				<table cellspacing="0" width="100%">
					<tbody>
              		<tr>
						<td>
							<textarea id="filt_keywords" style="width:99%;height:400px;padding:4px;" name="filt_keywords" data-rule="required" data-msg="请输入过滤关键字"><?php echo isset($filt_keywords)?$filt_keywords:'';?></textarea>
						</td>
					</tr>
					</tbody>
				</table>
				<div class="onlineTime-ft">
					<input type="submit" class="ui-form-button ui-form-buttonBlue" value="保存设置" />
					<input type="hidden" name="save" value="yes" />
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End 用户名关键字过滤编辑表单 -->
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>