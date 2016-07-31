<?php
/**
 * 所有订单 - 控制器
 * @author 杨积广  2014.1.11
 */
class Order extends MY_Controller {

	protected $states = array('1' => '待填写订单号', '3' => '待审核订单号', '4' => '待返现', '5' => '审核不通过', '6' => '申诉中', '7' => '已关闭', '8' => '返现中', '9' => '已完成');
	protected $page_size = 10;
	protected $data = array();

	/**
	 * 获取订单列表
	 *
	 * @param string table 查询的表
	 * @param string select_fields 读取的数据表字段
	 */
	public function _get_data($table, $select_fields='*') {

		$data = $this->data;
		$where=array();
		$where_like=array();

		// 获取搜索信息
		$search_key = isset($_GET['search_key']) ? trim(strip_tags($_GET['search_key'])) : '';
		$search_val = isset($_GET['search_val']) ? trim(strip_tags($_GET['search_val'])) : '';
		$type_key = isset($_GET['type_key']) ? trim(strip_tags($_GET['type_key'])) : '';
		$type_val = isset($_GET['type_val']) ? trim(strip_tags($_GET['type_val'])) : '';

		if($search_key=='oid' && $search_val){
			$where['order.oid']=$search_val;
		}elseif ($search_key=='no' && $search_val){
			$where['order.trade_no']=$search_val;
		}elseif ($search_key=='gid' && $search_val){
			$where['order.gid']=$search_val;
		}elseif ($search_key=='title' && $search_val){
			$where_like['order.title']=$search_val;
		}
		if($type_key=='buyer' && $type_val){
			$where_like['order.buyer_uname']=$type_val;
		}elseif ($type_key=='seller' && $type_val){
			$where_like['order.seller_uname']=$type_val;
		}

		// 获取总条数,（查询订单总行数太久了，使用缓存存下来）
		$cache_order_count = 'admin_order_count'; 
		$total = cache($cache_order_count);
		if( ! $total){
			if(count($where_like)>0 ){$this->db->like($where_like);}
			if(count($where)>0 ){$this->db->where($where);}
			$total = $this->db->from($table)->count_all_results();
			cache($cache_order_count, $total, 3600);
		}
		$offset = $this->uri->segment(3);
		$page_size = $this->page_size;
		
		$segment = $this->uri->segment(4);

		// 获取数据
		if(count($where_like)>0 ){$this->db->like($where_like);}
		if(count($where)>0 ){$this->db->where($where);}
		$this->db->join('order_appeal','order_appeal.id=order.appeal_id','left');
		$this->db->select($select_fields)->from($table)->limit($page_size, $offset);
		$data['rows'] = $this->db->order_by('oid DESC')->get()->result_array();
		
		foreach ($data['rows'] as $k=>$v){
			$adjust_rebate_where = array('oid'=>$v['oid'], 'before_state'=>6, 'content REGEXP'=>'^管理员处理申诉,调整(划算金|返现金)');
			$this->load->model('order_log_model', 'log_model');
			$data['rows'][$k]['appeals'] = $this->log_model->get($adjust_rebate_where);
		}

		// 获取图片url和状态的时间
		$this->load->helper('image_url');
		foreach ($data['rows'] as $key => $row) {
			$data['rows'][$key]['img'] = image_url($row['gid'], $row['img']);
			if($row['state'] == 1){
				$data['rows'][$key]['count_down_default'] = $this->init_count_down($row['auto_timeout_time']);
			}elseif($row['state'] == 3 || $row['state'] == 4){
				$data['rows'][$key]['count_down_default'] = $this->init_count_down($row['auto_checkout_time']);
			}elseif($row['state'] == 5){
				$data['rows'][$key]['count_down_default'] = $this->init_count_down($row['auto_close_time']);
			}
			
			// 获取买家状态
			$data['rows'][$key]['buyer_user'] = $this->db->select('is_lock,lock_day')->from('user')->where('uid',$row['buyer_uid'])->get()->row_array();
		}
		// 翻页
		$page_conf = array('uri_segment'=>3,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment.'/0');
		$data['pager'] = $this->pager($total, $page_size, $page_conf);

		$data['search_val'] = $search_val;
		$data['search_key'] = $search_key;
		$data['type_key'] = $type_key;
		$data['type_val'] = $type_val;
		$data['statelist'] = $this->states;

		return $data;
	}

	/**
	 * 显示订单列表
	 */
	public function all() {
		$this->load->helper('user');
		$fields = 'order.oid,order.gid,order.state,order.seller_uid,order.seller_uname,order.buyer_uid, order.buyer_uname, order.dateline,order.trade_no,order.title,order.img,order.show_id,order.url,order.price,order.discount,order.price,order.cost_price,order.search_reward,';
		$fields.='order_appeal.type_id,order_appeal.result_content,order_appeal.finish_time,order.real_rebate,order.auto_timeout_time,order.auto_checkout_time,order.auto_close_time,order.show_id,order.adjust_rebate,order.appeal_id,order.appeal_utype,order.appeal_state,order.appeal_count,';
		$fields.='site_type,fill_site_type';
		$data = &$this->_get_data('order', $fields);

		$this->load->view('order/order',$data);
	}
	/**
	 * 取消资格页面
	 */
	public function handle(){
		$oid = trim($this->get_post('oid'));
		$this->load->view('order/handle', get_defined_vars());
	}
	/**
	 * 处理取消资格操作
	 */
	public function disqualification()
	{ 
		$oid_str = trim($this->get_post('oid'));
		$content = $this->get_post('msg');
		
		if(!$oid_str or !$content){
			$this->error('参数错误');
		}
		
		$oids = explode(',', $oid_str);

		foreach ($oids as $oid) {
			$oid = intval($oid);
			$result = $this->db->query('CALL proc_order_cancel_qualification(?,?,?,?,?)', array($oid, $content, $this->user_id, $this->username, bindec(decbin(ip2long($this->input->ip_address())))));
			$rs = $result->first_row('array');
			$result->free_result();
			$this->db->close();
			if (!in_array($rs['Code'], array(0, 1))) {
				$this->error($rs['Message']);
			} else {
				//取消未填写单号app推送信息
				$this->db->reconnect();
				$this->load->model('zhs_user_push_remind_model');
				$this->zhs_user_push_remind_model->delete_user_push_remind($this->user['id'], 1, $oid);
			}
		}
		$this->success('操作成功');

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
	 * 批量封号，这个函数起到到请求转发的作用，从oid获取买家uid请求到封号('user/lock)界面
	 * 
	 * @author 杜嘉杰
	 * @version 2014-12-2
	 */
	public function batch_lock_user(){
		$oids = $this->get_post('oids');
		$oids = explode(',', $oids);
		
		$uid = array();
		foreach ($oids as $oid){
			$order = $this->db->select('buyer_uid')->from('order')->where('oid',$oid)->get()->row_array();
			if (isset($order['buyer_uid'])) {
				$uid[] = $order['buyer_uid'];
			}
		}
		$url =  site_url('user/lock?&utype=1&uids='.implode($uid, ','));
		header('Location:'.$url);
	}
}
?>