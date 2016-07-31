<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 syslog"><div class="ui-box-outer"><div class="ui-box-inner"> 
<div class="ui-box-head syslog-head"><h2 class="ui-box-tit">温馨提示设置</h2></div>

<div class="ui-box-body">
	<div class="ui-box-inner">
		<ul class="ui-tab-nav" id="setting-tabs">
		   <?php foreach(Goods_model::$type_str as $type=>$name){?>
			   	<li class="ui-tab-item <?php if($goods_type==$type) echo 'ui-tab-itemCurrent'; ?>" data-url="<?php echo site_url('prompts/index/'.$type);?>">
					<a href="javascript:;"><?php echo $name;?></a>
				</li>
		   <?php }?>
		</ul>
	</div>
	<a callback="load('<?php echo site_url('prompts/index/'.$goods_type.'/'.$state);?>','div#goods_setting_general')" type="form" width="580" height="300" data-showform="yes" href="<?php echo site_url('prompts/add?goods_type='.$goods_type);?>" style="display:block; width:50px; color:#fff; text-align:center; margin:15px;" class="ui-form-button ui-form-buttonBlue">新增</a>
	<div id="content_main">
		<table class="ui-table">
			<thead>
				<tr>
					<th>标题</th>
	                <th>类型</th>
					<th>提示语</th>
	                <th>
	                       <select id="title_state" >
	                           <option value="0" <?php if($state==0) echo 'selected="selected"';?>>全部状态</option>
	                           <option value="1" <?php if($state==1) echo 'selected="selected"';?>>在用</option>
	                           <option value="2" <?php if($state==2) echo 'selected="selected"';?>>停用</option>
	                       </select>
	                </th>
	                <th>排序 [&nbsp;
								<a href="javascript:;" class="ui-operate-button ui-operate-buttonEdit" onclick="item_sort();">修改排序</a>]
	                </th>
	                <th>操作</th>
				</tr>
			</thead>
			<tbody class="ui-table-operate ">
				<?php if (is_array($prompts_list)):?>
				<?php foreach ($prompts_list as $k=>$v):?>
				<tr>
					<td><?php echo $v['title'];?></td>
	                <td><?php if($v['type']==1)echo '普通';elseif($v['type']==2) echo '可填写';else echo '未知';?></td>
					<td><?php echo $v['prompts'];?></td>
	                <td><?php if($v['state']==1)echo '在用';elseif($v['state']==2) echo '停用';else echo '未知';?></td>
	                <td><?php echo $v['sort']; ?>&nbsp;<input type="text" value="<?php echo $v['sort'];?>" data-id="<?php echo $v['id'];?>" name="sort"  style="width: 30px;"/></td>
					<td>
	               	 	<a callback="load('<?php echo site_url('prompts/index/'.$goods_type.'/'.$state);?>','div#goods_setting_general')" title="确认<?php if($v['state']==1){echo '隐藏'; }else{ echo '显示';}?>该项？" type="confirm" href="<?php echo site_url('prompts/hide').'?id='.$v['id'].'&state='.$v['state'];?>"><?php if($v['state']==1){echo '隐藏'; }else{ echo '显示';}?></a>
	              </td>
				</tr>
				<?php endforeach;?>
				<?php endif;?>
			</tbody>
		</table>
	</div>
</div>

</div></div></div>
<script type="text/javascript">

$(function(){
	$("#setting-tabs li").click(function(){
		var $this = $(this);

		// 取消批量全选的复选框
		$("input[type='checkbox'][name='appeal_ids[]']").attr('checked', false);
		load($this.data()['url'], "div#goods_setting_general");

	});

	//选择状态：在用/停用
	$('#title_state').change(function(){
	    var state = $('#title_state').val();
	    var url = $("#setting-tabs .ui-tab-itemCurrent").data()['url']+'/'+state;
	    load(url, "div#goods_setting_general");
	});
	
});
function item_sort(){
	
	var sorts = '';
	var flag = true;
	var v =  $("input[type='text'][name='sort']").each(function(i,obj){
		if (!isNaN($(obj).val()) && $(obj).val()>=0) {
			sorts +=  $(obj).data('id') +'_'+$(obj).val() + ';';
		}else {
			flag = false;
			alert('商品编号[' + $(obj).attr('name') + ']的排序必须是大于0的正整数');
			return false;
		}
	});

	var data = {'sorts':sorts , 'goods_type':'<?php echo $goods_type;?>'};
	
	if (flag) {
		$.post("<?php echo site_url('prompts/set_sort')?>", data, function(rs){
			if(AjaxFilter(rs)){
				load('<?php echo site_url(uri_string());?>',"div#goods_setting_general")
			}
		},'json');
	}
	
	return flag;
	
}
</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>