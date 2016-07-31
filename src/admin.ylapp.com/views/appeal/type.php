<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 appealType"><div class="ui-box-outer"><div class="ui-box-inner"> 

	<table class="ui-table">
		<thead>
			<tr><th><?php echo $type_name[$utype_str]; ?>申诉类型</th><th>操作</th></tr>
		</thead>
		<tbody>
			<?php if(is_array($type_list)): foreach ($type_list as $k=>$v):?>
			<tr data-id="<?php echo $v['id'];?>">
				<?php if( $v['shield'] == Order_appeal_type_model::SHIELD_OFF  ):?>
				<td><?php echo $v['name']; ?></td>
				<?php elseif ( $v['shield'] == Order_appeal_type_model::SHIELD_ON ):?>
				<td style="color: #aaa"><?php echo $v['name']; ?></td>
				<?php endif;?>
				<td>
					<!-- 
					<a href="<?php //echo site_url('appeal/type_delete');?>" type="confirm" title="确定要删除该申诉类型吗？" class="ui-operate-button ui-operate-buttonDel" callback="reload()" data-id="<?php //echo $v['id'];?>">删除</a>
					 -->
					<?php if( $v['shield'] == Order_appeal_type_model::SHIELD_OFF  ):?>
					<a href="<?php echo site_url('appeal/type_form');?>" type="form" width="400" height="200" callback="reload()" title="编辑申诉类型" class="ui-operate-button ui-operate-buttonEdit" data-id="<?php echo $v['id'];?>">编辑</a>
					<a href="<?php echo site_url('appeal/type_shield/'.Order_appeal_type_model::SHIELD_ON );?>" type="confirm" title="屏蔽后，<?php echo $type_name[$utype_str];?>将失去该申诉类型。确定要屏蔽吗？" class="ui-operate-button ui-operate-buttonShield" callback="reload()" data-id="<?php echo $v['id'];?>">屏蔽</a>
					<?php elseif ( $v['shield'] == Order_appeal_type_model::SHIELD_ON ):?>
					<a href="javascript:;" title="屏蔽状态下无法编辑申诉类型" class="ui-operate-button ui-operate-buttonEditDisable" >编辑</a>
					<a href="<?php echo site_url('appeal/type_shield/'.Order_appeal_type_model::SHIELD_OFF);?>" type="confirm" title="解屏后，<?php echo $type_name[$utype_str];?>将可以恢复使用该申诉类型。确定要解屏吗？" class="ui-operate-button ui-operate-buttonShield" callback="reload()" data-id="<?php echo $v['id'];?>">解屏</a>
					<?php endif;?>
				</td>
			</tr>
			<?php endforeach; endif;?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2"><a class="ui-operate-button ui-operate-buttonAdd" href="<?php echo site_url('appeal/type_form/'.$utype_str); ?>" type="form" width="400" height="200" callback="reload()" data-type="<?php echo $utype_str;?>">添加<?php echo $type_name[$utype_str]; ?>申诉类型</a></td>
			</tr>
		</tfoot>
	</table>

</div></div></div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>