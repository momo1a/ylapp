<?php
	$CI = &get_instance();
	$admin = get_user();
	
	// 超级管理员直接获取所有菜单
	$columns = in_array($admin['id'], $CI->config->item('super_admin_uids'))
				? $CI->rbac_model->create_action_columns()
				: $CI->rbac_model->create_user_action_columns($admin['id']);
?>
<div id="js-sidebar" class="sidebar">
	<?php if ($columns):?>
		<?php $position = 1;?>
		<?php foreach ($columns as $module):?>
		<?php $css_class = menu_show2hidden($module['id'], $position)?'menu':'menu hidden';?>
		<div class="<?php echo $css_class;?>" data-js="<?php echo $module['code'];?>">
			<?php if ($module['columns']):?>
			<?php foreach ($module['columns'] as $column):?>
			<dl class="menu-item">
				<dt><?php echo $column['name']?></dt>
				<dd>
					<?php if ($column['column_actions']):?>
					<?php foreach ($column['column_actions'] as $action):?>
					<a href="<?php echo site_url($action['uri'])?>" <?php if(in_array($action['uri'], array('message/sent','message/send'))){echo 'noajax';}?>><?php echo $action['title'];?></a>
					<?php endforeach;?>
					<?php endif;?>
				</dd></dl>
			<?php endforeach;?>
			<?php endif;?>
		</div><!-- menu-<?php echo $module['code'];?> -->
		<?php $position ++;?>
		<?php endforeach;?>
	<?php endif;?>
	</div><!-- /sidebar -->