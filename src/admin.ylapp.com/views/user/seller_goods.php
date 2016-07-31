<?php 
function user_name_color($uid,$yzcm,$mpg){
	// 商家名称字体颜色
	$style='';
	if($yzcm[$uid]['deposit_type']==1){
		if($yzcm[$uid]['state']==2){
		$style .=' color:#289728; ';
		}
	}
	  if($mpg[$uid]['deposit_type']==2){
		  if($mpg[$uid]['state']==2){
		  $style .=' font-weight:bold; ';
		  }
		}  
	return $style;
}
?>

<form id="div_user_list_<?php echo $type_id;?>" rel="div#user_list_<?php echo $type_id;?>" action="<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$uid);?>" method="get"  target="exportifrm" class="from">
	<select name="search_type">
		<option value="gid"  <?php echo $search_type=='gid' ? 'selected="selected"':''?>>活动编号</option>
		<option value="title"  <?php echo $search_type=='title' ? 'selected="selected"':''?>>活动标题</option>
	</select>
	<input class="ui-form-text ui-form-textRed" name="search_value" value="<?php echo $search_value;?>" >
	<input class="ui-form-btnSearch" type="submit" value="搜索">
	<input type="hidden" name="type_id"  id="type_id" value="<?php echo $type_id?>"/>
	<input type="hidden" name="listonly"  value="yes"/>
	<input type="hidden" id="doexport_<?php echo $type_id?>" name="doexport"  value="no"/>
	<input type="button" class="ui-form-btnSearch doexport"   onclick="export_goods()" value="导出" />
</form>
<table class="ui-table">
	<thead>
		<tr>
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
                        if($v['state']==32){
                             $seed=$v['uid'];
                        }else{
                            $seed=$v['dateline'];
                        }
                    $goods_link = create_fuzz_link($v['gid'], $v['state'], $seed);
        ?>
		<tr>
			<td><?php echo $v['gid'];?></td>
			<td><a href="<?php echo $goods_link;?>" target="_blank"><?php echo $v['title'];?></a></td>
			<td><span style=" <?php echo user_name_color($v['uid'],$yzcm,$mpg); ?>"><?php echo $v['uname'];?></span><br /><?php echo $v['email'];?><br /><?php echo $v['uid'];?></td>
			<td><?php echo $v['first_starttime'] ? date('Y-m-d H:i:s',$v['first_starttime']) : '';?><br />- <?php echo $v['endtime'] ? date('Y-m-d H:i:s',$v['endtime']): '';?></td>
			<td><?php echo $v['first_days'];?>天</td>
			<td><?php echo $v['quantity'];?>份</td>
			<td><?php echo $v['price'];?>元<br />/<?php echo $v['discount'];?>折
			<?php if($v['price_type']==Goods_model::PRICE_TYPE_MOBILE){?>
				<p style="color: #0099cc">手机专享：<br/>￥<?php echo $v['mobile_price'];?>元</p>
			<?php }?>
			</td>
			<td><?php
			//计算商家存入的每份担保金金额 说明：之前存入的每份担保金金额为网购价，修改后存入的每份担保金金额为返回给买家的金额  updateby 关小龙 2015-09-22 10:12:00
			$real_single_guaranty = $v['deposit_type']==1 ? $v['single_rebate'] : $v['price'];
			echo ($real_single_guaranty+$v['single_fee']+$v['search_reward'])*$v['quantity']?>元<br />/<?php echo in_array($v['state'], array(1,2,11,13))? 0 : $v['paid_guaranty']+$v['paid_fee']+$v['paid_search_reward'];?>元
			</td>
			<td><?php echo $v['mobile'];?></td>
			<td>
				<?php echo $goods_util->get_status($v['state']);?>
			</td>
			<td>
				<?php echo $goods_util->get_goods_type($goods_types_map, $v['type']);?>
			</td>
			<td class="ui-table-operate">
				<?php echo $goods_util->get_action($v);?>
			</td>
		</tr>
		<?php endforeach;?>
		<?php endif;?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="12">
				<div class="ui-paging"><?php echo $pager;?></div></td>
		</tr>
	</tfoot>
</table>
<script>

function export_goods(){
	$("#doexport_<?php echo $type_id?>").val("yes");
	var data = $("#div_user_list_<?php echo $type_id;?>").serialize();
	var url =  "<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$uid);?>?"+data;
	$("#doexport_<?php echo $type_id?>").val("no");
	window.open(url);
}
</script>
<style>
.from{margin-bottom: 15px;}
.doexport{float:right}
</style>
