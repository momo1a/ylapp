<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
	<div class="ui-box ui-box2" style="padding-bottom:0">
		<form id="msgsentform" type="ajax" action="<?php echo site_url('message/sent?mod=del&do=1')?>" method="post" beforesubmit="delMsgSentConfirm" callback="reload" style="margin-right:-1px;">
				<?php foreach ($msg_sent as $k=>$sent_item) {?>
				<input type="hidden" id="msg_<?php echo $sent_item['id'];?>" data-id="<?php echo $sent_item['id'];?>" name="id[]">
				<?php }?>
		</form>
		<table class="ui-table" cellspacing="0">
			<col span="1"  style="width:6em;" />
			<col span="1" />
			<col span="1" />
			<col span="1" style="width:10em;" />
			<thead>
				<tr class="ui-box-head">
					<th><label style="margin-right: 0"><input type="checkbox" id="checkAll"/>全选</label></th>
					<th>收件人</th>
					<th>标题</th>
					<th>发送时间</th>
				</tr>
			</thead>
			<tbody>
			<?php if(empty($msg_sent)){?>
				<tr><td colspan="4">没有找到任何记录!</td></tr>
			<?php }else{?>
				<?php foreach ($msg_sent as $k=>$sent_item) {?>
				<tr>
					<td class="sent_id">
						<input type="checkbox" value="<?php echo $sent_item['id'];?>">
						<span class="message-statusSucc">已发送</span>
					</td>
					<td><?php echo $sent_item['to_uname'];?></td>
					<td><a href="javascript:;" onclick="sent_view('<?php echo $sent_item['id'];?>');sent_highlight(this);"><?php echo $sent_item['title'];?></a></td>
					<td><?php echo date('m月d日 H:i:s', $sent_item['dateline']);?></td>
				</tr>
				<?php }?>
			<?php }?>
			</tbody>
			<tfoot>
				<tr>
					<td class="col1" colspan="4" style="padding:12px 15px;">
						<p class="floatL"><label><input type="checkbox"  id="checkAll2" /> 全选</label>
						<input class="ui-form-button ui-form-buttonBlue" type="button" value="删除" onclick="del_submit()"></p>
						<div class="ui-paging paging-center floatR">
							<?php if(trim($pageString) != ''){?>
								<?php echo $pageString;?>
								<script type="text/javascript">
									var sumpage = parseInt('<?php echo $sumpage;?>') || 0;
									var onclk = "var gto=parseInt($('#goto_page').val()) || 1;gto=gto>sumpage?sumpage:gto;load_sent_list('/message/sent?mod=list&p='+gto);";
									var goto_html = '<span class="paging-total"> 共 '+sumpage+' 页 </span>';
									goto_html += ' <span> 到第 <input onkeydown="press_enter(event);" type="text" class="ui-form-text ui-form-textRed" style="width:2em;text-align: center;" id="goto_page" value=""> 页 </span>';
									goto_html += ' <input class="ui-form-btnSearch" type="button" value="确定" onclick="'+onclk+'">';
									$('.paging').append(goto_html);
									function press_enter(event){
										var e = event?event:window.event;
										if(e.keyCode == 13){
											var gto=parseInt($('#goto_page').val()) || 1;
											gto=gto>sumpage?sumpage:gto;
											load_sent_list('/message/sent?mod=list&p='+gto);
										}
									}
								</script>
							<?php }?>
						</div>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
<script type="text/javascript">
$(function(){
	$('.paging-center .paging').find('a').each(function(){
		var href = $(this).attr('href');
		$(this).attr('href', 'javascript:;');
		$(this).on('click', function(){
			load_sent_list(href);
		});
	});
});
function sent_highlight(obj){
	$("#msgsentform table tbody").find("tr").removeAttr("style");
	$(obj).closest("tr").attr("style","background-color: #EEE;");
}
function sent_view(id, direction){
	var id = id || 0;
	var direction = direction || 0;
	$.post('/message/sent', {
		mod:'view',
		d:direction,
		id:id,
		inajax:1
	}, function(data){
		if(typeof data == 'object'){
			PopupTips(data.msg, 'error', 2000);
		}else{
			$('#sent_detail').html(data);
		}
	});
}
$ && $("#checkAll2").click(function(){
	$("#checkAll").click();
});
$ && $("#checkAll").click(function(){
	$(".sent_id input[type=checkbox]").prop("checked",$(this).prop("checked"));
	$("#checkAll2").prop("checked",$(this).prop("checked"));
});
$(".sent_id input[type=checkbox]").click(function(){
	var allLen = $(".sent_id input[type=checkbox]").length,
		trueLen = $(".sent_id input[type=checkbox]").filter(":checked").length,
		checkAll = $("#checkAll");
		checkAll2 = $("#checkAll2");
	if(allLen===trueLen){
		checkAll.prop("checked",true);
		checkAll2.prop("checked",true);
	}else{
		checkAll.prop("checked",false);
		checkAll2.prop("checked",false);
	}
});
function del_submit(){
	var undel_sentids = [],del_sentids = [];
	$('.sent_id :checkbox').each(function(){
		var ck = $(this).prop("checked");
		if(ck){
			del_sentids.push(this.value);
		}else{
			undel_sentids.push(this.value);
		}
	});
	for(var i in del_sentids){
		var targt = $('#msg_'+del_sentids[i]);
		targt.val(targt.data('id'));
	}
	for(var i in undel_sentids){
		var targt = $('#msg_'+undel_sentids[i]);
		targt.val('');
	}
	$('#msgsentform').submit();
}
function delMsgSentConfirm(){
	var sentids = [];
	$('.sent_id :checkbox:checked').each(function(){
		sentids.push(this.value);
	});
	if(sentids.length<=0){
		alert('您没有选择任何操作项');
		return false;
	}
	if(confirm('确定要删除当前所选的已发送的站内信吗？')){
		return true;
	}else{
		return false;
	}
}
</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>