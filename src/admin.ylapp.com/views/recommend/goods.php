<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<?php 
//重新编排数组，并限制分类个数
function new_array($arr){
	$i=0;
	foreach($arr as $k=>$val){
		$new_arr[$val['id']] = $val['name'];
		$i++;
		if($i>=9)break;
	}
	return $new_arr;
}
?>
<script type="text/javascript">
function recommend_sort(){
	var data = {};
	var v = $("input.sort_order").each(function(i,o){
		data[$(o).attr('name')] = $(o).val();
	});
	$.post(SITE_URL+"recommend/set_sort", data, function(rs){
		if(AjaxFilter(rs)){
			load('<?php echo site_url(uri_string());?>', $('div#RecommendList'), {listonly:'yes'})
		}
	},'json');
	return false;
}

function submitcid(){
	$cidval=$("#goods_cid").val();
	$cat_type=$("#goods_cid").data('cat_type');
	window.location.href='<?php  echo site_url('recommend/goods/'); ?>/category_'+$cat_type+'_'+$cidval;
}
</script>
<div class="ui-box ui-box2 goods-list">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-body">
				<div class="ui-tab">
					<ul class="ui-tab-nav" id="tabs">
					<?php if('category' == $category){ ?>
						<?php if( ! empty($goods_categories['parent'])) { ?>
							<?php foreach ($goods_categories['parent'] as $k=>$item) { ?>
								<li class="ui-tab-item<?php if($cat_type == $item['id']){echo ' ui-tab-itemCurrent';}?>">
									<a href="<?php echo site_url('recommend/goods/category_'.$item['id'].'_'.$goods_categories['children'][$item['id']]['0']['id']);?>"><?php echo $item['name'];?></a>
								</li>
							<?php }?>
							<?php if( ! empty( $goods_categories['children'] )){ ?>
									&nbsp; &nbsp; <strong>类目</strong>：
									<?php echo form_dropdown ( 'goods_cid',new_array($goods_categories['children'][ $cat_type ]), array ($cid), 'id="goods_cid" data-cat_type="'.$cat_type.'" onchange="submitcid();"' );
								}echo ' &nbsp;  &nbsp; <font color="#FF0000">每个"类目"下最多可推荐 <strong>10</strong> 个活动</font>';
							?>
						<?php } ?>
					<?php }elseif(in_array($segment, array('advance', 'new'))){?>
						<li class="ui-tab-item<?php if($segment == 'new'){echo ' ui-tab-itemCurrent';}?>">
							<a href="<?php echo site_url('recommend/goods/new');?>">最新上线</a>
						</li>
						<li class="ui-tab-item<?php if($segment == 'advance'){echo ' ui-tab-itemCurrent';}?>">
							<a href="<?php echo site_url('recommend/goods/advance');?>">新品预告</a>
						</li>
					<?php }elseif(in_array($segment, array('fenqi', 'fenqi_new'))){?>
						<li class="ui-tab-item<?php if($segment == 'fenqi'){echo ' ui-tab-itemCurrent';}?>">
							<a href="<?php echo site_url('recommend/goods/fenqi');?>">正在进行</a>
						</li>
						<li class="ui-tab-item<?php if($segment == 'fenqi_new'){echo ' ui-tab-itemCurrent';}?>">
							<a href="<?php echo site_url('recommend/goods/fenqi_new');?>">新品预告</a>
						</li>
					<?php } ?>
					</ul>
					<div class="ui-tab-cont">
						<div class="ui-box ui-box2">
							<div class="ui-box-head">
								<form rel="div#searchList" method="get" action="<?php echo site_url('recommend/goods/'.$segment)?>">
									<span>活动搜索：</span>
									<select id="type" name="search_key">
										<option value="gid">活动编号</option>
										<option value="title">活动标题</option>
										<option value="uname">用户昵称</option>
										<option value="email">用户邮箱</option>
										<option value="uid">用户编号</option>
									</select>
									<input class="ui-form-text ui-form-textRed" name="search_val" />
									<input class="ui-form-btnSearch" type="submit" value="搜 索" />
									<?php if(in_array($recommend_type, array(1,2,6))): ?>
									<a href="<?php echo site_url('recommend/batch_push_goods?type_id='.$recommend_type .'&cate_id='. $cid)?>" type="form" callback="reload" style="float: right; margin-right: 10px;">批量推荐</a>
									<?php endif;?>
									<input type="hidden" name="recommend_type" value="<?php echo $recommend_type?>" />
									<input type="hidden" name="category" value="<?php echo $category?>" />
									<input type="hidden" name="cate_id" value="<?php echo $cid?>" />
									<input type="hidden" name="uri_string" value="<?php echo uri_string()?>" />
									<input type="hidden" name="search_goods" value="<?php echo $segment?>" />
								</form>
							</div>
							<div id="searchList" style="padding: 15px;">
								<?php $this->load->view('recommend/search_goods');?>
							</div>
						</div>
						<div id="RecommendList" style="margin-top: 20px;">
							<?php
								if ($recommend_type == 11) :
									$this->load->view ( 'recommend/goods_list_mpg' );
								 else :
									$this->load->view ( 'recommend/goods_list' );
								endif;
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>