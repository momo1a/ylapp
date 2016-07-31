<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 伙伴数据导出
 * 
 * @author yangjiguang
 * @property Excel_Xml $excel
 * @property Data_export_model $data
 */
class Partner_export extends MY_Controller {
	public $check_access = TRUE;
	public $except_methods = array ();
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'data_export_model', 'data' );
	}
	
	/**
	 * 审核统计数据和导出
	 */
	public function check_data() {
		$startTime = strtotime ( $this->get_post ( 'startTime' ) ) ? strtotime ( $this->get_post ( 'startTime' ) ) : strtotime ( date ( 'Y-m-d', time () ) );
		$endTime = strtotime ( $this->get_post ( 'endTime' ) ) ? strtotime ( $this->get_post ( 'endTime' ) ) : time ();
		$search_key = $this->get_post ( 'search_key' );
		$search_val = trim ( $this->get_post ( 'search_val' ) );
		
		$where .= '  shs_goods_log.dateline  BETWEEN ' . $startTime . ' and ' . $endTime;
		if ($search_val) {
			switch ($search_key) {
				case 'uid' :
					$where .= ' and shs_goods_log.uid="' . $search_val . '"';
					break;
				case 'uname' :
					$where .= ' and shs_goods_log.uname like"' . $search_val . '"';
					break;
			}
		}
		
		// 审核的次数
		$this->db->select ( 'COUNT(*) sumnum, uid , uname' );
		$this->db->where ( array (
				'before_state' => 3,
				'after_state' => 5 
		) );
		$this->db->where ( $where, NULL, FALSE );
		$this->db->group_by ( 'uid' );
		$this->db->order_by ( 'uid' );
		$checknum = $this->db->from ( 'goods_log' )->get ()->result_array ();

		// 待上线的
		$this->db->select ( 'COUNT(distinct shs_goods_log.gid ) notonlinenum, goods_log.uid , goods_log.uname' );
		$this->db->distinct ( 'goods_log.gid' );
		$this->db->where ( array (
				'goods_log.before_state' => 3,
				'goods_log.after_state' => 5 
		) );
		$this->db->join('goods', 'goods.gid = goods_log.gid');
		$this->db->where ( $where, NULL, FALSE );
		$this->db->where('goods.state',5);
		$this->db->group_by ( 'goods_log.uid' );
		$this->db->order_by('goods_log.dateline desc, goods_log.uid asc');
		$notonline = $this->db->from ( 'goods_log' )->get ()->result_array ();
		$notonlinelist = array ();
		foreach ( $notonline as $k => $val ) {
			$notonlinelist [$val ['uid']] = $val;
		}
		
		// 已经上线
		$this->db->select ( 'COUNT(distinct shs_goods_log.gid ) onlinenum, goods_log.uid , goods_log.uname' );
		$this->db->distinct ( 'goods_log.gid' );
		$this->db->where ( array (
				'goods_log.before_state' => 3,
				'goods_log.after_state' => 5
		) );
		$this->db->join('goods', 'goods.gid = goods_log.gid');
		$this->db->where ( $where, NULL, FALSE );
		$this->db->where('goods.state >=',20);
		$this->db->group_by ( 'goods_log.uid' );
		$this->db->order_by('goods_log.dateline desc, goods_log.uid asc');
		$online = $this->db->from ( 'goods_log' )->get ()->result_array ();
		$onlinelist = array ();
		foreach ( $online as $k => $val ) {
			$onlinelist [$val ['uid']] = $val;
		}
		foreach ( $checknum as $k => $v ) {
			
			$checknum [$k] ['onlinenum'] = isset ( $onlinelist [$v ['uid']] ['onlinenum'] ) ? $onlinelist [$v ['uid']] ['onlinenum'] : 0;
			$checknum [$k] ['notonlinenum'] = isset ( $notonlinelist [$v ['uid']] ['notonlinenum'] ) ? $notonlinelist [$v ['uid']] ['notonlinenum'] : 0;
			$checknum [$k] ['chenknum'] = $checknum [$k] ['onlinenum'] + $checknum [$k] ['notonlinenum'];
			$countsum += isset ( $checknum [$k] ['sumnum'] ) ? $checknum [$k] ['sumnum'] : 0;
			$countchenk += $checknum [$k] ['chenknum'];
			$countonline += $checknum [$k] ['onlinenum'];
			$counnotonline += $checknum [$k] ['notonlinenum'];
		}
		 if( $this->get_post ( 'export' )){
		 	foreach ( $checknum as $k => $v ) {
		 		$data[] = array(
		 				$v['uid'],
		 				$v['uname'],
		 				$v['sumnum'],
		 				$v['onlinenum']+$v['notonlinenum'],
		 				$v['onlinenum'],
		 				$v['notonlinenum']
		 		);
		 	}
		 	$data[] = array('合计','全部管理员',$countsum,$countchenk,$countonline,$counnotonline);
		 	$title = '管理员审核活动记录'.date('Y-m-d H-i-s',$startTime).'-'.date('Y-m-d H-i-s',$endTime);
		 	$filename = $title.'.xls';
		 	$header = array(
		 			'用户ID',
		 			'管理员',
		 			'审核次数',
		 			'审核活动个数',
		 			'已审核-已上线',
		 			'已审核-待上线'
		 	);
		 	array_unshift($data, $header);
		 	$this->data_export($data, $title, $filename);
		 }else{
		 	$this->load->view ( 'partner_export/check_data', get_defined_vars () );
		 }

		
	}
	
	/**
	 * 申诉数据
	 */
	public function appeal_data() {
		
		$startTime = strtotime ( $this->get_post ( 'startTime' ) ) ? strtotime ( $this->get_post ( 'startTime' ) ) : strtotime ( date ( 'Y-m-d', time () ) );
		$endTime = strtotime ( $this->get_post ( 'endTime' ) ) ? strtotime ( $this->get_post ( 'endTime' ) ) : time ();
		$search_key = $this->get_post ( 'search_key' );
		$search_val = trim ( $this->get_post ( 'search_val' ) );
		$where = '  finish_time  BETWEEN ' . $startTime . ' and ' . $endTime;
		if ($search_val) {
			switch ($search_key) {
				case 'uid' :
					$where .= ' and admin_uid="' . $search_val . '"';
					break;
				case 'uname' :
					$where .= ' and admin_uname like"%' . $search_val . '%"';
					break;
			}
		}
		$limit = 10;
		$offset = $this->uri->segment ( 3 );
		$this->load->model ( 'admin_order_appeal_model', 'order_appeal' );
		$appeal_data = $this->order_appeal->get_appeal_data ( $where, 'admin_uid ASC', $limit, $offset );
		$appeal_total = $this->order_appeal->get_appeal_data_count ( $where );
		$page_conf = array (
				'anchor_class' => 'type="load" rel="div#main-wrap"' 
		);
		$pager = $this->pager ( $appeal_total, $limit, $page_conf );
		
		$total_seller = $total_buyer = $total_num = 0;
		foreach ( $appeal_data as $k => $v ) {
			$total_seller += $v ['seller'];
			$total_buyer += $v ['buyer'];
			$total_num += $v ['num'];
		}
		$this->load->view ( 'partner_export/appeal_data', get_defined_vars () );
	}
	
	/**
	 * 导出申诉数据
	 */
	public function export_appeal_data() {
	    
		$startTime = strtotime ( $this->get_post ( 'startTime' ) ) ? strtotime ( $this->get_post ( 'startTime' ) ) : strtotime ( date ( 'Y-m-d', time () ) );
		$endTime = strtotime ( $this->get_post ( 'endTime' ) ) ? strtotime ( $this->get_post ( 'endTime' ) ) : time ();
		$search_key = $this->get_post ( 'search_key' );
		$search_val = trim ( $this->get_post ( 'search_val' ) );
		$where = '  finish_time  BETWEEN ' . $startTime . ' and ' . $endTime;
		if ($search_val) {
			switch ($search_key) {
				case 'uid' :
					$where .= ' and admin_uid="' . $search_val . '"';
					break;
				case 'uname' :
					$where .= ' and admin_uname like"%' . $search_val . '%"';
					break;
			}
		}
		$data = $this->data->export_appeal_data($where);
		$title = '管理员处理申诉记录'.date('Y-m-d H-i-s',$startTime).'-'.date('Y-m-d H-i-s',$endTime);
		$filename = $title.'.xls';
		$header = array(
				'用户ID',
				'管理员',
				'已处理买家申诉',
				'已处理商家申诉',
				'处理申诉总量'
		);
		array_unshift($data, $header);
		$this->data_export($data, $title, $filename);
		
	}
}