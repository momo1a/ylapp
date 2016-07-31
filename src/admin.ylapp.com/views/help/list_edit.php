<script type="text/javascript">
var cate_p_flag = true;	//主类型已选交状态
var cate_c_flag = true;	//子类型已选交状态
var title_flag = false;	//标题
var tag_flag = false;	//标签
var content_flag = false;	//内容
var summarize_flag = false;	//简介

$(document).ready(function(){

	var editor;	//存储文本编辑器对象
	$.getScript("<?php echo $this->config->item('static_url');?>/javascript/common/kindeditor/kindeditor-min.js", function(){
		/*文本编辑插件*/
	 	window.editor = KindEditor.create('#content_edit',{
			uploadJson : '<?php echo site_url('help/upload_img');?>',
			allowFileManager : false,
			//需要的功能
			items : ['source','fontname','fontsize','bold','underline','forecolor','preview','selectall','justifyleft','justifycenter','justifyright','table','emoticons','link','unlink','image'],
			//简单模式
			afterChange : function() {
			   this.sync();  
		   }
		});
	   
	});

});



//二级联动菜单，主类型事件
$("#form_edit select[name='pid']").bind("change",function(){	//选择主分类
	var pid = $(this).val();	//获取主分类选中值（即id）作为子分类查询参数，0除外
	if(pid > 0){ cate_p_flag = true; }else{ cate_p_flag = false; return false; }
	
	check_select("#form_edit select[name='cid']", pid);	//调用二级联动方法，异步变更子分类html
	cate_p_flag = true;	//主类型选中
}).bind("blur",function(){	//主类未选则提示语，主类已选且子类未选则提示语
	if(cate_p_flag && !cate_c_flag){ $("#form_edit .cate_msg").attr("style","color:red;").text("请选择子类目"); }
	if(!cate_p_flag && !cate_c_flag || !cate_p_flag){ 
		$("#form_edit .cate_msg").attr("style","color:red;").text("请选择分类"); 
		$("#form_edit select[name='cid']").html("<option value='0'>请选择分类</option>");
	}
});

//子类型事件
$("#form_edit select[name='cid']").bind("change",function(){	//获取子分类选中值（即id）作为选中判断依据，0除外
	if($(this).val() > 0){ cate_c_flag = true; }else{ cate_c_flag = false; }	//获取子分类id，>0为已选，反之
	//判断子类型是否有选择，已选打√，未选则隐藏
	if(cate_c_flag){ $("#form_edit .cate_msg").attr("style","color:#4BDB9B; font-sie:14px;").html("&nbsp;√"); }else{ $("#cate_msg").attr("style","color:red;").text("请选择子类目"); }
	if(cate_p_flag && !cate_c_flag){ $("#form_edit .cate_msg").attr("style","color:red;").text("请选择子类目"); }
}).bind("blur",function(){	//主类已选且子类未选则提示语
	if(!cate_p_flag && !cate_c_flag){ $("#form_edit .cate_msg").attr("style","color:red;").text("请选择分类"); }
});

//规则 123
var not_null_reg = /.+/;	//必填，有内容返回true
var str_reg = /[\+\@\#\$\?\=\*]/;	//特殊字符，有则返回true
/*标题验证*/
$("#form_edit input[name='title']").bind("blur",function(){
	var title_val = $(this).val();	//标题内容
	if( !not_null_reg.test(title_val) ){	//为空
		$('#edit_title_msg').attr('style','color:red').html('&nbsp;&nbsp;请输入标题');	//标题提示框 color:#A1A1A1
	}else if( str_reg.test(title_val) ){	//有特殊字符
		$('#edit_title_msg').attr('style','color:red').html('&nbsp;&nbsp;标题不能包含特殊字符(+@#$?=*)');
	}else if ( title_val.length > 50 ) {	//超过50个字符
		$('#edit_title_msg').attr('style','color:red').html('&nbsp;&nbsp;最多只能输入50个字符');
	}else{	//正确
		$('#edit_title_msg').attr('style','color:#4BDB9B').html('&nbsp;&nbsp;√');
		title_flag = true;
	}
});
/*标签验证*/
$("#form_edit input[name='tag']").bind("blur",function(){
	var tag_val = $(this).val();	//标题内容
	if( !not_null_reg.test(tag_val) ){	//为空
		$('#edit_tag_msg').attr('style','color:red').html('&nbsp;&nbsp;请输入标签');
	}else if( str_reg.test(tag_val) ){	//有特殊字符
		$('#edit_tag_msg').attr('style','color:red').html('&nbsp;&nbsp;标签不能包含特殊字符(+@#$?=*)');
	}else{	//正确
		$('#edit_tag_msg').attr('style','color:#4BDB9B').html('&nbsp;&nbsp;√');
		tag_flag = true;
	}
});
/*主要内容验证*/
$("#form_edit textarea[name='content']").bind("blur",function(){
	var content_val = window.editor.text();	//标题内容
	if( !not_null_reg.test(content_val) ){	//为空
		$('#edit_content_msg').attr('style','color:red').html('&nbsp;&nbsp;请输入主要内容');
	}else{	//正确
		$('#edit_content_msg').attr('style','color:#4BDB9B').html('&nbsp;&nbsp;√');
		content_flag = true;
	}
});
/*简介验证*/
$("#form_edit textarea[name='summarize']").bind("blur",function(){
	var summarize_val = $(this).val();	//标题内容
	if( !not_null_reg.test(summarize_val) ){	//为空
		$('#edit_summarize_msg').attr('style','color:red').html('&nbsp;&nbsp;请输入简要描述');
	}else{	//正确
		$('#edit_summarize_msg').attr('style','color:#4BDB9B').html('&nbsp;&nbsp;√');
		summarize_flag = true;
	}
});

//表单提交验证
function chkeditform(){

	var form_edit = $("#form_edit");
	
	var pid_val = form_edit.find('select[name=pid]').val();
	var cid_val = form_edit.find('select[name=cid]').val();
	if(pid_val > 0){
		cate_p_flag = true;
	}else{
		cate_p_flag = false;
	}
	if(cid_val > 0){
		cate_c_flag = true;
	}else{
		cate_c_flag = false;
	}
	//获取子分类id，>0为已选，反之
	//判断子类型是否有选择，已选打√，未选则隐藏
	if(cate_p_flag){
		$("#form_edit .cate_msg").attr("style","color:#4BDB9B; font-sie:14px;").html("&nbsp;√");
	}else{
		$("#form_edit .cate_msg").attr("style","color:red;").text("请选择分类");
	}
	if(cate_p_flag && ! cate_c_flag){ $("#form_add .cate_msg").attr("style","color:red;").text("请选择子类目"); }

	//规则
	var not_null_reg = /.+/;	//必填，有内容返回true
	var str_reg = /[\+\@\#\$\?\=\*]/;	//特殊字符，有则返回true

	/*标题验证*/
	var title_val = form_edit.find('input[name=title]').val();	//标题内容
	if( !not_null_reg.test(title_val) ){	//为空
		$('#edit_title_msg').attr('style','color:red').html('&nbsp;&nbsp;请输入标题');	//标题提示框 color:#A1A1A1
	}else if( str_reg.test(title_val) ){	//有特殊字符
		$('#edit_title_msg').attr('style','color:red').html('&nbsp;&nbsp;标题不能包含特殊字符(+@#$?=*)');
	}else if ( title_val.length > 50 ) {	//超过50个字符
		$('#edit_title_msg').attr('style','color:red').html('&nbsp;&nbsp;最多只能输入50个字符');
	}else{	//正确
		$('#edit_title_msg').attr('style','color:#4BDB9B').html('&nbsp;&nbsp;√');
		title_flag = true;
	}
	
	/*标签验证*/
	var tag_val = form_edit.find('input[name=tag]').val();	//标题内容
	if( !not_null_reg.test(tag_val) ){	//为空
		$('#edit_tag_msg').attr('style','color:red').html('&nbsp;&nbsp;请输入标签');
	}else if( str_reg.test(tag_val) ){	//有特殊字符
		$('#edit_tag_msg').attr('style','color:red').html('&nbsp;&nbsp;标签不能包含特殊字符(+@#$?=*)');
	}else{	//正确
		$('#edit_tag_msg').attr('style','color:#4BDB9B').html('&nbsp;&nbsp;√');
		tag_flag = true;
	}
	
	/*简介验证*/
	var summarize_val = form_edit.find('textarea[name=summarize]').val();	//标题内容
	if( !not_null_reg.test(summarize_val) ){	//为空
		$('#edit_summarize_msg').attr('style','color:red').html('&nbsp;&nbsp;请输入简要描述');
	}else{	//正确
		$('#edit_summarize_msg').attr('style','color:#4BDB9B').html('&nbsp;&nbsp;√');
		summarize_flag = true;
	}

	/*主要内容验证*/
	var content_val = window.editor.text();	//标题内容
	if( !not_null_reg.test(content_val) ){	//为空
		$('#edit_content_msg').attr('style','color:red').html('&nbsp;&nbsp;请输入主要内容');
	}else{	//正确
		$('#edit_content_msg').attr('style','color:#4BDB9B').html('&nbsp;&nbsp;√');
		content_flag = true;
	}

	var pid = $("#form_add select[name='pid']").val();	//主分类id
	var cid = $("#form_add select[name='cid']").val();	//子分类id
	var title = $("#form_add input[name='title']").val();	//标题
	var tag = $("#form_add input[name='tag']").val();	//标签
	// var content = $("#form_add textarea[name='content']").val();	//主要内容
	var summarize = $("#form_add textarea[name='summarize']").val();	//简述
	var content = window.editor.html();	//主要内容

	if( !not_null_reg.test(content) ){	//为空
		$('#edit_content_msg').attr('style','color:red').html('&nbsp;&nbsp;请输入主要内容');
	}else{	//正确
		$('#edit_content_msg').attr('style','color:#4BDB9B').html('&nbsp;&nbsp;√');
		content_flag = true;
	}
	
	var id = $("#form_edit input[name='id']").val();
	var pid = $("#form_edit select[name='pid']").val();	//主分类id
	var cid = $("#form_edit select[name='cid']").val();	//子分类id
	var title = $("#form_edit input[name='title']").val();	//标题
	var tag = $("#form_edit input[name='tag']").val();	//标签
	var content = $("#form_edit textarea[name='content']").val();	//主要内容
	var summarize = $("#form_edit textarea[name='summarize']").val();	//简述

	if(cate_p_flag && cate_c_flag && title_flag && tag_flag && content_flag && summarize_flag){	//所有选项中，有一个为false则不提交
		return true;
	}else{
		return false;
	}
	
}


/*字符数实时统计插件*/
;(function($){
	$.fn.extend({
    // 回调函数在字符串长度统计完成后触发，this指向应用该插件的DOM元素，实参是统计得到的字符串长度；
    sumOfChars: function (options, callback) {    
        var settings = $.extend({
            eType: 'input',    // 事件类型  (ps：测试发现'input'事件在IE9下使用退格键删减内容时竟然不能触发！)
            isByte: false,      // 统计的长度类型, true表示统计字节(一个汉字两个字节)长度; false表示统计字符长度; 
            maxLength: false   // 限制输入长度，默认不限制
        }, options || {});
        // 当调用该插件时实参仅包含回调函数：
        typeof arguments[0] === 'function' && (callback = options);
        this.each(function(){
                var self = $(this),
                    type = settings.eType;
                // 'on'是jQuery 1.7+ 才有的方法
                self.on(type, _handler).triggerHandler(type);
                type === 'input' && self.on('propertychange', function(){   // IE 8-
                    // 如果发生改变的属性不是value就退出
                    if(!window.event || window.event.propertyName !== 'value') return;    
                    // 避免循环调用
                    $(this).off('propertychange', arguments.callee);
                    _handler.apply(this);
                    $(this).on('propertychange', arguments.callee);
                }).triggerHandler('propertychange');
                settings.maxLength && self.on('keypress textInput textinput', function (e) {
                    if( _count(this.value, settings.isByte) >= settings.maxLength)
                    	e.preventDefault();
                });
        });
        // 长度统计
        function _count (str, b) {
		    return b? str.replace(/[^\x00-\xff]/g, "aa").length : str.length;
        }
        // 事件处理程序
        function _handler (e) {
                var num = _count(this.value, settings.isByte);
                if( num > settings.maxLength){
                	while(_count(this.value, settings.isByte)>settings.maxLength){
                	 this.value = this.value.substr(0,this.value.length-1);
                	}
                	num = _count(this.value, settings.isByte);
                }
                typeof callback === 'function' && callback.apply(this, [num]);
        }
        return this;    // 返回jQuery对象以使其链式操作得以持续
        }
    });
}(jQuery));

// 字符数实时统计插件使用
$('.elm').sumOfChars({ maxLength: 50 }, function(n){
	$('#edit_title_count').html("&nbsp;" + n + "/50");
} );

</script>

<form id="form_edit" callback="reload" name="form_edit" method="post" beforesubmit="chkeditform" type="ajax" action="<?php echo site_url('help/listing_action/edit')?>">
<table class="ui-table2">
	<col style="width:8em;" /><col />
	<tbody id="tbody">
		<tr class="tr_cate">
			<th>分类:</th>
			<td>
			<select name="pid">
				<option value="0">请选择分类</option>
				<?php foreach ($cate_parents as $cate_p):?>
					<?php if($rs['pid']==$cate_p['id']):?>
						<option selected="selected" value="<?php echo $cate_p['id']?>"><?php echo $cate_p['name']?></option>
					<?php else :?>
						<option value="<?php echo $cate_p['id']?>"><?php echo $cate_p['name']?></option>
					<?php endif;?>
				<?php endforeach;?>
			</select>
			<select name="cid">
				<option value="0">请选择分类</option>
				<?php foreach ($cate_childs as $cate):?>
					<?php if($rs['cid']==$cate['id']):?>
						<option selected="selected" value="<?php echo $cate['id']?>"><?php echo $cate['name']?></option>
					<?php else :?>
						<option value="<?php echo $cate['id']?>"><?php echo $cate['name']?></option>
					<?php endif;?>
				<?php endforeach;?>
			</select>
				<span class="cate_msg"></span>
			</td>
		</tr>
		
		<tr>
			<th>标题:</th>
			<td><input type="text" name="title" value='<?php echo $rs['title']?>' size="50" class="ui-form-text ui-form-textRed elm" /><span id="edit_title_count">&nbsp;0/50</span><span id="edit_title_msg" style="color:#A1A1A1;">&nbsp;&nbsp;标题不能包含特殊字符(+@#$?=*)</span>
				<input type="hidden" name="id" value='<?php echo $id?>' />
			</td>
		</tr>
		
		<tr>
			<th>标签:</th>
			<td><input type='text' name='tag' value='<?php echo $rs['tag']?>' size='50' class='ui-form-text ui-form-textRed' /><span id='edit_tag_msg' style='color:#A1A1A1;'>&nbsp;多个标签可用空格隔开</span></td>
		</tr>
			<th style="vertical-align: top;">简要描述:</th>
			<td><textarea name="summarize" class="ui-form-textRed" cols="80" rows="5"><?php echo $rs['summarize']?></textarea><span id="edit_summarize_msg"></span></td>
		</tr>
		<tr>
			<th style="vertical-align: top;">主要内容:</th>
			<td><textarea id='content_edit' name='content' class="ui-form-textRed" style="width:700px;height:300px;"><?php echo $rs['content']?></textarea><span id="edit_content_msg"></span></td></tr>
		<tr>
	</tbody>
	<tfoot>
			<tr>
				<th></th>
				<td><input callback="reload" type="submit" value="发布" class="ui-form-button ui-form-buttonBlue" /></td>
			</tr>
	</tfoot>
</table>
</form>