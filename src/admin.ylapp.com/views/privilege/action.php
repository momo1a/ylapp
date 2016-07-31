<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<!-- 韦明磊<nicolaslei@163.com>修改于2014.1.22 -->
<div class="ui-box ui-box2">
	<div class="ui-box-outer">
		<div class="ui-box-inner"> 
			<div class="ui-box-head">
				<span class="ui-box-tit">操作管理</span>
				<a type="form" href="<?php echo site_url('privilege/action_form_module');?>" width="400" height="200" callback="reload" validate="validateAction" class="ui-operate-button ui-operate-buttonAdd" type="button">添加模块[一级]</a>
				<a type="form" href="<?php echo site_url('privilege/action_form_column');?>" width="400" height="200" callback="reload" validate="validateAction" class="ui-operate-button ui-operate-buttonAdd" type="button">添加栏目[二级]</a>
				<a type="form" href="<?php echo site_url('privilege/action_form');?>" width="400" height="200" callback="reload" validate="validateAction" class="ui-operate-button ui-operate-buttonAdd" type="button">添加操作[三级]</a></div>
			<div class="ui-box-body">
				<table width="100%" border="1" id="role_action_list">
					<?php foreach ($actions as $module):?>
					<tr>
						<td width="150">
							<!-- 一级分类 -->
							<a type="form" href="<?php echo site_url('privilege/action_form_module');?>" data-id="<?php echo $module['id'];?>" callback="reload" title="编辑-<?php echo $module['name'];?>" width="400" height="200" validate="validateAction" class="ui-operate-button" type="button">
								<?php echo $module['name'];?></a>
								[<a type="dialog" height="220" width="700" href="<?php echo site_url('privilege/menu_sql/'.$module['id']);?>"><font color="green">SQL</font></a>]
								[<a href="<?php echo site_url('privilege/delete_action_category?id='.$module['id']);?>" callback="reload" type="confirm" title="确认删除？" rel="div#main-wrap"><font color="red">删?</font></a>]
						<td>
							<table width="100%" border="1">
								<tr><td width="150" height="40"></td><td align="center">
										<strong>独占功能</strong></td>
									<td width="250" align="center">
										<strong>通用功能</strong></td></tr>
								<!-- 二级分类 -->
								<?php if ($module['columns']):?>
								<?php foreach ($module['columns'] as $column):?>
								<tr>
									<td width="150">
										<a type="form" callback="reload" href="<?php echo site_url('privilege/action_form_column');?>" data-id="<?php echo $column['id'];?>" width="400" height="200" validate="validateAction" class="ui-operate-button" type="button">
											<?php echo $column['name'];?></a>
										[<a type="dialog" height="220" width="700" href="<?php echo site_url('privilege/menu_sql/'.$column['id']);?>"><font color="green">SQL</font></a>]
										[<a href="<?php echo site_url('privilege/delete_action_category?id='.$column['id']);?>" callback="reload" type="confirm" title="确认删除？" rel="div#main-wrap"><font color="red">删?</font></a>]
									</td>
									<td>
										<table width="100%" border="1">
											<?php foreach ($column['column_actions'] as $column_action):?>
											<tr>
												<td width="200">
													<a type="form" callback="reload" href="<?php echo site_url('privilege/action_form');?>" data-id="<?php echo $column_action['id'];?>" width="400" height="200" validate="validateAction" class="ui-operate-button" type="button">
														<?php echo $column_action['title']?></a>
													[<a type="dialog" height="220" width="700" href="<?php echo site_url('privilege/action_sql/'.$column_action['id']);?>"><font color="green">SQL</font></a>]
													<a href="<?php echo site_url('privilege/delete_action?id='.$column_action['id']);?>" callback="reload" type="confirm" title="确认删除？" rel="div#main-wrap">[<font color="red">删?</font></a>]
												</td>
												<td>
													<!-- 三级分类[栏目] -->
													<?php if (isset($column_action['actions'])):?>
													<?php foreach ($column_action['actions'] as $action):?>
													<a type="form" callback="reload" href="<?php echo site_url('privilege/action_form');?>" data-id="<?php echo $action['id'];?>" width="400" height="200" validate="validateAction" class="ui-operate-button" type="button">
														<?php echo $action['title']?></a>
													[<a type="dialog" height="220" width="700" href="<?php echo site_url('privilege/action_sql/'.$action['id']);?>"><font color="green">SQL</font></a>]	
													<a href="<?php echo site_url('privilege/delete_action?id='.$action['id']);?>" type="confirm" title="确认删除？" callback="reload" rel="div#main-wrap">[<font color="red">删?</font></a>]
													<?php endforeach;?>
													<?php endif;?>
												</td>
											</tr>
											<?php endforeach;?>
										</table>
									</td>
									<td>
										<?php if ($column['universal_actions']):?>
										<?php foreach ($column['universal_actions'] as $uaction):?>
											<a type="form" callback="reload" href="<?php echo site_url('privilege/action_form');?>" data-id="<?php echo $uaction['id'];?>" width="400" height="200" validate="validateAction" class="ui-operate-button" type="button">
												<?php echo $uaction['title']?></a>
												[<a type="dialog" height="220" width="700" href="<?php echo site_url('privilege/action_sql/'.$uaction['id']);?>"><font color="green">SQL</font></a>]
												<a href="<?php echo site_url('privilege/delete_action?id='.$uaction['id']);?>" type="confirm" title="确认删除？" callback="reload" rel="div#main-wrap">[<font color="red">删?</font></a>]
										<?php endforeach;?>
										<?php endif;?>
									</td>
								</tr>
								<?php endforeach;?>
								<?php endif;?>
							</table>
						</td>
					</tr>
					<?php endforeach;?>
				</table>
			</div>
		</div>
	</div>
</div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>