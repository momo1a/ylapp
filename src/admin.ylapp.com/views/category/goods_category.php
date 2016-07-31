<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<script type="text/javascript">
$(function(){
	$("#category_lv1").change(function(){ /* 切换类目 */
		load("<?php echo site_url('category/goods_category')?>?pid=" + $(this).val());
	});
});
function loadcate()
{
	load("<?php echo site_url('category/goods_category')?>");
}
MyRule.sort = /^\d*$/;
MyRule.discount = /^\d*(\.\d)?$/;
</script>

<div class="ui-box ui-box2 goods-category"><div class="ui-box-outer"><div class="ui-box-inner">


<div class="ui-box-head goods-category-head">
	<span>类目:</span>
	<select id="category_lv1">
	    <?php foreach ($cate_level1 as $cate):?>
		<option value="<?php echo $cate['id']?>" <?php if($pid == $cate['id']):?>selected="selected"<?php endif;?>><?php echo $cate['name']?></option>
		<?php endforeach;?>
	</select>
	<a href="<?php echo site_url('category/add_goods_category'); ?>" type="form" callback="reload" width="580" height="220" data-id="0" data-pid="0" class="ui-operate-button ui-operate-buttonAdd js-add-cate">添加分类目</a>
</div>
<div class="ui-box-body">
	<dl class="cate-list">
		<dt data-id="<?php echo $current_cate['id'];?>">
			<span class="cate-name"><?php echo $current_cate['name']; ?></span>
			<div class="cate-btngroup">
				<a href="<?php echo site_url('category/add_goods_category'); ?>" type="form" callback="reload" width="580" height="220" data-pid="<?php echo $current_cate['id'];?>"  class="ui-operate-button ui-operate-buttonAdd js-add-subs">添加子分类</a>
				<a href="<?php echo site_url('category/edit_goods_category'); ?>" type="form" callback="reload" width="580" height="220" data-id="<?php echo $current_cate['id'];?>"  class="ui-operate-button ui-operate-buttonEdit js-edit">编辑</a>
				<a <?php if(count($cate_level2)):?>href="javascript:;" onclick="return false;" <?php else:?> href="<?php echo site_url('category/delete_goods_category')?>" type="confirm" callback="loadcate" title="确定要删除该分类吗？删除后可能导致商品无法读取！并且数据不可恢复!" data-id="<?php echo $current_cate['id'];?>"<?php endif;?> class="ui-operate-button <?php if(count($cate_level2)):?>ui-operate-buttonDelDisable<?php else:?>ui-operate-buttonDel js-del<?php endif;?>">删除</a>
			</div>
		</dt>
		<?php foreach ($cate_level2 as $k=>$v):?>
		<dd data-id="<?php echo $v['id'];?>">
		    <span class="subcate-name"><?php echo $v['name']; ?></span>
			<div class="subcate-btngroup">
			    <a href="<?php echo site_url('category/edit_goods_category'); ?>" type="form" callback="reload" width="580" height="220" data-id="<?php echo $v['id'];?>" class="ui-operate-button ui-operate-buttonEdit">编辑</a>
			    <a href="<?php echo site_url('category/delete_goods_category')?>" type="confirm" callback="reload" title="确定要删除该分类吗？删除后可能导致商品无法读取！并且数据不可恢复!" data-id="<?php echo $v['id'];?>" class="ui-operate-button ui-operate-buttonDel">删除</a>
			</div>
		</dd>
		<?php endforeach;?>

	</dl>
</div>
</div></div></div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>