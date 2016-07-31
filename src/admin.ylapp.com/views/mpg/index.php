<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
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
		$.post(SITE_URL+"mpg/sort", data, function(rs){
			if(AjaxFilter(rs)){
				load('<?php echo site_url(uri_string());?>')
			}
		},'json');
	}
	return flag;
}
</script>
<div class="ui-box ui-box2 goods-list">
  <div class="ui-box-outer">
    <div class="ui-box-inner">
      <div class="ui-box-body">
        <div class="ui-tab">
          <ul class="ui-tab-nav" id="tabs">
            <li class="ui-tab-item<?php if($get_type == 'ongoing'){echo ' ui-tab-itemCurrent';}?>">
            	<a href="<?php echo site_url('mpg/ongoing');?>">正在进行</a></li>
            <li class="ui-tab-item<?php if($get_type == 'herald'){echo ' ui-tab-itemCurrent';}?>">
            	<a href="<?php echo site_url('mpg/herald');?>">新品预告</a></li>
            <li class="ui-tab-item<?php if($get_type == 'complete'){echo ' ui-tab-itemCurrent';}?>">
            	<a href="<?php echo site_url('mpg/complete');?>">已结算</a></li>
          </ul>
          <div class="ui-tab-cont">
            <div id="RecommendList">
				<style>
				.item-sort input{text-align: center;display:none;}
				</style>
				<div class="ui-box ui-box2">
					<div class="ui-box-head">
						<form rel="div#main-wrap" method="get" action="<?php echo site_url('mpg/'.$get_type)?>">
							<span>活动搜索：</span>
							<select id="type" name="search_key">
								<option value="gid"<?php if ($search_key=='gid'):echo ' selected=selected';endif;?>>活动编号</option>
								<option value="title"<?php if ($search_key=='title'):echo ' selected=selected';endif;?>>活动标题</option>
								<option value="uname"<?php if ($search_key=='uname'):echo ' selected=selected';endif;?>>商家名称</option>
							</select>
							<input class="ui-form-text ui-form-textRed" name="search_val" value="<?php echo $search_val;?>" />
							<input class="ui-form-btnSearch" type="submit" value="搜 索" />
						</form>
					</div>
					<div class="ui-box-body">
						<table class="ui-table">
							<thead>
								<tr>
									<th width="8%">活动编号</th>
									<th width="35%">活动标题</th>
									<th>商家名称</th>
									<th>数量\剩余</th>
									<th>网购价/折扣</th>
				                    <th>活动状态</th>
									<th>排序(倒序) [&nbsp;
										<a href="javascript:;" class="ui-operate-button ui-operate-buttonEdit" onclick="editSort(this);return false;">&nbsp;</a>
										<a style="display: none;" href="javascript:;" class="ui-operate-button ui-operate-buttonSave" onclick="saveSort();return false;">&nbsp;</a>
									]</th>
								</tr>
							</thead>
							<tbody>
								<?php if(isset($list) && is_array($list)):?>
								<?php foreach ($list as $k=>$goods):?>
								<tr id="row_<?php echo $goods['id'];?>">
									<td><?php echo $goods['gid'];?></td>
									<td><a href="<?php echo $this->config->item('domain_detail').$goods['gid'].'.html'?>" target="_blank">
										<span style="float:left; width:20%;">
											<img src="<?php echo image_url($goods['gid'], $goods['img'], '60x60');?>" width="50" />
										</span>
										<span style="float:left; width:80%;"><font color="#0066CC">
										<?php echo $goods['title'];?></font></span></a></td>
									<td><?php echo $goods['uname'];?><br /></td>
									<td><em><?php echo $goods['quantity'];?></em> 份\<em><?php echo intval($goods['remain_quantity']);?></em>份</td>
									<td><em><?php echo $goods['price'];?></em> 元/<em><?php echo $goods['discount'];?></em> 折</td>
				                    <td><?php echo goods_buying_status($goods['state'],$goods['remain_quantity'],$goods['wait_fill_num'],$goods['endtime'])?> </td>
									<td class="item-sort">
										<span><?php echo $goods['manual_sort'];?></span>
										<input size="2" class="ui-form-text ui-form-textRed sort_order" type="text" name="<?php echo $goods['gid'];?>" value="<?php echo $goods['manual_sort'];?>" />
									</td>
								</tr>
								<?php endforeach;?>
								<?php endif;?>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="7"><div class="ui-paging"><?php echo $pager;?></div></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<script>
					function editSort(o){
						$(o).hide();
						$('.item-sort span').hide();
						$(o).siblings().show();
						$('.item-sort input').show();
					}
				</script>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>