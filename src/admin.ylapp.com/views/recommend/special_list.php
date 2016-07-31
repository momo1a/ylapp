				<form rel="div#special_search_<?php echo $segment.'_'.$cate_id;?>" action="<?php echo site_url($this->router->class.'/special_search/'.$segment);?>" method="get">
				<p style="margin-bottom: 12px;">
					<span>活动搜索：</span>
					<select class="ui-select" name="search_key">
						<option value="gid">活动编号</option>
						<option value="title">活动标题</option>
						<option value="uname">用户昵称</option>
						<option value="email">用户邮箱</option>
						<option value="uid">用户编号</option>
					</select>
					<input class="ui-form-text ui-form-textRed" type="text" name="search_val" />
					<input class="ui-form-btnSearch" type="submit" value="搜索" />
					<input type="hidden" name="cate_id" value="<?php echo $cate_id; ?>" />
                     <a class="ui-form-btnSearch" href="<?php echo site_url('recommend/batch_cancel_special?type_id='.$id .'&cate_id='. $cate_id)?>" type="form"  callback="reload" style="margin: 0px 7px 0 0;float: right;">批量取消</a>
					 <a class="ui-form-btnSearch" href="<?php echo site_url('recommend/batch_push_special?type_id='.$id .'&cate_id='. $cate_id)?>" type="form"  callback="reload" style="margin: 0px 7px 0 0;float: right;">批量推送</a>
				</p>
				</form>
				<div id="special_search_<?php echo $segment.'_'.$cate_id;?>" >
					<?php $this->load->view('recommend/special_search_list');?>
				</div>
				<div class="ui-box-head ui-box2" style="margin: 30px auto 8px;">商品推荐列表</div>
				<div id="spacial_list_<?php echo $segment.'_'.$cate_id;?>">
					<?php $this->load->view('recommend/special_item_list');?>
				</div>