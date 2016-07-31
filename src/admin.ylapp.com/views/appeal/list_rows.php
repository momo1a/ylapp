<style>
	.simpletooltip{
		display: inline-block;
		*display: inline;
		*zoom: 1;
		width: 15px;
		height: 15px;
		background-image: url(<?php echo $this->config->item('domain_static')?>images/admin/question.png);
		vertical-align: middle;
		cursor:pointer;
	}
	.apptip{
		background-color: #f1273a;
		color: #fff;
		width: 65px;
		margin: 5px auto 0;
	}
</style>
<form rel="div#appeal_list_<?php echo $type_id;?>" action="<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$utype_str);?>" method="get">
	<input class="ui-form-text ui-form-textGray ui-form-textDatetime" readonly="" name="startTime" data-datefmt="yyyy-MM-dd HH:mm:ss" title="开始日期"
			value="<?php echo $this->input->get('startTime')?$this->input->get('startTime',TRUE):'';?>" />
	-
	<input class="ui-form-text ui-form-textGray ui-form-textDatetime" readonly="" name="endTime"
		data-datefmt="yyyy-MM-dd HH:mm:ss" title="截止日期" value="<?php echo $this->input->get('endTime')?$this->input->get('endTime',TRUE):'';?>" />
	<select id="key" name="key">
		<option <?php if ($this->input->get('key') == 'oid' OR !$this->input->get('key')):?>selected="selected"<?php endif;?> value="oid">抢购编号</option>
		<option <?php if ($this->input->get('key') == 'id'):?>selected="selected"<?php endif;?> value="id">申诉编号</option>
		<option <?php if ($this->input->get('key') == 'trade_no'):?>selected="selected"<?php endif;?> value="trade_no">填写的订单号</option>
		<option <?php if ($this->input->get('key') == 'gid'):?>selected="selected"<?php endif;?> value="gid">活动编号</option>
		<option <?php if ($this->input->get('key') == 'title'):?>selected="selected"<?php endif;?> value="title">活动标题</option>
		<option <?php if ($this->input->get('key') == 'seller'):?>selected="selected"<?php endif;?> value="seller">商家名称</option>
		<option <?php if ($this->input->get('key') == 'buyer'):?>selected="selected"<?php endif;?> value="buyer">买家名称</option>
	</select>
	<input class="ui-form-text ui-form-textRed" id="val" name="val" type="text"
		value="<?php echo $this->input->get('val')?$this->input->get('val',TRUE):'';?>" />
	<input id="SaveSearchCondition" class="ui-form-btnSearch" type="submit" value="搜 索" />
	<input type="hidden" name="type_id" value="<?php echo $type_id;?>" />
		<input type="hidden" name="listonly" value="yes"/>
		<!-- 一键处理申诉:申请恢复买家资格(12) -->
		<?php if (isset($type_id) AND in_array($type_id, array(2,12))):?>
		<input class="ui-form-btnSearch" type="button"
				onclick="handleOfAllFront(this);"
				style="float:right;" value="一键处理申诉" />
		<?php endif;?>
</form>

	<table class="ui-table">
		<thead>
			<tr>
                <th><input name="appeal_ids[]" type="checkbox" class="checkAll" value="" data-status="appeal_list_<?php echo $type_id;?>" /> 全选</th>
				<th>申诉编号</th>
				<th>抢购编号</th>
				<th style="width: 12em;">活动标题</th>
				<th>商家名称</th>
				<th>买家名称</th>
				<th>活动价/网购价</th>
				<th>返现金额</th>
				<th style="width: 10em;">填写的订单号</th>
				<th style="width: 4em;">淘宝客</th>
				<th>进度状态</th>
				<th style="width: 6em;">申诉时间</th>
				<th>操作</th>
			</tr>
	</thead>
	<?php if(is_array($list) && count($list)):?>
	<tbody>
		<?php foreach ($list as $k=>$v):
            $goods_link=  create_fuzz_link($v['gid'], $v['goods_state'], $v['seller_uid']);
        ?>
		<tr id="appeal_row_<?php echo $v['id'];?>">
            <td><input name="appeal_ids[]" type="checkbox" value="<?php echo $v['id'];?>" /></td>
			<td><?php echo $v['id'];?></td>
			<td><?php echo $v['oid'];?><br /><span style="color:#0066CC;">已申诉：<?php echo $v['appeal_count'];?>次</span></td>
			<td ><a href="<?php echo $goods_link;?>" target="_blank" style="color:#0066CC;"><?php echo $v['title'];?></a><br />活动编号：<?php echo $v['gid'];?></td>
			<td><?php echo $v['seller_uname'];?></td>
			<td>
				<p><?php echo $v['buyer_uname'];?></p>
				<p style="color:<?php echo user_stat_coror($v['buyer_user']['is_lock'])?>"><?php echo user_stat_str($v['buyer_user']['is_lock'], $v['buyer_user']['lock_day']);?></p>
			</td>
			<td><?php echo '<em style="color:#009900; font-weight:bold";>￥'.$v['cost_price'].'</em><br />￥'.$v['price'];?>元
			<?php if($v['price_type']==Goods_model::PRICE_TYPE_MOBILE){?>
				<p style="color: #0099cc">手机专享：<br/>￥<?php echo $v['mobile_price'];?>元</p>
			<?php }?>
			</td>
			<td>￥<?php echo $v['real_rebate'];?><?php if( isset($v['search_reward']) && $v['search_reward']>0 ){?><br />￥<em style="color:#009900;"><?php echo $v['search_reward'];?></em><?php }?>
			<?php if( $v['adjust_rebate'] !=0 ):?>
				<a class="simpletooltip pastelblue top" href="/appeal/show_adjust_tips/<?php echo $v['oid'];?>" type="tips">&nbsp;</a>
			<?php endif;?>
			</td>
			<td><?php echo $v['trade_no'];?>
			<?php if( Order_model::is_mobile_order($v['site_type'],$v['fill_site_type']) ){?>
            <p class="apptip">手机客户端</p>
            <?php }?></td>
			<td><?php echo $v['is_taoke'] ? '<em style="color:red;">是</em>' : '否';?></td>
			<td><?php if($v['state']==1): echo '<em style="color:#FF6600">待处理</em><br />(等待回应)'; elseif($v['state']==2): echo '<em style="color:#FF6600">待处理</em><br />(已回应)'; elseif($v['state']==3): echo '<em style="color:#FF6600">待处理</em><br />(无需回应)'; endif;?></td>
			<td><?php echo date("Y-m-d H:i:s", $v['dateline']);?></td>
			<td class="ui-table-operate">
				<p>
					<a href="<?php echo site_url('appeal/handle?handle=1')?>" type="form" callback="$('tr#appeal_row_<?php echo $v['id'];?>').remove();" width="580" data-id="<?php echo $v['id'];?>">处理申诉</a>
				</p>
				<a href="<?php echo site_url('goods/order_flow');?>" type="dialog" width="500" height="260" data-oid="<?php echo $v['oid'];?>">抢购记录</a>
			</td>
		</tr>
		<?php endforeach;?>
	</tbody>
	<tfoot>
		<tr>
           <td colspan="1">
         	<input name="appeal_ids[]" type="checkbox" class="checkAll" value="" data-status="appeal_list_<?php echo $type_id;?>" /> 全选
           </td>
           <td colspan="12">
            <a href="javascript:;" class="ui-form-button ui-form-buttonBlue" callback="reload" onclick="showall(this)">批量处理申诉</a>  
            &nbsp;&nbsp;
            <?php if($utype_str == 'seller'):?>
            <a onclick="batchUserLock(this)" callback="reload" class="ui-form-button ui-form-buttonBlue" href="javascript:;">批量封号/屏蔽</a>
            <?php endif;?>
            </td>
		</tr>
		<tr>
			<td colspan="13" class="ui-paging">
		   <?php echo $pager;?> 
		   </td>
		</tr>
	</tfoot>
	<?php else:?>
	<tbody>
		<tr><td colspan="13">无此申诉信息</td></tr>
	</tbody>
	<tfoot>
	</tfoot>
	<?php endif;?>
	</table>
<?php if (in_array($type_id, array(2,12))):?>

<script type="text/javascript">
<!--
/**
 * 一键处理前置操作
 * 1、获取要处理的数据，用于显示
 * 2、填写处理结果用于备注
 */
function handleOfAllFront(obj) {
	handleCount = handleSuccessCount = handleErrorCount = 0; // 还原为0
	var $form = $(obj).parent('form');
	var startTime = $form.find('input[name="startTime"]').val(),
		endTime = $form.find('input[name="endTime"]').val(),
		key = $form.find('select').eq(0).val(),
		val = $form.find('input[name="val"]').val(),
		keyName = $form.find('select').find("option:selected").text(),
		artDialogHeight = 250,
		flag = false,
		appealText = '<?php echo $type_id==12 ? '申请恢复买家资格' : '申请取消资格';?>';

	// 请求数据
	$.getJSON('<?php echo site_url('appeal/handle_of_all_front');?>',{ut:'<?php echo $utype_str;?>',at:<?php echo $type_id;?>,starttime:startTime,endtime:endTime,key:key,val:val},function(data){
		if (data.type && data.type=='ACCESS_DENY') {
			PopupTips('你没有权限进行此项操作','error');
			return;
		}
		
		handleCount = data.handleCount;
		
		// 弹窗的条件HTML
		var whereHTML = '<tr><td width="100"><strong>处理条件</strong></td><td></td></tr>';
		if (startTime || endTime) {
			whereHTML += '<tr><td align="right">时间范围：</td><td>'
							+ '从' + (startTime ? startTime : '一开始')
							+ ' <strong>到 </strong> ' + (endTime ? endTime : '现在')
							+ '</td></tr>';
		}
		if (key && val) {
			whereHTML += '<tr><td align="right">附加条件：</td><td>'+keyName+'为('+val+')</td></tr>';
		}
		if (!startTime && !endTime && !val) {
			whereHTML += '<tr><td colspan="2"><strong>无</strong>---(全部未处理的【'+appealText+'】申诉)</td></tr>';
		}

		// 弹窗的主体HTML
		var artDialogHTML = '';
		if (handleCount == 0) {
			artDialogHeight = 100;
			artDialogHTML += '<table width="480" height="'+(artDialogHeight-10)+'"><tr>'
								+ whereHTML
								+ '<tr><td><strong>搜索结果：</strong></td><td style="font-size:15px;">没有要处理【'+appealText+'】申诉。</td></tr>'
								+ '</table>';
		}else {
			flag = true;
			var handleText = '<?php echo $type_id==12 ? '恢复买家资格' : '取消资格';?>';
			artDialogHTML += '<table width="480" height="'+(artDialogHeight-10)+'"><tr>'
								+ whereHTML
								+ '<tr><td colspan="2" style="font-size:15px;">一共有'+handleCount+'个【'+appealText+'】申诉。</td></tr>'
								+ '<tr><td><strong>管理员处理申诉</strong></td><td></td></tr>'
								+ '<tr><td align="right" valign="top">处理结果：</td><td><textarea style="margin: 0px; width: 331px; height: 100px;"></textarea><br /><span id="message_error" style="color:red;display:none;">请填写处理结果</span></td></tr>'
								+ '<tr><td align="right">处理类型：</td><td>'+handleText+'</td></tr>';
								+ '</table>';
		}
			
		// 显示弹窗
		art.dialog({
			id: 'artFormDialog',
			title: '一键处理申诉',
			content: '<div id="artFormDialog">'+artDialogHTML+'</div>',
			width: 500,
			height: artDialogHeight,
			padding: 0,
			background: '#000',
			opacity: 0.35,
			lock: true,
			ok: function() {
				if (flag) {
					var objContent = $(this.DOM.content[0]).find('textarea').eq(0);
					var content = $.trim(objContent.val());
					if (content.length == 0) {
						objContent.css('border-color', 'red');
						//objContent.after('<br /><span style="color:red;">请填写处理结果</span>');
						$(this.DOM.content[0]).find('#message_error').show();
						return false;
					}
					$(this.DOM.buttons[0].children[0]).hide(); // 隐藏确定按钮
					$(this.DOM.buttons[0].children[1]).hide(); // 隐藏取消按钮
					$('.aui_dialog .aui_close').hide();
					var handleURL = '<?php echo site_url('appeal/handle_of_all');?>?ut=<?php echo $utype_str;?>&at=<?php echo $type_id;?>&c=' + handleCount
								+ '&id=0&starttime=' + startTime+'&endtime=' + endTime
								+ '&c=' + handleCount + '&key=' + key + '&val=' + val
								+ '&content=' + encodeURIComponent(content);
					var handleingHTML =
					'<div>'
					+	'<p style="height:50px;">正在处理申诉，请稍后...</p>'
					+	'<div class="progress_bar"><div class="bar" style="width:0%;"></div></div>'
					+	'<p style="height:50px;"><span id="currentHandleCount">0</span>/'+handleCount+'</p>'
					+'</div>'
					+'<iframe id="handleIframe" style="display:none" src="'+handleURL+'"></iframe>';
					
					$('#artFormDialog').html(handleingHTML);
					return false;
				}else return true;
			},
			cancel: true
		});
	});
}

function showHandleResult() {
	artDialog({id: 'artFormDialog'}).close();
	artDialog({
		id: 'artFormDialog',
		title: '一键处理申诉',
		width: 300,
		height: 100,
		content: '<p>处理完毕</p><p>处理成功'+handleSuccessCount+'个</p><p>处理失败'+handleErrorCount+'个</p>',
		ok: function() {
			load('<?php echo site_url('appeal/index/'.$utype_str);?>','div#appeal_list_<?php echo $type_id;?>',{type_id:<?php echo $type_id;?>,listonly:'yes'});
		},
		cancel: function() {
			load('<?php echo site_url('appeal/index/'.$utype_str);?>','div#appeal_list_<?php echo $type_id;?>',{type_id:<?php echo $type_id;?>,listonly:'yes'});
		},
		fixed: true,
		lock: true,
		//icon: 'success'
	}).show();
	$('.aui_buttons button').eq(1).hide(); // 隐藏取消按钮
	//$('#artFormDialog').html('<div></div>');
}

function showErrorMessage(message) {
	$('#artFormDialog').html(message);
}

/**
 * 显示进度条
 * @para flag 处理结果
 */
function showProgressBar(flag) {
	if (flag) {
		handleSuccessCount ++;
	}else {
		handleErrorCount ++
	}

	var handledCount = handleSuccessCount + handleErrorCount;
	var progress = (handledCount/handleCount)*100+'%';

	$('.progress_bar .bar').css('width', progress);
	$('#currentHandleCount').html(handledCount);
}

//-->
</script>
<style>
<!--
.progress_bar{ 
    background-color:#eee; 
    color:#222; 
    height:16px; 
    width:300px; 
    border:1px solid #bbb; 
    text-align:center; 
    position:relative; 
} 
.progress_bar .bar { 
    background-color:#6CAF00; 
    height:16px; 
    width:0; 
    position:absolute; 
    left:0; 
    top:0; 
} 

-->

</style>
<?php endif;?>
<script type="text/javascript">
$(function(){
	$("input[type='checkbox'].checkAll").click(function(){
		var $this = $(this),str = $this.data('status');
		$('#'+str).find("input[type='checkbox'][name='"+$this.attr('name')+"']").attr('checked', $this.is(':checked'));
	});
});
function showall(obj){
	var $obj = $(obj);
	var appeal_ids = $("input[type='checkbox'][class!='checkAll'][name^='appeal_ids']:checked").map(function(){
		return $(this).val();
	}).get().join(",");
	
	if(appeal_ids === '') {
		alert('必须选择一条以上');
		return false
	}
	var url = '<?php echo site_url('appeal/batch_handle?handle=1'); ?>'+'&appeal_ids='+appeal_ids;
	url = encodeURI(url);
	$obj.attr('href', url);
	$obj.attr('type', 'form');
	return true;
}

// 批量封号，屏蔽
function batchUserLock(obj){
	var $obj = $(obj);
	var appeal_ids = $("input[type='checkbox'][class!='checkAll'][name^='appeal_ids']:checked").map(function(){
		return $(this).val();
	}).get().join(",");
	
	if(appeal_ids === '') {
		alert('必须选择一条以上');
		return false
	}
	var url = '<?php echo site_url('appeal/batch_lock_user?'); ?>'+'appeal_ids='+appeal_ids;
	url = encodeURI(url);
	$obj.attr('href', url);
	$obj.attr('type', 'form');
	return true;
}
</script>
