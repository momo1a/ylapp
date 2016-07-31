<link rel="stylesheet" href="<?php echo $this->config->item('static_url');?>/javascript/common/jquery/colorpicker/jquery.colorpicker.css" type="text/css" media="screen" />
<script type="text/javascript">

$(document).ready(function(){
	/*————————————————常见问题————————————————*/

	/*加载调色板控件*/
	$.getScript("<?php echo $this->config->item('static_url');?>/javascript/common/jquery/colorpicker/jquery.colorpicker.js", function(){
		/*颜色版控件调用*/
	 	$('#picker').colorpicker({
			size: 20,  
			label: 'Color: ',  
			hide: false
		}); 
	
	});
	
	/*批量撤销推送*/
	$(".clearfix input[name='cancel_push']").bind("click",function(){
		var $this = $(this);
		var ids = $("#question_tb input[type='checkbox'][class!='checkAll'][name^='ids']:checked").map(function(){
			return $(this).val();
		}).get().join(",");	//返回选中记录的id，以逗号连接

		if(ids.length<1){ alert("请勾选需要操作的内容"); return false; }

		$.post('<?php echo site_url('help/callback_cancel_push')?>', {'ids':ids, 'push':0}, function(rs){
			AjaxFilter(rs, $this);	//过滤服务器返回的内容
		});
	});
	

	/* 批量排序 */
	var open = false;	//排序输入框：false 为隐藏、true 为文本框
	$("#question_tb a[name='th_sort']").bind("click",function(){
		var $this = $(this);
		
		if(open === false){	//显示文本框
			$("#question_tb tbody span[class='span_sort']").attr("style","display:none;");	//隐藏排序号
			$("#question_tb tbody input[type='hidden']").attr("type","text");	//显示修改排序的输入框
			open = true;
		}else{	//隐藏文本框
			var ids = $("#question_tb input[type='text']").map(function(){
				return $(this).attr("id");
			}).get().join(",");	//返回选中记录的id，以逗号连接
			
			var sorts = $("#question_tb input[type='text'][name^='sorts'][value!='']").map(function(){
				var value = $(this).val();
				var checkNum = /^[0-9]+$/;
				//如果不是数字就改成0
				if(!checkNum.test(value)){ value = $(this).attr('source-val'); }
				return value;
			}).get().join(",");
			
			$.post('<?php echo site_url('help/callback_edit_sort')?>', {'sorts':sorts, 'ids':ids}, function(rs){
				AjaxFilter(rs, $this);	//过滤服务器返回的内容
			});
			
			$("#question_tb tbody span[class='span_sort']").removeAttr("style");	//删除隐藏的排序号
			$("#question_tb tbody input[type='text']").attr("type","hidden");	//隐藏修改排序的输入框
			open = false;
		}// End IF
	});// End 批量排序

	/*批量字体加粗*/
	$(".clearfix input[name='font_strong']").bind("click",function(){
		var $this = $(this);

		var ids = $("#question_tb input[type='checkbox'][class!='checkAll'][name^='ids']:checked").map(function(){
			return $(this).val();
		}).get().join(",");	//返回选中记录的id，以逗号连接

		if(ids.length<1){ alert("请勾选需要操作的内容"); return false; }

		var title_font = 'bold';	//标题粗细

		$.post('<?php echo site_url('help/callback_update_font_strong')?>', {'ids':ids, 'title_font':title_font}, function(rs){
			AjaxFilter(rs, $this);	//过滤服务器返回的内容
		});
	});//End 字体加粗
	
	/*颜色选择动作*/
	var color = '';	//初始化颜色值
	$('#picker').bind('change', function(){
		color = $(this).val();	//获取颜色值
		if(color.length > 1){color = '#'+color;}
	});
	/*批量字体变色*/
	$(".clearfix input[name='font_color']").bind("click",function(){
		var $this = $(this);

		var ids = $("#question_tb input[type='checkbox'][class!='checkAll'][name^='ids']:checked").map(function(){
			return $(this).val();
		}).get().join(",");	//返回选中记录的id，以逗号连接


		if(ids.length<1){ alert("请勾选需要操作的内容"); return false; }

		$.post('<?php echo site_url('help/callback_update_font_color')?>', {'ids':ids, 'title_color':color}, function(rs){
			AjaxFilter(rs, $this);	//过滤服务器返回的内容
		});
	});

	

});
</script>
<div class="clearfix" style="margin-bottom:8px;">
	<input callback="reload" type="button" name="cancel_push" value="取消推送" class="ui-form-btnSearch" />
	<input callback="reload" type="button" name="font_strong" value="字体加粗" class="ui-form-btnSearch" />
	<input callback="reload" type="button" name="font_color" value="字体变色" class="ui-form-btnSearch" />
	<select id="picker" style="display: none;">
		<option value="">无</option>
		<option value="FF0000">FF0000</option>
		<option value="cc0000">cc0000</option>
		<option value="FF6600">FF6600</option>
		<option value="EA5900">EA5900</option>
		<option value="0000FF">0000FF</option>
		<option value="4285F4">4285F4</option>
		<option value="3D4D66">3D4D66</option>
		<option value="2D3D57">2D3D57</option>
		<option value="2ABF17">2ABF17</option>
		<option value="02B102">02B102</option>
		<option value="8EB10C">8EB10C</option>
		<option value="000000">000000</option>
		<option value="666">666</option>
	</select>
	<input callback="reload" type="button" class="ui-form-button ui-form-buttonBlue update_html_cache" style="float:right;" value="更新" />
</div>

<style type="text/css">
    <?php foreach ($questions as $k=>$q):   //首页/买家控制器/分类方法/主类id/子类id/详情id?>
    /*动态样式*/
    .title_style<?php echo $k;?>{   
        color: <?php echo $q['title_color'];?>;
        font-weight:<?php echo $q['title_font'];?>;
    }
    <?php endforeach;?>
</style>
<table id="question_tb" class="ui-table">
	<thead>
		<tr>
			<th>选择</th>
			<th>状态</th>
			<th>编号</th>
			<th>标题</th>
			<th>发布时间</th>
			<th>排序 [<a callback="reload" name="th_sort" href="javascript:;" class="ui-operate-button ui-operate-buttonEdit">修改排序</a>]</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($questions as $k=>$q):?>
		<tr>
			<td><input type="checkbox" name="ids[]" value="<?php echo $q['id']?>"/></td>
			<td>
			<?php if ($q['parent_state']==2):?><span class="u-ifont" style="color:#CC4D00;font-size:20px;" title="主类目被屏蔽">&#xe606;</span>
			<?php elseif ($q['child_state']==2):?><span class="u-ifont" style="color:#FDAC2F;font-size:20px;" title="子类目被屏蔽">&#xe606;</span>
			<?php elseif ($q['state']==0):?><span class="u-ifont" style="color:#EADF08;font-size:20px;" title="帮助被屏蔽">&#xe606;</span>
			<?php elseif ($q['state']==1):?><span class="u-ifont" style="color:#00cc00;font-size:20px;" title="正常">&#xe603;</span>
			<?php endif;?></td>
			<td><?php echo $q['id']?></td>
			<td class="title_style<?php echo $k;?>"><?php echo $q['title']?></td>
			<td><?php echo date('Y-m-d H:i:s', $q['dateline'])?></td>
			<td><span class="span_sort"><?php echo $q['sort']?></span><input id="<?php echo $q['id']?>" class="ui-form-text ui-form-textRed" type="hidden" source-val="<?php echo $q['sort']?>" name="sorts[]" size="2" value="<?php echo $q['sort']?>" /></td>
			<td class="ui-table-operate">
				<a href="<?php echo $q['link']?>" target="_blank">预览</a>
				<a class="a_edit" data-id="<?php echo $q['id']?>" href="<?php echo site_url('help/listing/'.$type_url.'?tag_type=3&id='.$q['id'])?>">编辑</a>
				<a href="<?php echo site_url('help/listing_action/delete/'.$q['id'])?>" type="confirm" title="确认删除该项？" callback="reload">删除</a>
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
	<tfoot>
		<tr>
			<td><label><input class="checkAll" type="checkbox" name="" />&nbsp;全选</label></td>
			<td colspan="6">
				<!-- 分页 -->
				<div class="ui-paging">
				<?php echo $pager?>
				</div>
			</td>
		</tr>
	</tfoot>
</table>
