<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 订单应用类库
 * @author minch <yeah@minch.me>
 */
class Order_util
{
	private $_CI;

	/**
	 * 订单状态
	 * @var unknown
	 */
	private $status = array(
		'STATUS_UNFILL' => 1,
		'STATUS_UNFILL_STR' => '待填单号',
		'STATUS_TIMEOUT' => 2,
		'STATUS_TIMEOUT_STR' => '填单号超时',
		'STATUS_FILLED' => 3,
		'STATUS_FILLED_STR' => '已填单号',
		'STATUS_CHECK_SUCCESS' => 4,
		'STATUS_CHECK_SUCCESS_STR' => '审核通过',
		'STATUS_CHECK_FAILURE' => 5,
		'STATUS_CHECK_FAILURE_STR' => '订单号有误',
		'STATUS_APPEAL' => 6,
		'STATUS_APPEAL_STR' => '申诉中',
		'STATUS_CLOSED' => 7,
		'STATUS_CLOSED_STR' => '已关闭',
		'STATUS_REFUND_PAYING' => 8,
		'STATUS_REFUND_PAYING_STR' => '返现中',
		'STATUS_REFUNDED' => 9,
		'STATUS_REFUNDED_STR' => '已返现'
	);
	
	private $status_const;
	
	private $status_text = array(
			'STATUS_UNFILL' => '待填单号',
			'STATUS_TIMEOUT' => '超时自动清除',
			'STATUS_FILLED' => '已填单号',
			'STATUS_CHECK_SUCCESS' => '审核通过',
			'STATUS_CHECK_FAILURE' => '审核不通过',
			'STATUS_APPEAL' => '申诉中',
			'STATUS_CLOSED' => '已关闭',
			'STATUS_REFUND_PAYING' => '返现中',
			'STATUS_REFUNDED' => '已返现',
			'STATUS_ONLINE' => '正在进行',
			'STATUS_BLOCKED' => '已屏蔽',
			'STATUS_OFFLINE' => '已下架',
			'STATUS_ADDITION_PAYING' => '追加付款中',
			'STATUS_CHECKOUT_PAYING' => '结算退款中',
			'STATUS_CHECKOUT' => '结算中',
			'STATUS_CHECKOUT_CLOSED' => '已结算'
	);

	/**
	 * 构造函数
	 */
	public function __construct()
	{
		$this->_CI = &get_instance();
		$this->_CI->load->model(array('admin_order_model')); //加载数据库模型
		$this->_CI->load->helper('url'); //加载数据库模型

		$this->status_const = array_flip($this->status);
	}
	
	/**
	 * 返回订单状态文字说明
	 * @param int $var 订单状态
	 * @param array $other 相应需要改变的状态，如：array(4=>'订单号正确'),原状态为4的是“审核通过”，改为“订单号正确”
	 * @return string
	 */
	public function get_status($var, $other=array()){
		if(isset($other[$var])){
			return $other[$var];
		}
		return $this->status_text[$this->status_const[$var]];
	}
	
	/**
	 * 获取当前订单状态可执行的操作
	 * @param array $order 订单信息数组
	 * @return string
	 */
	public function get_action($order){
		$rs = $this->_get_cancel_action($order);
		return $rs;
	}
	private function _get_deduct_action($order)
	{
		$str = '';
		if(in_array($order['state'], array(3,4,5))){
			$str = <<<AA
			<a href="javascript:;" onclick="DeductBrokerage({$order['oid']}, {$order['gid']}, {$order['uid']}, '{$order['uname']}', '');return false;">扣除佣金</a><br />
AA;
		}
		return $str;
	}
	private function _get_cancel_action($order)
	{
		$str = '';
		$url = site_url('goods/cancel_join');
		if(in_array($order['state'], array(3,4))){
			$str = <<<AA
			<a data-oid="{$order['oid']}" data-submit="0" type="form" href="{$url}">取消资格</a><br />
AA;
		}
		return $str;
	}
}