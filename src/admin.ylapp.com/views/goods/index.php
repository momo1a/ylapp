<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<?php 
function user_name_color($uid,$yzcm,$mpg){
	// 商家名称字体颜色
	$style='';
	if(isset($yzcm[$uid])){
	  if($yzcm[$uid]['deposit_type']==1){
		if($yzcm[$uid]['state']==2){
		$style .=' color:#289728; ';
		}
	}
	}
	if(isset($mpg[$uid])){
	  if($mpg[$uid]['deposit_type']==2){
		  if($mpg[$uid]['state']==2){
		  $style .=' font-weight:bold; ';
		  }
		}  
	}
	return $style;
}
?>
<div class="ui-box ui-box2">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
        
     <?php if($segment=='checked'):  ?>
      <ul id="tabs" class="ui-tab-nav" style="margin-left:20px; margin-top:20px;">
        <li class="ui-tab-item <?php if($timetype=='')echo 'ui-tab-itemCurrent' ; ?>">
        <a href="<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$segment); ?>"> &nbsp;  &nbsp; 全部 &nbsp;  &nbsp; </a></li>
        <li class="ui-tab-item  <?php if($timetype=='today')echo 'ui-tab-itemCurrent' ; ?>">
        <a data-timetype="today" href="<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$segment); ?>"> &nbsp;  &nbsp; 今天 &nbsp;  &nbsp; </a></li>
        <li class="ui-tab-item  <?php if($timetype=='tomorrow')echo 'ui-tab-itemCurrent' ; ?>">
        <a data-timetype="tomorrow" href="<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$segment); ?>"> &nbsp;  &nbsp; 明天 &nbsp;  &nbsp; </a></li>
      </ul>
      
     <?php endif; ?>
			<div class="ui-box-head">
		<form rel="div#main-wrap" action="<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$segment);?>" method="get"> 
            <?php if($segment=='checked'):  ?>
                  <input name="timetype" type="hidden" value="<?php echo $timetype; ?>" />
                整点场次：<select onchange="$(this).closest('form').submit()" id="parvial_field" name="parvial_field">
                    <option value="-1" selected="selected">全部场次</option>
                    <?php foreach(explode(',',$this->config->item('goods_new_parvial_field')) as $k=>$val) {
					 $ival=intval($val);
					 $sel= $parvial_field==$ival ? 'selected="selected"' :'';
					 echo '<option value="'.$ival.'" '.$sel.'>'.$val.'</option>'; 
					}
					?>
                   </select>
              <?php endif; ?>
                
					活动类型：<?php echo form_dropdown('goods_type', $this->goods_types_map, $goods_type, 'id="goods_type" onchange="$(this).closest(\'form\').submit()"');?>
					<input type="hidden" id="type" value="0" name="type" />
					<?php echo form_dropdown('search_key', $this->search_map, $this->input->get_post('search_key'), 'id="search_key"');?>
					<input name="search_value"  class="ui-form-text ui-form-textRed" type="text" value="<?php echo $this->input->get_post('search_value');?>" />
					<?php if('all'==$segment):?>
					<?php echo form_dropdown('status', $this->status_map, $this->input->get_post('status'), 'id="status" onchange="$(this).closest(\'form\').submit()"');?>
					<input id="startTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly  value="<?php echo $this->input->get_post('startTime');?>" name="startTime" data-datefmt="yyyy-MM-dd HH:mm:ss">
					<input id="endTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly value="<?php echo $this->input->get_post('endTime');?>"  name="endTime" data-datefmt="yyyy-MM-dd HH:mm:ss">
					<?php endif;?>
					<button type="submit" class="ui-form-btnSearch"  id="sgoods">搜 索</button>
                    
                    <?php if($segment=='all'){  ?>
                    <button type="button" class="ui-form-btnSearch" style="float:right; margin:5px; margin-right:50px;" id="export">导出活动</button>     <?php } ?>
                    
				</form>
                <?php  if(in_array($segment,array('all','unckeck'))){ ?>
                
               <div style="float:right; padding-right:60px;" class="ui-table-operate"> 提示：当前的默认上线时间为<font color="#FF0000">
               "<?php 
			       $online_type=unserialize($this->config->item('goods_default_online_type'));
			       if(isset($online_type[$user_id])){
			      if($online_type[$user_id]==1){
					 $today=strtotime(date('Y-m-d',time()));
					 $curtime=time();
					 $mtime=0;
					 $goods_new_parvial_field=explode(',',$this->config->item('goods_new_parvial_field'));
				     foreach($goods_new_parvial_field as $k=>$val){
						    $val=intval($val);
                            if(($today+$val*60*60) > $curtime){
							   $mtime= $today+$val*60*60;
							   break;
							}
						 }
					echo $mtime >0 ? ' 今天 '. date("H:i:s",$mtime) : ' 明天 '. date("H:i:s",($today+intval($goods_new_parvial_field[0])*60*60+86400));
				   }elseif($online_type[$user_id]==2){
					   echo '全部手动设置';
				   }elseif($online_type[$user_id]==3){
					       $online_type_value=unserialize($this->config->item('goods_default_online_type_value'));
						   $type_value=isset($online_type_value[$user_id])?$online_type_value[$user_id]:0;
					    echo date("Y-m-d H:i:s",$type_value);
				   } 
				   }else{
					    echo '全部手动设置';
					   }
					?>"
                </font>&nbsp; &nbsp;
                 <a style="display:inline;" href="<?php echo site_url('setting/set_default_online_time'); ?>"  data-showform="yes"  height="300" width="580" type="form" callback="reload">设置默认时间</a></div>
                 <?php } ?>
                 
			</div>
			<div class="ui-box-body">
				<table class="ui-table">
					<thead>
						<tr>
							<th style="width:6%;">请选择</th>
							<th style="width:4%;">编号</th>
							<th style="width:18%;">活动标题</th>
							<th style="width:10%;">商家名称<br />商家邮箱<br />商家编号</th>
							<th style="width:5%;">活动时间</th>
							<th style="width:5%;">活动天数</th>
							<th style="width:4%;">数量</th>
							<th style="width:6%;">网购价<br />/折扣</th>
							<th style="width:6%;">应存费用<br />/已存费用</th>
							<th style="width:6%;">联系商家</th>
							<th style="width:8%;">活动状态</th>
							<th style="width:8%;">活动类型</th>
							<th style="width:10%;">操作</th>
						</tr>
					</thead>
					<tbody>
						<?php if (is_array($items)):?>
						<?php foreach ($items as $k=>$v):
                         if($v['state']==32)
                        {
                             $seed=$v['uid'];
                        }else{
                            $seed=$v['dateline'];
                        }
                            $goods_link=  create_fuzz_link($v['gid'], $v['state'], $seed);
                ?>
						<tr>
							<td><input type="checkbox" name="gids[]" value="<?php echo $v['gid']; ?>" /></td>
							<td><?php echo $v['gid'];?></td>
							<td><a href="<?php echo $goods_link;?>" target="_blank"><?php echo $v['title'];?></a></td>
							<td><span style=" <?php echo user_name_color($v['uid'],$yzcm,$mpg); ?>"><?php echo $v['uname'];?></span><br /><?php echo $v['email'];?><br /><?php echo $v['uid'];?></td>
							<td><?php echo $v['first_starttime'] ? date('Y-m-d H:i:s',$v['first_starttime']) : '';?><br />- <?php echo $v['endtime'] ? date('Y-m-d H:i:s',$v['endtime']): '';?></td>
							<td><?php echo $v['first_days'];?>天</td>
							<td><?php echo $v['quantity'];?>份</td>
							<td>
								<p><?php echo $v['price'];?>元<br />/<?php echo $v['discount'];?>折</p>
								<?php if($v['price_type']==Goods_model::PRICE_TYPE_MOBILE):?>
								<span style="color:#0099CC;" >手机专享：<br/>￥<?php echo $v['mobile_price']; ?></span>
								<?php endif;?>
							</td>
							<td><?php 
							//计算商家存入的每份担保金金额 说明：之前存入的每份担保金金额为网购价，修改后存入的每份担保金金额为返回给买家的金额  updateby 关小龙 2015-09-22 10:12:00
							$real_single_guaranty = $v['deposit_type']==1 ? $v['single_rebate'] : $v['price'];
							echo ($real_single_guaranty+$v['single_fee']+$v['search_reward'])*$v['quantity'];?>元<br />/<?php echo in_array($v['state'], array(1,2,11,13))? 0 : $v['paid_guaranty']+$v['paid_fee']+$v['paid_search_reward'];?>元</td>
							<td><?php echo $v['mobile'];?></td>
							<td>
								<?php echo $goods_util->get_status($v['state']);?>
							</td>
							<td>
								<?php echo $goods_util->get_goods_type($this->goods_types_map, $v['type']);?>
							</td>
							<td class="ui-table-operate">
								<?php 
								$v['user_id']=$user_id;
								echo $goods_util->get_action($v);?>
							</td>
						</tr>
						<?php endforeach;?>
						<?php endif;?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="13">
								<p style="float: left;margin-left: 20px;">
									<label><input type="checkbox" />&nbsp;全选/取消</label>
									<?php if( 'checked' == $this->uri->segment(3)):?>
										<a class="ui-form-button ui-form-buttonBlue" data-showform="yes" data-gids="" height="200" width="400" type="form" onclick="return set_online_time(this);" href="<?php echo site_url('goods/set_online_time'); ?>">修改上线</a>
									<?php endif;?>
								</p>
								<div class="ui-paging"><?php echo $pager;?></div></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function set_online_time(e)
{
	var gids = $("input[type='checkbox'][name^='gid']:checked").map(function(){
		return $(this).val();
	}).get().join(',');
	$(e).data('gids', gids);
}
/*要求jQuery版本在1.6以上*/
;(function($){
	$.fn.checkAll = function(checkbox){ /*参数：匹配需要被选中的checkbox的选择器;*/
		var $cAll = this.eq(0),
			$cBox = $(checkbox);
		$cAll.click(function(){
			$cBox.prop("checked",$cAll.prop("checked"));
		});
		$cBox.click(function(){
			var len = $cBox.length,
				trueLen = $cBox.filter(":checked").length;
			$cAll.prop("checked",len===trueLen);
		});
	}
})(jQuery);
    // 全选功能
 $(function(){
	$('tfoot input[type=checkbox]').checkAll('tbody input[type=checkbox]');
 });
//导出活动
 $("#export").click(function(){
	 if($("#startTime").val()=='' || $("#endTime").val()==''){
		alert('由于数据太多请务必选择起止时间!');
		return false ;
		}
	$("form:eq(0)").removeAttr('rel'); 
	$("form:eq(0)").attr("action", "<?php echo site_url($this->router->class.'/exportgoods');?>").submit();
 });
 //搜索商品
  $("#sgoods").click(function(){
	$("form:eq(0)").attr('rel="div#main-wrap"'); 
	$("form:eq(0)").attr("action", "<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$segment);?>").submit();
 });

</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>