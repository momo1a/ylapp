<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<form class="clearfix" rel="div#main-wrap" action="<?php echo site_url($this->router->class.'/'.$this->router->method);?>" method="get">
					<input id="start_time" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly name="start_time" data-datefmt="yyyy-MM-dd HH:mm:ss" value="<?php echo $start_time ? date('Y-m-d H:i:s',$start_time) : ''; ?>">
					<input id="end_time" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly name="end_time" data-datefmt="yyyy-MM-dd HH:mm:ss" value="<?php echo $end_time ? date('Y-m-d H:i:s',$end_time) : ''; ?>">
					<button type="submit" class="ui-form-btnSearch">确定</button>
				</form>
			</div>
			<div class="ui-box-body">
				<table class="ui-table">
					<thead>
						<tr>
							<th >商品来源</th>
							<th >活动数量</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($sources as $item):?>
						<tr>
							<td><?php 
								if($item['state'] == Zhs_goods_source_model::STATE_HIDDEN){
									echo '其他（' .$item['input_name'] . '）';
								}else{
									echo $item['input_name']; 
								}?>
							</td>
							<td><?php echo $item['source_count']?></td>
						</tr>
						<?php endforeach;?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2">
								<div class="ui-paging"><?php echo $pager;?></div>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>