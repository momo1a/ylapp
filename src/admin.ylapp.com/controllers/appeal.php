<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 申诉管理控制器
 * @author minch <yeah@minch.me>
 * @version 2013-06-06
 * @property Hlpay $hlpay
 */
class Appeal extends MY_Controller 
{
	public $check_access = TRUE;
	public $except_methods = array('handle');
	
	private $type_name = array('buyer'=>'买家','seller'=>'商家');
	private $type_map = array();
	
	public function __construct(){
		parent::__construct();
		$this->load->model('order_appeal_type_model');
		$this->load->model('admin_order_appeal_model', 'order_appeal_model');
		$this->type_map['buyer'] = Order_appeal_type_model::UTYPE_BUYER;
		$this->type_map['seller'] = Order_appeal_type_model::UTYPE_SELLER;
	}
	
	/**
	 * 未处理申诉列表
	 * @param string $utype_str 用户类型uri（buyer,seller）
	 */
	public function index($utype_str)
	{
		$type_name = $this->type_name;
		$type_map = $this->type_map;
		$key = $this->get_post('key');
		$val = $this->get_post('val');
		$start_time = strtotime($this->get_post('startTime'));	//开始时间
		$end_time = strtotime($this->get_post('endTime'));	//结束时间
		$type_id = intval($this->get_post('type_id'));
		$appeal_type = $this->_get_type($type_map[$utype_str]);
		array_unshift($appeal_type, array('id'=>0, 'name'=>'全部'));	//在第一位增加一个标签项
		$type_id = $type_id ? $type_id : $appeal_type[0]['id'];
		
		$this->load->helper('user');
		
		$data = array();
		/*其他查询条件*/
		if($type_id){	//有值，则为根据type_id，否则为全部查询
			$data['type_id'] = $type_id;
		}
		$ext_where = count($data)? $data : '';

		$limit = 10;
		$offset = $this->uri->segment(4);
		$list = $this->order_appeal_model->get($type_map[$utype_str], array(Order_appeal_model::STATE_REPLIED, Order_appeal_model::STATE_UNREPLY, Order_appeal_model::STATE_UNNEEDED_REPLY), $key, $val, $limit, $offset, $start_time, $end_time, $ext_where);
		$total = $this->order_appeal_model->count($type_map[$utype_str], array(Order_appeal_model::STATE_REPLIED, Order_appeal_model::STATE_UNREPLY, Order_appeal_model::STATE_UNNEEDED_REPLY), $key, $val, $start_time, $end_time, $ext_where);

		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#appeal_list_'.$type_id.'" data-listonly="yes"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$utype_str);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$utype_str.'/0');
		$pager = $this->pager($total, $limit, $page_conf);
		
		$type_count = array();	//存储'申诉记录数，按照type_id分组
		$type_count = $this->order_appeal_model->count($type_map[$utype_str], array(Order_appeal_model::STATE_REPLIED, Order_appeal_model::STATE_UNREPLY, Order_appeal_model::STATE_UNNEEDED_REPLY), $key, $val, 0, 0, '', 'type_id');
		array_unshift($type_count, array('type_id'=>'0','count'=>$total));	//全部记录数放在数组第一位
		
		/*将记录数插入到对应的类型数组中*/
		foreach ($appeal_type as $key=>$val){
			foreach ($type_count as $k=>$v){
				if($appeal_type[$key]['id'] == $type_count[$k]['type_id']){
					$appeal_type[$key]['count'] = $v['count'];
				}
			}
		}
		// 调整返现金额提示"?"
		foreach ( $list as $key=>$val ){
			// 获取买家状态及状态的描述
			$buyer_uid = $val['utype']==1 ? $val['uid'] : $val['reply_uid'];
			$list[$key]['buyer_user'] = $this->db->select('is_lock,lock_day')->from('user')->where('uid', $buyer_uid)->get()->row_array();
		}

		if($this->is_ajax() && $this->get_post('listonly')){
			$this->load->view('appeal/list_rows', get_defined_vars());
		}else{
			$this->load->view('appeal/list', get_defined_vars());
		}
	}

	/**
	 * 申诉记录
	 * @param string $utype_str 用户类型uri（buyer,seller）
	 */
	public function record($utype_str)
	{
		$type_name = $this->type_name;
		$type_map = $this->type_map;
		$key = $this->get_post('key');
		$val = $this->get_post('val');
		$type_id = intval($this->get_post('type_id'));
		$appeal_type = $this->_get_type($type_map[$utype_str]);
		array_unshift($appeal_type, array('id'=>0, 'name'=>'全部'));	//在第一位增加一个标签项
		$type_id = $type_id ? $type_id : $appeal_type[0]['id'];
		
		$data = array();
		/*其他查询条件*/
		if($type_id){	//有值，则为根据type_id，否则为全部查询
			$data['type_id'] = $type_id;
		}
		$ext_where = count($data)? $data : '';
		
		$limit = 10;
		$offset = $this->uri->segment(4);
		$list = $this->order_appeal_model->get($type_map[$utype_str], Order_appeal_model::STATE_CLOSE, $key, $val, $limit, $offset, 0, 0, $ext_where);
		
		// 加入shs_goods_content数据-start, 杜嘉杰 2015-04-03
		$gids = array();
		foreach ($list as $row )
		{
			$gids[] = $row['gid'];		
		}
		if(count($gids))
		{
		
			$goods_content_list = NULL;
			foreach ($this->db->select('gid,url')->from('goods_content')->where_in('gid', $gids)->get()->result_array() as $goods_content)
			{
				$goods_content_list[$goods_content['gid']] = $goods_content;
			}
			foreach ($list as $k=>$v)
			{
				// 第三平台url
				$list[$k]['third_party_url'] = isset($goods_content_list[$v['gid']]['url']) ? $goods_content_list[$v['gid']]['url'] : '';
			}
		}
		// 加入活动数据-end
		
		$total = $this->order_appeal_model->count($type_map[$utype_str], Order_appeal_model::STATE_CLOSE, $key, $val, 0, 0, $ext_where);
		
		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#appeal_records_'.$type_id.'" data-listonly="yes"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$utype_str);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$utype_str.'/0');
		$pager = $this->pager($total, $limit, $page_conf);
		
		$type_count = array();	//存储'申诉记录数，按照type_id分组
		$type_count = $this->order_appeal_model->count($type_map[$utype_str], Order_appeal_model::STATE_CLOSE, $key, $val, 0, 0, '', 'type_id');
		array_unshift($type_count, array('type_id'=>'0','count'=>$total));	//全部记录数放在数组第一位
		
		/*将记录数插入到对应的类型数组中*/
		foreach ($appeal_type as $key=>$val){
			foreach ($type_count as $k=>$v){
				if($appeal_type[$key]['id'] == $type_count[$k]['type_id']){
					$appeal_type[$key]['count'] = $v['count'];
				}
			}
		}
		
		if($this->is_ajax() && $this->get_post('listonly')){
			$this->load->view('appeal/records_rows', get_defined_vars());
		}else{
			$this->load->view('appeal/records', get_defined_vars());
		}
	}

	/**
	 * 处理申诉页面
	 */
	public function handle(){
		$id = $this->get_post('id');
		$handle = intval($this->get_post('handle'));	//处理状态 0为查看申诉，1为处理申诉
		$vo = $this->order_appeal_model->getby_id($id);	//申诉记录
		/*通过申诉记录获取 用户和商品信息*/
		$data = $handle ? array(Order_appeal_model::STATE_REPLIED, Order_appeal_model::STATE_UNREPLY, Order_appeal_model::STATE_UNNEEDED_REPLY) : array(Order_appeal_model::STATE_CLOSE);
		// 当前申诉
		$list = $this->order_appeal_model->get($vo['utype'], $data, 'id', $vo['id'], 1, 0, 0, 0, '');
		$list = $list[0];
		
		if($list == FALSE){
			$this->error('该申诉不存在，可能是已经撤销申诉');
		}
		
		$adjust_rebate_log = array();
		// 查看申诉并且本次申述的处理结果为调整返现金额，才会去获取调整返现金额记录
		if (!$handle AND $vo['result_action'] == 2) {
			$this->load->model('order_adjust_rebate_model');
			$adjust_rebate_log = $this->order_adjust_rebate_model->getby_appeal($id);
		}
		
		// 是否显示凭证、联系方式
		$is_show_contact = $this->order_appeal_model->check_in_type_id( $list['type_id'], array(2,12), $list['dateline'] );
		$this->load->view('appeal/handle', get_defined_vars());
	}
	
	/**
	 * 一键处理申述前置查询
	 * @author 韦明磊(2014.08.14)
	 * @return void
	 */
	public function handle_of_all_front()
	{	
		$this->handle_of_all_where();
		
		$handleCount = $this->db->count_all_results();

		if ($this->input->is_ajax_request())
			echo json_encode(array('handleCount'=>$handleCount));
		else
			return $handleCount;
	}
	
	/**
	 * 一键处理申述
	 * @author 韦明磊(2014.08.14)
	 * @version 0.1
	 * @return void
	 */
	public function handle_of_all()
	{
		$count = (int)$this->input->get('c');
		
		$current_handle_count = (int)$this->input->get('chc');
		
		// 有申诉并且已处理的申诉数量小于等于总数
		if ($count <= 0)
		{
			show_error('非法操作.');
		}
		
		if ($current_handle_count > $count)
		{
			echo '<script type="text/javascript">window.top.showHandleResult();</script>';
			return;
		}
		
		$id = (int)$this->input->get('id'); // 已经处理的申诉ID
		
		$this->db->select('id');
		// 处理条件
		$url_parm = $this->handle_of_all_where();
		
		
		$this->db->where('order_appeal.id >', $id);
		$this->db->order_by('order_appeal.id', 'ASC');
		$this->db->limit(1);
		$appeal = $this->db->get()->row_array();
		
		if (!$appeal)
		{
			//echo '<script type="text/javascript">window.top.showErrorMessage("申诉处理失败!你要处理的申诉已经被处理.");</script>';
			echo '<script type="text/javascript">window.top.showHandleResult();</script>';
			return;
		}
		
		$url_parm['id'] = $appeal['id'];
		$url_parm['content'] = $this->input->get('content')
							? $this->input->get('content', TRUE)
							: '';
		$rs['Code'] = false;
		if ($url_parm['at'] == 12)
		{
			// 恢复资格
			$rs = $this->order_appeal_model->close($appeal['id'], urldecode($url_parm['content']), $this->user_id, $this->username);
		}elseif ($url_parm['at'] == 2) {
			// 取消资格
			$rs = $this->order_appeal_model->disqualification($appeal['id'], urldecode($url_parm['content']), $this->user_id, $this->username);
		}
		$flag = $rs['Code'] ? false : true;
		
		$current_handle_count ++; // 处理数量++
		
		// 传递到URL中做下次处理的依据
		$url_parm['chc'] = $current_handle_count;
		$url_parm['c'] = $count;
		
		$url = site_url('appeal/handle_of_all').'?'.http_build_query($url_parm);

		echo <<<EOT
<script type="text/javascript">window.top.showProgressBar({$flag});</script>
<script type="text/javascript">
	function redirect() {window.location.replace('{$url}');}
	setTimeout('redirect();', 0);
</script>
EOT;
	}
	
	/**
	 * 处理搜索条件并且将处理条件返回
	 * @return array
	 */
	private function handle_of_all_where()
	{
		$url_parm	= array(
				'ut'		=> $this->input->get('ut'),
				'at'		=> (int)$this->input->get('at'),
				// 处理条件
				'starttime'	=> $_GET['starttime'],
				'endtime'	=> $_GET['endtime'],
				'key'		=> $this->input->get('key'),
				'val'		=> $this->input->get('val'),
		);

		$this->db->from('order_appeal');
		$this->db->join('order', 'order_appeal.oid=order.oid');
		$this->db->join('goods', 'goods.gid=order.gid');
		
		$this->db->where('order_appeal.utype', $this->type_map[$url_parm['ut']]);
		$this->db->where_in('order_appeal.state', array(
				Order_appeal_model::STATE_REPLIED,
				Order_appeal_model::STATE_UNREPLY,
				Order_appeal_model::STATE_UNNEEDED_REPLY
		));
		$this->db->where('order_appeal.type_id', $url_parm['at']);
			
		if($url_parm['key'] AND $url_parm['val'])
		{
			switch ($url_parm['key'])
			{
				case 'title':
					$this->db->like('goods.title', $url_parm['val']);
					break;
				case 'gid':
					$this->db->where('goods.gid', (int)$url_parm['val']);
					break;
				case 'buyer':
					$this->db->like('order.buyer_uname', $url_parm['val']);
					break;
				case 'buyer_id':
					$this->db->where('order.buyer_uid', (int)$url_parm['val']);
					break;
				case 'seller_id':
					$this->db->where('order.seller_uid', (int)$url_parm['val']);
					break;
				case 'seller':
					$this->db->like('order.seller_uname', $url_parm['val']);
					break;
				case 'trade_no':
					$this->db->where('order.trade_no', $url_parm['val']);
					break;
				case 'id':
					$this->db->where('order_appeal.id', (int)$url_parm['val']);
					break;
				case 'oid':
					$this->db->where('order.oid', (int)$url_parm['val']);
					break;
				default:
					// XXXX do nothing
			}
		}

		if($url_parm['starttime'])
		{
			$this->db->where('order_appeal.dateline >= ', strtotime($url_parm['starttime']));
		}
		if($url_parm['endtime'])
		{
			$this->db->where('order_appeal.dateline <= ', strtotime($url_parm['endtime']));
		}
		
		return $url_parm;
	}

	/**
	 *批量 处理申诉页面
	 */
	public function batch_handle(){
		$appeal_ids = $this->get_post('appeal_ids');
		$handle = intval($this->get_post('handle'));	//处理状态 0为查看申诉，1为处理申诉
		$this->load->view('appeal/batch_handle', get_defined_vars());
	}
	
	/**
	 * 根据用户类型查询所有申诉类型
	 * @param unknown $utype
	 */
	private function _get_type($utype)
	{
		return $this->order_appeal_type_model->getby_utype($utype);
	}
	
	/**
	 * 申诉详细信息
	 */
	public function detail(){
		$id = $this->get_post('id');
		$vo = $this->order_appeal_model->getby_id($id);
		$this->load->view('appeal/detail', get_defined_vars());
	}
	
	/**
	 * 处理申诉直接返现
	 */
	public function checkout()
	{
		$id = $this->get_post ( 'id' );
		$content = $this->get_post ( 'result' );
		if (! $id or ! $content) {
			$this->error ( '参数错误' );
		}
		// 如果单个处理，在字符后面拼接逗号
		$id = strpos ( $id, ',' ) ? $id . ',' : $id;
		$error = '';
		foreach ( explode ( ',', $id ) as $tiem ) {
			$appeal_id = intval ( $tiem );
			if ($appeal_id > 0) {
				$rs = $this->order_appeal_model->checkout ( $appeal_id, $content, $this->user_id, $this->username );
				if ($rs ['Code']) {
					$error .= $rs ['Message'] . $appeal_id;
				}else{
					$this->app_push($appeal_id, '您的申诉已被管理员处理直接返现');
				}
			}
		}
		if ($error != '') {
			$this->error ( $error );
		} else {
			$this->success ( '系统返现处理中，返现完成后申诉自动关闭' );
		}
	}
	
	/**
	 * 互联支付返现
	 */
	private function _checkout($appeal_id)
	{
		$msg = '';
		$this->load->library('Hlpay');
		$this->load->model('admin_order_appeal_model');
		$this->load->model('admin_order_model');
		$this->load->model('admin_goods_model');
		$appeal = $this->admin_order_appeal_model->getby_id($appeal_id);
		//var_dump($this->order_model);
		$order = $this->admin_order_model->get($appeal['oid']);
		$goods = $this->admin_goods_model->get($order['gid']);
		$money_stat = $this->admin_goods_model->get_money_stat($order['gid']);
		$buyers = '';
		$rs = $this->hlpay->order_checkout($goods['gid'], $goods['uid'], $goods['title'], $goods['single_fee'], $money_stat['remain_guaranty'], $money_stat['remain_fee'], $buyers);
		if(FALSE === $rs){
			$msg = '请求互联支付系统失败。';
		}
		switch ($rs){
			case -1:
				$msg = '商家担保金余额不足。';
				break;
			case -2:
				$msg = '签名不正确。';
				break;
			case -3:
				$msg = '商家UID不正确。';
				break;
			case -4:
				$msg = '请求互联支付系统失败。';
				break;
			case -5:
			case -6:
				$msg = '交易信息格式错误。';
				break;
			case -7:
				$msg = '已经存在的订单。';
				break;
			case -8:
				$msg = '服务费金额错误。';
				break;
			case -9:
				$msg = '剩余的担保金不正确。';
				break;
			case -10:
				$msg = '剩余的服务费不正确。';
				break;
			default:
				if(0 <= $rs){
					$msg = TRUE;
				}else{
					$msg = '请求互联支付系统失败。';
				}
		}
		return $msg;
	}
	
	/**
	 * 处理申诉,增加返现时间
	 */
	public function increase_deadline()
	{
		$id = $this->get_post ( 'id' );
		$days = intval ( $this->get_post ( 'days' ) );
		$content = $this->get_post ( 'result' );
		if (! $id or ! $content or ! $days) {
			$this->error ( '参数错误' );
		}
		// 如果单个处理，在字符后面拼接逗号
		$id = strpos ( $id, ',' ) ? $id . ',' : $id;
		$error = '';
		foreach ( explode ( ',', $id ) as $tiem ) {
			$appeal_id = intval ( $tiem );
			if ($appeal_id > 0) {
				$rs = $this->order_appeal_model->increase_deadline ( $appeal_id, $content, $days, $this->user_id, $this->username );
				if ($rs ['Code']) {
					$error .= $rs ['Message'] . $appeal_id;
				}else{
					$this->app_push($appeal_id, '您的申诉已被管理员增加返现时间');
				}
			}
		}
		if ($error != '') {
			$this->error ( $error );
		} else {
			$this->success ( '操作成功' );
		}
	}
	
	/**
	 * 处理申诉,关闭申诉
	 */
	public function close()
	{
		$id = $this->get_post('id');    
		$content = trim(strval($this->get_post('result')));
		if(!$id or !$content){
			$this->error('参数错误');
		}
		// 如果单个处理，在字符后面拼接逗号
		$id = strpos($id, ',') ? $id.',' : $id;
		$error='';
	   foreach (explode(',', $id) as $tiem){
			$appeal_id = intval($tiem);
			if($appeal_id>0){
				$rs = $this->order_appeal_model->close($appeal_id, $content, $this->user_id, $this->username);
				if($rs['Code']){
					$error.=$rs['Message'].$appeal_id;
			    }else{
			    	$this->app_push($appeal_id, '您的申诉已被管理员处理恢复资格');
			  }
		  }
		}
		if($error !=''){
			$this->error($error);
		}else{
			$this->success('操作成功');
		}
	}
	
	/**
	 * 处理申诉,取消资格
	 */
	public function disqualification()
	{
		$id = $this->get_post('id');
		$content = $this->get_post('result');
		if(!$id or !$content){
			$this->error('参数错误');
		}
		// 如果单个处理，在字符后面拼接逗号
		$id = strpos($id, ',') ? $id.',' : $id;
		$error='';
		foreach (explode(',', $id) as $tiem){
			$appeal_id = intval ( $tiem );
			if ($appeal_id > 0) {
				$rs = $this->order_appeal_model->disqualification ( $appeal_id, $content, $this->user_id, $this->username );
				if ($rs ['Code']) {
					$error .= $rs ['Message'] . $appeal_id;
				}else{
					$this->app_push($appeal_id, '您的申诉订单已被管理员取消用户资格');
				}
			}
		}
		if($error !=''){
			$this->error($error);
		}else{
			$this->success('操作成功');
		}
	}
	
	/**
	 * 处理申诉,调整返现金额
	 */
	public function adjust_rebate()
	{
		$id = $this->get_post ( 'id' );
		$content = trim ( strval ( $this->get_post ( 'result' ) ) );
		$amount = floatval ( $this->get_post ( 'amount' ) );
		if (! $id or ! $content or ! $amount) {
			$this->error ( '参数错误' );
		}
		// 如果单个处理，在字符后面拼接逗号
		$id = strpos ( $id, ',' ) ? $id . ',' : $id;
		$error = '';
		foreach ( explode ( ',', $id ) as $tiem ) {
			$appeal_id = intval ( $tiem );
			if ($appeal_id > 0) {
				$rs = $this->order_appeal_model->adjust_rebate ( $appeal_id, $content, $amount, $this->user_id, $this->username );
				if ($rs ['Code']) {
					$error .= $rs ['Message'] . $appeal_id;
				}else{
					$this->app_push($appeal_id, '您的申诉的订单已被管理员调整返现金额');
				}
			}
		}
		if ($error != '') {
			$this->error ( $error );
		} else {
			$this->success ( '操作成功' );
		}
	}
	
	/**
	 * 判定买家订单号有误,需48小时内修改单号
	 */
	public function tradeno_error()
	{
		$id = $this->get_post ( 'id' );
		$content = trim ( strval ( $this->get_post ( 'result' ) ) );
		if (! $id or ! $content) {
			$this->error ( '参数错误' );
		}
		// 如果单个处理，在字符后面拼接逗号
		$id = strpos ( $id, ',' ) ? $id . ',' : $id;
		$error = '';
		// 处理成功的申述id，用户推送消息到客户端
		$success_id=array();
		foreach ( explode ( ',', $id ) as $tiem ) {
			$appeal_id = intval ( $tiem );
			if ($appeal_id > 0) {
				$rs = $this->order_appeal_model->tradeno_error ( $appeal_id, $content, $this->user_id, $this->username );
				if ($rs ['Code']) {
					$error .= $rs ['Message'] . $appeal_id;
				}else{
					$this->app_push($appeal_id, '您的申诉已被管理员判定订单号有误,请在48小时内修改订单号');
				}
			}
		}
		
		if ($error != '') {
			$this->error ( $error );
		} else {
			$this->success ( '操作成功' );
		}
	}
	
	/**
	 * 判定买家订单号正确
	 */
	public function tradeno_correct()
	{
		$id = $this->get_post ( 'id' );
		$content = trim ( strval ( $this->get_post ( 'result' ) ) );
		if (! $id or ! $content) {
			$this->error ( '参数错误' );
		}
		// 如果单个处理，在字符后面拼接逗号
		$id = strpos ( $id, ',' ) ? $id . ',' : $id;
		$error = '';
		foreach ( explode ( ',', $id ) as $tiem ) {
			$appeal_id = intval ( $tiem );
			if ($appeal_id > 0) {
				$rs = $this->order_appeal_model->tradeno_correct( $appeal_id, $content, $this->user_id, $this->username );
				if ($rs ['Code']) {
					$error .= $rs ['Message'] . $appeal_id;
				}else{
					$this->app_push($appeal_id, '您的申诉的订单已被管理员判断订单号正确');
				}
			}
		}
		if ($error != '') {
			$this->error ( $error );
		} else {
			$this->success ( '操作成功' );
		}
	}
	
	/**
	 * 处理申诉,修改订单号
	 */
	public function adjust_tradeno()
	{
		$id = $this->get_post ( 'id' );
		$content = trim ( strval ( $this->get_post ( 'result' ) ) );
		$tradeno = trim ( strval ( $this->get_post ( 'tradeno' ) ) );
		if (! $id or ! $content or ! $tradeno) {
			$this->error ( '参数错误' );
		}
		// 如果单个处理，在字符后面拼接逗号
		$id = strpos ( $id, ',' ) ? $id . ',' : $id;
		$error = '';
		foreach ( explode ( ',', $id ) as $tiem ) {
			$appeal_id = intval ( $tiem );
			if ($appeal_id > 0) {
				$rs = $this->order_appeal_model->adjust_tradeno ( $appeal_id, $content, $tradeno, $this->user_id, $this->username );
				if ($rs ['Code']) {
					$error .= $rs ['Message'] . $appeal_id;
				}else{
					$this->app_push($appeal_id, '您的申诉的订单已被管理员修改单号');
				}
			}
		}
		if ($error != '') {
			$this->error ( $error );
		} else {
			$this->success ( '操作成功' );
		}
	}

	/**
	 * 申诉类型管理
	 * @param string $user_type 用户类型（buyer、seller）
	 */
	public function type($utype_str){
		$type_name = $this->type_name;
		$type_map = $this->type_map;
		$type_list = $this->order_appeal_type_model->getby_utype($type_map[$utype_str]);
		$this->load->view('appeal/type', get_defined_vars());
	}
	
	/**
	 * 增加或编辑申诉类型管理
	 */
	public function type_form(){
		$id = $this->get_post('id', 0);
		if('yes'==$_POST['dosave']){
			$data = array();
			$data['name'] = trim(strip_tags($this->get_post('name')));
			$data['utype'] = $this->get_post('utype');
			$data['need_reply'] = $this->get_post('need_reply');
			$data['sort'] = intval($this->get_post('sort'));
			if($id){
				$rs = $this->order_appeal_type_model->update($data, array('id'=>$id));
			}else{
				$rs = $this->order_appeal_type_model->insert($data);
			}
			if($rs){
				$this->success('保存成功');
			}else{
				$this->error('保存失败');
			}
		}
		$type = $this->uri->segment(3);
		$vo = $this->order_appeal_type_model->getby_id($id);
		if(empty($vo)){
			$vo['utype'] = $this->type_map[$type];
		}
		$this->load->view('appeal/type_form', get_defined_vars());
	}
	
	/**
	 * 删除申诉类型（【系统优化1.0】停用）
	 */
	public function type_delete(){
		$id = $this->get_post('id');
		$rs = $this->order_appeal_type_model->delete($id);
		if($id && $rs){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
	
	/**
	 * 屏蔽/解屏
	 * @param int $shield 屏蔽状态：0解屏、1屏蔽
	 */
	public function type_shield( $shield ){
		$shield = intval( $shield );
		$id = intval( $this->get_post('id') );
		$rs = $this->order_appeal_type_model->shield( $id, $shield );
		
		if( $shield ){
			if($id && $rs){
				$this->success('屏蔽成功');
			}else{
				$this->error('屏蔽失败');
			}
		}else{
			if($id && $rs){
				$this->success('解屏成功');
			}else{
				$this->error('解屏失败');
			}
		}
	}
	
	/**
	 * 管理员处理申述后，向买家APP客户端发送推送消息
	 * @param int $appeal_id 申述ID
	 * @param string $content 管理员处理内容
	 * @author 关小龙
	 * @version 2014-9-13
	 * @return boolean
	 */
	public function app_push($appeal_id,$content)
	{
		$ret = $this->order_appeal_model->getby_id($appeal_id);
		if( !$ret ){
			return FALSE;
		}
		if( $ret['utype']==$this->type_map['buyer'] ){
			$buyer_uid = $ret['uid'];
		}elseif( $ret['utype']==$this->type_map['seller'] ){
			$buyer_uid = $ret['reply_uid'];
		}
		$this->load->driver('app_push');
		$custom = array('type'=>'1009', 'uid'=>$buyer_uid, 'value'=>array('appeal_id'=>$appeal_id));//申诉详情
		return $this->app_push->push_app_single($buyer_uid,$content, $time=0, $custom);
		
	}

	/**
	 * 批量封号，这个函数起到到请求转发的作用，从申述编号获取买家uid请求到封号('user/lock)界面
	 *
	 * @author 杜嘉杰
	 * @version 2014-12-2
	 */
	public function batch_lock_user(){
		$appeal_ids = $this->get_post('appeal_ids');
		$appeal_ids = explode(',', $appeal_ids);
	
		$uid = array();
		foreach ($appeal_ids as $id){
			$appel = $this->db->select('utype,uid,reply_uid')->from('order_appeal')->where('id',$id)->get()->row_array();
			if ($appel) {
				$uid[] = $appel['utype']==1 ? $appel['uid'] : $appel['reply_uid'];

			}
		}
		$url =  site_url('user/lock?&utype=1&uids='.implode($uid, ','));
		header('Location:'.$url);
	}
	
	
	/**
	 * 显示调整返现金额提示
	 * @author 关小龙
	 * @version 2015-03-13
	 * @return void
	 */
	public function show_adjust_tips()
	{
		$search_where = array(
			'oid'  => intval($this->uri->segment(3)) //订单ID
		);
		die( adjust_tips($search_where) );
	}
}
// End of class Appeal

/* End of file appeal.php */
/* Location: ./application/controllers/appeal.php */