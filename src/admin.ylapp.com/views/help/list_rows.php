<script type="text/javascript">
$(document).ready(function(){
	/*————————————————帮助列表————————————————*/
	//列表二级联动，主类型事件
	$("#form_list select[name='pid']").bind("change",function(){	//选择主分类
		var pid = $(this).val();	//获取主分类选中值（即id）作为子分类查询参数，0除外
		if(pid < 1){ return false; }
		check_select("#form_list select[name='cid']", pid);	//调用二级联动方法
	});
	
	//状态列
	$("#btn_block").bind("click",function(){	//屏蔽
		var $this = $(this);
		var ids = $("#list_tb input[type='checkbox'][class!='checkAll'][name^='ids']:checked").map(function(){
			return $(this).val();
		}).get().join(",");	//返回选中记录的id，以逗号连接

		if(ids.length<1){ alert("请勾选需要操作的内容"); return false; }
		
		$.post('<?php echo site_url('help/callback_block_change')?>', {'ids':ids, 'state':0}, function(rs){
			AjaxFilter(rs, $this);	//过滤服务器返回的内容
		});
	});
	$("#btn_unblock").bind("click",function(){	//显示
		var $this = $(this);
		var ids = $("#list_tb input[type='checkbox'][class!='checkAll'][name^='ids']:checked").map(function(){
			return $(this).val();
		}).get().join(",");	//返回选中记录的id，以逗号连接

		if(ids.length<1){ alert("请勾选需要操作的内容"); return false; }
		
		$.post('<?php echo site_url('help/callback_block_change')?>', {'ids':ids,'state':1}, function(rs){
			AjaxFilter(rs, $this);
		});
	});
	$("#btn_del").bind("click",function(){	//删除
		var $this = $(this);
		var ids = $("#list_tb input[type='checkbox'][class!='checkAll'][name^='ids']:checked").map(function(){
			return $(this).val();
		}).get().join(",");	//返回选中记录的id，以逗号连接

		if(ids.length<1){ alert("请勾选需要操作的内容"); return false; }

		if(confirm("确定要批量删除吗？")){
			$.post('<?php echo site_url('help/callback_delete')?>', {'ids':ids}, function(rs){
				AjaxFilter(rs, $this);
			});
		}
	});
	//推送列
	$("#list_tb .push").bind("click",function(){
		var $this = $(this);
		var id = $(this).attr("data-id");
		$.post('<?php echo site_url('help/callback_push')?>', {'id':id, 'push':1}, function(rs){
			AjaxFilter(rs, $this);	//过滤服务器返回的内容
		});
	});
	/*————————————————/帮助列表————————————————*/
});
</script>

<div class="clearfix" style="margin-bottom:8px;">
	<!-- 搜索 -->
	<form rel="div#main-wrap" id="form_list" name="form_list" action="<?php echo site_url('help/listing_search/'.$type_url)?>" method="get" style="float:left;">
		<select name="pid" class="parent_cate">
			<option value="0">请选择分类</option>
			<?php foreach ($cate_parents as $cate):?>
			<option value="<?php echo $cate['id']?>" <?php if ($pid==$cate['id']):?>selected="selected"<?php endif;?> ><?php echo $cate['name']?></option>
			<?php endforeach;?>
		</select>
		<select name="cid" class="child_cate">
			<option value="0">请选择分类</option>
			<?php foreach ($cate_childs as $cate):?>
			<option value="<?php echo $cate['id']?>" <?php if ($cid==$cate['id']):?>selected="selected"<?php endif;?> ><?php echo $cate['name']?></option>
			<?php endforeach;?>
		</select>
		<input type="text" name="search_title" class="ui-form-text ui-form-textRed" />
		<input type="submit" name="" value="搜索" class="ui-form-btnSearch" />
	</form>
	<!-- /搜索 -->
	<input type="button" class="ui-form-button ui-form-buttonBlue update_html_cache" style="float:right;" value="更新" />
</div>

<table id="list_tb" class="ui-table">
	<colgroup>
		<col width="50px"></col>
		<col span="6"></col>
	</colgroup>
	<thead>
		<tr>
			<th>选择</th>
			<th>状态</th>
			<th>编号</th>
			<th>标题</th>
			<th>发布时间</th>
			<th>推送</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($contents as $c):?>
		<tr>
			<td><input type="checkbox" name="ids[]" value="<?php echo $c['id']?>" /></td>
			<td>
			<?php if ($c['parent_state']==2):?><span class="u-ifont" style="color:#CC4D00;font-size:20px;" title="主类目被屏蔽">&#xe606;</span>
			<?php elseif ($c['child_state']==2):?><span class="u-ifont" style="color:#FDAC2F;font-size:20px;" title="子类目被屏蔽">&#xe606;</span>
			<?php elseif ($c['state']==0):?><span class="u-ifont" style="color:#EADF08;font-size:20px;" title="帮助被屏蔽">&#xe606;</span>
			<?php elseif ($c['state']==1):?><span class="u-ifont" style="color:#00cc00;font-size:20px;" title="正常">&#xe603;</span>
			<?php endif;?>
			</td>
			<td><?php echo $c['id']?></td>
			<td><?php echo $c['title']?></td>
			<td><?php echo date('Y-m-d H:i:s', $c['dateline'])?></td>
			<?php if ($c['push']==='0'):?>
			<td class="ui-table-operate"><a class="push" href="javascript:void(0);" callback="reload" data-id="<?php echo $c['id']?>">推送</a></td>
			<?php elseif ($c['push']==='1'):?>
			<td class="help-table-push" style="cursor: default;"><a>已推送</a></td>
			<?php endif;?>
			<td class="ui-table-operate">
				<a href="<?php echo $c['link']?>" target="_blank">预览</a>
				<a class="a_edit" data-id="<?php echo $c['id']?>" href="<?php echo site_url('help/listing/'.$type_url.'?tag_type=3&id='.$c['id'])?>">编辑</a>
				<a href="<?php echo site_url('help/listing_action/delete/'.$c['id'])?>" type="confirm" title="确认删除该项？" callback="reload">删除</a>
			</td>
		<?php endforeach;?>
		</tr>
	</tbody>
	<?php if ($list_count):?>
	<tfoot>
		<tr>
			<td colspan="7" style="text-align:left;padding:12px;">
				<label><input class="checkAll" type="checkbox" name="" />&nbsp;全选</label>
				<input id="btn_block" callback="reload" type="button" value="屏蔽" class="ui-form-btnSearch" />
				<input id="btn_unblock" callback="reload" type="button" value="显示" class="ui-form-btnSearch" />
				<input id="btn_del" callback="reload" title="批量删除" type="button" value="删除" class="ui-form-btnSearch" />
				<!-- 分页 -->
				<div class="ui-paging floatR">
					<?php echo $pager;?>
				</div>
			</td>
		</tr>
	</tfoot>
	<?php endif;?>
</table>
