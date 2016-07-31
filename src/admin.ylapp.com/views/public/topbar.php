<?php
/**
 * 更新
 * 顶部导航由数据获取
 *
 * @author 韦明磊<nicolaslei@163.com>
 * @date 2014.1.21
 */
$CI =& get_instance();

$admin = get_user();
$modules = in_array($admin['id'], $CI->config->item('super_admin_uids'))
			? $CI->rbac_model->find_action_categorys()
			: $CI->rbac_model->find_user_modules($admin['id']);
?>
<div class="topbar">
	<div class="topbar-wrap">
		<p class="topbar-user">
			<a href="<?php echo $this->config->item('domain_mobile');?>">手机版</a>
			<a class="topbar-user-name" href="javascript:;" onclick="return false;"><?php echo $admin['name'];?></a>
			<a href="<?php echo $this->config->item('url_logout');?>">退出</a>
		</p><!-- /topbar-user -->
		<div class="topbar-nav">
			<a href="<?php echo site_url('recommend/make_index');?>" type="post">更新首页</a>
			<a href="http://www.zhonghuasuan.com">众划算首页</a>
			<a href="http://www.shikee.com">试客联盟首页</a>
			<a href="http://www.hulianpay.com">互联支付</a>
			<a href="http://help.shikee.com">帮助中心</a>
		</div><!-- /topbar-nav -->
	</div>
</div>
<div id="js-header-nav" class="header-nav">
		<ul class="clearfix">
			<li class="nav-admin"><?php echo $admin['name'];?></li>
			<?php
			$position = 1;
			foreach ($modules as $module):
			?>
			<li class="<?php echo $module['css'];?><?php if(menu_show2hidden($module['id'], $position)): echo ' nav-current'; endif;?>" data-js="<?php echo $module['code'];?>">
				<?php echo $module['name'];?></li>
			<?php
				$position ++;
			endforeach;
			unset($modules);
			?>
		</ul>
</div>