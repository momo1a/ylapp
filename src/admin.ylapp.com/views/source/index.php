<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 userList">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<form action="<?php echo site_url('source/index/'); ?>" method="get" rel="div#main-wrap">
				  
					<input id="startTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly name="startTime" data-datefmt="yyyy-MM-dd HH:mm:ss" value="<?php echo  date('Y-m-d H:i:s',$startTime); ?>">
					<input id="endTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly name="endTime" data-datefmt="yyyy-MM-dd HH:mm:ss" value="<?php echo  date('Y-m-d H:i:s',$endTime); ?>">
					<input class="ui-form-text ui-form-textRed" name="search_value" placeholder='名称/URL' value="<?php echo isset($_GET['search_value'])?$_GET['search_value']:'';?>" >
                    <input id="SaveSearchCondition" class="ui-form-btnSearch" type="submit" value="搜 索" />
                    <input class="ui-form-btnSearch" type="button" value="导出列表" style="margin-left:50px;" onclick="location.href='<?php echo site_url('source/export/');?>?' +$(this).parent().serialize()"/>   
			<span style="float:right; margin-right:15px;">
                <a callback="reload" type="form" width="580" height="300" data-showform="yes" href="<?php echo site_url('setting/set_reg_source_name');?>" style="display:inline; "  class="ui-form-btnSearch">添加用户来源</a>	
                </span>
                </form>
			</div>
			<div class="ui-box-body">
				<table class="ui-table">
					<thead>
						<tr>
							<th>用户来源</th>
							<th>来源地址</th>
							<th>注册商家</th>
							<th>注册买家</th>
							<th width="200">操作</th>
						</tr>
					</thead>
					<tbody>
                       <?php if($search_val ==''): ?>
                    	<tr>
							<td>其它</td>
							<td>-</td>
							<td><a href="<?php echo site_url('user/index/seller?url=other&startTime='.date('Y-m-d H:i:s',$startTime).'&endTime='.date('Y-m-d H:i:s',$endTime))?>" target="_blank"><?php echo $other_num_seller; ?></a></td>
							<td><a href="<?php echo site_url('user/index/buyer?url=other&startTime='.date('Y-m-d H:i:s',$startTime).'&endTime='.date('Y-m-d H:i:s',$endTime))?>" target="_blank"><?php echo $other_num_buyer; ?></a></td>
							<td class="ui-table-operate">
								<a href="<?php echo site_url('source/export_url/?url=other&startTime='.$startTime.'&endTime='.$endTime.'&name=其它')?>">URL导出</a>
							</td>
						</tr>
						<?php endif;
						if(is_array($urls)):
							foreach ($urls as $k=>$v):
							if($search_val !==''):
							  if(stristr($v['name'],$search_val)||stristr($v['url'],$search_val)):
						?>
						<tr>
							<td><?php echo $v['name']; ?></td>
							<td><?php echo $v['url']; ?></td>
							<td><a href="<?php echo site_url('user/index/seller?url='.urlencode($v['url']).'&startTime='.date('Y-m-d H:i:s',$startTime).'&endTime='.date('Y-m-d H:i:s',$endTime).'&typeurl='.$v['type'] ) ?>" target="_blank"><?php echo $v['num_seller']; ?></a></td>
							<td><a href="<?php echo site_url('user/index/buyer?url='.urlencode($v['url']).'&startTime='.date('Y-m-d H:i:s',$startTime).'&endTime='.date('Y-m-d H:i:s',$endTime).'&typeurl='.$v['type'] )?>" target="_blank"><?php echo $v['num_buyer'];?></a></td>
							<td class="ui-table-operate">
                              <a callback="reload" type="form" width="580" height="300" data-showform="yes" href="<?php echo site_url('setting/edit_reg_source_name?id='.$v['id']);?>" style="display:inline; " >编辑</a>		
									<a href="<?php echo site_url('source/export_url/?url='.urlencode($v['url']).'&startTime='.$startTime.'&endTime='.$endTime.'&type='.$v['type'].'&name='.$v['name'])?>">URL导出</a>
								          <a callback="reload" href="<?php echo site_url('setting/del_reg_source_name?id='.$v['id']);?>" style="display:inline; " >删除</a>	
							</td>
						</tr>
                        <?php endif;  
						 else: ?>
                          <tr>
                              <td><?php echo $v['name']; ?></td>
                              <td><?php echo $v['url']; ?></td>
                              <td><a href="<?php echo site_url('user/index/seller?url='.urlencode($v['url']).'&startTime='.date('Y-m-d H:i:s',$startTime).'&endTime='.date('Y-m-d H:i:s',$endTime).'&typeurl='.$v['type'] )?>" target="_blank"><?php echo $v['num_seller']; ?></a></td>
							<td><a href="<?php echo site_url('user/index/buyer?url='.urlencode($v['url']).'&startTime='.date('Y-m-d H:i:s',$startTime).'&endTime='.date('Y-m-d H:i:s',$endTime).'&typeurl='.$v['type'] )?>" target="_blank"><?php echo $v['num_buyer'];?></a></td>
                              <td class="ui-table-operate">
                                 <a callback="reload" type="form" width="580" height="300" data-showform="yes" href="<?php echo site_url('setting/edit_reg_source_name?id='.$v['id']);?>" style="display:inline; " >编辑</a>	
                                	<a href="<?php echo site_url('source/export_url/?url='.urlencode($v['url']).'&startTime='.$startTime.'&endTime='.$endTime.'&type='.$v['type'].'&name='.$v['name'])?>">URL导出</a>
                                  <a callback="reload" title="确定要删除吗？" type="confirm"  href="<?php echo site_url('setting/del_reg_source_name?id='.$v['id']);?>" style="display:inline; " >删除</a>	
                              </td>
                          </tr>
                        
                        
						<?php endif; endforeach; endif;?>
					</tbody>
				</table>
				<div class="ui-paging-center" style="margin-top:20px;">
					<div class="ui-paging"></div>
				</div>
			</div>
			<!-- /userList-body -->
		</div>
	</div>
</div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>