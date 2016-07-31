<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<form id="tpllistform" type="ajax" action="<?php echo site_url('message/template?mod=del&do=1')?>" method="post" beforesubmit="delMsgTplConfirm" callback="re_load">
	<table class="ui-table" style="margin-bottom:-1px;">
		<col span="1" style="width:3em;" />
		<col span="1" />
		<col span="1" style="width:8em;" />
		<col span="1" style="width:9em;" />
		<?php if( ! empty($msg_template)){?>
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th>标题</th>
					<th>日期</th>
					<th>操作</th>
				</tr>
			</thead>
			<?php foreach ($msg_template as $tpl){?>
			<tr>
				<td class="col1"><input type="checkbox" name="id[]" value="<?php echo $tpl['id'];?>"></td>
				<td class="col1"><a href="javascript:useMsgTpl('<?php echo $tpl['id'];?>', true);">标题:<?php echo $tpl['title'];?></a></td>
				<td class="col1" title="<?php echo date('Y-m-d H:i:s', $tpl['dateline']);?>"><?php echo date('m月d日', $tpl['dateline']);?></td>
				<td class="col1" id="optd">
					<input type="hidden" id="tpl_title_<?php echo $tpl['id'];?>" value="<?php echo $tpl['title'];?>">
					<textarea style="display:none;" id="tpl_content_<?php echo $tpl['id'];?>"><?php echo $tpl['content'];?></textarea>
					<a href="javascript:useMsgTpl('<?php echo $tpl['id'];?>', true);" class="ui-operate-button ui-operate-buttonEdit editBtn" >编辑</a>
					<a href="<?php echo site_url('message/template?mod=del');?>" type="confirm" title="确定要删除当前站内信模板吗？" callback="re_load" class="ui-operate-button ui-operate-buttonDel" data-id[]="<?php echo $tpl['id'];?>" data-do="1">删除</a>
				</td>
			</tr>
			<?php }?>
		<?php }else{?>
		<tr>
			<td class="col1" colspan="4">没有任何站内信模板</td>
		</tr>
		<?php }?>
	<tr><td colspan="4" style="padding: 12px">
		<p class="floatL"><label><input id="checkAll" type="checkbox"> 全选</label>
		<input type="submit" class="ui-form-button ui-form-buttonBlue" value="删除" title="确定要删除当前站内信模板吗？" ></p>
		<div class="ui-paging floatR">
			<span><?php echo $page.'/'.$sumpage;?></span>
			<?php if($sumpage > 1):?>
				<?php if($page <= 1){?>
					<a href="<?php echo site_url('message/template?mod=index&p='.($page+1));?>">下一页</a>
				<?php }elseif($page > 1 && $page < $sumpage){?>
					<a href="<?php echo site_url('message/template?mod=index&p='.($page-1));?>">上一页</a>
					<a href="<?php echo site_url('message/template?mod=index&p='.($page+1));?>">下一页</a>
				<?php }else{?>
					<a href="<?php echo site_url('message/template?mod=index&p='.($page-1));?>">上一页</a>
				<?php }?>
			<?php endif;?>
		</div>
	</td></tr>
	</table>
</form>
<script type="text/javascript">
$ && $("#checkAll").click(function(){
	$(".ui-table input[type=checkbox]").prop("checked",$(this).prop("checked"));
});
$(function(){
	$('.ui-paging').find('a').each(function(){
		var href = $(this).attr('href');
		$(this).attr('href', 'javascript:void(0);');
		$(this).on('click', function(){
			load_tpl_list(href);
		});
	});
});
$(".ui-table input[type=checkbox]").click(function(){
	var allLen = $(".ui-table input[type=checkbox]").length,
		trueLen = $(".ui-table input[type=checkbox]").filter(":checked").length,
		checkAll = $("#checkAll");
	if(allLen===trueLen){
		checkAll.prop("checked",true);
	}else{
		checkAll.prop("checked",false);
	}
});
function delMsgTplConfirm(){
	var tplids = [];
	$('#tpllistform').find('tbody :checkbox:checked').each(function(){
		tplids.push(this.value);
	});	
	if(tplids.length<=0){
		alert('您没有选择任何操作项');
		return false;
	}
	if(confirm('确定要删除当前所选的站内信模板吗？')){
		return true;
	}else{
		return false;
	}
}
</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>