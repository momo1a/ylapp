<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2">
<div class="ui-box-head">
	<a href="<?php echo site_url('message/send?resend=1&id='.$msg_sent['id'])?>" class="ui-form-button ui-form-buttonBlue" type="button" target="_blank">转发</a>
	<a href="<?php echo site_url('message/sent?mod=del')?>" type="confirm" title="确定要删除所选的站内信吗？" callback="reload" class="ui-form-button ui-form-buttonBlue" data-id[]="<?php echo $msg_sent['id'];?>" data-do="1">删除</a>
	<a style="margin-right: 15px;display: inline;" class="floatR" href="javascript:sent_view('<?php echo $msg_sent['id'];?>', -1);">上一封</a>
</div>
<style>
	.msg-content{margin-top:10px;padding:5px;border: 1px solid #ccc;height: 320px;overflow: auto;}
</style>
<div id="msg_view" style="padding:15px;">
	<table class="concurrent-edit" width="100%">
		<colgroup>
			<col span="1" style="width:120px;"></col>
		</colgroup>
		<tbody>
			<tr style="font-weight: bold;">
				<td class="col1">标题:</td>
				<td><?php echo $msg_sent['title'];?></td>
			</tr>
			<tr>
				<td class="col1">时间:</td>
				<td><?php echo date("Y年m月d日 H:i:s",$msg_sent['dateline']);?></td>
			</tr>
			<tr>
				<td class="col1">收件人:</td>
				<td style="word-wrap:break-word;word-break:break-all;"><?php echo $msg_sent['to_uname'];?></td>
			</tr>
			<?php if ($msg_sent['limit_lastlogintime'] > 0):?>
			<tr>
				<td class="col1">用户最后登录时间<br />(筛选条件):</td>
				<td><?php echo '>='.date("Y年m月d日 H:i:s",$msg_sent['limit_lastlogintime']);?></td>
			</tr>
			<?php endif;?>
			<?php if ($msg_sent['startline'] > 0 || $msg_sent['endline'] > 0):?>
			<tr>
				<td class="col1">定时发送:</td>
				<td><?php echo $msg_sent['startline'] > 0 ? date("Y年m月d日 H:i:s",$msg_sent['startline']) : '-';?>
					~
					<?php echo $msg_sent['endline'] > 0 ? date("Y年m月d日 H:i:s",$msg_sent['endline']) : '-';?>
				</td>
			</tr>
			<?php endif;?>
		</tbody>
	</table>
	<div class="msg-content">
		<?php echo $msg_sent['content'];?>
	</div>
	<p class="clearfix" style="margin-top:15px"><a style="margin-right: 15px;display: inline;" class="floatR" href="javascript:sent_view('<?php echo $msg_sent['id'];?>', 1);">下一封</a></p>
	
</div>
</div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>