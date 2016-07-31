<?php
/**
 *管理后台结算活动
 * @author 杨积广
 * @date 20140520
 *
 */

class admin_goods_balance extends CI_Model{
	function __construct(){
  		parent::__construct();
	}
	
	/**
	 * 获取指定商品活动信息
	 * @param int $uid 用户UID
	 * @param int $gid 商品ID
	 * @param bool $includeImgs 是否需要包含4张活动附图 ,默认不包含
	 * @return array
	 */
	function get_goods_info($gid=0, $includeImgs=FALSE){
		$field_array = array();
		$field_goods = array(
				'dateline', 'pid', 'cid','uid',
				'is_taoke', 'source', 'block_reason', 'sort', 'child_sort', 'hits','search_reward','type',
				'wait_fill_num', 'fill_order_num', 'rebate_num', 'show_num', 'join_num', 'source', 'buy_limit', 'type', 'single_rebate', 'deposit_type'
		);
		$field_goods_business = array('*');
		$field_goods_content = array(
				'keyword', 'content',
		);
		$sql = 'SELECT '.implode(',', $field_goods).' FROM '.$this->db->dbprefix.'goods '.
				'WHERE  gid=\''.$gid.'\' LIMIT 1';
		$rowGoods = $this->db->query($sql)->row_array();
		if( ! empty($rowGoods)){
			$sql = 'SELECT '.implode(',', $field_goods_business).' FROM '.$this->db->dbprefix.'goods_business '.
					'WHERE  gid=\''.$gid.'\' LIMIT 1';
			$rowGoodsBusiness = $this->db->query($sql)->row_array();
			if( ! empty($rowGoodsBusiness)){
				$sql = 'SELECT '.implode(',', $field_goods_content).' FROM '.$this->db->dbprefix.'goods_content '.
						'WHERE gid=\''.$gid.'\' LIMIT 1';
				$rowGoodsContent = $this->db->query($sql)->row_array();
				if( ! empty($rowGoodsContent)){
					$row = array_merge($rowGoods, $rowGoodsBusiness, $rowGoodsContent);
				}
			}
		}
		if($includeImgs && ! empty($row)){
			$photo = $this->get_user_img( $row['gid']);
			foreach ($photo as $one) {
				$img[$one['sort']] = $one['img'];
			}
			$row['img'] = $img;
		}
		return $row;
	}
	
	/**
	 * 检测商品是否存在追加待上线记录
	 * @param int $gid 商品gid
	 * @return bool true|false
	 */
	function check_goods_addition($gid){
		$return = false;
		$gid = intval($gid);
		if($gid){
			//state=1为发布支付成功(一站成名时，若是多批次，则会后多条state=1的记录)， state=2为追加成功待上线
			$this->db->from('goods_addition')->where(array('gid'=>$gid));
			$this->db->where_in('state', array(1, 2));
			$this->db->where(array('add_day >'=>0, 'online_time >'=>0));
			$count = $this->db->count_all_results();
			$return = $count > 0 ? true : false;
		}
		return $return;
	}
	
	/**
	 * 获取指定商品金额信息
	 * @param int $gid 商品ID
	 *
	 * @return array
	 */
	function get_goods_money_stat($gid){
		$sql = 'SELECT * FROM '.$this->db->dbprefix.'goods_money_stat '.
				'WHERE gid=\''.$gid.'\' LIMIT 1';
		$row = $this->db->query($sql)->row_array();
		return $row;
	}
	/**
	 * 获取指定用户上传的图片
	 * @param int $uid 用户UID
	 * @param int $gid 商品ID
	 *
	 * @return array
	 */
	function get_user_img( $gid=0){
		$return = array();
		if($gid){
			$sql = 'SELECT * FROM '.$this->db->dbprefix.'goods_photo where  gid=\''.$gid.'\'';
			$return = $this->db->query($sql)->result_array();
		}
		return $return;
	}
	
	
	/**
	 * 执行存储过程:处理结算商品支付前业务(更新商品和支付订单信息)
	 * @param array $params 存储过程参数数组(按表字段顺序排)
	 * @return array 不成功array(Code!=1,Message=错误提示)，成功返回array(Code=1,Message='')
	 */
	function update_data_by_procedure_before_checkout_pay($params){
		$back = array('Code'=>-1000, 'Message'=>'更新商品和支付订单信息存储过程未执行或执行错误');
		$_param = $inparams = array();
		if(is_array($params)){
			foreach ($params as $k=>$value) {
				$inparams[$k] = $value;
			}
			$_param = array_pad(array(), count($inparams), '?');
			$call = 'CALL proc_admin_balance_goods_before_checkout_pay('.implode(', ', $_param).');';//以?填充参数
			$back = $this->db->query($call, $inparams)->row_array();
		}
		return $back;
	}

	/**
	 * 执行存储过程:处理结算(点结算)商品支付成功后业务(更新商品和支付订单信息)
	 * @param array $params 存储过程参数数组(按表字段顺序排)
	 * @return array 不成功array(Code!=0,Message=错误提示)，成功返回array(Code=0,Message='')
	 */
	function update_data_by_procedure_after_checkout_pay($params){
		$back = array('Code'=>-1000, 'Message'=>'更新商品和支付订单信息存储过程未执行或执行错误');
		$_param = $inparams = array();
		if(is_array($params)){
			foreach ($params as $k=>$value) {
				$inparams[$k] = $value;
			}
			$_param = array_pad(array(), count($inparams), '?');
			$call = 'CALL proc_admin_balance_goods_after_checkout_pay('.implode(', ', $_param).');';//以?填充参数
			$this->db->reconnect();
			$back = $this->db->query($call, $inparams)->row_array();
		}
		return $back;
	}

	/**
	 * 获取结算订单号
	 * @param int $gid 商品id
	 * @param int $state 订单状态
	 */
	function get_balance_order($gid){
		$return=array();
		if($gid){
			$sql = 'SELECT * FROM '.$this->db->dbprefix.'goods_checkout_pay where gid=\''.$gid.'\' ';
			$return = $this->db->query($sql)->row_array();
		}
		return $return;
	}
}