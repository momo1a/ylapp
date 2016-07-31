<script type="text/javascript">
var cate_p_flag = false;	//主类型已选交状态
var cate_c_flag = false;	//子类型已选交状态
var title_flag = false;	//标题
var tag_flag = false;	//标签
var content_flag = false;	//内容
var summarize_flag = false;	//简介
$(document).ready(function(){
	var editor;	//存储文本编辑器对象

	$.getScript("<?php echo $this->config->item('static_url');?>/javascript/common/kindeditor/kindeditor-min.js", function(){
		/*文本编辑插件*/
	 	window.editor = KindEditor.create('#kindeditor_content',{
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
	//二级联动菜单，主类型事件
	$("#form_add select[name='pid']").bind("change",function(){	//选择主分类
		var pid = $(this).val();	//获取主分类选中值（即id）作为子分类查询参数，0除外
		if(pid > 0){ cate_p_flag = true; }else{ cate_p_flag = false; return false; }
		
		check_select("#form_add select[name='cid']", pid);	//调用二级联动方法，异步变更子分类html
		cate_p_flag = true;	//主类型选中
	}).bind("blur",function(){	//主类未选则提示语，主类已选且子类未选则提示语
		if(cate_p_flag && !cate_c_flag){ $("#form_add .cate_msg").attr("style","color:red;").text("请选择子类目"); }
		if(!cate_p_flag && !cate_c_flag || !cate_p_flag){ 
			$("#form_add .cate_msg").attr("style","color:red;").text("请选择分类"); 
			$("#form_add select[name='cid']").html("<option value='0'>请选择分类</option>");
		}
	});

	//子类型事件
	$("#form_add select[name='cid']").bind("change",function(){	//获取子分类选中值（即id）作为选中判断依据，0除外
		if($(this).val() > 0){ cate_c_flag = true; }else{ cate_c_flag = false; }	//获取子分类id，>0为已选，反之
		//判断子类型是否有选择，已选打√，未选则隐藏
		if(cate_c_flag){ $("#form_add .cate_msg").attr("style","color:#4BDB9B; font-sie:14px;").html("&nbsp;√"); }else{ $("#cate_msg").attr("style","color:red;").text("请选择子类目"); }
		if(cate_p_flag && !cate_c_flag){ $("#form_add .cate_msg").attr("style","color:red;").text("请选择子类目"); }
	}).bind("blur",function(){	//主类已选且子类未选则提示语
		if(!cate_p_flag && !cate_c_flag){ $("#form_add .cate_msg").attr("style","color:red;").text("请选择分类"); }
	});
	
});

function chkaddform(){
	var add_form = $("#form_add");
	
	var pid_val = add_form.find('select[name=pid]').val();
	var cid_val = add_form.find('select[name=cid]').val();
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
		$("#form_add .cate_msg").attr("style","color:#4BDB9B; font-sie:14px;").html("&nbsp;√");
	}else{
		$("#form_add .cate_msg").attr("style","color:red;").text("请选择分类");
	}
	if(cate_p_flag && ! cate_c_flag){ $("#form_add .cate_msg").attr("style","color:red;").text("请选择子类目"); }

	//规则 123
	var not_null_reg = /.+/;	//必填，有内容返回true
	var str_reg = /[\+\@\#\$\?\=\*]/;	//特殊字符，有则返回true

	/*标题验证*/
	var title_val = add_form.find('input[name=title]').val();	//标题内容
	if( !not_null_reg.test(title_val) ){	//为空
		$('#title_msg').attr('style','color:red').html('&nbsp;&nbsp;请输入标题');	//标题提示框 color:#A1A1A1
	}else if( str_reg.test(title_val) ){	//有特殊字符
		$('#title_msg').attr('style','color:red').html('&nbsp;&nbsp;标题不能包含特殊字符(+@#$?=*)');
	}else if ( title_val.length > 50 ) {	//超过50个字符
		$('#title_msg').attr('style','color:red').html('&nbsp;&nbsp;最多只能输入50个字符');
	}else{	//正确
		$('#title_msg').attr('style','color:#4BDB9B').html('&nbsp;&nbsp;√');
		title_flag = true;
	}

	/*标签验证*/
	var tag_val = add_form.find('input[name=tag]').val();	//标题内容
	if( !not_null_reg.test(tag_val) ){	//为空
		$('#tag_msg').attr('style','color:red').html('&nbsp;&nbsp;请输入标签');
	}else if( str_reg.test(tag_val) ){	//有特殊字符
		$('#tag_msg').attr('style','color:red').html('&nbsp;&nbsp;标签不能包含特殊字符(+@#$?=*)');
	}else{	//正确
		$('#tag_msg').attr('style','color:#4BDB9B').html('&nbsp;&nbsp;√');
		tag_flag = true;
	}
	
	/*简介验证*/
	var summarize_val = add_form.find('textarea[name=summarize]').val();	//标题内容
	if( !not_null_reg.test(summarize_val) ){	//为空
		$('#summarize_msg').attr('style','color:red').html('&nbsp;&nbsp;请输入简要描述');
	}else{	//正确
		$('#summarize_msg').attr('style','color:#4BDB9B').html('&nbsp;&nbsp;√');
		summarize_flag = true;
	}

	/*主要内容验证*/
	var content_val = window.editor.text();	//标题内容
	if( !not_null_reg.test(content_val) ){	//为空
		$('#content_msg').attr('style','color:red').html('&nbsp;&nbsp;请输入主要内容');
	}else{	//正确
		$('#content_msg').attr('style','color:#4BDB9B').html('&nbsp;&nbsp;√');
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
		$('#content_msg').attr('style','color:red').html('&nbsp;&nbsp;请输入主要内容');
	}else{	//正确
		$('#content_msg').attr('style','color:#4BDB9B').html('&nbsp;&nbsp;√');
		content_flag = true;
	}

	//判断每个flag是否为true
	if(cate_p_flag && cate_c_flag && title_flag && tag_flag && content_flag && summarize_flag){	//所有选项中，有一个为false则不提交
		return true;
	}else{
		return false;
	}
	/*————————————————/添加帮助————————————————*/
}
</script>
<form id="form_add" callback="reload" name="form_add" method="post" beforesubmit="chkaddform" type="ajax" action="<?php echo site_url('help/listing_action/add/'.$type_url.'/'.$type)?>">
	<table class="ui-table2">
		<col style="width: 8em;" />
		<col />
		<tbody>
			<tr>
				<th>分类:</th>
				<td><select name="pid">
						<option value="0">请选择分类</option>
				<?php foreach ($cate_parents as $cate):?>
				<option value="<?php echo $cate['id']?>"><?php echo $cate['name']?></option>
				<?php endforeach;?>
			</select> <select name="cid">
						<option value="0">请选择分类</option>
				</select> <span class="cate_msg"></span></td>
			</tr>
			<tr>
				<th>标题:</th>
				<td><input type="text" name="title" value="" size="50" class="ui-form-text ui-form-textRed elm" /><span id="title_count">&nbsp;0/50</span><span id="title_msg" style="color: #A1A1A1;">&nbsp;&nbsp;标题不能包含特殊字符(+@#$?=*)</span></td>
			</tr>
			<tr>
				<th>标签:</th>
				<td><input type="text" name="tag" value="" size="50" class="ui-form-text ui-form-textRed" /><span id="tag_msg" style="color: #A1A1A1;">&nbsp;&nbsp;多个标签可用空格隔开</span></td>
			</tr>
			<!-- <tr>
				<th style="vertical-align: top;">主要内容:</th>
				<td><textarea name="content" class="ui-form-textRed" cols="80" rows="10"></textarea><span id="content_msg"></span></td>
			</tr> -->
			<tr>
				<th style="vertical-align: top;">简要描述:</th>
				<td><textarea name="summarize" class="ui-form-textRed" cols="80" rows="5"></textarea><span id="summarize_msg"></span></td>
			</tr>
			<tr>
				<th style="vertical-align: top;">主要内容:</th>
				<td><textarea id="kindeditor_content" name="content" class="ui-form-textRed" style="width:700px;height:300px;"></textarea><span id="content_msg"></span></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th></th>
				<td><input type="submit" value="发布" class="ui-form-button ui-form-buttonBlue" /></td>
			</tr>
		</tfoot>
	</table>
</form>