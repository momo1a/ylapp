<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>

<div class="ui-box ui-box2">
	<div style="padding:10px;">
		<form rel="div#main-wrap" action="<?php echo site_url($this->router->class.'/'.$this->router->method.'/');?>" method="get" id="godd_form">
			<input style="padding:3px 10px; width:70%;" type="text" name="fuzz_gid_txt" cols="140" placeholder="在此输入活动编号进行查询，最多可同时查询20条活动编号，请以英文逗号隔开" value="<?php echo $fuzz_gid_txt; ?>">
			<input type="submit" class="ui-form-btnSearch" value="搜 索"/>
			<span style="padding-left:10px;">说明：活动编号格式111111-XXXXXX</span>
		</form>
	</div>
	<?php if(count($failure_fuzz_gid)):?>
	<div style="padding:10px; color:#f00;">
	抱歉！未查到以下活动编号信息：<?php echo implode(',', $failure_fuzz_gid);?>
	</div>
	<?php endif;?>
	
	<?php if (count($goods_list)):?>
	<table class="ui-table">
		<thead>
			<tr>
				<th style="width:2%;">选择</th>
				<th style="width:6%;">活动编号</th>
				<th style="width:14%;">活动标题</th>
				<th style="width:4%;">商家名称</th>
				<th style="width:4%;">活动天数</th>
				<th style="width:4%;">数量</th>
				<th style="width:4%;">网购价/折扣</th>
				<th style="width:6%;">应存费用/已存费用</th>
				<th style="width:6%;">活动状态</th>
				<th style="width:4%;">活动类型</th>
				<th style="width:4%;">操作</th>
			</tr>
		</thead>
		<tbody>
			
			<?php foreach ($goods_list as $k=>$v):
					if($v['state']==32)
					{
						$seed=$v['uid'];
					}else{
						$seed=$v['dateline'];
					}
					$goods_link = create_fuzz_link($v['gid'], $v['state'], $seed);
                ?>
			<tr>
				<td>
					<?php if ($v['state'] == YL_goods_model::STATUS_UNCHECK_PAID):?>
					<input type="checkbox" class="checkbox" checked="checked" name="gids[]" value="<?php echo $v['fuzz_data']['gid_fuzz']; ?>" />
					<?php else:?>
					&nbsp;
					<?php endif;?>
				</td>
				<td><?php echo $v['fuzz_data']['gid_fuzz']; ?></td>
				<td><a href="<?php echo $goods_link;?>" target="_blank"><?php echo $v['title'];?></a></td>
				<td><?php echo $v['uname'];?></td>
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
				echo ($real_single_guaranty+$v['single_fee']+$v['search_reward'])*$v['quantity'];?>元/<?php echo in_array($v['state'], array(1,2,11,13))? 0 : $v['paid_guaranty']+$v['paid_fee']+$v['paid_search_reward'];?>元</td>
				<td>
					<?php echo $goods_util->get_status($v['state']);?>
				</td>
				<td>
					<?php echo $goods_util->get_goods_type($this->goods_types_map, $v['type']);?>
				</td>
				<td class="ui-table-operate">
					<a href="<?php echo site_url($this->router->class.'/'.$this->router->method.'/?post_type=log&gid_fuzz='.$v['fuzz_data']['gid_fuzz']);?>" type="dialog" width="800" data-gid="<?php echo $v['gid']?>">操作记录</a>
				</td>
			</tr>
			<?php endforeach;?>
			
		</tbody>
	</table>
	
	<form rel="div#main-wrap" action="<?php echo site_url($this->router->class.'/'.$this->router->method.'/');?>" method="post" id="pass_form"> 
		<div style="padding:10px;">
			<input type="checkbox" class="checkall" checked="checked" name="fuzz_gid_txt" onclick="select_checkbox()" value=""/>&nbsp;全选/取消
			 <?php 
			 $today=strtotime(date('Y-m-d',time()));
			 $curtime=time();
			 $mtime=0;
			 $chevaltype=0;
			 $goods_new_parvial_field=explode(',',$this->config->item('goods_new_parvial_field'));
			 if($goods_new_parvial_field[0]> 0 ){
			 //今天的上线时间
			 foreach($goods_new_parvial_field as $k=>$val){
				    $val=intval($val);
					$disval='';
					$timeval=$today+$val*60*60;
					if($timeval < $curtime){
						$disval=' disabled="disabled" ';
					}
					$cheval = '';
					if($chevaltype==0 && $timeval > $curtime){
						 $cheval=' checked="checked" ';
						 $chevaltype=1;
					}
					echo '<label><input type="radio" name="online_time" value="'.$timeval.'"  '.$disval.$cheval.' /> 今天'.date('H:i',$timeval).'&nbsp; &nbsp;</label> ';
					$cheval='';
				 }
			 	//echo '<br/><br/>';
				//明天的上线时间
			 	foreach($goods_new_parvial_field as $k=>$val){
				    $val=intval($val);
				    $tomorrowtime=$today+$val*60*60+86400;
					echo '<label><input type="radio" name="online_time" value="'.$tomorrowtime.'" /> 明天'.date('H:i',$tomorrowtime).'&nbsp; &nbsp; </label>';
				 }
				 
			 } 
		 ?>
		 	<input type="hidden" name="post_type" value="check_goods"/>
		 	<input type="button" class="ui-form-btnSearch pass-btn" value="通过"/>
		</div>
	</form>
	<?php endif;?>
</div>

<script type="text/javascript">
//全选
function select_checkbox() {
    var checked = $('.checkall').prop('checked');
	$(".checkbox").prop("checked", checked);
}
// 搜索值为空时阻止表单提交
$('#godd_form').submit(function() {
	if ($('input[name=fuzz_gid_txt]').val() == '') {
		alert('请输入活动编号查询。');
		return false;
	};
})
$(function() {
	//初始状态给全选框赋值
	var checkAll_V = ''
	$('.checkbox:checked').each(function(){
		checkAll_V += checkAll_V == ''? $(this).val(): ','+$(this).val();
	});
	$('.checkall').attr('value',checkAll_V);

	$('.checkbox,.checkall').click(function() {
		var checkAll_V = ''
		$('.checkbox:checked').each(function(){
			checkAll_V += checkAll_V == ''? $(this).val(): ','+$(this).val();
		});
		$('.checkall').attr('value',checkAll_V);
	});

	//执行“通过”的审核事件
	$('.pass-btn').click(function() {
		var formData = $('#pass_form').serialize();
		var checked = $('.checkbox:checked');
		if (checked.length != 0) {
		$.ajax({
            url: $('#pass_form').attr('action'),
            type: 'POST',
            dataType: 'json',
            data: formData,
            success:function(msg){
            	if (msg.msg != null) {
                	alert(msg.msg);
            	} else {
            		alert('未知错误！');
            	}
                $('#godd_form').submit();
            },
            error:function(){
	                alert('服务器繁忙，请稍后再试！')
            }
        });
		} else {
			alert('请选择活动');
		}
	})
});

</script>

<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>