<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>

<div class="ui-box ui-box2 checkoutList"><div class="ui-box-outer"><div class="ui-box-inner"> 

<?php if ($user): ?>
<div class="ui-box-head">当前位置：
	<a href="<?php echo site_url('user/index/seller')?>">会员管理</a>&nbsp>&nbsp;
	<?php if($user['utype'] == 1):?>
		<a href="<?php echo site_url('user/index/buyer')?>">买家</a>
	<?php else:?>
		<a href="<?php echo site_url('user/index/seller')?>">商家</a>
	<?php endif;?>
	&nbsp>
	<?php 
		// 搜索用户名
		$uname_url = site_url('user/index/'.($user['utype']==1?'buyer':'seller').'?search_key=uname&search_value='.urlencode($user['uname']).'&_='.rand()); 
	?>
	<a href="<?php echo $uname_url ?>"><?php echo $user['uname']?></a>&nbsp>&nbsp;
	详细信息</div>
<div class="ui-box-body">
	<table width="100%"  border="0">
		<col span="1"  style="width: 10%;"/>
		<col span="1" style="width: 40%;"/>
		<col span="1" style="width: 10%;"/>
		<col span="1" style="width: 40%;"/>
		<tr>
			<td align="right" >会员名：</td>
			<td><?php echo $user['uname'] ?>
			<?php if (isset($user['bind_taobao']) AND $user['bind_taobao']):?>
			<img src="<?php echo config_item('domain_static')?>images/admin/bind_taobao.png">
			<?php endif;?>
			<?php if (isset($user['login_binds']['qq']) && is_array($user['login_binds']['qq'])):?>
			<img src="<?php echo config_item('domain_static')?>images/admin/login_bind_qq.png">
			<?php endif;?>
			&nbsp;&nbsp;&nbsp;&nbsp;等级：/&nbsp;&nbsp;&nbsp;&nbsp;积分：/</td>
			<td align="right" >出生地：</td>
			<td>/</td>
		</tr>
		<tr>
			<td align="right" >用户ID：</td>
			<td><?php echo $user['uid']?></td>
			<td align="right" >居住地：</td>
			<td>/</td>
		</tr>
		<tr>
			<td align="right" >注册时间：</td>
			<td><?php echo date('Y-m-d H:i:s',$user['dateline']);?></td>
			<td align="right" >常用QQ：</td>
			<td><?php echo $user['qq']?$user['qq']:'/'?></td>
		</tr>
		<tr>
			<td align="right" >绑定手机：</td>
			<td><?php echo $user['mobile']?$user['mobile']:'/'?>
			<?php if($user['mobile_valid']==1) {?>
			<a type="form" href="<?php echo site_url('user/unbind_mobile')?>"  data-uid="<?php echo $user['uid']; ?>" callback="reload" style="color: #0066FF;" title="解除用户绑定的手机" >解除绑定</a>|
      		<?php }?>
      		<a type="dialog" href="<?php echo site_url('user/bind_mobile_log/?uid='.$user['uid']);?>" style="color: #0066FF;" title="绑定手机记录">绑定记录</a>

			</td>
			<td align="right" >常用旺旺：</td>
			<td><?php echo $user['wangwang']?$user['wangwang']:'/'?></td>
		</tr>
		<tr>
			<td align="right" >绑定邮箱：</td>
			<td><?php echo $user['email']? $user['email']:'/' ?></td>
			<td align="right" >收货地址：</td>
			<td>/</td>
		</tr>
		<tr>
			<td align="right">真实姓名：</td>
			<td id="true_name" onclick='true_name();'><a href="javascript:void(0);" style="color: #0066FF;">查看真实姓名</a></td>
			<td align="right" >兴趣爱好：</td>
			<td>/</td>
		</tr>
		<tr>
			<td align="right" >身份证：</td>
			<td id="true_name_auth" onclick='show_auth();'><a href="javascript:void(0);" style="color: #0066FF;">查看认证情况<a/></td>
			<td align="right" >注册来源：</td>
			<td><?php if($user['reg_source']==1){echo '试客联盟';}elseif($user['reg_source']==2){echo '互联支付';}elseif($user['reg_source']==3){echo '众划算';}else{echo '-';}?></td>
		</tr>
		<?php if($user['utype']==YL_user_model::USER_TYPE_SELLER):?>
		<tr>
			<td align="right" >所属伙伴：</td>
			<td><?php echo $user_seller['salesman_uname'] ? $user_seller['salesman_uname']:'-';?>&nbsp;&nbsp;<a type="form" href="<?php echo site_url('user/save_salesman_uname')?>" data-uid="<?php echo $user['uid']; ?>" callback="reload" style="color: #0066FF;" title="修改" >修改</a></td>
		</tr>
		<?php endif;?>
		<?php if($user['utype']==1):?>
		<tr>
			<td align="right" valign="top">认证淘宝：</td>
			<td colspan="3">
				<table class="ui-table" style="width:450px">
					<?php if(is_array($user['bind_taobao']) && count($user['bind_taobao'])):?>
					<?php foreach ($user['bind_taobao'] as $k=>$v):?>
					<tr>
						<td style="padding:3px 0;"><?php echo $v['bind_name']; ?></td>
						<td style="padding:3px 0;"><?php echo str_replace(',', '<br />', $v['bind_note']); ?></td>
						<td style="padding:3px 0; width:60px;">
							<a type="form" href="<?php echo site_url('user/bind_taobao_reset')?>" data-id="<?php echo $v['id']; ?>" data-uid="<?php echo $v['uid']; ?>" callback="reload" title="重置已认证的淘宝账户">重置</a>
						</td>
					</tr>
					<?php endforeach;?>
					<?php endif;?>
					<tr>
						<td colspan="3" style="padding:3px 8px;text-align:right;">
							<a type="dialog" href="<?php echo site_url('user/bind_taobao_log')?>" data-uid="<?php echo $user['uid']; ?>">认证记录</a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<?php if (isset($user['login_binds']) && $user['login_binds']):?>
		<?php foreach ($user['login_binds'] as $bind_type=>$bind):?>
		<?php $bind_type = strtoupper($bind_type);?>
		<tr><td colspan="4" height="10"></td></tr>
		<tr>
			<td align="right" valign="top"><b>绑定<?php echo $bind_type;?>：</b></td>
			<td>
				<?php if (is_array($bind)):?>
				<table class="ui-table" style="width:450px">
					<tr>
						<td>
						<?php echo $bind['nickname'], '(', gender_int2string($bind['gender']), ')', '<br />', $bind['open_id'];?>
						</td>
						<td>
						<a type="form" callback="reload" title="强制解除绑定<?php echo $bind_type;?>" href="<?php echo site_url('user/un_login_bind/'.$bind['type'].'/'.$bind['uid'])?>" style="color: #0066FF;">解除绑定</a> | <a type="dialog" href="<?php echo site_url('user/login_bind_log/'.$user['uid'].'/'.$bind['type']);?>" style="color: #0066FF;">绑定记录</a>
						</td>
					</tr>
				</table>
				<?php else:?>
				<table class="ui-table" style="width:450px">
					<tr>
						<td>
						-
						</td>
						<td>________<a type="dialog" href="<?php echo site_url('user/login_bind_log/'.$user['uid'].'/'.$bind);?>" style="color: #0066FF;">绑定记录</a></td>
					</tr>
				</table>
				<?php endif;?>
			</td>
			<td colspan="2">
				
			</td>
		</tr>
		<?php endforeach;?>
		<?php endif;?>
		<!-- 登录绑定信息 end-->
		<?php endif;?>
	</table>
</div>
<?php else: ?>
	<label>暂无用户信息</label>
<?php endif;?>

<div class="ui-box-body ui-tab">
	<ul class="ui-tab-nav">
		<?php if($user['utype'] == 2):?>
		<li class="ui-tab-item <?php if($type_id =='goods') echo ' ui-tab-itemCurrent'?>"><a href="javascipt:;" onclick="return false">所有活动(<?php echo $data_count['goods_count'];?>)</a></li>
		<?php endif;?>
		<li class="ui-tab-item <?php if($type_id =='order') echo ' ui-tab-itemCurrent'?>" data-type_id="order"><a href="javascipt:;" onclick="return false">抢购参与 (<?php echo $data_count['order_count'];?>)</a></li>
		<li class="ui-tab-item <?php if($type_id =='appeal_post') echo ' ui-tab-itemCurrent'?>" data-type_id="appeal_post"><a href="javascipt:;" onclick="return false">发起申诉(<?php echo $data_count['appeal_post_count'];?>)</a></li>
		<li class="ui-tab-item <?php if($type_id =='appeal_receive') echo ' ui-tab-itemCurrent'?>" data-type_id="appeal_receive"><a href="javascipt:;" onclick="return false">收到申诉(<?php echo $data_count['appeal_receive_count'];?>)</a></li>
	</ul><!-- /ui-tab-nav -->
	<div class="ui-tab-cont">
		<?php if($user['utype'] == 1) :?>
		<div id="user_list_order" class="ui-tab-panel" >
			<?php $this->load->view('user/order_list');?>
		</div><!-- /ui-tab-panel -->
		<?php else:?>
		<div id="user_list_goods" class="ui-tab-panel" >
			<?php $this->load->view('user/seller_goods');?>
		</div><!-- /ui-tab-panel -->
		<div id="user_list_order" class="ui-tab-panel" >
		</div><!-- /ui-tab-panel -->
		<?php endif;?>
		<div id="user_list_appeal_post" class="ui-tab-panel" style="display:none;">
		</div><!-- /ui-tab-panel -->
		<div id="user_list_appeal_receive" class="ui-tab-panel" style="display:none;">
		</div><!-- /ui-tab-panel -->
	</div><!-- /ui-tab-cont -->
</div>

</div></div></div>

<script type="text/javascript">
	/*选项卡功能*/
	$(function(){
		$(".ui-tab-item").click(function(){
			var $this = $(this);
			if($this.data()['type_id']){
				// 第一次加载内容
				load("<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$uid);?>", "div#user_list_"+$this.data()['type_id'], {type_id:$this.data()['type_id'], listonly:'yes'});
				$this.removeData('type_id');
			}
			$this.addClass("ui-tab-itemCurrent").siblings($this.selector).removeClass("ui-tab-itemCurrent");
			var $panel = $this.closest(".ui-tab").find(".ui-tab-panel").eq($this.index());//获取对应的panel
			$panel.show().siblings(".ui-tab-panel").hide();
		});
	});

	function true_name()
	{
		$.ajax({
			url:"/user/true_name/"+<?php echo $user['uid'];?>,
			type : "get",
			dataType:"json",
			error:function(){
				PopupTips('服务器繁忙，请重试', 'notice', 2000);return;
			},
			success:function(ret){
				if(ret.type=='ACCESS_DENY'){
					PopupTips('您无此操作权限', 'notice',3000);return;
				}
				if(ret.true_name)
					$("#true_name").html(ret.true_name);
				else
					$("#true_name").html('/');
			}
		});
	}

	function show_auth()
	{
		$.ajax({
			url:"/user/true_name/"+<?php echo $user['uid'];?>,
			type : "get",
			dataType:"json",
			error:function(){
				PopupTips('服务器繁忙，请重试', 'notice', 2000);return;
			},
			success:function(ret){
				if(ret.type=='ACCESS_DENY'){
					PopupTips('您无此操作权限', 'notice',3000);return;
				}
				if(ret.is_true_name_auth==1)
					$("#true_name_auth").html('已认证');
				else
					$("#true_name_auth").html('未认证');
			}
		});
	}
	
	//处理申诉
	function checked_type(e){
		var $this = $(e);
		$this.closest('form').attr('action',$this.data()['action']);
		$("div.extinput_box").hide();
		if('addtime' == $this.find('input').val()){
			$("div#addtimebox").show();
		}
		if('adjust_rebate' == $this.find('input').val()){
			$("div#adjust_rebate_box").show();
		}
		if('adjust_tradeno' == $this.find('input').val()){
			$("div#adjust_tradeno_box").show();
		}
	}
	MyRule.amount=/^(\+|-)?\d+(\.\d{1,2})?$/;

</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>