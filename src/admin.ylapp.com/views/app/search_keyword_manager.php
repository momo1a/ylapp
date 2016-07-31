<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
   <div class="ui-box ui-box2 syslog"><div class="ui-box-outer"><div class="ui-box-inner">
     <div class="ui-box-head syslog-head"><h2 class="ui-box-tit">搜索关键字管理</h2></div>
        <div class="ui-box-body">    
			<style>
			.item-sort input{text-align: center;display:none;}
			</style>
				<form rel="div#main-wrap" method="get" action="<?php echo site_url('app_content_manager/show_keyword')?>">
				<span style="margin-left:15px; ">关键字：</span>
				<input class="ui-form-text ui-form-textRed" name="search_key" value="<?php echo $key?>" />
				<input class="ui-form-btnSearch" type="submit" value="搜 索" />							
				<a  callback="reload" style="margin: 0px 15px 0 0;float: right;text-decoration : none;"><input type="button" class="ui-form-btnSearch" id="export" value="导出"></a>						
                <a href="<?php echo site_url('app_content_manager/keyword_add')?>" type="form" callback="reload" style="margin: 0px 7px 0 0;float: right;text-decoration : none;"><input class="ui-form-btnSearch"  type="button" value="新增" /></a>						 
				</form>				
			<table class="ui-table" style="margin-top:15px; ">
				<thead>
				<tr>
					<th >序号</th>					
					<th >关键字<br /></th>
					<th >搜索次数</th>
					<th >web</th>
					<th >iOS</th>
					<th >Android</th>
					<th >排名</th>			
		            <th >排名变化</th>
					<th >编辑排序</br> [&nbsp;
					<a href="javascript:;" class="ui-operate-button ui-operate-buttonEdit" onclick="editSort(this);return false;">&nbsp;</a>
					<a style="display: none;" href="javascript:;" class="ui-operate-button ui-operate-buttonSave" onclick="saveSort();return false;">&nbsp;</a>
					]</th>
				</tr>
					</thead>
					<tbody>							
				<?php foreach ($all_keywords['list'] as $k=>$v):?>						
				<tr id="row_<?php echo $v['id']?>">
					<td><?php echo $v['id']?></td>					
					<td><?php echo $v['keyword'];?></td>
					<td><?php echo $v['search_num']?></td>
					<td><?php echo $v['web_count']?></td>
					<td><?php echo $v['ios_count']?></td>
					<td><?php echo $v['android_count']?></td>
					<td><?php echo $v['sort']?></td>
					<?php if ($v['sort_change']>0):?>
				    <td class="up_sort"><?php echo $v['sort_change']?> </td>
				    <?php elseif ($v['sort_change']<0):?>
				    <td class="down_sort"><?php echo abs($v['sort_change'])?> </td>
				    <?php else :?>
				    <td class="no_change"><?php echo $v['sort_change']?> </td>
				    <?php endif;?>
					<td class="item-sort">
					<span><?php echo $v['sort_val'];?></span>
					<input size="2" class="ui-form-text ui-form-textRed sort_order" type="text" name="<?php echo $v['id']?>" value="<?php echo $v['sort_val'];?>" />
					</td>
					</tr>
				<?php endforeach;?>				
					</tbody>
				<tfoot>
				<tr>
					<td colspan="9"><div class="ui-paging"><?php echo $pager;?></div></td>
				</tr>
				</tfoot>
		 	</table>
	   </div>
	 </div>
   </div>
 </div>
<script type="text/javascript">
	//导出活动
 $("#export").click(function(){
	$("form:eq(0)").removeAttr('rel'); 
	$("form:eq(0)").attr("action", "<?php echo site_url('app_content_manager/export_keyword');?>").submit();
 });
</script>
<script type="text/javascript">
function saveSort(){
	var data = {sorts:{}};
	var flag = true;
	var v = $("input.sort_order").each(function(i,obj){
		if (!isNaN($(obj).val()) && $(obj).val()>=0) {
			data['sorts'][i] = $(obj).attr('name')+'_'+$(obj).val();
		}else {
			flag = false;
			alert('商品编号[' + $(obj).attr('name') + ']的排序必须是大于0的正整数');
			return false;
		}
	});
	if (flag) {
		$.post(SITE_URL+"app_content_manager/sort", data, function(rs){
			if(AjaxFilter(rs)){
				load('<?php echo site_url(uri_string());?>')
			}
		},'json');
	}
	return flag;
}

	function editSort(o){
		$(o).hide();
		$('.item-sort span').hide();
		$(o).siblings().show();
		$('.item-sort input').show();
	}
</script>	
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>				