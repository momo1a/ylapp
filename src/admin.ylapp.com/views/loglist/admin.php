<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 syslog"><div class="ui-box-outer"><div class="ui-box-inner"> 

<div class="ui-box-head syslog-head">
	<form rel="div#main-wrap" action="<?php echo site_url($this->router->class.'/'.$this->router->method);?>" method="get">
			<input class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly value="<?php echo $this->input->get_post('startTime');?>" data-dateFmt='yyyy-MM-dd HH:mm:ss' name="startTime">-<input  class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly value="<?php echo $this->input->get_post('endTime');?>" data-dateFmt='yyyy-MM-dd HH:mm:ss' name="endTime">
			<span>关键字:</span>
			<input class="ui-form-text ui-form-textRed" type="text" name="key" value="<?php echo $this->input->get_post('key');?>" />
			<input class="ui-form-btnSearch" type="submit" value="搜 索" />
	</form>
</div>

<div class="ui-box-body">
	<table class="ui-table">
		<thead>
			<tr>
				<th>记录时间</th>
				<th>日志内容</th>
			</tr>
		</thead>
		<tbody>
			<?php if (is_array($list)):?>
			<?php foreach ($list as $k=>$v):?>
			<tr>
				<td><?php echo date("Y年m月d日 H时i分", $v['dateline']);?></td>
				<td><?php echo $v['uname'].'('.$v['uid'].')'.$v['content'];?></td>
			</tr>
			<?php endforeach;?>
			<?php endif;?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" class="ui-paging"><?php echo $pager;?></td>
			</tr>
		</tfoot>
	</table>
</div>

</div></div></div>

<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>