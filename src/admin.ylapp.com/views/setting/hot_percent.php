<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>

<div class="ui-box">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<form type="ajax" class="hot-percent-form" action="<?php echo site_url('setting/hot_percent')?>" method="post">
				<p>销量达到<input class="ui-form-text ui-form-textRed" type="text" value="<?php echo $sellWell; ?>" id="sellWell" name="sellWell" data-rule="number|range(1,100)" data-msg="只能输入数字|范围只能为1-100之间" />%&nbsp;&nbsp;进入近期热卖栏目。</p>
				<div class="hot-percent-form-ft"><input class="ui-form-button ui-form-buttonBlue" type="submit" value="保存设置"/></div>
			
				<input type="hidden" name="sellWell_remark" value="销量达到_%进入近期热卖栏目" />
				<input type="hidden" name="save" value="yes" />
			</form>
		</div>
	</div>
</div><!-- /ui-box -->

<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>