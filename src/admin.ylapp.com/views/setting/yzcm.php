<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 remindTime">
	<div class="ui-box-head">
		<h2 class="ui-box-tit">一站成名参数设置</h2>
	</div>
	<div class="ui-box-body">
		<form type="ajax" action="<?php echo site_url('setting/yzcm'); ?>" method="post">
			<p><strong>一、上线要求：商品总价值≥
				<input class="ui-form-text ui-form-textRed"
					type="text"
					value="<?php echo $setting_guarantee_money;?>"
					style="width:80px;"
					name="guarantee_money"
					data-rule="^[0-9]+(\.[0-9]{1,2})?$|range(0.01,10000000)"
					msgname="mpg_guarantee_money"
					data-msg="阿拉伯数字，保留2位小数，0.01~10000000|阿拉伯数字，保留2位小数，0.01~10000000" />元</strong>
				<span style="color:gray;">（商品总价值=网购价*份数）</span>
				<span id="for_mpg_guarantee_money" class="error"></span>
				<input type="hidden" name="setting[mpg_guarantee_money][remark]" value="名品馆上线要求：商品总价值≥设定的值" />
				<input type="hidden" name="setting[mpg_guarantee_money][name]" value="商品总价值" />
			</p>
			<div class="remindTime-ft">
				<input type="submit" class="ui-form-button ui-form-buttonBlue" value="保存设置" />
				<input type="hidden" name="save" value="yes" />
			</div>
		</form>
	</div>
</div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>