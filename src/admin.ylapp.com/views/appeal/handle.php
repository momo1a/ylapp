	<?php if($handle):	//未处理申诉?>
	<form id="handleForm" action="" type="ajax" method="post" class="window_form" callback="reload">
	<?php else:	//已处理申诉?>
	<div class="window_form">
	<?php endif;?>
		<div class="h">
			<h3>申诉方</h3>
			<span>商品名称：</span>
			<div class="pingzhen clearfix">
          <a href="<?php echo create_fuzz_link($list['gid'], $list['goods_state'], $list['seller_uid'])?>" target="_blank" style="color:#0066CC;"><?php echo $list['title'];?></a>
			</div>
		</div>
		<div class="h">
			<span>申诉人：</span>
			<div class="pingzhen clearfix">
				<em style="color: #0066CC;"><?php echo $list['uname'];?></em><em style="color: #949494;">(<?php if($list['utype']==='2'): echo '商家'; elseif($list['utype']==='1'): echo '买家'; endif;?>)</em>
			</div>
		</div>
		<div class="h">
			<span>申诉时间：</span>
			<div class="pingzhen clearfix">
				<?php echo date("Y-m-d H:i:s", $list['dateline']);?>
			</div>
		</div>
		<div class="h">
			<span>申诉类型：</span>
			<div class="pingzhen clearfix">
				<?php echo $list['type_name'];?>
			</div>
		</div>
		<div class="h">
			<span>申诉理由：</span>
			<div class="pingzhen clearfix">
				<?php echo $list['content'];?>
			</div>
		</div>
		<?php if( $is_show_contact ) :?>
		<div class="h">
			<span>申诉凭证：</span>
			<div class="pingzhen clearfix">
				<?php $atts = explode(',', $list['attachement']);?>
				<?php if(strlen(is_array($atts))):?>
				<?php foreach ($atts as $k=>$v): if(strlen($v) >= 5):?>
				<a href="<?php echo image_url($i, $v)?>" target="_blank"><img src="<?php echo image_url($i, $v)?>" width="84" height="78" /></a>
				<?php endif; endforeach;?>
				<?php endif;?>
			</div>
		</div>
		<div class="h">
			<span>电话：</span>
			<div class="pingzhen clearfix">
				<?php 
				if($list['contact_telephone']){
					echo $list['contact_telephone'];
				}else{
					echo '-';
				}
				?>
			</div>
		</div>
		<div class="h">
			<span>旺旺：</span>
			<div class="pingzhen clearfix">
				<?php 
				if($list['contact_wangwang']){
					echo $list['contact_wangwang'];
				}else{
					echo '-';
				}
				?>
			</div>
		</div>
		<div class="h">
			<span>QQ：</span>
			<div class="pingzhen clearfix">
				<?php 
				if($list['contact_qq']){
					echo $list['contact_qq'];
				}else{
					echo '-';
				}
				?>
			</div>
		</div>
		<?php endif;?>
		<div class="h">
			<h3>被申诉方</h3>
			<span>被申诉人：</span>
			<div class="pingzhen clearfix">
				<?php if($list['utype']==='1'):?>
				<em style="color: #0066CC;"><?php echo $list['seller_uname'];?></em><em style="color: #949494;">(商家)</em>
				<?php elseif($list['utype']==='2'):?>
				<em style="color: #0066CC;"><?php echo $list['buyer_uname'];?></em><em style="color: #949494;">(买家)</em>
				<?php endif;?>
			</div>
		</div>
		<?php if($list['state'] === '1'):	//等待回应?>
		<div class="h">
			<span>回应时间：</span>
			<div class="pingzhen clearfix">
				<span style="width: auto; color:#FF6600;">等待回应</span>
			</div>
		</div>
		<div class="h">
			<span>回应内容：</span>
			<div class="pingzhen clearfix">
				<span style="width: auto; color:#FF6600;">等待回应</span>
			</div>
		</div>
		<div class="h">
			<span>回应凭证：</span>
			<div class="pingzhen clearfix">
				<span style="width: auto; color:#FF6600;">等待回应</span>
			</div>
		</div>
		<?php elseif($list['state'] === '2'):	//已回应?>
		<div class="h">
			<span>回应时间：</span>
			<div class="pingzhen clearfix">
				<?php echo date("Y-m-d H:i:s", $list['reply_time']);?>
			</div>
		</div>
		<div class="h">
			<span>回应内容：</span>
			<div class="pingzhen clearfix">
				<?php echo $list['reply_content'];?>
			</div>
		</div>
		<div class="h">
			<span>回应凭证：</span>
			<div class="pingzhen clearfix">
				<?php $atts = explode(',', $list['reply_attachement']);?>
				<?php if(strlen(is_array($atts))):?>
				<?php foreach ($atts as $k=>$v): if(strlen($v) >= 5):?>
				<a href="<?php echo image_url($i, $v)?>" target="_blank"><img src="<?php echo image_url($i, $v)?>" width="84" height="78" /></a>
				<?php endif; endforeach;?>
				<?php endif;?>
			</div>
		</div>
		<?php elseif($list['state'] === '3'):	//无需回应?>
		<div class="h">
			<span>回应时间：</span>
			<div class="pingzhen clearfix">
				<span style="width: auto; color:#FF6600;">无需回应</span>
			</div>
		</div>
		<div class="h">
			<span>回应内容：</span>
			<div class="pingzhen clearfix">
				<span style="width: auto; color:#FF6600;">无需回应</span>
			</div>
		</div>
		<div class="h">
			<span>回应凭证：</span>
			<div class="pingzhen clearfix">
				<span style="width: auto; color:#FF6600;">无需回应</span>
			</div>
		</div>
		<?php elseif($list['state'] === '4'):	//已处理?>
			<?php if($list['need_reply']):	//是否需要双方解决 0/1?>
		<div class="h">
			<span>回应时间：</span>
			<div class="pingzhen clearfix">
				<?php
				if($list['reply_time']){
					echo date("Y-m-d H:i:s", $list['reply_time']);
				}else{
					echo '-';
				}
				?>
			</div>
		</div>
		<div class="h">
			<span>回应内容：</span>
			<div class="pingzhen clearfix">
				<?php 
				if($list['reply_content']){
					echo $list['reply_content'];
				}else{
					echo '-';
				}
				?>
			</div>
		</div>
		<div class="h">
			<span>回应凭证：</span>
			<div class="pingzhen clearfix">
				<?php 
				if($list['reply_attachement']){
					$atts = explode(',', $list['reply_attachement']);
					if(strlen(is_array($atts))){
						foreach ($atts as $k=>$v){
							if(strlen($v) >= 5){
				?>
				<a href="<?php echo image_url($i, $v)?>" target="_blank"><img src="<?php echo image_url($i, $v)?>" width="84" height="78" /></a>
				<?php
							}
						}
					}
				}else{
					echo '-';
				}
				?>							
			</div>
		</div>
			<?php else :		//不需要双方解决?>
		<div class="h">
			<span>回应时间：</span>
			<div class="pingzhen clearfix">
				<span style="width: auto; color:#FF6600;">无需回应</span>
			</div>
		</div>
		<div class="h">
			<span>回应内容：</span>
			<div class="pingzhen clearfix">
				<span style="width: auto; color:#FF6600;">无需回应</span>
			</div>
		</div>
		<div class="h">
			<span>回应凭证：</span>
			<div class="pingzhen clearfix">
				<span style="width: auto; color:#FF6600;">无需回应</span>
			</div>
		</div>
			<?php endif;?>
		<?php endif;?>
		
		<?php if($handle):	//未处理申诉?>
		<div class="h">
			<h3>管理员处理申诉</h3>
			<span>处理结果：</span>
			<div class="pingzhen clearfix">
				<textarea name="result" data-rule="required" data-msg="请填写处理结果" style="width:360px;height:60px;"></textarea><br />
				<span id="for_result" style="width: auto;text-align:left;"></span>
			</div>
		</div>
		<div class="h">
			<span>处理类型：</span>
			<div id="action_type" style="display:block;float:left;width:360px;">
				<label data-action="<?php echo site_url('appeal/close');?>" onclick="checked_type(this)"><input type="radio" name="act_type" value="close" data-rule="required" data-msg="请选择处理类型" />恢复资格</label>&nbsp;
				<label data-action="<?php echo site_url('appeal/disqualification');?>" onclick="checked_type(this)"><input type="radio" name="act_type" value="cancel" />取消资格</label>&nbsp;
				<label data-action="<?php echo site_url('appeal/increase_deadline');?>" onclick="checked_type(this)"><input type="radio" name="act_type" value="addtime" />增加返现时间</label>&nbsp;
				<label data-action="<?php echo site_url('appeal/adjust_rebate');?>" onclick="checked_type(this)"><input type="radio" name="act_type" value="adjust_rebate"/>调整返现金额</label><br />
				<label data-action="<?php echo site_url('appeal/checkout');?>" onclick="checked_type(this)"><input type="radio" name="act_type" value="checkout" />直接返现</label>&nbsp;
				<label data-action="<?php echo site_url('appeal/tradeno_error');?>" onclick="checked_type(this)"><input type="radio" name="act_type" value="tradeno_error"/>订单号有误</label>
                 <label data-action="<?php echo site_url('appeal/tradeno_correct');?>" onclick="checked_type(this)"><input type="radio" name="act_type" value="tradeno_correct"/>订单号正确</label>
				<label data-action="<?php echo site_url('appeal/adjust_tradeno');?>" onclick="checked_type(this)"><input type="radio" name="act_type" value="adjust_tradeno"/>填写订单号</label><br />
				<span id="for_act_type" style="width: auto;text-align:left;"></span>
			</div>
		</div>
		<div class="h extinput_box" id="addtimebox" style="display:none;">
			<span>增加天数：</span>
			<div class="pingzhen clearfix">
				<span style="width: auto;"><input size="3" style="display: inline;" name="days" prefix="return $('input[name=\'act_type\']:checked').val()=='addtime'" data-rule="required|number|range(1,21)" data-msg="请填写增加返现天数|只能填写数字|请填写1-21的范围"/> 天</span>
				<span id="for_days" style="width: auto;text-align:left;"></span>
			</div>
		</div>
		<div class="h extinput_box" id="adjust_rebate_box" style="display:none;">
			<span>调整金额：</span>
			<div class="pingzhen clearfix">
				<span style="width: auto;">
				<input width="172" height="25" style="display: inline;" name="amount"
					prefix="return $('input[name=\'act_type\']:checked').val()=='adjust_rebate'"
					data-rule="required|amount|iszero(0)|adjustrebate(0,<?php echo $list['price'];?>,<?php echo $list['real_rebate']?>)"
					data-msg="请填写调整金额|金额只能为'+'、'-'数字或两位小数|调整金额不能为0|超出可调整金额范围"/> 元（活动价：￥<?php echo ($list['price']-$list['single_rebate'])?> 网购价：￥<?php echo $list['price']?> 返现金额：<?php echo '<em style="color:#CC3300;">￥'. $list['single_rebate'] .'</em>'?>）</span>
				<span id="for_amount" style="width: auto;text-align:left;"></span>
				<br /><span style="color:#949494; width:35em; text-align:left;">调整返现金额说明：正数的时候，是从商家担保金扣除相应金额返回给买家；负数的时候，是从买家返现金额中扣除相应金额返回给商家。</span>
			</div>
		</div>
		<div class="h extinput_box" id="adjust_tradeno_box" style="display:none;">
			<span>填写订单号：</span>
			<div class="pingzhen clearfix">
				<span style="width: auto;"><input size="35" style="display: inline;" name="tradeno" prefix="return $('input[name=\'act_type\']:checked').val()=='adjust_tradeno'" data-rule="required" data-msg="请填写订单号"/></span>
				<span id="for_tradeno" style="width: auto;text-align:left;"></span>
			</div>
		</div>
		<?php else:	//已处理申诉?>
		<div class="h">
			<h3>管理员处理申诉</h3>
			<span>处理结果：</span>
			<div class="pingzhen clearfix">
				<p><?php
				if($list['result_content']){
					echo $list['result_content'];
				}else{
					echo '-';
				}
				?></p>
			</div>
		</div>
		<div class="h">
			<span>管理员操作：</span>
			<div id="action_type" style="display:block;float:left;width:360px;">
				<?php if($list['result_action']==='1'):?>
				<span style="width: auto; color:#FF6600;">取消资格</span>
				<?php elseif($list['result_action']==='2'):?>
				<span style="width: auto; color:#FF6600;">调整返现金额：<?php echo isset($adjust_rebate_log['adjust_rebate']) ? $adjust_rebate_log['adjust_rebate'] : $list['adjust_rebate'];?>元</span>
				<br /><span style="color:#949494; width:35em; text-align:left;">调整返现金额说明：正数的时候，是从商家担保金扣除相应金额返回给买家；负数的时候，是从买家返现扣除相应金额返回给商家。</span>
				<?php elseif($list['result_action']==='3'):?>
				<span style="width: auto; color:#FF6600;">增加返现时间</span>
				<?php elseif($list['result_action']==='4'):?>
				<span style="width: auto; color:#FF6600;">直接返现</span>
				<?php elseif($list['result_action']==='5'):?>
				<span style="width: auto; color:#FF6600;">恢复资格</span>
				<?php elseif($list['result_action']==='6'):?>
				<span style="width: auto; color:#FF6600;">填写订单号</span>
                <?php elseif($list['result_action']==='9'):?>
				<span style="width: auto; color:#FF6600;">单号正确</span>
				<?php elseif($list['result_action']==='7'):?>
				<span style="width: auto; color:#FF6600;">单号有误</span>
				<?php elseif($list['result_action']==='8'):?>
				<span style="width: auto; color:#FF6600;">直接返现</span>
				<?php endif;?>
				</div>
		</div>
		<?php endif;?>
		<input type="hidden" value="yes" name="dosave" />
		<input type="hidden" value="<?php echo $id;?>" name="id">
		
	<?php if($handle):?>
	</form>
	<?php else:?>
	</div>
	<?php endif;?>