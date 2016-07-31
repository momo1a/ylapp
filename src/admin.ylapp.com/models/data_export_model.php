<?php

/**
 * 数据导出模型类
 * @author minch <yeah@minch.me>
 * @version 20130723
 */
class Data_export_model extends CI_Model
{

	/**
	 * 数据库表名
	 * @var string
	 */
	private $_table;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 交易额导出
	 * @param number $starttime 起始时间
	 * @param number $endtime 结束时间
	 * @param number $pid 活动主类目
	 * @param number $cid 活动子类目
	 * @param unknown $goods_season_type 期号类型（0新品，1追加过）
	 * @param unknown $goods_type 商品类型（0普通，1一站成名_普通活动，2名品馆，3搜索下单活动，4一站成名_二维码活动，5一站成名_搜索下单活动， 1000手机专享价）
	 * @return multitype:|multitype:multitype:number unknown string Ambigous <unknown>
	 */
	public function trade( $starttime = 0, $endtime = 0,$pid=0,$cid=0, $goods_season_type, $goods_type )
	{
		//添加分类名称导出
		$categorylist = $this->db->select ( ' id,name,pid ' )->order_by ( 'sort' )->get ( 'goods_category' )->result_array ();
		foreach ( $categorylist as $k => $v ) {
			$cidlist[$v['id']]= $v['name'];
		}
		$ogids=array();
		
		if ($pid) {
			$this->db->where ( 'pid', $pid );
			if ($cid) {
				$this->db->where ( 'cid', $cid );
			}
		}
		if ($pid || $cid) {
			$ogoods = $this->db->select ( 'gid' )->from ( 'goods' )->get ()->result_array ();
			foreach ($ogoods as $val){
				$ogids[]=$val['gid'];
			}
		}
		
		//添加分类名称导出结束
		$data = array();
		if($starttime){
			$this->db->where('dateline >=', $starttime);
		}
		if($endtime){
			$this->db->where('dateline <=', $endtime);
		}
		if (count ( $ogids ) > 0) {
			$this->db->where_in ( 'gid', $ogids );
		}
		$this->db->select('gid,count(oid) as order_count');
		$orders=$this->db->from('order')->where_in( 'state', array(Order_model::STATUS_FILLED, Order_model::STATUS_CHECK_SUCCESS, Order_model::STATUS_APPEAL, Order_model::STATUS_REFUND_PAYING, Order_model::STATUS_REFUNDED) )->group_by('gid')->get()->result_array();
		$goods_order_count = array();
		foreach($orders as $k=>$v){
			$goods_order_count[$v['gid']] = $v['order_count'];
		}
	
		if(!count($goods_order_count)){
			return $data;
		}
		$gids = array_keys($goods_order_count);
	
		// 区分新品或追加
		$new_or_add_str = '所有活动';
		if( $this->_set_where_by_season_no($goods_season_type) ){
			$new_or_add_str = isset( Goods_model::$new_or_add_str[$goods_season_type] ) ? Goods_model::$new_or_add_str[$goods_season_type] : '所有活动';
		}
		

		// 区分商品活动类型
		$goods_type_name = '所有活动类型';
		if( isset( Goods_model::$type_str[$goods_type] ) ){
			$this->db->where( 'type ', $goods_type );
			$goods_type_name = isset( Goods_model::$type_str[$goods_type] ) ? Goods_model::$type_str[$goods_type] : '所有活动类型';
		}
		
		// 手机下单专享
		if($goods_type == Admin_goods_model::GOODS_IS_MOBILE_PRICE)
		{
			$this->db->where( 'price_type ', 2 );
			$goods_type_name = '手机专享价';
		}

		if($pid){
			$this->db->where('pid', $pid);
			if($cid){
				$this->db->where('cid', $cid);
			}
		}
		$goods = $this->db->select('gid,uname,pid,cid,price,single_fee,fill_order_num,first_starttime,quantity,first_quantity,uid')->from('goods')->where_in('gid', $gids)->get()->result_array();
		
		// 查询shs_user_seller表
		$seller_uids = array();
		foreach ($goods as $g)
		{
			$seller_uids[] = $g['uid'];
		}
		$seller_uids = array_unique($seller_uids);
		$user_sellers = array();
		foreach ($this->db->select('uid,salesman_uname')->from(Zhs_user_seller_model::$table_name)->where_in('uid',$seller_uids)->get()->result_array() as $seller)
		{
			$user_sellers[$seller['uid']] = $seller;
		}
		
		foreach ($goods as $k=>$v){
			$data[] = array(
					$new_or_add_str,
					$goods_type_name,
					$v['gid'],
					$cidlist[$v['pid']],
					$cidlist[$v['cid']],
					$v['uname'],
					date("Y-m-d H:i:s", $v['first_starttime']),
					$v['first_quantity'],
					$v['quantity']-$v['first_quantity'],
					$v['price'],
					$goods_order_count[$v['gid']],
					$v['price']*$goods_order_count[$v['gid']],
					$v['single_fee'],
					$v['single_fee']*$goods_order_count[$v['gid']],
					$v['salesman_uname'] = $user_sellers[$v['uid']]['salesman_uname'],
			);
		}
		return $data;
	}
	
	/**
	 * 根据商品场次新旧定义where条件
	 * @param int $goods_season_type 商品期号新旧
	 * @return boolean
	 */
	private function _set_where_by_season_no($goods_season_type){
		if( isset( $goods_season_type ) ){
			// 全新活动
			if( $goods_season_type === Goods_model::NEW_GOODS ){
				$this->db->where( 'season_no <=', $goods_season_type );
			// 追加活动
			}elseif ( $goods_season_type === Goods_model::ADD_GOODS ){
				$this->db->where( 'season_no >=', $goods_season_type );
			}
			return true;
		}
		return false;
	}

	/**
	 * 损耗费数据导出
	 * @param number $starttime 起始时间
	 * @param number $endtime 结束时间
	 */
	public function fee($starttime = 0, $endtime = 0)
	{
		$data = array();
		$hlpaydb = $this->load->database('hulianpay', TRUE);
		//查询区间内的返现的订单数
		if($starttime){
			$hlpaydb->where('postTime >=', date("Y-m-d H:i:s.000", $starttime));
		}
		if($endtime){
			$hlpaydb->where('postTime <=', date("Y-m-d H:i:s.999", $endtime));
		}
		$hlpaydb->select('gid,count(gid) order_count');
		$goods_order_count = array();
		foreach ($hlpaydb->group_by('gid')->from('P_GoodsPay')->where('type', 1)->where('uid <> touid')->get()->result_array() as $k=>$v){
			$goods_order_count[$v['gid']] = $v['order_count'];
		}
		if(!count($goods_order_count)){
			return $data;
		}
		$gids = array_keys($goods_order_count);
		// 查询商品信息
		$goods = $this->db->select('gid,uname,price,single_fee,fill_order_num,rebate_num,first_starttime,quantity,first_quantity,uid')->from('goods')->where_in('gid', $gids)->get()->result_array();
		
		// 查询shs_user_seller表
		$seller_uids = array();
		foreach ($goods as $g)
		{
			$seller_uids[] = $g['uid'];
		}
		$seller_uids = array_unique($seller_uids);
		$user_sellers = array();
		foreach ($this->db->select('uid,salesman_uname')->from(Zhs_user_seller_model::$table_name)->where_in('uid',$seller_uids)->get()->result_array() as $seller)
		{
			$user_sellers[$seller['uid']] = $seller;
		}
		
		// 整理数据
		foreach ($goods as $k=>$v){
			$order_count = intval($goods_order_count[$v['gid']]);
			$data[] = array(
				$v['gid'],
				$v['uname'],
				date("Y-m-d H:i:s", $v['first_starttime']),
				$v['first_quantity'],
				$v['quantity']-$v['first_quantity'],
				$v['price'],
				$v['price']*$v['fill_order_num'],
				$order_count,
				$v['rebate_num'],
				$v['single_fee'],
				$v['single_fee']*$order_count,
				$v['single_fee']*$v['rebate_num'],
				$v['salesman_uname'] = $user_sellers[$v['uid']]['salesman_uname'],
			);
		}
		return $data;
	}

	/**
	 * 财务数据导出
	 * @param string $type 活动类型(new:新上线,add:追加,其它为全部)
	 * @param number $starttime 起始时间
	 * @param number $endtime 结束时间
	 */
	public function finance($type = '', $starttime = 0, $endtime = 0,$pid=0,$cid=0)
	{
		//增加分类查询
		$categorylist = $this->db->select ( ' id,name,pid ' )->order_by ( 'sort' )->get ( 'goods_category' )->result_array ();
		foreach ( $categorylist as $k => $v ) {
			$cidlist[$v['id']]= $v['name'];
		}
		$rgids=array();
		if ($pid) {
			$this->db->where ( 'pid', $pid );
		}
		if ($cid) {
			$this->db->where ( 'cid', $cid );
		}
		if ($pid || $cid) {
			$rgoods = $this->db->select ( 'gid' )->from ( 'goods' )->get ()->result_array ();
			foreach ($rgoods as $val){
				$rgids[]=$val['gid'];
			}
		}
		//增加分类查询结束
		$data = array();
		switch($type){
			case 'new':
				$this->db->where('num', 0);
				break;
			case 'add':
				$this->db->where('num <>', 0);
				break;
			default:
		}
		if($starttime){
			$this->db->where('dateline >=', $starttime);
		}
		if($endtime){
			$this->db->where('dateline <=', $endtime);
		}
		if (count ( $rgids ) > 0) {
			$this->db->where_in ( 'gid', $rgids );
		}
		$this->db->where('state', 3);
		$this->db->select('gid,num,add_num,add_guaranty,add_fee,add_day,dateline');
		$addition = $this->db->from('goods_addition')->order_by('num ASC')->get()->result_array();
		$gids = array();
		foreach ($addition as $k=>$v){
			$gids[] = $v['gid'];
		}
		if (!count($gids)) {
			return $data;
		}
		$seller_uids = array(); // 商家uid
		$rs = $this->db->select('gid,uid,uname,pid,cid,paid_guaranty,paid_fee,first_starttime,first_quantity')->from('goods')->where_in('gid', $gids)->get()->result_array();
		$goods = array();
		foreach ($rs as $k=>$v){
			$goods[$v['gid']] = $v;
			$seller_uids[] = $v['uid'];
		}
		
		// 查询shs_user_seller表
		$seller_uids = array_unique($seller_uids);
		$user_sellers = array();
		foreach ($this->db->select('uid,salesman_uname')->from(Zhs_user_seller_model::$table_name)->where_in('uid',$seller_uids)->get()->result_array() as $seller)
		{
			$user_sellers[$seller['uid']] = $seller;
		}
		// 整理数据
		foreach ($addition as $k=>$v){
			$order_count = intval($goods[$v['gid']]);
			$data[] = array(
				date("Y-m-d H:i:s", $v['dateline']),
				$goods[$v['gid']]['uid'],
				$goods[$v['gid']]['uname'],
				$v['num'] ? '追加' : '全新',
				$v['gid'],
				$cidlist[$goods[$v['gid']]['pid']],
				$cidlist[$goods[$v['gid']]['cid']],
				$v['add_guaranty'],
				$v['add_fee'],
				$v['salesman_uname'] = $user_sellers[$goods[$v['gid']]['uid']]['salesman_uname'],
			);
		}
		return $data;
	}
	
	/**
	 * 开团提醒数据导出
	 * @param number $stime 开始时间
	 * @param number $etime 结束时间
	 * @param string $search_key 搜索键名
	 * @param string $search_val 搜索值
	 * @param string $ext_where 其它条件
	 */
	public function goods_remind($stime = 0, $etime = 0, $search_key='', $search_val='', $ext_where = '')
	{
		if ($stime) {
			$this->db->where('dateline >=', $stime);
		}
		if($etime){
			$this->db->where('dateline <=', $etime);
		}
		if(''!==$search_key && ''!==$search_val){
			switch($search_key){
				case 'uname':
					$this->db->like('uname', $search_val);
					break;
				case 'email':
					$this->db->like('email', $search_val);
					break;
				case 'mobile':
					$this->db->like('mobile', $search_val);
					break;
				case 'gid':
					$this->db->where('gid', $search_val);
					break;
				default:
					// XXXX do nothing
			}
		}
		if('' !== $ext_where){
			if (is_array($ext_where)) {
				foreach ($ext_where as $k=>$v){
					if(is_numeric($k)){
						$this->db->where($v);
						continue;
					}
					if(is_array($v)){
						$this->db->where($k, array_shift($v), (boolean)array_shift($v));
					}elseif (is_string($v)){
						$this->db->where($k, $v);
					}
				}
			}elseif (is_string($ext_where)){
				$this->db->where($ext_where);
			}
		}
		$data = array();
		$this->db->select('id,type,gid,uid,uname,email,mobile,sys_msg,state,dateline');
		$result = $this->db->from('goods_remind')->order_by('id DESC')->get()->result_array();
		// 整理数据
		foreach ($result as $k=>$v){
			$state = '';
			switch ($v['state']) {
				case 3:
					$state = '×';
					break;
				case 2:
					$state = '√';
					break;
				case 1:
					$state = '!';
					break;
			}
			$data[] = array(
					$state,
					$v['uname'],
					$v['gid'],
					$v['email'],
					$v['mobile'],
					2 == $v['type'] ? '追加提醒' : '开团提醒',
					date("Y-m-d", $v['dateline'])
			);
		}
		return $data;
	}
	
	/**
	 * 导出管理员处理申诉统计
	 * @param string $where
	 */
	public function export_appeal_data($where=''){
		
		if($where !=''){
			$this->db->where($where, NULL, FALSE);
		}
		$this->db->select('admin_uid,admin_uname,count(if(utype=2,true,null)) seller,count(if(utype=1,true,null)) buyer,count(id) num',false);
		$this->db->where('state',4);
		$this->db->group_by('admin_uid');
		$this->db->order_by('admin_uid ASC');
		$appeal_data=$this->db->from('order_appeal')->get()->result_array();
		foreach ( $appeal_data as $k => $v ) {
			$total_seller += $v ['seller'];
			$total_buyer += $v ['buyer'];
			$total_num += $v ['num'];
			$data[] = array(
					$v['admin_uid'],
					$v['admin_uname'],
					$v['buyer'],
					$v['seller'],
					$v['num']
			);
		}
		   $data[] = array('合计','全部管理员',$total_seller,$total_buyer,$total_num);
		   
		return $data;
	}
	
	
}