<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>

<script type="text/javascript">
$(function(){
	$("#LinkList .ui-operate-buttonEdit").click(function(){
		var id = parseInt($(this).data('id')) || 0;
		init_error_tips();
		if(id > 0){
			$('#row_'+id+' .default_show').hide();
			$('#row_'+id+' .default_mode').hide();
			$('#row_'+id+' .edit_mode').show();
			$('#row_'+id+' input').show();
		    $('#row_'+id+' select').show();
		}
		return false;
	});
	$("#LinkList #cancel").click(function(){cancel(this)});
	$("#LinkList #save").click(function(){save(this)});
});
function save(o, isnew){
	var id = parseInt($(o).data('id')) || 0;
	var isnew = isnew || 0;
	if(id > 0 || isnew){
		$('#link_form input[name=id]').val(isnew ? 0 : id);
		$('#link_form input[name=title]').val($('#row_'+id+' input[name=title]').val());
		$('#link_form input[name=content]').val($('#row_'+id+' input[name=content]').val());
		$('#link_form input[name=url]').val($('#row_'+id+' input[name=url]').val());
		$('#link_form input[name=differ]').val($('#row_'+id+' select[name=differ]').find('option:selected').val());
		$('#link_form input[name=sort]').val($('#row_'+id+' input[name=sort]').val());
		$('#link_form').submit();
	}
	return false;
}
function cancel(o, isnew){
	var id = parseInt($(o).data('id')) || 0;
	var isnew = isnew || 0;
	init_error_tips();
	if(id > 0 || isnew){
		$('#link_form input[name=id]').val('')
		$('#row_'+id+' .default_show').show();
		$('#row_'+id+' .default_mode').show();
		$('#row_'+id+' .edit_mode').hide();
		$('#row_'+id+' input').hide();
		$('#row_'+id+' select').hide();
	}
	return false;
}
function del(){
	init_error_tips();
	$('tr#row_0').remove()
}
function init_error_tips(){
	var for_title = $('span#for_title'),
	for_url = $('span#for_url');
	if(for_title.length > 0){
		for_title.remove();
	}
	if(for_url.length > 0){
		for_url.remove();
	}
}
function add(o){
	var cltr = $('tr#row_0'),
	clone = $('tr#row_00').clone(),
	target = $(o).parents('tr');
	init_error_tips();
	if(cltr.length <= 0){
		clone.attr('id', 'row_0').insertBefore(target).show();
		$('#row_0 input').show();
		$('#row_0 select').show();
		$('#row_0 .edit_mode').show();
		$('#row_0 .default_mode').hide();
	}
}
</script>
<style>
.edit_mode,.ui-table tr input,.ui-table tr select{display: none;}
.ui-table tr input{text-align: center;}
.ui-table tr input[name="title"]{width: 20em;}
.ui-table tr input[name="sort"]{width: 3em;}
</style>

<div class="ui-box ui-box2 advertisement"><div class="ui-box-outer"><div class="ui-box-inner">
<div class="ui-tab">
	<ul class="ui-tab-nav" id="tabs">
	<?php if(in_array($type_uri, array('notice', 'seller_rule', 'buyer_rule','headernotice'))){?>
    	<li class="ui-tab-item<?php if($type_uri == 'headernotice'){echo ' ui-tab-itemCurrent';}?>"><a href="<?php echo site_url('link/index/headernotice');?>">页头公告条</a></li>
	<li class="ui-tab-item<?php if($type_uri == 'notice'){echo ' ui-tab-itemCurrent';}?>"><a href="<?php echo site_url('link/index/notice');?>">公告</a></li>
	<li class="ui-tab-item<?php if($type_uri == 'seller_rule'){echo ' ui-tab-itemCurrent';}?>"><a href="<?php echo site_url('link/index/seller_rule');?>">商家规则</a></li>
	<li class="ui-tab-item<?php if($type_uri == 'buyer_rule'){echo ' ui-tab-itemCurrent';}?>"><a href="<?php echo site_url('link/index/buyer_rule');?>">买家规则</a></li>
	<?php }?>
	</ul>
	<div class="ui-tab-cont">
	<table id="LinkList" class="ui-table">
		<thead>
			<tr>
			<?php if($type_uri == 'headernotice'):?>
				<th style="width:25%;">标题</th>
				<th style="width:25%;">内容(用于app公告)</th>
				<th style="width:15%;">链接</th>
                <th style="width:10%;">平台类型</th>
				<th style="width:10%;">排序</th>
				<th style="width:15%;">操作</th>
				<?php else:?>
				<th style="width:25%;">标题</th>
				<th style="width:25%;">链接</th>
				<th style="width:20%;">平台类型</th>
				<th style="width:15%;">排序</th>
				<th style="width:15%;">操作</th>
				<?php endif;?>
			</tr>
		</thead>
		<tbody>
			<?php if(is_array($list)): foreach ($list as $k=>$v):?>
			<tr id="row_<?php echo $v['id'];?>">
				<td>
					<input value="<?php echo $v['title'];?>" class="ui-form-text ui-form-textRed" name="title" />
					<span class="default_show"><a href="<?php echo $v['url'];?>" target="_blank"><?php echo $v['title'];?></a></span>
				</td>
				<?php if($type_uri == 'headernotice'):?>
				<td>
					<input value="<?php echo $v['content'];?>" class="ui-form-text ui-form-textRed" name="content" />
					<span class="default_show"><?php echo $v['content'];?></span>
				</td>
				<?php endif;?>
				<td>
					<input value="<?php echo $v['url'];?>" class="ui-form-text ui-form-textRed" name="url" />
					<span class="default_show"><?php echo $v['url'];?></span>
				</td>
                <td>
                     <select name="differ">
                     <option value="1"  <?php if($v['differ']==1)echo ' selected="selected"';?>>PC 端</option>
                     <option value="2" <?php if($v['differ']==2)echo ' selected="selected"';?>>APP 端</option>
                   </select>
					<span class="default_show"><?php if($v['differ']==2)echo 'APP 端';else echo 'PC 端';?></span>
				</td>
				<td>
					<input value="<?php echo $v['sort'];?>" class="ui-form-text ui-form-textRed" name="sort" />
					<span class="default_show"><?php echo $v['sort'];?></span>
				</td>
				<td>
					<span class="edit_mode">
						<a id="save" data-id="<?php echo $v['id'];?>" href="javascript:void(0);" class="ui-form-button ui-form-buttonBlue">保存</a>
						<a id="cancel" data-id="<?php echo $v['id'];?>" href="javascript:void(0);" class="ui-form-button ui-form-buttonBlue">取消</a>
					</span>
					<span class="default_mode">
						<a href="javascript:void(0);" class="ui-operate-button ui-operate-buttonEdit" data-id="<?php echo $v['id'];?>">编辑</a>
						<a href="<?php echo site_url('link/delete')?>" type="confirm" title="确定要删除当前链接吗？" data-id="<?php echo $v['id'];?>" data-type="<?php echo $v['type'];?>" callback="reload();" class="ui-operate-button ui-operate-buttonDel">删除</a>
					</span>
				</td>
			</tr>
			<?php endforeach; endif;?>
			<tr>
				<td><a href="javascript:void(0);" onclick="add(this)">添加+</a></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
                <td></td>
			</tr>
			<tr id="row_00" style="display: none;">
				<td><input value="" class="ui-form-text ui-form-textRed" name="title" /></td>
<?php if($type_uri == 'headernotice'):?><td><input value="" class="ui-form-text ui-form-textRed" name="content" /></td><?php endif;?>
				<td><input value="" class="ui-form-text ui-form-textRed" name="url" /></td>
                <td>
                   <select name="differ">
                     <option value="1">PC 端</option>
                     <option value="2">APP 端</option>
                   </select>
                </td>
				<td><input value="" class="ui-form-text ui-form-textRed" name="sort" /></td>
				<td>
					<span class="edit_mode">
						<a id="save" data-id="0" onclick="save(this, 1)" href="javascript:void(0);" class="ui-form-button ui-form-buttonBlue">保存</a>
						<a id="cancel" data-id="0" onclick="del()" href="javascript:void(0);" class="ui-form-button ui-form-buttonBlue">取消</a>
					</span>
					<span class="default_mode">
						<a href="javascript:void(0);" data-id="0" class="ui-operate-button ui-operate-buttonEdit">编辑</a>
						<a href="javascript:void(0);" data-id="0" class="ui-operate-button ui-operate-buttonDel">删除</a>
					</span>
				</td>
			</tr>
		</tbody>
	</table>
	</div>
	<form id="link_form" type="ajax" method="post" callback="reload" action="<?php echo site_url('link/save')?>">
		<input type="hidden"  name="id" value="" />
		<input type="hidden" name="type" value="<?php echo $type;?>" />
		<input type="hidden" name="title" data-rule="required" data-msg="请输入标题"/>
		<?php if($type_uri == 'headernotice'):?><input type="hidden" name="content" data-rule="required" data-msg="请输入内容"/><?php endif;?>
		<input type="hidden" name="url" data-rule="required|url" data-msg="请输入链接地址|链接地址错误"/>
        <input type="hidden" name="differ" data-rule="required" data-msg="请选择平台类型"/>
		<input type="hidden" name="sort" prefix="noempty" data-rule="number" data-msg="排序只能输入整数"/>
	</form>
	<div class="ui-box ui-box2  advertisement-add" style="display: none;">
		<div class=" ui-box-head">添加新链接</div>
		<form id="" type="ajax" method="post" callback="reload" action="<?php echo site_url('link/save')?>" style="padding:15px;">
			<ul>
				<li><span>标题:</span><input class="ui-form-text ui-form-textRed" name="title" data-rule="required" data-msg="请输入标题"/><span id="for_title" style="width: auto;text-align:left;"></span></li>
				<li><span>内容:</span><input class="ui-form-text ui-form-textRed" name="content" data-rule="required" data-msg="请输入标题"/><span id="for_content" style="width: auto;text-align:left;"></span></li>
				<li><span>链接地址:</span><input class="ui-form-text ui-form-textRed" name="url" data-rule="required|url" data-msg="请输入链接地址|链接地址错误" style="width: 30em;" /><span id="for_url" style="width: auto;text-align:left;"></span></li>
				<li>
                <span>平台类型:</span>
                  <select name="differ">
                  <option value="1">PC 端</option>
                  <option value="2">APP 端</option>
                  </select>
                </li>
                <li><span>排序:</span><input class="ui-form-text ui-form-textRed" name="sort" prefix="noempty" data-rule="number" data-msg="排序只能输入整数"/><span id="for_sort" style="width: auto;text-align:left;"></span></li>
			</ul>
			<input class="ui-form-button ui-form-buttonBlue" type="submit" value="保存" />
			<input class="ui-form-button ui-form-buttonBlue" type="reset" onclick="$(this).next('input').val(0);" value="取消" />
			<input type="hidden"  name="id" value="" />
			<input type="hidden" name="type" value="<?php echo $type;?>" />
		</form>
	</div>
</div>
</div></div></div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>