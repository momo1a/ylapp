<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 concurrent"><div class="ui-box-outer"><div class="ui-box-inner">
	<div class="ui-box-head concurrent-head">站内群发</div>
	<div class="ui-box-body clearfix">
		<div style="width: 49%;float:left;" class="ui-box ui-box2">
			<form style="margin:15px;" id="msgpostform" type="ajax" action="<?php echo site_url('message/send')?>" method="post" beforesubmit="sendConfirm" callback="reset">
				<label style="margin:0"><input type="checkbox" id="check_level" name="height_level" value="1">&nbsp;高级模式</label>
				<table class="concurrent-edit">
					<tbody>
						<tr id="type_tr" style="display: none;">
							<td class="col1">用户类型:</td>
							<td>
								<label><input type="radio" name="utype" value="buyer" <?php if(isset($to_type) && $to_type==1)echo 'checked="checked"';?> >&nbsp;买家</label>
								<label><input type="radio" name="utype" value="seller" <?php if(isset($to_type) && $to_type==2)echo 'checked="checked"';?>>&nbsp;商家</label>
								<label><input type="radio" name="utype" value="all" <?php if(isset($to_type) && $to_type==3)echo 'checked="checked"';?>>&nbsp;全部</label>
							</td>
						</tr>
						<tr id="tousers_tr">
							<td class="col1">收件人:</td>
							<td>
								<input class="ui-form-text ui-form-textRed" name="tousers" value="<?php echo isset($to_uname)?$to_uname:'';?>" />
							</td>
						</tr>
						<tr id="priority_tr">
							<td class="col1" style="vertical-align: text-top;">优先级:</td>
							<td>
								<label><input type="radio" name="priority" value="1">1(最高)</label>
								<label><input type="radio" name="priority" value="2">2</label>
								<label><input type="radio" name="priority" value="3" checked="checked">3(正常)</label>
								<label><input type="radio" name="priority" value="4">4</label>
								<label><input type="radio" name="priority" value="5">5(最低)</label>
								<br />（发送优先级，越高越优先发送）
							</td>
						</tr>
						<tr id="shaixuan_tr" style="display:none;">
							<td class="col1" style="vertical-align: text-top;">用户最后登录时间:</td>
							<td>
								大于等于：<input style="width:120px;" class="ui-form-text ui-form-textGray ui-form-textDatetime" readonly="" name="lastlogintime" data-datefmt="yyyy-MM-dd HH:mm:00" data-maxdate="#F{$dp.$D('msgpostform-endTime')}" value="<?php echo isset($lastlogintime)?$lastlogintime:'';?>">
								<br />（如果不设定将不进行筛选，默认不筛选）
							</td>
						</tr>
						<tr>
							<td class="col1" style="vertical-align: text-top;">定时发送:</td>
							<td>
								<input id="msgpostform-startTime" style="width:120px;" class="ui-form-text ui-form-textGray ui-form-textDatetime" readonly="" name="startTime" data-datefmt="yyyy-MM-dd HH:mm:00" data-maxdate="#F{$dp.$D('msgpostform-endTime')}" value="<?php echo isset($startline)?$startline:'';?>">
								~
								<input id="msgpostform-endTime" style="width:120px;" class="ui-form-text ui-form-textGray ui-form-textDatetime" readonly="" name="endTime" data-datefmt="yyyy-MM-dd HH:mm:00" data-mindate="#F{$dp.$D('msgpostform-startTime')}" value="<?php echo isset($endline)?$endline:'';?>">
								<br />（你可以设定在一个时间范围内发送站内信，如果在该时间段中没有发送完成，
								<br />则系统会在第二天的相同时间点继续发送，若开始时间留空,则表示立即发送）
							</td>
						</tr>
						<tr>
							<td class="col1" style="vertical-align: text-top;padding-top: 9px;">标题内容:</td>
							<td>
								<input class="ui-form-text ui-form-textRed" name="title" value="<?php echo isset($title)?$title:'';?>" data-rule="required|maxlength(50)" data-msg="请输入标题|标题最多50个字符"/>
								<span id="for_title" style="color: red;display:block;">&nbsp;</span>
							</td>
						</tr>
						<tr>
							<td  class="col1">正文内容:</td><td>
								<textarea id="message" class="ui-form-text ui-form-textRed" name="content"><?php echo isset($content)?$content:'';?></textarea>
								<span id="message_text_len_show"><em></em>/500</span>
								<input type="hidden" id="message_text_len" name="message_text_len" value="0" data-rule="required|min(1)|max(500)" data-msg="请输入正文内容|请输入正文内容|正文内容最大长度为500字"/>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td>&nbsp;</td>
							<td>
								<input class="ui-form-button ui-form-buttonBlue" type="submit" value="发站内信">
								<input id="savefortpl" class="ui-form-button ui-form-buttonBlue" type="button" value="存为模板">
							</td>
						</tr>
					</tfoot>
				</table>
				<input type="hidden" name="dosend" value="yes">
				<input type="reset" style="display:none;">
			</form>
			<form id="addmsgtplorm" style="display: none;" type="ajax" method="post" callback="reset(true)">
				<input type="hidden" name="title" />
				<input type="hidden" name="id" />
				<textarea name="content"></textarea>
			</form>
		</div>
		<div style="width: 50%;float:right;" class="ui-box ui-box2">
			<div id="msg_tpl_list"></div>
		</div>
	</div>

</div></div></div>
	<script src="<?php echo $this->config->item('static_url');?>/javascript/common/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<!--[if IE]><script>window.isIE=true;</script><![endif]-->
<script type="text/javascript">

var loaded = false;
$(function(){
	if( ! loaded){
		window.editor = KindEditor.create('#message', {
			width : '434px',
			height : '300px',
			minWidth: '434px',
			resizeType : 1,
			items : ['source', '|', 'undo', 'redo', '|', 'forecolor', 'hilitecolor', 'bold', 'underline','removeformat','|','justifyleft','justifycenter','justifyright','|', 'link', 'unlink', '|', 'preview', 'fullscreen'],
			htmlTags : {
				font : ['color', 'size', 'face', '.background-color'],
				span : [
						'.color', '.background-color', '.font-size', '.font-family', '.background',
						'.font-weight', '.font-style', '.text-decoration', '.vertical-align', '.line-height'
				],
				div : [
						'align', '.border', '.margin', '.padding', '.text-align', '.color',
						'.background-color', '.font-size', '.font-family', '.font-weight', '.background',
						'.font-style', '.text-decoration', '.vertical-align', '.margin-left'
				],
				table: [
						'border', 'cellspacing', 'cellpadding', 'width', 'height', 'align', 'bordercolor',
						'.padding', '.margin', '.border', 'bgcolor', '.text-align', '.color', '.background-color',
						'.font-size', '.font-family', '.font-weight', '.font-style', '.text-decoration', '.background',
						'.width', '.height', '.border-collapse'
				],
				'td,th': [
						'align', 'valign', 'width', 'height', 'colspan', 'rowspan', 'bgcolor',
						'.text-align', '.color', '.background-color', '.font-size', '.font-family', '.font-weight',
						'.font-style', '.text-decoration', '.vertical-align', '.background', '.border'
				],
				a : ['href', 'target', 'name'],
				embed : ['src', 'width', 'height', 'type', 'loop', 'autostart', 'quality', '.width', '.height', 'align', 'allowscriptaccess'],
				img : ['src', 'width', 'height', 'border', 'alt', 'title', 'align', '.width', '.height', '.border'],
				'p,ol,ul,li,blockquote,h1,h2,h3,h4,h5,h6' : [
						'align', '.text-align', '.color', '.background-color', '.font-size', '.font-family', '.background',
						'.font-weight', '.font-style', '.text-decoration', '.vertical-align', '.text-indent', '.margin-left'
				],
				pre : ['class'],
				hr : ['class', '.page-break-after'],
				'br,tbody,tr,strong,b,sub,sup,em,i,u,strike,s,del' : []
			 },
			afterChange: editorAfterChange,
			afterCreate: function () {
				if(window.isIE){
					$(".ke-icon-source").click().click();// 修复IE浏览器的一个BUG，此方法有后遗症，如有完美解决办法，不胜感激
					// 下文三句代码作用是修复上句代码产生的后遗症
					$('#msgpostform input[name=title]').focus().blur();
					editorAfterChange();
					tip = $('#for_message_text_len').hide();
				}
			}
		});

		$.get('/message/template?mod=index',function(data){
			$('#msg_tpl_list').html(data);
		});
		loaded = true;
		if(typeof $('#savefortpl').data("events") == 'undefined'){
			$('#savefortpl').on('click', function(){
				addMsgTplChk();
			});
		}
		$('#check_level').on('click', function(){
			switch_to_send(this,'<?php echo isset($to_type)?$to_type:'';?>');
		});
		<?php if(isset($error) && $error != ''){?>
			PopupTips('<?php echo $error;?>', 'error', 3000);
		<?php }elseif(isset($height_level) && $height_level){?>
			setTimeout("$('#check_level').click()",500);
		<?php }else{?>
			var table = $('#msgpostform table:first');
			table.find('tr:lt(2)').find(":radio").prop("checked",false).prop("disabled",true);
		<?php }?>
	}
});

function editorAfterChange(){
	window.editor.sync();
	var len = window.editor.count('text'),
	len_show = $('#message_text_len_show'),
	len_show_em = len_show.find('em'),
	tip_id = 'for_message_text_len',
	tip = $('#'+tip_id);
	$('#message_text_len').val(len);
	len_show_em.html(len);
	if(len > 500){
		len_show_em.css({color:'red'});
		var tip_text = '正文内容最大长度为500字';
		if(tip.length){
			tip.html(tip_text);
			tip.show();
		}else{
			var tip_str = '<span id="'+tip_id+'" style="color: red; display: inline;">'+tip_text+'</span>';
			len_show.parent().append(tip_str);
		}
	}else{
		len_show_em.css({color:'black'});
		len > 0 && tip.hide();
		var tip_text = '请输入正文内容';
		if(tip.length){
			tip.html(tip_text);
			len == 0 && tip.show();
		}else{
			var tip_str = '<span id="'+tip_id+'" style="color: red; display: none;">'+tip_text+'</span>';
			len_show.parent().append(tip_str);
			len == 0 && tip.show();
		}
	}
}
function reset(load_tpl_list){
	$('#msgpostform input[name=tousers]').val('');
	$('#msgpostform input[name=title]').val('');
	$('#msgpostform input[name=startTime]').val('');
	$('#msgpostform  input[name=endTime]').val('');
	window.editor.text('');
	window.editor.html('');
	editorAfterChange();
	tip = $('#for_message_text_len').hide();
	var b = load_tpl_list != true ? false : true;
	if(b){
		$.get('/message/template?mod=index',function(data){
			$('#msg_tpl_list').html(data);
		});
	}
}
function sendConfirm(){
	var is_height_level = $('#check_level').prop("checked");
	if(is_height_level){
		var chk_utype = $('#msgpostform input[name=utype]').filter(":checked").length;
		if(chk_utype <= 0){
			PopupTips('请选择用户类型', 'error', 800);
			return false;
		}
	}else{
		var to_users = $.trim($('#msgpostform input[name=tousers]').val());
		if(to_users == ''){
			PopupTips('请填写收件人', 'error', 800);
			return false;
		}
	}
	if(! chk_content_len()){
		return false;
	}
	if(confirm('是否确认发送消息？')){
		return true;
	}else{
		return false;
	}
}
function chk_content_len(){
	window.editor.sync();
	var content_len = window.editor.count('text');
	if(content_len > 500){
		PopupTips('正文内容最多500个字符', 'error', 1000);
		return false;
	}else if(content_len <= 0){
		PopupTips('请输入正文内', 'error', 1000);
		return false;
	}
	return true;
}
function addMsgTplChk(tpl_id){
	var tplform = $('#addmsgtplorm');
	var tpl_id = parseInt(tplform.find('input[name=id]').val());
	var title = $.trim($('#msgpostform input[name=title]').val());
	window.editor.sync();
	var content = $.trim(window.editor.html());
	if(title == ''){
		PopupTips('站内信模板标题不能为空', 'error', 1000);
		return false;
	}else if(content == ''){
		PopupTips('站内信模板内容不能为空', 'error', 1000);
		return false;
	}else{
		var mod = tpl_id > 0 ? 'edit' : 'add';
		tplform.attr('action','<?php echo site_url('message/template?do=save&mod=')?>'+mod);
		tplform.find('input[name=title]').val(title);
		tplform.find('textarea[name=content]').val(window.editor.html());
		setTimeout(function(){
			tplform.submit();
		}, 500);
	}
}
function load_tpl_list(url){
	var url = (url || '/message/template');
	$.post(url, {
			mod:'index',
			inajax:1
		}, function(data){
		$('#msg_tpl_list').html(data);
	});
}
function useMsgTpl(tpl_id, reget){
	var tpl_id = (tpl_id || 0);
	var reget = (reget==true || false);
	var titleEle = $('#msgpostform input[name=title]');
	var contentEle = $('#msgpostform textarea[name=content]');
	if(tpl_id > 0){
		if(reget){
			var url = '/message/template';
			$.ajax({
				url:url,
				data:'mod=use&tpl_id='+tpl_id,
				dataType:'json',
				success:function(back){
						if(back.type == 'SUCCESS'){
							titleEle.val(back.data.title);
							contentEle.val(back.data.content);
							window.editor.html(back.data.content);
							window.editorAfterChange();
							$('#addmsgtplorm input[name=id]').val(tpl_id);
						}else{
							PopupTips(back.msg, 'error', 3000);
						}
				}
			});
		}else{
			var tpl_title = $('#tpl_title_'+tpl_id).val();
			var tpl_content = $('#tpl_content_'+tpl_id).val();
			titleEle.val(tpl_title);
			contentEle.val(tpl_content);
			window.editor.html(tpl_content);
			$('#addmsgtplorm input[name=id]').val(tpl_id);
		}
	}
}

function switch_to_send(o, to_type){
	var b = $(o).prop("checked");
	var type = {1:0, 2:1, 3:2};
	var to_type = typeof type[to_type] == 'undefined' ? 2 : type[to_type];
	var table = $('#msgpostform table:first');
	var scope_tr = table.find('#scope_tr');
	var type_tr = table.find('#type_tr');
	var tousers_tr = table.find('#tousers_tr');
	if(b){
		type_tr.find(":radio").prop("disabled",false).prop("checked",false)
		 .eq(to_type).prop("checked",true);/*本行eq值：0试客、1商家、2全部*/
		tousers_tr.find(":text").val("").prop("disabled",true);
		scope_tr.show();
		type_tr.show();
		tousers_tr.hide();
		$('#priority_tr').hide();
		$('#shaixuan_tr').show();
	}else{
		scope_tr.hide();
		type_tr.hide();
		$('#priority_tr').show();
		$('#shaixuan_tr').hide();
		tousers_tr.show();
		type_tr.find(":radio").prop("checked",false).prop("disabled",true);
		tousers_tr.find(":text").prop("disabled",false).val("").focus();
	}
}
function re_load(){
	window.location.reload();
}

</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>