<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 现金券管理后台控制器
 * @property cash_model $cash_model
 * @property hlpay $hlpay
 * 
 * @author 关小龙
 * @version 2014-07-18 11:30:00
 */
class Cash extends MY_Controller 
{
	/**
	 * 互联支付来源站点
	 * @var int
	 */
	public $shsSiteId;
	
	/**
	 * 现金券给买家打款帐号UID
	 * @var int
	 */
	public $cash_pay_uid;
	
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('cash_model');
		$this->shsSiteId    = $this->cash_model->shsSiteId;
		$this->cash_pay_uid = $this->cash_model->cash_pay_uid;
	}
	
	/**
	 * 现金券发放不同页面显示
	 */
	public function index( $send_type )
	{
		switch( $send_type ){
			case 'cash_send':
				$cash_info = $this->cash_info();
				$this->load->view('cash/hand_send', get_defined_vars());
				break;
			case 'bath_send':
				$cash_info = $this->cash_info();
				$this->load->view('cash/bath_send', get_defined_vars());
				break;
			case 'code_send':
				$cash_info = $this->cash_info();
				$this->load->view('cash/code_send', get_defined_vars());
				break;
			case 'cash_type':
			    $this->cash_type();
				break;
			case 'detail_send':
				$this->detail_send();
				break;
			case 'cash_cdkey':
				$this->cash_cdkey();
				break;
		}
	}
	
	/**
	 * 添加现金券类型
	 */
	public function add_cash_type()
	{
		$goods_category = $this->db->select('id,name')->from('goods_category')->where('pid','0')->get()->result_array();
		if('is_post' == $this->input->get_post('is_post')){
			$cash = $this->get_post_cash();
			$this->check_cash_post($cash); //检测提交表单的合法性规则等
			
			$this->db->trans_begin();//开始事务
			$cid = $this->cash_model->save_cash_info( $cash ); //保存现金券的信息，得到现金券ID
			$this->cash_model->add_cash_timeout_tasktimer( $cid,strtotime($cash['valid_end_time']) );//添加现金券过期处理定时任务
			$this->cash_model->add_timeout_remind_tasktimer( $cid , strtotime($cash['valid_start_time']) , strtotime($cash['valid_end_time']) );//添加现金券消息提醒定时任务
	 		$this->cash_model->save_log(array('cid'=>$cid,'uid'=>'0','pay_id'=>'0','admin_uid' =>$this->user_id,'uname'=>$this->username,'content'=>'添加现金券类型'));
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$this->error('现金券类型添加失败，请重新生成！');
			}else{
				$this->db->trans_commit();
				$this->success('现金券类型添加成功！');
			}
		}else{
			$this->load->view('cash/dialog/add_cash_type',get_defined_vars());
		}
	}
	
	/**
	 * 获取现金券表单提交函数，解决为未定义报错问题
	 * @return array 现金券数组
	 */
	private function get_post_cash()
	{
		$cash_data = array(
		  'cname'=>'',
		  'ctitle'=>'',
		  'cprice'=>'0',
		  'not_limit'=>'0',
		  'is_time_limit'=>'0',
		  'time_limit_start_time'=>'0',
		  'time_limit_end_time'=>'0',
		  'category_id'=>'0',
		  'category_name'=>'',
		  'is_phone'=>'0',
		  'sum_price'=>'0',
		  'sum_cost_price'=>'0',
		  'sum_rebate'=>'0',
		  'valid_start_time'=>'0',
		  'valid_end_time'=>'0',
		  'is_reg_time'=>'0',
		  'reg_start_time'=>'0',
		  'reg_end_time'=>'0',
		  'is_phone_reg'=>'0',
		  'is_last_order_time'=>'0',
		  'last_order_start_time'=>'0',
		  'last_order_end_time'=>'0',
		  'is_sum_price'=>'0',
		  'sum_price_or'=>'0',
		  'send_sum_price'=>'0',
		  'sum_price_start_time'=>'0',
		  'sum_price_end_time'=>'0',
		  'is_sum_cost_price'=>'0',
		  'sum_cost_price_or'=>'0',
		  'send_sum_cost_price'=>'0',
		  'sum_cost_price_start_time'=>'0',
		  'sum_cost_price_end_time'=>'0',
		  'is_sum_rebate'=>'0',
		  'sum_rebate_or'=>'0',
		  'send_sum_rebate'=>'0',
		  'sum_rebate_start_time'=>'0',
		  'sum_rebate_end_time'=>'0',
		  'settled'=>'0',
		  'bath_remain_quantity'=>'0',
		  'bath_used_quantity'=>'0',
		);
		$cash = $this->input->post('cash',TRUE);
		return array_merge($cash_data,$cash);//提交的cash数组覆盖定义的
	}
	
	/**
	 * 显示现金券下拉框
	 */
	public function cash_info()
	{
		$this->db->where('valid_end_time >',time());
		return $this->cash_model->cash_info();
	}
	
	/**
	 * 选择下拉框显示不同的现金券内容
	 * @param Int $cid
	 */
	public function show_cash( $cid )
	{
		$cash = $this->cash_model->show_cash( $cid );
		$this->load->view('cash/show_cash',get_defined_vars());
	}
	
	/**
	 * 手动发放 - 处理表单提交的数据
	 */
	public function hand_send()
	{
		$cash = $this->input->post('cash',TRUE);
		$cid = intval( $cash['cid'] );
		if( !$cid ){
			$this->error('还没有选择现金券类型');
		}
		
		$cash_info = $this->cash_model->show_cash( $cid );
		$users = $this->explode_user( $cash['users'],$cid );
		
		$quantity = count( $users );
		$money = bcmul( $cash_info['cprice'], $quantity, 2 );
		
		$this->db->trans_begin();//开始事务
		$pay_id = $this->cash_model->save_cash_pay( $cid , $quantity , $money ,$send_type=1 );//生成支付现金券担保金信息
		$this->cash_model->save_cash_users( $users , $cid , $pay_id ,$send_type=1 );   //保存现金券对应的用户信息
		$this->cash_model->save_log(array('cid'=>$cid,'uid'=>'0','pay_id'=>$pay_id,'admin_uid' =>$this->user_id,'uname'=>$this->username,'content'=>'手动发布现金券'));
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$this->error('现金券手动发放失败，请重新发放！');
		}else{
			$this->db->trans_commit();
			$this->success('现金券手动发放成功！',array('pay_id'=>$pay_id));
		}
	}
	
	/**
	 * 批量发放现金券  - 处理表单提交的数据
	 */
	public function bath_send()
	{
		$cash = $this->input->post('cash',TRUE);
		$cid = intval( $cash['cid'] );
		if( !$cid ){
			$this->error('还没有选择现金券类型');
		}
		$cash_info = $this->cash_model->show_cash( $cid );
		$cash_bath = $cash_info['is_reg_time'] + $cash_info['is_phone_reg'] + $cash_info['is_last_order_time'] + $cash_info['is_sum_price'] + $cash_info['is_sum_cost_price'] + $cash_info['is_sum_rebate'];
		if( $cash_bath==0 ){
			$this->error('请先设置批量发放的条件！');
		}
		
		$quantity = intval($cash['quantity']);
		if( $quantity<=0 || $quantity>20000 ){
			$this->error('发放数量最小值为1,最大值为20000');
		}
		$money = bcmul( $cash_info['cprice'], $quantity, 2 );
		
		$this->db->trans_begin();//开始事务
		$pay_id = $this->cash_model->save_cash_pay( $cid , $quantity , $money ,$send_type=2);//生成支付现金券担保金信息
		$this->cash_model->save_log(array('cid'=>$cid,'uid'=>'0','pay_id'=>$pay_id,'admin_uid' =>$this->user_id,'uname'=>$this->username,'content'=>'批量发放现金券'));
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$this->error('现金券批量发放失败，请重新发放！');
		}else{
			$this->db->trans_commit();
			$this->success('现金券批量发放成功！',array('pay_id'=>$pay_id));
		}
	}
	
	/**
	 * 兑换码发放  - 处理表单提交的数据
	 */
	public function code_send()
	{
		$cash = $this->input->post('cash',TRUE);
		$cid = intval( $cash['cid'] );
		if( !$cid ){
			$this->error('还没有选择现金券类型');
		}
		$cash_info = $this->cash_model->show_cash( $cid );
		
		$quantity = intval($cash['quantity']);
		if( $quantity<=0 || $quantity>20000 ){
			$this->error('发放数量最小值为1,最大值为20000');
		}
		$money = bcmul( $cash_info['cprice'], $quantity, 2 );
		
		$this->db->trans_begin();//开始事务
		$pay_id = $this->cash_model->save_cash_pay( $cid , $quantity , $money , $send_type=3 ); //生成支付现金券担保金信息
		$this->cash_model->save_cash_cdkey( $cid , $quantity ,$pay_id);   //保存现金券对应兑换码的信息
		$this->cash_model->save_log(array('cid'=>$cid,'uid'=>'0','pay_id'=>$pay_id,'admin_uid' =>$this->user_id,'uname'=>$this->username,'content'=>'兑换码发放现金券'));
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$this->error('兑换码发放失败，请重新发放！');
		}else{
			$this->db->trans_commit();
			$this->success('兑换码发放成功！',array('pay_id'=>$pay_id));
		}
	}
	
	/**
	 * 显示现金券发放记录
	 */
	private function detail_send()
	{
		$search_key = $this->get_post('search_key');
		$search_val = trim($this->get_post('search_value'));
	
		$segment = $this->uri->segment(4) ? $this->uri->segment(4) : '10';
		$offset = $this->uri->segment(5);
		$cash_data =  $this->cash_model->cash_send_detail($search_key,$search_val,$ext_where = '',$segment, $offset);
		// 分页相关
		$items_count = $this->cash_model->cash_send_detail_count($search_key,$search_val,$ext_where = '');
		$page_conf = array('uri_segment'=>5,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$this->uri->segment(3).'/'.$segment);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$this->uri->segment(3).'/'.$segment.'/0');
		$pager = $this->pager($items_count, $segment, $page_conf);
	
		$this->load->view('cash/detail_send' , get_defined_vars());
	}
	
	/**
	 * 导出发放的现金券用户
	 */
	public function export_cash_send()
	{
		$pay_id    = $this->input->get_post('pay_id');
		$cid       = $this->input->get_post('cid');
		$send_type = $this->input->get_post('send_type');
	
		$data = $this->cash_model->export_cash_send_data( $pay_id,$cid,$send_type );
	
		$title = '现金券发放数据导出';
		$filename = '现金券发放数据导出.xls';
	
		$header = $send_type==3 ? array('现金券类型','兑换码','用户ID','用户名称') : array('现金券类型','用户ID','用户名称');
	
		array_unshift($data, $header);
		$this->data_export($data, $title, $filename);
	}
	
	/**
	 * 显示现金券类型记录页面
	 */
	public function cash_type()
	{
		$search_key = $this->get_post('search_key');
		$search_val = trim($this->get_post('search_value'));
		
		$segment = $this->uri->segment(4) ? $this->uri->segment(4) : '10';
		$offset = $this->uri->segment(5);
		$size = $segment;
		
		$cash_info =  $this->cash_model->search_cash_type($search_key, $search_val, $ext_where = '', $size, $offset, $order = 'cid DESC');
		// 分页相关
		$items_count = $this->cash_model->search_cash_type_count($search_key, $search_val, $ext_where = '');
		$page_conf = array('uri_segment'=>5,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$this->uri->segment(3).'/'.$segment);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$this->uri->segment(3).'/'.$segment.'/0');
		$pager = $this->pager($items_count, $size, $page_conf);
		
		$this->load->view('cash/cash_type',get_defined_vars());
	}
	
	/**
	 * 编辑批量发放现金券的条件
	 */
	public function edit_bath_send()
	{
		$cid = $this->get_post( 'cid' );
		$cash_info = $this->cash_model->show_cash( $cid );
		if('is_post' == $this->input->get_post('is_post')){
			$cash = $this->get_post_cash();
			$res = $this->cash_model->update_cash_info( $cid , $cash ); //更新现金券批量发放条件
			if( $res ){
				$this->success('现金券批量发放条件修改成功！');
			}else{
				$this->error('现金券批量发放条件修改失败！');
			}
		}else{
			$this->load->view('cash/dialog/edit_bath_send',get_defined_vars());
		}
	}
	
	/**
	 * 显示现金券批量发放条件
	 */
	public function bath_send_detail()
	{
		$cid = $this->input->get_post('cid');
		$cash_info = $this->cash_model->show_cash( $cid );
		$this->load->view('cash/dialog/bath_send_detail',get_defined_vars());
	}
	
	/**
	 * 显示现金券兑换码记录页面
	 */
	public function cash_cdkey()
	{
		$search_key = $this->get_post('search_key');
		$search_val = trim($this->get_post('search_value'));
	
		$segment = $this->uri->segment(4) ? $this->uri->segment(4) : '10';
		$offset = $this->uri->segment(5);
		$size = $segment;
	
		$cash_info =  $this->cash_model->search_cash_cdkey($search_key, $search_val, $ext_where = '', $size, $offset, $order = 'cash_cdkey.dateline DESC,cash_cdkey.id ASC');
		// 分页相关
		$items_count = $this->cash_model->search_cash_cdkey_count($search_key, $search_val, $ext_where = '');
		$page_conf = array('uri_segment'=>5,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$this->uri->segment(3).'/'.$segment);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$this->uri->segment(3).'/'.$segment.'/0');
		$pager = $this->pager($items_count, $size, $page_conf);
	
		$this->load->view('cash/cash_cdkey',get_defined_vars());
	}
	
	/**
	 * 修改现金券操作码
	 */
	public function password()
	{
		if('is_post' == $this->input->get_post('is_post')){
			$old_pwd =md5($this->get_post('old_pwd',''));
			$new_pwd = md5($this->get_post('new_pwd',''));
			
			$db_old_pwd = $this->db->select('value')->from('system_config')->where('key','admin_cash_password')->get()->row_array();
			if ($old_pwd != $db_old_pwd['value']) $this->error('当前现金券操作码不正确！');

			$this->db->where('key','admin_cash_password')->update('system_config',array('value'=>$new_pwd));
			$this->success('修改操作码成功！');
		}else{
			$this->load->view('cash/dialog/password');
		}
	}
	
	/**
	 * 对待打款的用户进行打款
	 */
	public function pay_user()
	{
		$ids = $this->get_post('ids');
		
		if('is_post' == $this->input->get_post('is_post')){
			$op_password =  trim($this->input->post('op_password', TRUE));
			$db_pwd = $this->db->select('value')->from('system_config')->where('key','admin_cash_password')->get()->row_array();
			if ( md5($op_password) != $db_pwd['value'] ) $this->error('输入的现金券操作码不正确！');
			
			$ids = explode(',', $ids);
			foreach( $ids as $oid ){
				$res = $this->cash_model->cash_user_info(array('id'=>$oid),'state,cid');
				if( $res['state']!=2 ){
					$this->error('只有待打款的状态才能打款');
				}
				$pay_res = $this->cash_model->get_pay_user($oid);
				if( $pay_res['state']==3 ){
					$this->error('已经完成打款的不能再次打款');
				}
				if( $pay_res['state']==2 ){
					$this->error('打款订单在处理中，不能打款');
				}
			}
			
			foreach( $ids as $oid ){
				$this->db->update ( 'cash_user_pay', array('state'=>'2') , array('oid' => $oid) );//打款前把订单状态改成2(处理中)，防止同时操作
				$pay_user_info[] = $this->cash_model->get_pay_user($oid);
			}
			
			$this->load->library('hlpay');
			foreach( $pay_user_info as $v ){
				$data = array(
					'uid'          => $v['buyer_uid'],
					'pNo'          => $v['pno'],
					'paypNo'       => $v['paypno'],
					'title'        => '支付现金券“'.$this->cash_model->get_cash_title($v['cid']).'”金额',
					'money'        => $v['money'],
				);
				$back_int = (int)$this->hlpay->hlpay_deduct_cash( $data );
				if( $back_int > 0 ){
					$this->cash_model->change_pay_order($v['oid'] ,$state=3); //打款成功，把状态改为3(处理完成)
					$this->cash_model->chang_user_state($state=3,array('id'=>$v['oid'])); //把用户的现金券状态改成3(已兑换)
					$this->cash_model->del_timeout_pay_tasktimer($v['oid']); //管理员已经打款了，则删除定时打款任务
					$this->cash_model->save_log(array('cid'=>$v['cid'],'uid'=>$v['buyer_uid'],'admin_uid' =>$this->user_id,'uname'=>$this->username,'content'=>'打款给买家'));
				}else{
					$this->cash_model->change_pay_order($v['oid'] ,$state=1); //打款不成功，则把订单状态改为1(未处理)
				}
			}
			$this->success('打款完成，请查看操作后结果！');
		}else{
			$this->load->view('cash/dialog/pay_user',get_defined_vars());
		}
	}
	
	/**
	 * 支付冻结现金券担保金
	 */
	public function pay()
	{
		$id = intval( $this->get_post('id',TRUE) );
		$pay_info = $this->cash_model->get_pay_info( array('id'=>$id) );
		if( !$pay_info ){
			$this->error('支付现金券不存在');
		}
		if( $pay_info['valid_end_time'] < time() ){
			$this->error('现金券已经过期，不能付款');
		}
		if( $pay_info['state'] == 3 ) $this->error('该现金券已经支付担保金');
		 
		
		$this->load->library('hlpay');
		$user_money = $this->hlpay->get_user_money($this->cash_pay_uid);
		if($user_money === FALSE){
			show_message('系统繁忙，请重试');
		}
		//诚信金总需要的钱
		$pay_money = bcadd($pay_info['money'], 0, 2); 
		//差额
		$sub_money = bcsub($user_money, $pay_money, 2);
		if($sub_money < 0){  //跳到充值页面
			$recharge_money = bcadd(abs($sub_money), 0, 2);
			$key = md5($this->cash_pay_uid.$recharge_money.KEY_HLPAY);
			$backurl = site_url('cash/index/detail_send');
			
			//充值-表单数据
			$data = array(
					'uid' => $this->cash_pay_uid,
					'type' => $this->shsSiteId,
					'title' => '现金券充值',
					'money' => $recharge_money,
					'sign' => $key,
					'backurl' => $backurl
			);
			$this->load->vars('data', $data);
			$this->load->view('cash/cash_pay_recharge');
		}else{
			$this->db->update('cash_pay',array('state'=>2),array('id'=>$id)); //把订单状态改成2(支付中)
			
			$money = $pay_info['money'];
			$pno = $pay_info['pno'];
			$type = 4;//接口操作类型
			$notifyurl = $this->config->item('domain_handle').'handle/cash_notify/'; //回调地址，处理众划算业务
			$backurl = site_url().'cash/index/detail_send/'; //支付成功后跳转地址
			$key = md5($this->shsSiteId.$money.$this->cash_pay_uid.$pno.KEY_HLPAY);
			$data = array(
				'site'      => $this->shsSiteId,
				'uid'       => $this->cash_pay_uid,
				'type'      => $type,
				'title'     => '冻结现金券“'.$pay_info['ctitle'].'”担保金',
				'pno'       => $pno,
				'money'     => $money,
				'key'       => $key,
				'notifyurl' => $notifyurl,
				'backurl'   => $backurl,
				'temp1'     => '',
			);
			$this->load->vars('data', $data);
			$this->load->vars('id', $id);
			$data = get_config();
			$this->load->view('cash/cash_payment_transit');
		}
	}
	
	/**
	 * 结算现金券冻结金额
	 */
	public function freezerecord()
	{
		$cid = $this->get_post('cid');
		$cash_info = $this->cash_model->show_cash($cid);
		if( $cash_info['valid_end_time'] > time() ){
			$this->error('现金券没过期，不能结算！');
		}
		
		if('is_post' == $this->input->get_post('is_post')){
			$op_password =  trim($this->input->post('op_password', TRUE));
			$db_pwd = $this->db->select('value')->from('system_config')->where('key','admin_cash_password')->get()->row_array();
			if ( md5($op_password) != $db_pwd['value'] ) $this->error('输入的现金券操作码不正确！');
			
			$this->load->library('hlpay');
			$cash_pay_info = $this->db->select('*')->from('cash_pay')->where( array('cid'=>$cid,'type'=>'1','state'=>'3') )->get()->result_array();
			//根据cid结算每一个支付冻结金额的订单
			foreach( $cash_pay_info as $pay_info ){
				$data = $this->get_freeze($pay_info); //结算每一个支付的订单，获得未兑换的金额和pno
				$this->hlpay->yzcm_refund( $data['uid'],$data['pno'],$data['title'],$data['money'],$deposit_type=3 );
				$this->db->update('cash_pay',array('state'=>'4','doneline'=>time()),array('pno'=>$pay_info['pno']));
			}
			if( $this->is_settled( $cid ) ){
				$this->db->update ( 'cash', array('settled'=>'1'), array('cid' => $cid) );
				$this->success('现金券结算成功！');
			}else{
				$this->success('现金券结算未完成，请重新结算！');
			}
		}else{
			$url = site_url('cash/freezerecord');
			$this->load->view('cash/dialog/freezerecord',get_defined_vars());
		}
	}
	
	/**
	 * 获取每一笔现金券结算的请求数据
	 */
	private function get_freeze($pay_info)
	{
		$cash_info = $this->cash_model->show_cash( $pay_info['cid'] );
		$freeze_info = $this->cash_model->cash_pay_info( array('pno'=>$pay_info['pno'],'type'=>'2') , 'pno,money' );
		if( !$freeze_info ){
			//计算出一个订单号对应已经兑换的数量
			$pay_cout = (int)$this->db->select('id')->from('cash_user')->where( array('cid'=>$pay_info['cid'],'state'=>'3','send_type'=>$pay_info['send_type'],'pay_id'=>$pay_info['id'],'pay_state'=>'1') )->get()->num_rows();
			
			$pno = $pay_info['pno'];
			$quantity = $pay_info['quantity'] - $pay_cout;
			$money = bcmul( $cash_info['cprice'], $quantity, 2 );
			
			$cash_pay = array(
				'cid'          => $pay_info['cid'],       //现金券ID
				'uid'          => $pay_info['uid'],       //打款账户UID
				'type'         => '2',                    //交易类型：1存款、2退款、3扣款
				'pno'          => $pay_info['pno'],       //支付担保金的订单号
				'state'        => '1',                    //支付状态：1交易未进行、2交易进行中、3交易已完成
				'quantity'     => $quantity,              //份数
				'money'        => $money,                 //退还担保金金额
				'send_type'    => $pay_info['send_type'], //现金券发放方式
				'dateline'     => time(),                 //记录插入时间
				'doneline'     => '0',                    //交易完成时间
			);
			$this->db->insert ( 'cash_pay', $cash_pay );
		}else{
			$pno = $freeze_info['pno'];
			$money = $freeze_info['money'];
		}
		$data = array('uid'=>$pay_info['cid'],'pno'=>$pno,'money'=>$money,'title'=>'结算现金券”'.$cash_info['ctitle'].'“金额');
		return $data;
	}
	
	/**
	 * 判断一个现金券是否已经全部结算完成
	 * @param int $cid 现金券ID
	 * @return boolean
	 */
	private function is_settled( $cid )
	{
		$cash_pay = $this->db->select('state')->from('cash_pay')->where('cid',$cid)->get()->result_array();
		foreach( $cash_pay as $val ){
			if( $val['state']!=3 ){
				return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	 * 取消付款
	 */
	public function cancel_pay()
	{
		$id = intval( $this->get_post('id',TRUE) );
		if('is_post' == $this->input->get_post('is_post')){
			$pay_info = $this->cash_model->cash_pay_info( array('id'=>$id) , 'state');
			if( $pay_info['state']==3 ){
				$this->error('现金券已经付款，不能撤销！');
			}
			$res = $this->db->update('cash_pay',array('state'=>1),array('id'=>$id));
			if( !$res ){
				$this->error('现金券撤销付款失败！');
			}
			$this->success('现金券撤销付款成功！');
		}else{
			$this->load->view('cash/dialog/cancel_pay',get_defined_vars());
		}
	}
	
	/**
	 * 作废现金券
	 */
	public function destroy()
	{
		$ids = $this->get_post('ids');
		$reason = $this->get_post('reason');
		
		if('is_post' == $this->input->get_post('is_post')){
			$ids = explode(',', $ids);
			$this->db->trans_begin();//开始事务
			foreach($ids as $id){
				$res = $this->cash_model->cash_user_info( array('id'=>$id) , 'id,cid,uid,state,pay_id' );
				if( in_array($res['state'], array('3','4','5') )){
					$this->error('操作失败，只有未兑换或者待打款的现金券才能作废');
				}
				$this->cash_model->del_timeout_pay_tasktimer( $res['id'] );
				$this->db->update ( 'cash_user', array('state'=>5) , array('id' => $id) );
				$this->cash_model->save_log(array('cid'=>$res['cid'],'uid'=>$res['uid'],'pay_id'=>$res['pay_id'],'admin_uid' =>$this->user_id,'uname'=>$this->username,'content'=>'作废处理原因:'.$reason,'is_destroy'=>'1'));
			}
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$this->error('现金券作废失败');
			}else{
				$this->db->trans_commit();
				$this->success('现金券作废成功！');
			}
		}else{
			$this->load->view('cash/dialog/destroy',get_defined_vars());
		}
	}
	
	/**
	 * 现金券明细，查询用户的使用情况
	 */
	public function detail()
	{
		$search_key = $this->get_post('search_key');
		$search_val = trim($this->get_post('search_value'));
		
		$ext_where = array();
		//现金券状态查询条件
		$sh_state = $this->get_post('cash_user_state','choose');
		if( $sh_state !='choose' ){
			$state_where = array('cash_user.state'=>$sh_state);
			$ext_where = array_merge($ext_where,$state_where);
		}
		//现金券获取时间查询条件
		$startTime = strtotime($this->get_post('startTime',0));
		$endTime   = strtotime($this->get_post('endTime',0));
		if($startTime > 0 && $endTime>0 ){
			$time_ext_where =  array('dateline >=' => $startTime, 'dateline <=' => $endTime);
			$ext_where = array_merge($ext_where,$time_ext_where);
		}
		$ext_where = !empty($ext_where)?$ext_where:'';
		
		$segment = $this->uri->segment(3) ? $this->uri->segment(3) : '10';
		$offset = $this->uri->segment(4);
		$size = $segment;
		
		$user_cash =  $this->cash_model->search_user_detail($search_key, $search_val, $ext_where, $size, $offset, $order = 'cash_user.id DESC');
		
		$state_count = $this->cash_model->all_state_money_count($search_key, $search_val, $ext_where);
		for( $i=1;$i<=5;$i++ ){
			$cash_count_state['state'.$i] = $state_count['state'.$i]['state_count'];
			$cash_count_money['state'.$i] = $state_count['state'.$i]['state_sum_price'];
		}
		
		// 分页相关
		$items_count = $state_count['state_all'];
		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment.'/0');
		$pager = $this->pager($items_count, $size, $page_conf);
		
		$this->load->view('cash/cash_user_detail',get_defined_vars());
	}
	
	/**
	 * 导出现金券用户数据
	 */
	public function export_user_detail()
	{
		$search_key = $this->get_post('search_key');
		$search_val = trim($this->get_post('search_value'));
		
		$ext_where = array();
		//现金券状态查询条件
		$sh_state = $this->get_post('cash_user_state','choose');
		if( $sh_state !='choose' ){
			$state_where = array('cash_user.state'=>$sh_state);
			$ext_where = array_merge($ext_where,$state_where);
		}
		//现金券获取时间查询条件
		$startTime = strtotime($this->get_post('startTime',0));
		$endTime   = strtotime($this->get_post('endTime',0));
		if($startTime > 0 && $endTime>0 ){
			$time_ext_where =  array('dateline >=' => $startTime, 'dateline <=' => $endTime);
			$ext_where = array_merge($ext_where,$time_ext_where);
		}
		$ext_where = !empty($ext_where)?$ext_where:'';
		$user_cash =  $this->cash_model->search_user_detail($search_key, $search_val, $ext_where, $size='', $offset='', $order = 'cash_user.id DESC');
		
		if( count($user_cash)>20000 ){
			$this->error('导出数据太大，不能导出！');
		}
		
		$data=array();
		$state_str = array(1=>'未兑换',2=>'待打款',3=>'已兑换', 4=>'已过期',5=>'已作废');
		foreach ($user_cash as $v){
			$data[] = array(
				$v['cid'],
				$v['cname'],
				$v['cdkey'],
				$v['uid'],
				$v['uname'],
				$v['cprice'],
				$state_str[$v['state']]
			);
		}
		
		$title = '现金券用户数据导出';
		$filename = '现金券用户数据导出.xls';
		$header = array(
				'现金券编号',
				'现金券类型',
				'兑换码',
				'用户UID',
				'所属用户',
				'现金券面额' ,
				'现金券状态'
		);
		array_unshift($data, $header);
		$this->data_export($data, $title, $filename);
	}
	
	/**
	 * 现金券统计，统计现金券的使用情况
	 */
	public function count()
	{
		$search_key = $this->get_post('search_key');
		$search_val = trim($this->get_post('search_value'));
		
		$segment = $this->uri->segment(3) ? $this->uri->segment(3) : '10';
		$offset = $this->uri->segment(4);
		$cash_count =  $this->cash_model->search_cash_count($search_key, $search_val, $ext_where = '', $segment, $offset, $order = 'cash_pay.cid DESC');
		
		// 分页相关
		$items_count = $this->cash_model->search_cash_count_num($search_key, $search_val, $ext_where = '');
		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment.'/0');
		$pager = $this->pager($items_count, $segment, $page_conf);
		
		$this->load->view('cash/cash_count' , get_defined_vars());
	}
	
	/**
	 * 检测添加现金券类型提交表单数据的合法性
	 * @param array $cash 表单提交数组
	 */
	private function check_cash_post( $cash )
	{
		if( $cash['cname']=='' )
		{
			$this->error('现金券类型不能为空');
		}
		if( !preg_match ( '/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u', $cash['cname'] ) )
		{
			$this->error('现金券类型名称不能包含特殊字符');
		}
		if( strlen( $cash['cname'] ) < 2  || strlen( $cash['cname'] ) >75 )
		{
			$this->error('对不起，您输入的现金券类型长度不对');
		}
		$res = $this->db->select('cid')->from('cash')->where( 'cname' , $cash['cname'] )->get()->row_array();
		if( $res )
		{
			$this->error('该现金券类型已经存在，请换一个现金券类型名称！');
		}
		if( strlen( $cash['ctitle'] ) < 2  || strlen( $cash['ctitle'] ) >30 )
		{
			$this->error('对不起，您输入的现金券标题长度不对');
		}
		if( !preg_match ( '/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u', $cash['ctitle'] ) )
		{
			$this->error('现金券类型标题不能包含特殊字符');
		}
		if( $cash['cprice']=='' )
		{
			$this->error('现金券面额不能为空');
		}
		if( $cash['cprice'] <=0 )
		{
			$this->error('输入的现金券面额不能小于等于0');
		}
		if( $cash['not_limit'] + $cash['is_time_limit'] + $cash['is_phone'] + $cash['is_category'] + $cash['is_sum_price'] + $cash['is_sum_cost_price'] + $cash['is_sum_rebate'] ==0 )
		{
			$this->error('请勾选现金券的使用条件');
		}
		if( $cash['is_time_limit'] )
		{
			if( $cash['time_limit_start_time']=='' || $cash['time_limit_end_time']=='' )
			{
				$this->error('请选择抢购时间限制开始和结束的时间');
			}
			if( strtotime($cash['time_limit_start_time']) >= strtotime($cash['time_limit_end_time']) )
			{
				$this->error('抢购时间限制的开始时间不能大于等于结束的时间');
			}
		}
		
		if( $cash['valid_start_time']=='' || $cash['valid_end_time']=='' )
		{
			$this->error('请选择现金券的有效期');
		}
		if( strtotime($cash['valid_start_time']) >= strtotime($cash['valid_end_time']) )
		{
			$this->error('有效期的开始时间不能大于等于结束时间');
		}
		if( strtotime($cash['valid_end_time']) <= time() )
		{
			$this->error('结束时间不能小于当前时间');
		}
		if( $cash['is_sum_price'] )
		{
			if( $cash['is_sum_cost_price']==1 || $cash['is_sum_rebate']==1 )
			{
				$this->error('网购价、活动价、已返现总额只能选择一个！');
			}
			if( $cash['sum_price']=='' ) $this->error('输入的网购价总额不能为空');
			if( $cash['sum_price']<=0 )  $this->error('输入的网购价总额不能小于等于0');
		}
		if( $cash['is_sum_cost_price'] )
		{
			if( $cash['is_sum_price']==1 || $cash['is_sum_rebate']==1 )
			{
				$this->error('网购价、活动价、已返现总额只能选择一个！');
			}
			if( $cash['sum_cost_price']=='' ) $this->error('输入的活动价总额不能为空');
			if( $cash['sum_cost_price']<=0 )  $this->error('输入的活动价总额不能小于等于0');
		}
		if( $cash['is_sum_rebate'] )
		{
			if( $cash['is_sum_cost_price']==1 || $cash['is_sum_price']==1 )
			{
				$this->error('网购价、活动价、已返现总额只能选择一个！');
			}
			if( $cash['sum_rebate']=='' ) $this->error('输入的已返现总额不能为空');
			if( $cash['sum_rebate']<=0 )  $this->error('输入的已返现总额不能小于等于0');
		}
	}
	
	/**
	 * 字符串user解析成数组
	 * @param string $users 输入的user字符串
	 * @param int $cid 现金券ID
	 * @param string $type 输入的user字符串类型：uid或者uname
	 * @return array('uid'=>$uid,'uname'=>$uname);
	 */
	private function explode_user( $users ,$cid , $type = 'uname') 
	{
		$users = str_replace(' ', '', $users);   // 去空格
		$users = str_replace('，', ',', $users); // 替换中文逗号为英文逗号
		$users = explode(',', $users);           // 组合成数组
		
		//去除为空的用户名或者UID
		foreach($users as $user){
			if( !empty($user) ){
				$users_fix[] = $user;
			}
		}
		$users = $users_fix;
		count($users) OR $this->error('请输入用户名！');
		$users = array_unique($users); //去重复
		
		$where = $type == 'uid' ? 'uid' : 'uname';
		// 查找数据库中用户名
		$db_users = array();
		foreach ($this->db->select('uid,uname,is_lock,utype')->distinct()->from('user')->where_in($where, $users)->get()->result_array() as $item){
			if( $item['is_lock']!=0 ){
				$lock_users[] = $item['uname'];
			}
			if( $item['utype']==2 ){
				$seller_users[] = $item['uname'];
			}
			$db_users[] = array('uid'=>$item['uid'],'uname'=>$item['uname']);
		}
		if( !empty($lock_users) ){
			$this->error('输入了被屏蔽的用户名：'. implode(',', array_reverse($lock_users) ). '，不能发放');
		}
		if( !empty($seller_users) ){
			$this->error('输入了商家的用户名：'. implode(',', array_reverse($seller_users) ) . '，不能发放');
		}
		count($db_users) OR $this->error('输入的用户名全都不存在');
		foreach($db_users as $user){
			$db_user[] = $user[$type];  //变成键值为数字的一维数组
		}
		$diff_users = array_diff($users, $db_user);
		count($diff_users) AND $this->error('输入了不存在的用户名：'. implode(',', $diff_users));
		$db_user = array_unique($db_user);
		
		foreach( $db_user as $k=>$uname ){
			$res = $this->db->select('id')->from('cash_user')->where( array('cid'=>$cid,'uname'=>$uname) )->get()->row_array();
			if( $res ){
				$this->error( '用户名：'.$uname.'已经得到该现金券，不能重新发放' );
			}
		}
		
		return $db_users;
	}
	
	/**
	 * 显示打款账户余额
	 */
	public function get_money()
	{
		$this->load->library('hlpay');
		$money = $this->hlpay->get_user_money( $this->cash_pay_uid );
		echo $money !== FALSE ? $money : '-';
	}
	
	/**
	 * 显示操作日志
	 */
	public function log()
	{
		$uid    = $this->get_post('uid');
		$cid    = $this->get_post('cid');
		$pay_id = $this->get_post('pay_id');
		
		$this->db->where('cid',$cid);
		$this->db->where_in('uid',array('0',$uid));
		$this->db->where_in('pay_id',array('0',$pay_id));
		$cash_log = $this->db->select('uname,dateline,content')->from('cash_log')->order_by('dateline','DESC')->get()->result_array();
		$this->load->view('cash/dialog/log',get_defined_vars());
	}
	
	/**
	 * 一键打款
	 * 
	 * @author 杜嘉杰
	 * @version 2014-12-4
	 */
	public function pay_all(){
	
		if('is_post' == $this->input->get_post('is_post')){
			// 打款操作
			$search_key = $this->get_post('search_key');
			$search_val = trim($this->get_post('search_value'));
			
			$op_password =  trim($this->input->post('op_password', TRUE));
			$db_pwd = $this->db->select('value')->from('system_config')->where('key','admin_cash_password')->get()->row_array();
			if ( md5($op_password) != $db_pwd['value'] ) $this->error('输入的现金券操作码不正确！');
			
			//现金券状态查询条件
			$ext_where = array();
			
			// 待返现的券
			$state_where = array('cash_user.state'=>2);
			$ext_where = array_merge($ext_where,$state_where);
			
			//现金券获取时间查询条件
			$startTime = strtotime($this->get_post('startTime',0));
			$endTime   = strtotime($this->get_post('endTime',0));
			if($startTime > 0 && $endTime>0 ){
				$time_ext_where =  array('dateline >=' => $startTime, 'dateline <=' => $endTime);
				$ext_where = array_merge($ext_where,$time_ext_where);
			}
			$ext_where = !empty($ext_where)?$ext_where:'';
			
			$segment = $this->uri->segment(3) ? $this->uri->segment(3) : '10';
			$offset = $this->uri->segment(4);
			$size = $segment;
			
			$user_cash =  $this->cash_model->search_user_detail($search_key, $search_val, $ext_where, '', '', '');
			
			// 需要打款的id
			$ids = array();
			foreach ($user_cash as $item){
				$ids[] = $item['id'];
			}
			
			if(count($ids)==0){
				$this->error('没有待打款记录');
			}
			
			$ret = $this->cash_model->pay_all($ids);
			if($ret){
				$this->success('正在执行打款操作');
			}else{
				$this->error('打款失败');
			}
		}else{
			// 显示输入操作码界面
			$params = array();
			$params['startTime'] = trim($this->get_post('startTime'));
			$params['endTime'] = trim($this->get_post('endTime'));
			$params['cash_user_state'] = trim($this->get_post('cash_user_state'));
			$params['search_key'] = trim($this->get_post('search_key'));
			$params['search_value'] = trim($this->get_post('search_value'));
			
			$url = site_url('cash/pay_all');
			$this->load->view('cash/dialog/freezerecord',get_defined_vars());
		}
	}
	
}
// End of class Message

/* End of file message.php */
/* Location: ./application/controllers/message.php */