<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<script type="text/javascript">
$(function(){
	$("#LinkList .editBtn").on('click', function(){
		var data = $(this).data();
		$.each(data, function(i, o){
			var ele = $("#ad_form input[name='"+i+"']");
			if(i == 'floor'){
				$('#floor_select').attr('value', o);
				ele.attr('value', o);
			}else if(ele.length > 0){
				$("#ad_form input[name='"+i+"']").val(o);
			}else if (i == 'enable') {
				$("#ad_form select").val(o);
			}
		});
		return false;
	});
	$('#floor_select').on('change', function(){
		var flr_input = $("#ad_form input[name='floor']"),
		is_full = Number(parseInt($(this).data('full'))) && 1,
		flr_val = parseInt($(this).val()) || 0;
		
		flr_input.val(is_full ? '-1' : flr_val);
	});
});

//排序修改
function setads_sort(){
	var data = {};
	var v = $("input.sort_order").each(function(i,o){
		data[$(o).attr('name')] = $(o).val();
	});
	$.post(SITE_URL+"advertisement/setads_sort", data, function(rs){
		if(AjaxFilter(rs)){
			load('<?php echo site_url(uri_string());?>', $('div#adslink'), {listonly:'yes'})
		}
	},'json');
	return false;
}
function adseditsort(o){
	$(o).hide();
	$('.item-sort span').hide();
	$(o).siblings().show();
	$('.item-sort input').show();
}
</script>
<div class="ui-box ui-box2 advertisement" id="adslink">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
		<?php if(in_array($adv_type,array('slider','indexlogo','headerxia','headerfixed','rightads','bannerads','rightfloat','floorleft'))){ ?>
			<ul class="ui-tab-nav" id="tabs">
				<li class="ui-tab-item <?php if($adv_type=='slider')echo 'ui-tab-itemCurrent'; ?>">
					<a href="<?php echo site_url('advertisement/index/slider');?>">首页焦点广告</a>
				</li>
				<li class="ui-tab-item <?php if($adv_type=='indexlogo')echo 'ui-tab-itemCurrent'; ?>">
					<a href="<?php echo site_url('advertisement/index/indexlogo');?>">优质商家推荐</a>
				</li>
				<li class="ui-tab-item <?php if($adv_type=='headerxia')echo 'ui-tab-itemCurrent'; ?>">
					<a href="<?php echo site_url('advertisement/index/headerxia');?>">页头下拉广告</a>
				</li>
				<li class="ui-tab-item <?php if($adv_type=='rightads')echo 'ui-tab-itemCurrent'; ?>">
					<a href="<?php echo site_url('advertisement/index/rightads');?>">招商入口下幻灯片广告</a>
				</li>
				<li class="ui-tab-item <?php if($adv_type=='bannerads')echo 'ui-tab-itemCurrent'; ?>">
					<a href="<?php echo site_url('advertisement/index/bannerads');?>">通栏广告</a>
				</li>
				<li class="ui-tab-item <?php if($adv_type=='rightfloat')echo 'ui-tab-itemCurrent'; ?>">
					<a href="<?php echo site_url('advertisement/index/rightfloat');?>">右侧广告</a>
				</li>
				<li class="ui-tab-item <?php if($adv_type=='floorleft')echo 'ui-tab-itemCurrent'; ?>">
					<a href="<?php echo site_url('advertisement/index/floorleft');?>">首页楼层广告</a>
				</li>
			</ul>
		<?php } ?>
		<div style="height: auto; border-top: 1px solid #CCCCCC; color: #00F; padding: 10px;"><?php echo $imgsize; ?></div>
			<table id="LinkList" class="ui-table">
				<thead>
					<tr>
						<th style="width: 10%;">
							<?php if($adv_type != 'floorleft'){?>
							排序<br /> [
							<a href="javascript:;" class="ui-operate-button ui-operate-buttonEdit" onclick="adseditsort(this);return false;">编辑排序</a>
							<a style="display: none;" href="javascript:;" class="ui-operate-button ui-operate-buttonSave" onclick="setads_sort();return false;">保存排序</a>
							]
							<?php }else{?>
							楼层
							<?php }?>
						</th>
						<th style="width: 10%;">标题</th>
						<th style="width: 35%;">图片地址</th>
						<th style="width: 25%;">链接地址</th>
						<th>是否可用</th>
						<th style="width: 10%;">操作</th>
					</tr>
				</thead>
				<tbody>
				<?php $floor_arr = $floor_select = array('11'=>'F1①', '12'=>'F1②', '21'=>'F2①', '22'=>'F2②', '31'=>'F3①', '32'=>'F3②');?>
			<?php if(is_array($list)): foreach ($list as $k=>$v):?>
				<?php 
					if($adv_type == 'floorleft'){
						if(isset($floor_arr[$v['sort']])) unset($floor_arr[$v['sort']]);
					}
				?>
				<tr id="row_<?php echo $v['id'];?>">
					<td class="item-sort">
						<?php if($adv_type != 'floorleft'){?>
						<span><?php echo $v['sort'];?></span>
						<input size="2" class="ui-form-text ui-form-textRed sort_order" style="display: none; text-align: center;" type="text" name="id_<?php echo $v['id'];?>" value="<?php echo $v['sort'];?>" />
						<?php }else{?>
							<?php echo (isset($floor_select[$v['sort']]) ? '<b>'.$floor_select[$v['sort']].'</b>' : '');?>
						<?php }?>
					</td>
					<td><?php echo $v['title'];?></td>
					<td><?php echo $v['img'];?></td>
					<td><?php echo $v['link'];?></td>
					<td><?php echo $v['enable'] == 1?'是':'否';?></td>
					<td>
						<a href="javascript:;" class="ui-operate-button ui-operate-buttonEdit editBtn" data-id="<?php echo $v['id'];?>" <?php echo $adv_type == 'floorleft' ? 'data-floor="'.$v['sort'].'"' : "data-enable='".$v['enable']."'";?> data-title="<?php echo $v['title'];?>" data-link="<?php echo $v['link'];?>" data-imgurl="<?php echo $v['img'];?>" data-sort="<?php echo $v['sort'];?>" data-start_time="<?php echo date("Y-m-d H:i:s", $v['starttime']);?>" data-end_time="<?php echo date("Y-m-d H:i:s", $v['endtime']);?>" data-type="<?php echo $v['type']?>" data-style="<?php echo $v['style'];?>" data-width="<?php echo $v['width'];?>" data-height="<?php echo $v['height'];?>">编辑</a>
						<a href="<?php echo site_url('advertisement/delete')?>" type="confirm" title="确定要删除当前链接吗？" callback="reload" class="ui-operate-button ui-operate-buttonDel" data-id="<?php echo $v['id'];?>">删除</a>
					</td>
				</tr>
			<?php endforeach; endif;?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="6"></td>
					</tr>
				</tfoot>
			</table>
			<div class="ui-box ui-box2 advertisement-add">
				<div class="ui-box-head">
					<span class="">添加/编辑链接</span>
				</div>
				<form id="ad_form" type="ajax" callback="reload" method="post" action="<?php echo site_url('advertisement/save_slider')?>" enctype="multipart/form-data">
					<ul>
						<li>
							<span>标题:</span>
							<input class="ui-form-text ui-form-textRed" name="title" data-rule="required" data-msg="请输入标题" />
							<span id="for_title" style="width: auto;"></span>
						</li>
						<?php if($adv_type == 'floorleft'){?>
						<li>
							<span>楼层:</span>
							<input name="floor" type="hidden" value="0" data-rule="required" data-msg="请选择所在楼层" />
							<select id="floor_select" data-full="<?php echo empty($floor_arr) ? '1' : '0';?>">
								<option value="0">请选择所在楼层</option>
								<?php foreach ($floor_select as $k=>$value) {?>
									<option value="<?php echo $k;?>"><?php echo $value;?></option>
								<?php }?>
							</select>
							<span id="for_floor" style="width: auto;"></span>
						</li>
						<?php }?>
						<li>
							<span>链接地址:</span>
							<input class="ui-form-text ui-form-textRed" name="link" data-rule="required|url" data-msg="链接地址不能为空|链接地址格式错误" />
							<span id="for_link" style="width: auto;"></span>
						</li>
						<li>
							<span>远程图片地址:</span>
							<input class="ui-form-text ui-form-textRed" name="imgurl" id="imgurl" onfocus="$('#for_img').hide()" prefix="noempty" data-rule="url" data-msg="远程图片地址格式错误" />
							<span id="for_imgurl" style="width: auto;"></span>
							<p>(以"http://"开头,如果不上传将以这里的地址为主，上传就以上传的图片为主)</p>
						</li>
						<li>
							<span>图片地址:</span>
							<input type="file" value="浏览" name="img" prefix="return $.trim($('#imgurl').val()).length<1" data-rule="required" data-msg="请输入远程图片地址或选择上传图片" />
							<span id="for_img" style="width: auto;"></span>
						</li>
						<li>
							<span>图片宽高:</span>
							<input title="图片宽度" class="ui-form-text ui-form-textRed" name="width" prefix="noempty" data-rule="number" data-msg="图片高度只能输入数字" />
							*
							<input title="图片高度" class="ui-form-text ui-form-textRed" name="height" prefix="noempty" data-rule="number" data-msg="图片宽度只能输入数字" />
							<span id="for_width" style="width: auto;"></span>
							<span id="for_height" style="width: auto;"></span>
							<p>(不填则自动以图片实际高宽为主)</p>
						</li>
						<li>
							<span>广告背景:</span>
							<input class="ui-form-text ui-form-textRed" name="style" prefix="noempty" />
							( 如：#FF0000 )
						</li>
						<li>
							<span>排序:</span>
							<input class="ui-form-text ui-form-textRed" size="5" name="sort" prefix="noempty" data-rule="number" data-msg="排序只能输入数字" />
						</li>
						<li>
							<span>起始时间:</span>
							<input id="start_time" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly value="<?php echo $this->input->get_post('startTime');?>" data-dateFmt='yyyy-MM-dd HH:mm:ss' name="start_time" data-rule="required|date" data-msg="请选择起始时间|格式错误">
							<span id="for_start_time" style="width: auto;"></span>
						</li>
						<li>
							<span>结束时间:</span>
							<input id="end_time" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly value="<?php echo $this->input->get_post('endTime');?>" data-dateFmt='yyyy-MM-dd HH:mm:ss' name="end_time" data-rule="required|date" data-msg="请选择结束时间|格式错误">
							<span id="for_end_time" style="width: auto;"></span>
						</li>
						<li>
							<span>是否可用:</span>
							<select name="enable">
								<option value="1">可用</option>
								<option value="0">不可用</option>
							</select>
						</li>
					</ul>
					<input class="ui-form-button ui-form-buttonBlue" type="submit" value="保存" />
					<input class="ui-form-button ui-form-buttonBlue" type="reset" onclick="$(this).next('input').val(0);" value="取消" />
					<input type="hidden" name="id" value="" />
					<input type="hidden" name="type" value="<?php echo $type;  ?>" />
				</form>
			</div>
		</div>
	</div>
</div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>