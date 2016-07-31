<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 remindTime">
	<div class="ui-box-head">
		<h2 class="ui-box-tit">名品馆参数设置</h2>
	</div>
	<div class="ui-box-body">
		<form type="ajax" action="<?php echo site_url('setting/mpg'); ?>" method="post">
			<p><strong>一、类目网购价限制</strong></p>
			<table cellspacing="0">
				<tbody>
				<?php foreach ($setting_category as $key=>$set):?>
					<tr>
						<td><?php echo $key+1,'、',$set['name'];?>：</td>
						<td>单笔网购价≥</td>
						<td>
							<input class="ui-form-text ui-form-textRed"
								type="text"
								value="<?php echo $set['setting'];?>"
								style="width:80px;"
								name="setting[<?php echo MPG_CATE_SETTING_PIX.$set['id'];?>][value]"
								msgname="<?php echo MPG_CATE_SETTING_PIX.$set['id'];?>"
								data-rule="^[0-9]+(\.[0-9]{1,2})?$|range(0.01,10000000)"
								data-msg="阿拉伯数字，保留2位小数，0.01~10000000|阿拉伯数字，保留2位小数，0.01~10000000" />元
							<span id="for_<?php echo MPG_CATE_SETTING_PIX.$set['id'];?>" class="error"></span>
							<input type="hidden" name="setting[<?php echo MPG_CATE_SETTING_PIX.$set['id'];?>][remark]"
								value="名品馆发布此分类[<?php echo $set['name']?>]的商品时,单笔网购价必须≥这个数值" />
							<input type="hidden" name="setting[<?php echo MPG_CATE_SETTING_PIX.$set['id'];?>][name]" value="<?php echo $set['name']?>" />
						</td></tr>
				<?php endforeach;?>
				</tbody>
			</table>
			<p><strong>二、上线要求：商品总价值≥
				<input class="ui-form-text ui-form-textRed"
					type="text"
					value="<?php echo $setting_guarantee_money;?>"
					style="width:80px;"
					name="setting[mpg_guarantee_money][value]"
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