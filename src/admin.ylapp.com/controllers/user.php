<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户管理控制器类
 * @author minch <yeah@minch.me>
 * @version 2013-07-15
 * @property user_seller_deposit_model $deposit_model
 * @property admin_user_model $user_model
 */
class User extends MY_Controller 
{
	public $check_access = TRUE;
	public $except_methods = array();
	
	//买家用户状态
	private $buyer_state = array(0=>'正常', 1=>'调查',2=>'屏蔽',5=>'封号',6=>'自动屏蔽',7=>'自动屏蔽',8=>'自动屏蔽');
	//商家用户状态
	private $seller_state = array(0=>'正常', 1=>'调查',2=>'屏蔽(一般)',3=>'屏蔽(严重)',4=>'屏蔽(很严重)' ,5=>'封号',9=>'特殊商家');
	
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_user_model', 'user_model');
		$this->load->model('user_seller_deposit_model', 'deposit_model');
	}
	
	/**
	 * 所有的用户列表
	 */
	public function index()
	{
		$segment = $this->uri->segment(3);
		$utype = '';
		switch ($segment){
			case 'seller':
				$utype = 2;
				break;
			case 'buyer':
				$utype = 1;
				break;
			case 'yzcm':
				$this->_yzcm();
				return ;
		}
		$this->_list($utype);
	}

	/**
	 * 一站成名商家
	 */
	private function _yzcm(){
		$key_type = $this->get_post('search_key_type','');
		$key =  trim($this->get_post('search_key',''));
		$state =  $this->get_post('search_state',0);
		$deposit_type =  $this->get_post('deposit_type',0);
		$startTime =strtotime($this->get_post('startTime',0));
		$endTime  =strtotime($this->get_post('endTime',0));
		$page = intval($this->uri->segment(4));
		$size = 10;

		$users = $this->deposit_model->get_users($key_type,$key,$state,$page,$size,$deposit_type,$startTime,$endTime);

		//颜色
		foreach ($users['rows'] as &$item){
			$color = '#000000';
			if( $item['state'] == 2){
				$color = '#289728';
			}elseif ($item['state'] == 3 ){
				$color = '#FF0000';
			}else{
				$color = '#000000';
			}
			$item['money_color'] = $color;
		}
		
		$segment = $this->uri->segment(3);
		// 分页相关
		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment.'/0');
		$pager = $this->pager($users['count'], $size, $page_conf);

		$this->load->view('user/yzcm',get_defined_vars());
		
	}
	
	/**
	 * 保证金详细信息
	 */
	public function detail(){
		$seller_uid = $this->get_post('uid');
		$deposit_type = $this->get_post('deposit_type');
		$state_str = array();
		$user_log = $this->db->select('l.*,u.uname as admin_uid')->from('shs_user_seller_deposit_log l')->join('user u','l.uid=u.uid')->where('l.seller_uid',$seller_uid)->where('l.type','2')->where('deposit_type',$deposit_type)->order_by('dateline','DESC')->get()->result_array();
		$this->load->view('user/dialog/detail',get_defined_vars());
	}

	/**
	 * 返还保证金
	 */ 
	public  function refund(){
		$seller_uid = $this->get_post('uid');
		$deposit_type = $this->get_post('deposit_type');
        if (empty($deposit_type))$this->error('退还类型出错！');
		if('is_post' == $_POST['is_post']){
			$msg_type=array('1'=>'一站成名','2'=>'【名品馆】');
			$password = md5($this->get_post('password',''));
			$db_password = $this->db->select('value')->from('system_config')->where('key','admin_yzcm_password')->get()->row_array();
			if ($password != $db_password['value']) $this->error('操作码不正确！');
			
			$seller_result = $this->db->select('money,state,pay_state')->from('user_seller_deposit')->where(array('uid'=>$seller_uid,'deposit_type'=>$deposit_type))->get()->row_array();
			if (count($seller_result) == FALSE) $this->error('无此'.$msg_type[$deposit_type].'商家');
			$title = '退还'.$msg_type[$deposit_type].'诚信金'.$seller_result['money'].'元';
			// 判断当前状态为退款中
			$call_repeat = !($seller_result['pay_state'] == 2);
			$ret = $this->deposit_model->refund($seller_uid,$seller_result['money'],$title,$this->user_id, bindec(decbin(ip2long($this->input->ip_address()))) ,$call_repeat,$deposit_type );
			if ($ret) {
				$this->success('诚信金成功解冻。');
			}else{
				$this->error($this->deposit_model->error());
			}
		}else{
			$this->load->view('user/dialog/refund',get_defined_vars());
		}
	}

	/**
	 * 扣除保证金
	 */
	public function deduct(){
		$seller_uid =  intval($this->get_post('uid', 0));
		$do_deduct =  intval($this->get_post('deduct_submit', 0));
		$deposit_type =  intval($this->get_post('deposit_type', 0));
		$default_deposit =$deposit_type==2?$this->config->item('mpg_deposit'): $this->config->item('yzcm_deposit');
		//未成功扣款的支付id
		$continue_deduct_pay_id = intval($this->get_post('continue_deduct_pay_id', 0));
		if( ! $seller_uid){
			$this->error('请求参数有误');
		}
		if($do_deduct === 1){
			
			$default_money =$deposit_type==2?$this->config->item('mpg_deposit'): $this->config->item('yzcm_deposit'); //保证金默认金额
			$deduct_reason =  trim($this->input->post('reason', TRUE));
			$op_password =  trim($this->input->post('op_password', TRUE));
			
			if($continue_deduct_pay_id > 0){//上次为成功扣款，继续上次扣款
				$deduct_data = $this->deposit_model->get_deduct(array('id'=>$continue_deduct_pay_id));
				if($deduct_data['state'] == 3){//该扣款支付id，状态已是成功扣款
					$this->error('该扣款订单已完成扣款，不能重复扣款');
				}else{
					$params = array(
						'pay_id'=>$continue_deduct_pay_id,
						'seller_uid'=>$seller_uid,
						'op_uid'=>$this->user_id,
						'op_password'=>$op_password,
						'deduct_reason'=>$deduct_reason,
						'default_money'=>$default_money,
						'ip'=>bindec(decbin(ip2long($this->input->ip_address()))),
					    'deposit_type'=>$deposit_type 
					);
					$execBack = $this->user_model->update_deduct_order($params, TRUE);
				}
			}else{//正常情况下扣款
				$deduct_money =  trim($this->input->post('deduct_money', TRUE));
				$deduct_money = bcadd($deduct_money, 0, 2);
				$params = array(
					'seller_uid'=>$seller_uid,
					'op_uid'=>$this->user_id,
					'op_password'=>$op_password,
					'deduct_money'=>$deduct_money,
					'deduct_reason'=>$deduct_reason,
					'default_money'=>$default_money,
					'ip'=>bindec(decbin(ip2long($this->input->ip_address()))),
				    'deposit_type'=>$deposit_type 
				);
				$execBack = $this->user_model->update_deduct_order($params);
			}
			$execBack['Code'] = intval($execBack['Code']);
			$execBack['Message'] = trim($execBack['Message']);
			if($execBack['Code'] === 1){//成功更新保证金扣款订单
				if($execBack['Message'] !== ''){
					$backInfo = json_decode($execBack['Message'], TRUE);
					$pno = $backInfo['pNo'];
					$paypNo = $backInfo['paypNo'];
					$pid = $backInfo['pid'];
					$deduct_money = $backInfo['deduct_money'];
					
					$this->load->library('hlpay');
					$back = $this->hlpay->hlpay_deduct($deduct_money,$pno,$paypNo,$deposit_type);
					$back_int = intval($back);
					if($back_int > 0){
						$params = array(
							'seller_uid'=>$seller_uid,
							'admin_uid'=>$this->user_id,
							'deduct_reason'=>$deduct_reason,
							'pay_id'=>$pid,
							'pno'=>$paypNo,
							'ip'=>bindec(decbin(ip2long($this->input->ip_address()))),
					    	'deposit_type'=>$deposit_type 
						);
						$execHandleBack = $this->user_model->deduct_handle($params);
						$execHandleBack['Code'] = intval($execHandleBack['Code']);
						$execHandleBack['Message'] = trim($execHandleBack['Message']);
						if($execHandleBack['Code'] === 1 && $execHandleBack['Message'] === ''){
							$this->success('扣款成功');
						}else{
							$this->error('互联支付扣款成功,众划算处理失败:错误编码['.$execHandleBack['Code'].'] <br>'.$execHandleBack['Message']);
						}
					}elseif($back_int === -2){
						$this->error('密匙验证不通过');
					}elseif($back_int === -3){
						$this->error('扣除金额不能小于0');
					}else{
						$this->error('未知错误，扣款失败。互联支付错误编码：'.$back.'错误信息：'.$this->hlpay->error());
					}
				}else{
					$this->error('更新诚信金扣款订单成功，但执行返回数据为空');
				}
			}else{
				$this->error($execBack['Message']);
			}
		}else{
			#获取未成功扣款的记录数据
			$deduct_data = $this->deposit_model->get_deduct(array('uid'=>$seller_uid,'_str'=>'state IN(1,2)','type'=>3,'deposit_type'=>$deposit_type));
			$dfo = $this->deposit_model->get_deposit(array('uid'=>$seller_uid,'deposit_type'=>$deposit_type));
			$deposit_info=$dfo[0];
			$this->load->view('user/dialog/deduct',get_defined_vars());
		}
	}
	
	/**
	 * 备注
	 */
	public function remark(){
		$seller_uid = $this->get_post('uid',0);
		$deposit_type = $this->get_post('deposit_type',0);
		if ( 'is_post' == $_POST['is_post']) {
			$remark = $this->get_post('remark','');
			$ret = $this->deposit_model->remark($seller_uid,$this->user_id,$remark,bindec(decbin(ip2long($this->input->ip_address()))),$deposit_type);
			if ($ret) {
				$this->success('修改成功');
			}else{
				$this->error('修改失败:'.$this->deposit_model->error());
			}
		}else{
			$data = $this->db->select('remark')->from('user_seller_deposit')->where(array('uid'=>$seller_uid,'deposit_type'=>$deposit_type))->get()->row_array();
			$this->load->view('user/dialog/remark',get_defined_vars());
		}
	}

	/**
	 * 保证金操作码
	 */
	public function password(){
		$award_uid = $this->config->item('deposit_password_uid') OR $this->error('系统未配置文件不存在配置项：deposit_password_uid');
		if(in_array($this->user_id, $award_uid) == FALSE){
			$this->error('您无此操作权限');
		}
		
		if('is_post' == $_POST['is_post']){
			$old_pwd =md5($this->get_post('old_pwd',''));
			$new_pwd = md5($this->get_post('new_pwd',''));
			
			$db_old_pwd = $this->db->select('value')->from('system_config')->where('key','admin_yzcm_password')->get()->row_array();
			if ($old_pwd != $db_old_pwd['value']) $this->error('当前诚信金操作码不正确！');

			$this->db->where('key','admin_yzcm_password')->update('system_config',array('value'=>$new_pwd));
			$this->success('修改操作码成功！');
		}else{
			$this->load->view('user/dialog/password');
		}
	}

	/**
	 * 列表页面
	 */
	private function _list($utype)
	{
		$this->load->helper('user');
		
		$search_key = $this->get_post('search_key');
		$search_val = $this->get_post('search_value');
		
		$startTime = strtotime($this->get_post ('startTime')) ;
		$endTime  = strtotime($this->get_post ('endTime') );
		$url           = urldecode(trim($this->get_post('url')));
		$typeurl           = intval($this->get_post('typeurl'));
		
		$status = array();
		//自动屏蔽 
	    $shield_key = $this->get_post('shield_key','choose');
		if($shield_key != 'choose'){
			switch ($shield_key){
				case  'autoshield':
					$status = array('is_lock in(6,7,8)');//自动屏蔽
					break;
				case 'normal':
					$status = array('is_lock'=>0);//正常
					break;
				case  'checking':
					$status = array('is_lock'=>1);//调查
					break;
				case 'shield':
					$status = array('is_lock in(2,3,4)');//屏蔽
					break;
				case 'lock':
					$status = array('is_lock'=>5);//封号
					break;
				case 'special':
					$status = array('is_lock'=>9);//特殊商家
					break;
			}
	    }
		$shield_reason = $this->get_post('shield_reason','choose');
		if($shield_reason != 'choose'){
			if($shield_reason == 'ordererror'){
				$status = array('is_lock'=>6);//订单错误
			}else if($shield_reason == 'refusetimes'){
				$status = array('is_lock'=>7);//被申诉
			}else if($shield_reason == 'buytimes'){
				$status = array('is_lock'=>8);//抢购
			}
		}
		//查询用户的注册来源
		$reg_source = $this->get_post('source', 'choose' );
		if( $reg_source != 'choose' ){
			$reg_source_search = array('reg_source'=>$reg_source);
		}else{
			$reg_source_search = array();
		}
		
		//查询优质会员
		$is_premium = $this->get_post('premium', 'choose' );
		if( $is_premium != 'choose' ){
		    $premium = array('is_premium'=>$is_premium);
		}else{
		    $premium = array();
		}
		
		//用户来源URL增加用户列表
		$user_source_where='';
		if($startTime>0 && $endTime){
			$user_source_where=  '  dateline  BETWEEN ' . $startTime . ' and  ' . $endTime;
		}
		
		
		/**
		 * 增加注册来源字段的查询条件
		 * $ext_where = array('is_lock'=>$is_lock,'reg_source'=>$reg_source);
		 * 以后添加查询字段往整个数组添加字段和对应值即可
		 */
		$ext_where = array_merge($status,$reg_source_search,$premium);
		$ext_where = !empty($ext_where) ? $ext_where : '';
		
		//
		$limit = 10;
		$segment = $this->uri->segment(3);
		$offset = $this->uri->segment(4);
		
		//更新用户屏蔽状态
		$this->user_model->update_lock_status();

		// 用户来源统计查看列表修改
		$other_uids=array();
		if ($url != '') {
			if ($url == 'other') {
				//配置用户来源URL名称
				$this->load->model('system_config_reg_source_model');
				$urls = $this->system_config_reg_source_model->find_all();
				$this->db->close();
			    $otherusers = $this->user_model->get_reg_source($startTime, $endTime);
				$other_uids=$this->get_other_uids($otherusers, $urls);
			    if(count($other_uids['otheruids'])>50000 || count($other_uids['user_uids'])>50000){
					$this->error('当前数据太大，可能不能正常显示。建议增加筛选条件后重新尝试”');
				}
			} else {
				if($typeurl==2){//精确匹配
					$user_source_where .='  and  reg_from_url =  "'.$url.'"  ';
				}else{
					$user_source_where .= '  and  reg_from_url like  "%' . $url . '%"  ';
					//模糊查询，排除精确 开始
					$this->load->model('system_config_reg_source_model');
					$urls = $this->system_config_reg_source_model->find_all();
					$urllist = array ();
					foreach ( $urls as $v ) {
						if ($v ['type'] == 2) {
							if (stristr ( $v ['url'], $url ) !== false && $v ['url'] != $url) {
								$urllist [] = $v ['url'];
							}
						}
					}
					if (count ( $urllist ) > 0) {
						if ($user_source_where !== '') {
							$this->db->where ( $user_source_where, null, false );
						}
						$this->db->where_in ( 'reg_from_url', $urllist );
						$urluser = $this->db->select ( 'uid' )->from ( 'user' )->get ()->result_array ();
	
						$url_uidlist = array ();
						foreach ( $urluser as $u ) {
							$url_uidlist [] = $u ['uid'];
						}
						if (count ( $url_uidlist ) > 0) {
							$user_source_where .= '  and  uid not in  (' . implode ( ',', $url_uidlist ) . ') ';
						}
					}
					//模糊查询，排除精确 结束
				}
			}
		}
		$items = $this->user_model->search ( $search_key, $search_val, $utype, $ext_where, '', $limit, $offset,$user_source_where,$other_uids );
		// 分页
		$items_count = $this->user_model->search_count ( $search_key, $search_val, $utype, $ext_where,$user_source_where ,$other_uids);
		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment.'/0');
		$pager = $this->pager($items_count, $limit, $page_conf);
		
		$buyer_state  = $this->buyer_state;
		$seller_state = $this->seller_state;
		$this->load->view('user/index', get_defined_vars());
	}
	
	/**
	 * 屏蔽用户
	 */
	public function lock()
 	{
 		$uids = $this->get_post('uids','');
 		$utype = $this->get_post('utype');
		if ($this->get_post('is_post') == 'yes') {
			// 写
			$day = $this->get_post('day',1);
			$state = $this->get_post('state', 0);
			$content = $this->get_post('content', '') OR $this->error('请输入原因');
			$rs = $this->user_model->lock($uids, $state ,$content, $day, $this->user_id);
			if($rs){
				$this->success('操作成功');
			}else{
				$error = $this->user_model->error();
				$this->error($error);
			}
		}else{
			// 查
			$uid = explode(',', $uids); 
			$user = null;
			if(count($uid) == 1){
				$user = $this->db->from('user')->where('uid',$uid[0])->get()->row_array();
			}
			$this->load->view('user/dialog/lock', get_defined_vars());
		}
	}
	
	/**
	 * 查看买家详细信息
	 */
	public function user_detail(){
		$this->load->helper('user');
		$uid =  $this->uri->segment(3) ;
		$type_id =  $this->get_post('type_id','');//选项卡
		$segment = $this->uri->segment(4); //偏移量(分页用)

		//用户信息
		$user = $this->db->from('user')->where('uid',$uid)->get()->row_array();
		// 查询绑定淘宝信息
		$this->load->model('user_bind_model');
		$user['bind_taobao'] = $this->user_bind_model->get($uid, 1);
		
		$this->load->model('user_login_bind_model');
		$user['login_binds'] = $this->user_login_bind_model->find_user_binds($uid);

		//默认的选项卡
		if ($type_id == '') {
			$type_id = $user['utype'] == 1 ? 'order' : 'goods';
		}
		
		if($type_id == 'order'){
			$this->_detail_order($uid,$segment,$type_id, $user);
		}elseif($type_id == 'appeal_post' || $type_id == 'appeal_receive'){
			$this->_detail_appeal($type_id, $uid,$segment, $user);
		}elseif($type_id == 'goods'){
			$this->_detail_seller_goods($uid, $segment, $type_id, $user);
		}else{
			die('无此选项卡:['.$type_id.']');
		}
		
	}
	
	/**
	 * 实名认证
	 */
	public function true_name()
	{
		$uid =  $this->uri->segment(3) ;
		
		$this->load->library('hlpay');
		$user_auth = $this->hlpay->get_user_info($uid);
		$user['is_true_name_auth'] = $user_auth->string['2'];
		$user['true_name'] = $user_auth->string['3'];
		echo json_encode($user);
	}
	
	/**
	 * 商家活动列表
	 * @param unknown $uid
	 * @param unknown $segment
	 * @param unknown $type_id
	 * @param unknown $user
	 */
	private function _detail_seller_goods($uid, $segment, $type_id, $user){
		$this->load->model('admin_goods_model','goods_model');
		$goods_type = trim($this->get_post('goods_type'));
		$search_type = $this->get_post('search_type');
		$search_value = $this->get_post('search_value');

		$ext_where['goods.uid'] = $uid;

		$order = 'gid desc';
		$limit = 10;
		
		$offset = $this->uri->segment(4);
		//导出数据
		if($this->get_post('doexport') == 'yes'){
			$this->load->model('data_export_model', 'data');
			$data = $this->goods_model->search($search_type, $search_value, '', 0, 0, $ext_where, $order, 0, 0);
			$this->_detail_seller_goods_export($data);
		}
		$items = $this->goods_model->search($search_type, $search_value, '', 0, 0, $ext_where, $order, $limit, $segment);
		foreach ($items as $k => $v)
		{
			// 第三方平台url
			if($v['price_type'] == Goods_model::PRICE_TYPE_MOBILE)
			{
				$goods_content = $this->db->select('url')->from('goods_content')->where('gid',$v['gid'])->get()->row_array();
				$items[$k]['third_party_url'] = isset($goods_content['url']) ? $goods_content['url'] : '';
			}
		}
	
		
		$deposit_type=$this->db->select('uid,state,deposit_type')->from('user_seller_deposit')->where('uid',$uid)->get()->result_array();
		
		$user_seller = $this->db->select('salesman_uname')->from(Zhs_user_seller_model::$table_name)->where('uid',$uid)->get()->row_array();
			
		foreach ( $deposit_type as $key=> $v) {
			if($v['deposit_type']==1){
				$yzcm[$v['uid']]['deposit_type']=$v['deposit_type'];
				$yzcm[$v['uid']]['state']=$v['state'];
			}else if($v['deposit_type']==2){
				$mpg[$v['uid']]['deposit_type']=$v['deposit_type'];
				$mpg[$v['uid']]['state']=$v['state'];
			}
		}

		$items_count = $this->goods_model->search_count($search_type, $search_value, '', 0, 0, $ext_where);
		
		$data_count = $this->_detail_user_stat($uid,$user['utype']);
		
		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$uid.'/');
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$uid.'/0');
		$pager = $this->pager($items_count, $limit, $page_conf);
		
		$this->load->library('goods_util');
		$this->util = new Goods_util();
		$goods_util = $this->util;
		$goods_types_map = Goods_model::$type_str;
		
		if($this->is_ajax() && $this->get_post('listonly')){
			$this->load->view('user/seller_goods',get_defined_vars());
		}else{
			$this->load->view('user/user_detail',get_defined_vars());
		}
	}
	
	/**
	 * 导出用户活动
	 * @param unknown $data
	 */
	private function _detail_seller_goods_export($data){
		$title = '活动导出';
		$filename = '活动导出('.date("Y-m-d-H:i:s", time()).').xls';
		
		$goods_types_map =  array(0=>'普通', 1=>'一站成名',2=>'名品馆');//商品类型，对应商品表字段type
		
		$header = array(
				'活动编号',
				'活动标题',
				'商家名称',
				'商家邮箱',
				'商家编号',
				
				'活动时间',
				'活动天数',
				'数量',
				'网购价',
				'折扣',
				
				'应存费用',
				'已存费用',
				'联系商家',
				'活动状态',
				'活动类型',
					
		);
		$this->load->library('goods_util');
		$this->util = new Goods_util();
		$rows = array();
		foreach ($data as $k=>$v){
			$rows[$k]['gid'] = $v['gid'];
			$rows[$k]['title'] = $v['title'];
			$rows[$k]['uname'] = $v['uname'];
			$rows[$k]['email'] = $v['email'];
			$rows[$k]['uid'] = $v['uid'];
			
			$rows[$k]['first_starttime'] = $v['first_starttime'] ? date('Y-m-d H:i:s',$v['first_starttime']) : '';
			$rows[$k]['first_days'] = $v['first_days'];
			$rows[$k]['quantity'] = $v['quantity'];
			$rows[$k]['price'] = $v['price'];
			$rows[$k]['discount'] = $v['discount'];
			
			$rows[$k]['should_money'] = ($v['price']+$v['single_fee'])*$v['quantity'];
			$rows[$k]['reality_monty'] = in_array($v['state'], array(1,2,11,13))? 0 : $v['paid_guaranty']+$v['paid_fee'];
			$rows[$k]['mobile'] = $v['mobile'];
			$rows[$k]['state'] = $this->util->get_status($v['state']);
			$rows[$k]['type'] = $goods_types_map[$v['type']];
		}
		array_unshift($rows, $header);
		$this->data_export($rows, $title, $filename);
	}
	
	/**
	 * 获取用户抢购的订单
	 * @param int $uid 用户id
	 * @param int $segment 偏移量
	 * @param int $type_id 选项卡类型
	 */
	private function _detail_order($uid, $segment, $type_id, $user){
		//全部抢购记录
		$page_size = 10;
		$listonly = $this->get_post('listonly');
		$search_type = $this->get_post('search_type','');
		$search_value = $this->get_post('search_value','');
		$domain_shikee_bbs = $this->config->item('domain_shikee_bbs') OR die('系统缺少配置项:domain_shikee_bbs');

		$this->load->model('admin_order_model', 'order_model');
		
		$where = array();
		if ($user['utype'] == 1) {
			$where['buyer_uid'] = $uid;
		}else{
			$where['seller_uid'] = $uid;
		}
		
		//导出数据
		if($this->get_post('doexport') == 'yes'){
			$this->load->model('data_export_model', 'data');
			$data = $this->order_model->search($search_type, $search_value, '', '', $where, 0,0, 'dateline DESC');
			$this->_detail_order_export($data);
		}
		
		$data_count = $this->_detail_user_stat($uid,$user['utype']);
		
		
		$order = $this->order_model->search($search_type, $search_value, '', '', $where, $page_size,$segment, 'dateline DESC');
		// 获取图片url和状态的时间
		$this->load->helper('image_url');
		foreach ($order as $key => $row) {
			$order[$key]['img'] = image_url($row['gid'], $row['img']);
			if($row['state'] == 1){
				$order[$key]['count_down_default'] = $this->init_count_down($row['auto_timeout_time']);
			}elseif($row['state'] == 3 || $row['state'] == 4){
				$order[$key]['count_down_default'] = $this->init_count_down($row['auto_checkout_time']);
			}elseif($row['state'] == 5){
				$order[$key]['count_down_default'] = $this->init_count_down($row['auto_close_time']);
			}
		}
		
		$count = $this->order_model->search_count($search_type, $search_value, '', $where);
		// 分页相关
		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#user_list_'.$type_id.'" data-listonly="yes"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$uid);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$uid.'/0');
		$pager = $this->pager($count, $page_size, $page_conf);
		if($this->is_ajax() && $this->get_post('listonly')){
			$this->load->view('user/order_list',get_defined_vars());
		}else{
			$this->load->view('user/user_detail',get_defined_vars());
		}
	}
	
	private function _detail_order_export($data){
		$title = '抢购参与导出';
		$filename = '抢购参与导出('.date("Y-m-d-H:i:s", time()).').xls';
		
		$states = array('0' => '全部', '1' => '待填写订单号', '3' => '待审核订单号', '4' => '待返现', '5' => '订单号有误', '6' => '申诉中', '7' => '已关闭', '8' => '返现中', '9' => '已完成');
		
		$header = array(
				'抢购编号',
				'活动编号',
				'活动标题',
				'卖家名称',
				'买家名称',
				'填写订单编号',
				
				'活动价',
				'网购价',
				'返现',
				'抢购状态'
		);
		$this->load->library('goods_util');
		$this->util = new Goods_util();
		$rows = array();
		foreach ($data as $k=>$v){
			$rows[$k]['oid'] = $v['oid'];
			$rows[$k]['gid'] = $v['gid'];
			$rows[$k]['title'] = $v['title'];
			$rows[$k]['seller_uname'] = $v['seller_uname'];
			$rows[$k]['buyer_uname'] = $v['buyer_uname'];
				
			$rows[$k]['first_starttime'] = $v['trade_no'] ? $v['trade_no'] : '-';
			$rows[$k]['cost_price'] = $v['cost_price'];
			$rows[$k]['price'] = $v['price'];
			$rows[$k]['real_rebate'] = $v['real_rebate'];
			$rows[$k]['state'] = $states[$v['state']];

		}
		array_unshift($rows, $header);
		$this->data_export($rows, $title, $filename);
	}
	
	/**
	 * 获取用户详细信息的统计
	 * @param unknown $uid
	 * @param unknown $utype
	 */
	private function _detail_user_stat($uid, $utype){
		//全部活动记录
		$goods_count = 0;
		//全部抢购记录行数
		$order_count = 0;
		//发起的申诉
		$appeal_post_count = 0;
		//收到的申诉
		$appeal_receive_count = 0;
		
		if($utype==1){
			$order_count = $this->db->from('order')->where('buyer_uid',$uid)->count_all_results();
		}else{
			$goods_count = $this->db->from('goods')->where('uid',$uid)->count_all_results();
			$order_count = $this->db->from('order')->where('seller_uid',$uid)->count_all_results();
		}
		$appeal_post_count = $this->db->from('order_appeal')->where('uid',$uid)->count_all_results();
		$appeal_receive_count = $this->db->from('order_appeal')->where('reply_uid',$uid)->count_all_results();
		$data_count = array('order_count'=>$order_count, 'appeal_post_count'=>$appeal_post_count, 'appeal_receive_count'=>$appeal_receive_count, 'goods_count'=>$goods_count);
		
		return $data_count;
	}
	
	/**
	 * 申诉列表
	 * @param int $type_id 选项卡类型
	 * @param int $uid 用户uid
	 * @param int $segment 偏移量
	 * @param array $user 用户信息
	 */
	private function _detail_appeal($type_id, $uid,$segment, $user){
		$this->load->model('admin_order_appeal_model', 'order_appeal_model');
		$key = $this->get_post('key', '');
		$val = $this->get_post('val', '');
		$appeal_where = NULL;
		$total = 0;
		$page_size = 10;
		if($type_id == 'appeal_post'){
			//发起的申诉
			$appeal_where = array('order_appeal.uid'=>$uid);
		}elseif ($type_id == 'appeal_receive'){
			//收到的申诉
			$appeal_where = array('order_appeal.reply_uid'=>$uid);
		}

		//导出数据
		if($this->get_post('doexport') == 'yes'){
			$this->load->model('data_export_model', 'data');
			$data = $this->order_appeal_model->get('', '', $key, $val, 0, 0, 0, 0, $appeal_where );
			$this->_detail_appeal_export($data,$type_id);
		}

		$total = $this->order_appeal_model->count('', '',$key, $val,0,0,$appeal_where);
		$list = $this->order_appeal_model->get('', '', $key, $val, $page_size,$segment,0,0,$appeal_where );

		$data_count = $this->_detail_user_stat($uid,$user['utype']);
		
		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#user_list_'.$type_id.'" data-listonly="yes"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$uid);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$uid.'/0');
		$pager = $this->pager($total, $page_size, $page_conf);
		$action_url = site_url($this->router->class.'/'.$this->router->method.'/'.$uid);
		if($this->is_ajax() && $this->get_post('listonly')){
			$this->load->view('user/user_appeal', get_defined_vars());
		}else{
			$this->load->view('user/user_detail',get_defined_vars());
		}
	}
	
	/**
	 * 导出申诉记录
	 * @param unknown $data
	 */
	private  function _detail_appeal_export($data,$type_id){
		$file_name = '';
		if($type_id == 'appeal_post'){
			//发起的申诉
			$file_name = '发起的申诉';
		}elseif ($type_id == 'appeal_receive'){
			//收到的申诉
			$file_name = '收到的申诉';
		}else{
			$file_name = '申诉导出';
		}
		
		$title = $file_name;
		$filename = $file_name.'('.date("Y-m-d-H:i:s", time()).').xls';
		
		$states = array('0' => '全部', '1' => '待填写订单号', '3' => '待审核订单号', '4' => '待返现', '5' => '订单号有误', '6' => '申诉中', '7' => '已关闭', '8' => '返现中', '9' => '已完成');
		
		$header = array(
				'申诉编号',
				'抢购编号',
				'活动标题',
				'商家名称',
				'买家名称',
				'活动价',
				'网购价'
				,'返现'
				,'订单号'
				,'淘宝客',
				'进度状态',
				'申诉时间'
		);
		$this->load->library('goods_util');
		$this->util = new Goods_util();
		$rows = array();
		$states = array(1=>'待处理(等待回应)', 2=>'待处理(已回应)', 3=>'待处理(无需回应)', 4=>'申诉关闭', 5=>'处理中', 6=>'已撤销');
		
		foreach ($data as $k=>$v){
			$rows[$k]['id'] = $v['id'];
			$rows[$k]['oid'] = $v['oid'];
			$rows[$k]['title'] = $v['title'];
			$rows[$k]['seller_uname'] = $v['seller_uname'];
			$rows[$k]['buyer_uname'] = $v['buyer_uname'];

			$rows[$k]['cost_price'] = $v['cost_price'];
			$rows[$k]['price'] = $v['price'];
			$rows[$k]['single_rebate'] = $v['single_rebate'];
			$rows[$k]['first_starttime'] = $v['trade_no'] ? $v['trade_no'] : '-';
			$rows[$k]['is_taoke'] = $v['is_taoke'] ? '是' : '否';
			
			$rows[$k]['state'] = $states[$v['state']];
			$rows[$k]['dateline'] = date("Y-m-d H:i:s", $v['dateline']);
		}
		array_unshift($rows, $header);
		$this->data_export($rows, $title, $filename);
	}

	/**
	 * 格式化倒计时时间
	 * @param int $timestamp 截止时间戳
	 */
	function init_count_down($timestamp) {
		$return = '-';
		$leftsec = $timestamp - time();
		if($leftsec > 0){
			$s = $leftsec;
			$left_s = $s % 60;
			$m = floor($s / 60);
			$left_m = $m % 60;
			$h = floor($m / 60);
			$left_h = $h % 24;
			$d = floor($h / 24);
	
			$return = $d > 0 ? '<em class="d">'.$d.'</em>天' : '';
			$return .= $left_h > 0 ? '<em class="h">'.$left_h.'</em>时' : '';
			$return .= $left_m > 0 ? '<em class="m">'.$left_m.'</em>分' : '';
			$return .= $left_s > 0 ? '<em class="s">'.$left_s.'</em>秒' : '';
		}
		return $return;
	}
	
	/**
	 * 查看用户屏蔽记录
	 */
	public function user_lock_log(){
		$uid = $this->uri->segment(3) OR die('获取不到uid');
		$uid = intval($uid);
		$lock_log = $this->db->from('user_lock_log')->where('uid', $uid)->order_by('dateline', 'DESC')->get()->result_array();
		$buyer_state = $this->buyer_state;
		$seller_state = $this->seller_state;
		$this->load->view('user/dialog/user_lock_log', get_defined_vars());
	}
	
	/**
	 * 淘宝账户认证记录
	 */
	public function bind_taobao_log()
	{
		$this->load->model('user_bind_model');
		$uid = intval($this->get_post('uid'));
		if(!$uid){
			$this->error('参数错误');
		}
		$logs = $this->user_bind_model->get_log($uid);
		$this->load->view('user/dialog/bind_taobao_log', get_defined_vars());
	}
	
	/**
	 * 重置认证淘宝账户
	 */
	public function bind_taobao_reset()
	{
		if($_POST['is_post'] == 'yes'){
			$this->load->model('user_bind_model');
			$id = intval($_POST['id']);
			$reason = trim(strval($this->get_post('reason','', TRUE)));
			$res = $this->user_bind_model->reset($id, $reason);
			if($res === FALSE){
				$this->error($this->user_bind_model->get_error());
			}else{
				$this->success('操作成功');
			}
		}
		$this->load->view('user/dialog/bind_reset_form', get_defined_vars());
	}
		
	/**
	 * 手机绑定相关日志
	 */
	public function bind_mobile_log()
	{
		$this->load->model('user_bind_mobile_log_model');
		$uid = intval($this->get_post('uid'));
		if(!$uid){
			$this->error('参数错误');
		}
		$logs = $this->user_bind_mobile_log_model->get_logs_by_uid($uid);
		$this->load->view('user/dialog/bind_mobile_log', array('logs'=>$logs));
	}
	
	/**
	 * 解绑手机
	 */
	public function unbind_mobile()
	{
		if($this->input->post('is_post') == 'yes'){
			$this->load->model('user_bind_model');
			$uid = intval($this->input->post('uid'));
			$reason = trim(strval($this->get_post('reason','', TRUE)));
			if (!$reason) {
				$this->error('请填写解绑原因');
			}
			if (mb_strlen($reason) > 100) {
				$this->error('原因限定100字内');
			}
			$res = $this->user_bind_model->unbind_mobile($uid, $reason);
			if($res === FALSE){
				$this->error($this->user_bind_model->get_error());
			}else{
				$this->success('操作成功');
			}
		}
		$this->load->view('user/dialog/unbind_moblie_form', get_defined_vars());
	}
	
	public function un_login_bind($bind_type, $uid)
	{
		$this->load->model('user_login_bind_model');
		if (isset($_POST['todo']) && $bind_info = $this->user_login_bind_model->find($uid, $bind_type))
		{
			$content = $this->input->post('content', TRUE);
			$this->user_login_bind_model->un_bind($uid, $bind_type, $this->user_id, $this->username, $bind_info['nickname'], $content);
			$this->success('操作成功');
		}
		$data = array('bind_type'=>$bind_type, 'uid'=>$uid);
		$this->load->view('user/dialog/un_login_bind', $data);
	}
	
	public function login_bind_log($uid, $bind_type = 0)
	{
		$this->load->helper('user');
		$this->load->model('user_login_bind_log_model');
		$data = array();
		$data['logs'] = $this->user_login_bind_log_model->find_logs($uid, $bind_type);
		$this->load->view('user/dialog/login_bind_log', $data);
	}

	/**
	 * 导出诚信金用户记录
	 * @author 杨积广
	 */
	public function export(){
		$key_type = $this->get_post('search_key_type','');
		$key =  trim($this->get_post('search_key',''));
		$state =  $this->get_post('search_state',0);
		$deposit_type =  $this->get_post('deposit_type',0);
		$startTime =strtotime($this->get_post('startTime',0));
		$endTime  =strtotime($this->get_post('endTime',0));
		$users = $this->deposit_model->get_users($key_type,$key,$state,0,10000,$deposit_type,$startTime,$endTime);
		if($users['count'] >10000){
			$this->error('由于数据太多超过10000条，请缩小起始时间跨度或者增加其它筛选条件！');
		}
		// 整理数据
		$data=array();
		$type_str = array(1=>'未存款',2=>'已存入',3=>'申请退还', 4=>'已退还');
		foreach ($users['rows'] as $k=>$v){
			$data[] = array(
					$v['uid'],
					$v['uname'],
					$v['money'],
					$v['deposit_type']==2?'名品馆':'一站成名',
					$type_str[$v['state']],
					date('Y-m-d H:i:s',$v['deposit_time']),
					$v['email'],
					$v['mobile']
			);
		}
		$title = '诚信金记录数据导出';
		$filename = '诚信金记录数据导出'.date("Y-m-d-his", $startTime).' - '.date("Y-m-d-his",$endTime).'.xls';
		$header = array(
				'商家uid',
				'商家名称',
				'存入金额/元',
				'存入类型',
				'诚信金状态' ,
				'存入时间',
				'绑定邮箱',
				'绑定手机'
		);
		array_unshift($data, $header);
		$this->data_export($data, $title, $filename);
	}
	
	/**
	 * 导出用户列表
	 */
	public  function export_user(){

		$segment = $this->uri->segment(3);
		$utype = '';
		switch ($segment){
			case 'seller':
				$utype = 2;
				break;
			case 'buyer':
				$utype = 1;
				break;
		}
		$this->load->helper('user');
		$search_key = $this->get_post('search_key');
		$search_val = $this->get_post('search_value');
		
		$startTime = strtotime($this->get_post ('startTime')) ;
		$endTime  = strtotime($this->get_post ('endTime'));
		$url           = trim($this->get_post('url'));
		$typeurl           = intval($this->get_post('typeurl'));
		
		$status = array();
		
		//自动屏蔽
		$shield_key = $this->get_post('shield_key','choose');
		if($shield_key != 'choose'){
			switch ($shield_key){
				case  'autoshield':
					$status = array('is_lock in(6,7,8)');//自动屏蔽
					break;
				case 'normal':
					$status = array('is_lock'=>0);//正常
					break;
				case  'checking':
					$status = array('is_lock'=>1);//调查
					break;
				case 'shield':
					$status = array('is_lock in(2,3,4)');//屏蔽
					break;
				case 'lock':
					$status = array('is_lock'=>5);//封号
					break;
				case 'special':
					$status = array('is_lock'=>9);//特殊商家
					break;
			}
		}
		$shield_reason = $this->get_post('shield_reason','choose');
		if($shield_reason != 'choose'){
			if($shield_reason == 'ordererror'){
				$status = array('is_lock'=>6);//订单错误
			}else if($shield_reason == 'refusetimes'){
				$status = array('is_lock'=>7);//被申诉
			}else if($shield_reason == 'buytimes'){
				$status = array('is_lock'=>8);//抢购
			}
		}
		//查询用户的注册来源
		$reg_source = $this->get_post('source', 'choose' );
		if( $reg_source != 'choose' ){
			$reg_source_search = array('reg_source'=>$reg_source);
		}else{
			$reg_source_search = array();
		}
		//用户来源URL增加用户列表
		$user_source_where='';
		if($startTime>0 && $endTime>0){
			$user_source_where=  '  dateline  BETWEEN ' . $startTime . ' and  ' . $endTime;
		}
		/**
		 * 增加注册来源字段的查询条件
		 * $ext_where = array('is_lock'=>$is_lock,'reg_source'=>$reg_source);
		 * 以后添加查询字段往整个数组添加字段和对应值即可
		 */
		$ext_where = array_merge($status,$reg_source_search);
		$ext_where = !empty($ext_where) ? $ext_where : '';
		
		//更新用户屏蔽状态
		$this->user_model->update_lock_status();
		
		// 用户来源统计查看列表修改
		$other_uids=array();
		if ($url != '') {
			if ($url == 'other') {
				//配置用户来源URL名称
				$this->load->model('system_config_reg_source_model');
				$urls = $this->system_config_reg_source_model->find_all();
				$this->db->close();
				$otherusers = $this->user_model->get_reg_source($startTime, $endTime);
				$other_uids=$this->get_other_uids($otherusers, $urls);
				  if(count($other_uids['otheruids'])>50000 || count($other_uids['user_uids'])>50000){
					$this->error('当前导出数据太大，不能正常导出。建议增加筛选条件后重新尝试导出！');
				}
			}else {
				if($typeurl==2){//精确匹配
					$user_source_where .='  and  reg_from_url =  "'.$url.'"  ';
				}else{
					$user_source_where .= '  and  reg_from_url like  "%' . $url . '%"  ';
					//模糊查询，排除精确 开始
					
					$this->load->model('system_config_reg_source_model');
					$urls = $this->system_config_reg_source_model->find_all();
					$urllist = array ();
					foreach ( $urls as $v ) {
						if ($v ['type']==2) {
							if (stristr ( $v ['url'], $url ) !== false && $v ['url'] != $url) {
								$urllist [] = $v ['url'];
							}
						}
					}
					if (count ( $urllist ) > 0) {
			
						if ($user_source_where !== '') {
							$this->db->where ( $user_source_where, null, false );
						}
						$this->db->where_in ( 'reg_from_url', $urllist );
						$urluser = $this->db->select ( 'uid' )->from ( 'user' )->get ()->result_array ();
						$url_uidlist = array ();
						foreach ( $urluser as $u ) {
							$url_uidlist [] = $u ['uid'];
						}
						if (count ( $url_uidlist ) > 0) {
							$user_source_where .= '  and  uid not in  (' . implode ( ',', $url_uidlist ) . ') ';
						}
					}
					//模糊查询，排除精确 结束
				}
			}
		}
		$items_count = $this->user_model->search_count ( $search_key, $search_val, $utype, $ext_where,$user_source_where ,$other_uids);
		if($items_count>20000){
			$this->error('当前导出数据太大，不能正常导出。建议增加筛选条件后重新尝试导出！');
		}
		$items = $this->user_model->search ( $search_key, $search_val, $utype, $ext_where, '', 20000, 0,$user_source_where,$other_uids );
		$buyer_state  = $this->buyer_state;
		$seller_state = $this->seller_state;
		
		// 整理数据
		$data=array();
		foreach ($items as $k=>$v){
			//注册来源
			if ($v ['reg_source'] == 1) {
				$reg_source = '试客联盟';
			} elseif ($v ['reg_source'] == 2) {
				$reg_source = '互联支付';
			} elseif ($v ['reg_source'] == 3) {
				$reg_source = '众划算';
			} else {
				$reg_source = '-';
			}
			//屏蔽状态
			if ($v['utype'] == 1) {
				$str_state =  isset($buyer_state[$v['is_lock']]) ? $buyer_state[$v['is_lock']] : '未知状态('.$v['is_lock'].')';
			}else{
				$str_state =  isset($seller_state[$v['is_lock']]) ? $seller_state[$v['is_lock']] : '未知状态('.$v['is_lock'].')';
			}
			if(in_array($v['is_lock'], array(2,3,4)) ){
				if($v['lock_day']==100){
					$str_state.='（永久）';
				}else {
					$str_state.='（'.$v['lock_day'].'天）';
				}
			}
			$data[] = array(
					$v['uid'],
					$v['uname'],
					$v['email'],
					$v['mobile'],
					$reg_source,
					$str_state,
					$v['content']
			);
		}
		$title =($utype==1?'买家列表':'商家列表').date('Y-m-d H:i:s',$startTime).'-'.date('Y-m-d H:i:s',$endTime).'导出';
		$filename =$title.'.xls';
		$header = array(
				($utype==1?'买家':'商家').'uid',
				($utype==1?'买家':'商家').'名称',
				'绑定邮箱',
				'绑定手机',
				'注册来源',
				'会员状态',
				'屏蔽原因'
		);
		array_unshift($data, $header);
		$this->data_export($data, $title, $filename);
	}
	
	/**
	 * 获取其它用户注册来源url的uid
	 * @param array $users 取出来全部的用户列表
	 * @param array $urls  配置项的用户URL组
	 * @return multitype:unknown
	 */
	private function get_other_uids($users = array(), $urls = array()) {
		$otheruids =$user_uids= array ();
		foreach ( $users as $val ) {
			$other_flag = true;
			if ($val ['reg_from_url']) {
				// 精确 匹配
				foreach ( $urls as $v ) {
					if ($other_flag == false) {
						break;
					}
					if ($v ['type'] == 2 && $other_flag) {
						if ($val ['reg_from_url'] == $v ['url']) {
							$user_uids [] = $val ['uid'];
							$other_flag = false;
						}
					}
				}
				// 模糊匹配
				foreach ( $urls as $v ) {
					if ($other_flag == false) {
						break;
					}
					if ($v ['type'] == 1 && $other_flag) {
						if (stristr ( $val ['reg_from_url'], $v ['url'] ) !== false) {
							$user_uids [] = $val ['uid'];
							$other_flag = false;
							break;
						}
					}
				}
			}
			if ($other_flag) {
				$otheruids [] = $val ['uid'];
			}
		}
		return array('otheruids'=>$otheruids,'user_uids'=>$user_uids);
	}
	
	/**
	 * 编辑商家所属的伙伴
	 *
	 * @author 杜嘉杰
	 * @version 2015年12月10日  下午4:46:44
	 */
	public function save_salesman_uname()
	{
		$uid = intval($this->get_post('uid'));
		if($uid<=0)
		{
			$this->error('用户不存在');
		}
		if($this->get_post('show_type')=='save' )
		{
			$salesman_uname = trim($this->get_post('salesman_uname'));
			if(mb_strlen($salesman_uname)>50)
			{
				$this->error('所属伙伴不能超过50个字符');
			}
			// 保存
			$re = $this->db->set('salesman_uname',$salesman_uname)->where('uid',$uid)->update(Zhs_user_seller_model::$table_name);
			if($re)
			{
				$this->success('保存成功');
			}
			else
			{
				$this->error('保存失败');
			}
		}
		else
		{
			// 展示
			$user_seller = $this->db->select('salesman_uname')->from(Zhs_user_seller_model::$table_name)->where('uid',$uid)->get()->row_array();
			$data['user_seller'] = $user_seller;
			$data['uid'] = $uid;
			$this->load->view('user/dialog/save_salesman_uname', $data);
		}
		
	}
	
}
// End of class User

/* End of file user.php */
/* Location: ./application/controllers/user.php */