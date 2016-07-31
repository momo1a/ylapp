<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>

<script type="text/javascript">
$(function(){
	//类目切换
	$("#cate_parent").change(function(){
		load("<?php echo site_url('help/category/'.$type_url)?>"+ "/" + $(this).val()); //最后一个参数为当前主类id值
	});
});
//删除主类时调用
function loadcate(){
	load("<?php echo site_url('help/category/'.$type_url)?>");
}
</script>

<div class="ui-box ui-box2 help-category"><div class="ui-box-outer"><div class="ui-box-inner">
	
	<div class="ui-box-head help-category-head">
		<span>主类目:</span>
		<select id="cate_parent">
			<?php if (count($cate_parents)):?>
			
			<?php foreach ($cate_parents as $cate):?>
		    <option value="<?php echo $cate['id']?>" <?php if ($id == $cate['id']):?>selected="selected"<?php endif;?>><?php echo $cate['name']?></option>
		    <?php endforeach;?>
		    
		    <?php else :?>
		    <option value="0">请添加主类</option>
		    <?php endif;?>
		</select>
		<a href="<?php echo site_url('help/category_action/add/'.$type)?>" class="ui-operate-button ui-operate-buttonAdd" type="form" width="450" callback="reload">添加主类目</a>
	</div>
	
	<?php if (count($cate_parents)): //判断有值则遍历?>
	<div class="ui-box-body">
		<dl class="cate-list">
			<!-- 主类 -->
			<dt>
				<span class="cate-name"><?php echo $current_cate_parent['name']?></span>
				<div class="cate-btngroup">
					<a href="<?php echo site_url('help/category_action/add/'.$type.'/'.$current_cate_parent['id'])?>"class="ui-operate-button ui-operate-buttonAdd" type="form" width="450" callback="reload">添加子分类</a>
					<a href="<?php echo site_url('help/category_action/edit/'.$current_cate_parent['id'])?>" class="ui-operate-button ui-operate-buttonEdit" type="form" callback="reload">编辑</a>
					<?php if( $current_cate_parent['state']== 1):?>
					<a href="<?php echo site_url('help/callback_category_block/'.$current_cate_parent['id'].'/2');?>" class="ui-operate-button ui-operate-buttonShield" type="confirm" title="确定要屏蔽该主类目吗？" callback="reload" >屏蔽</a>
					<?php elseif($current_cate_parent['state'] == 2):?>
					<a href="<?php echo site_url('help/callback_category_block/'.$current_cate_parent['id'].'/1');?>" class="ui-operate-button ui-operate-buttonShield" type="confirm" title="解屏后，该主类目、及其下的子类目将在前台显示。确定要解屏吗？"  callback="reload" >解屏</a>
					<?php else:?>
					<a href="#" class="ui-operate-button ui-operate-buttonShieldDisable">未知状态</a>
					<?php endif;?>
					

					<?php if (count($cate_childs)):?>
					<a class="ui-operate-button ui-operate-buttonDelDisable">删除</a>
					<?php else:?>
					<a href="<?php echo site_url('help/category_action/delete/'.$current_cate_parent['id'])?>" class="ui-operate-button ui-operate-buttonDel" type="confirm" callback="loadcate" title="确定要删除该分类吗？">删除</a>
					<?php endif;?>
				</div>
			</dt>
			
			<!-- 子类 -->
			<?php if (count($cate_childs)):?>
			<?php foreach ($cate_childs as $cate):?>
			<dd>
				<span class="subcate-name"><?php echo $cate['name']?></span>
				<div class="subcate-btngroup">
					
					<a href="<?php echo site_url('help/category_action/edit/'.$cate['id'])?>" class="ui-operate-button ui-operate-buttonEdit" type="form" callback="reload">编辑</a>
					<?php if($cate['state'] == 1):?>
					<a href="<?php echo site_url('help/callback_category_block/'.$cate['id'].'/2')?>" class="ui-operate-button ui-operate-buttonShield" type="confirm" title="确定要屏蔽该分类吗？" callback="reload" >屏蔽</a>
					<?php elseif($cate['state'] == 2):?>
					<a href="<?php echo site_url('help/callback_category_block/'.$cate['id'].'/1')?>"  class="ui-operate-button ui-operate-buttonShield" type="confirm" title="解屏后，该子类目将在前台显示。确定要解屏该分类吗？" callback="reload" >解屏</a>
					<?php else:?>
					<a href="#" class="ui-operate-button ui-operate-buttonShieldDisable">未知状态</a>
					<?php endif;?>
					
					<a href="<?php echo site_url('help/category_action/delete/'.$cate['id'])?>" class="ui-operate-button ui-operate-buttonDel" type="confirm" title="确定要删除该分类吗？" callback="reload">删除</a>
				</div>
			</dd>
			<?php endforeach;?>
			<?php endif;?>
			
		</dl>
	</div>
	<?php endif;?>

</div></div></div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>