<?php
/**
 * 互联支付交易类
 * 
 * @author 温守力
 * @version 13.7.16
 */
class Hlpay {
	protected $server;
	protected $site;
	protected $key;

	protected $error;

	function __construct() {
		$CI = &get_instance();
		$this->server = $CI->config->item('hulian_server') or show_error('缺少配置项：hulian_server');
		$this->site = $CI->config->item('hulian_site') or show_error('缺少配置项：hulian_site');
		$this->key = defined('KEY_HLPAY')?KEY_HLPAY:show_error('缺少配置项：hulian_key');
	}

	/**
	 * 返回最近操作的错误提示
	 */
	public function error() {
		return $this->error;
	}
	
	public function _call($method, $param) {
		$soap = new SoapClient($this->server . 'services/hlpay.asmx?WSDL');
		try {
			$ret = $soap->__soapCall($method, array($method => $param));
			return $ret;
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			return FALSE;
		}
	}

	/**
	 * 订单返现
	 * 审核通过订单，确认返现：支付返现金、服务费，返还担保金
	 *
	 * @param int gid 商品id
	 * @param int uid 商家id
	 * @param string title 商品标题
	 * @param float per_fee 单笔服务费
	 * @param float remain_guaranty 剩余的担保金
	 * @param float remain_fee 剩余的服务费
	 * @param string buyers id,uid,payno,backno,serverno,paymoney,backmoney（参与id,用户id,支付的订单号,退还差价订单号,服务费订单号,单笔返现金,退还的差价）
	 * 
	 * @return string 返回字符串，错误返回false
	 * //In_Goods_RecordResult 形式为oid,uid,orderno,backorder,serviceorder,state;oid,uid,orderno,backorder,serviceorder,state
	 * //抢购编号oid，买家uid，返给买家部分款的订单号，退还商家部分款的订单号，支付服务费的订单号，互联支付处理返现的状态，state如下：
	 * -1 没有担保金
	 * -2 签名不正确
	 * -3 pNo中的用户uid不正确，传错
	 * -4 pNo 为空或没有交易对象
	 * -5,-6 无法解析pNo 数据集
	 * -7 已经存在的订单
	 * -8 服务费不正常
	 * -9 剩余的担保金不正确
	 * -10 剩余的服务金不正确
	 * >0 操作成功
	 */
	public function order_checkout($gid, $uid, $title, $per_fee, $remain_guaranty, $remain_fee, $buyers) {
		$param = array();
		$param['uid'] = $uid;
		$param['gid'] = $gid;
		/*搜索下单增加参数*/
		$param['guaranteetitle'] = '支付众划算活动“'.$title.'”返现金额';     //给买家看的                     支付给买家的活动标题
		$param['backtitle']      = '退还众划算活动“'.$title.'”担保金';     //给卖家看的                     退还给卖家的活动标题
		$param['servicetitle']   = '支付众划算活动“'.$title.'”服务费';     //给互联支付看的           扣除服务费的活动标题
		$param['discounttitle']  = '支付众划算活动“'.$title.'”搜索奖励金'; //给买家看的                      返还搜索奖励活动标题
		/**结束**/
		$param['site'] = $this->site;
		$param['key'] = md5($this->site . $per_fee . $uid . $buyers . $this->key);
		$param['servicemoney'] = $per_fee;
		$param['freezeGoodsMoney'] = $remain_guaranty;
		$param['freezeServiceMoney'] = $remain_fee;
		$param['pNo'] = $buyers;
		$ret = $this->_call('In_Goods_Record', $param);
		return $ret ? $ret->In_Goods_RecordResult : FALSE;
	}

	/**
	 * 获取用户金钱
	 * 
	 * @param int uid 用户编号
	 * @return 成功返回金钱string，失败则返回false
	 */
	public function get_user_money($uid) {
		$auth = rand(0, 10000);
		$param = array('site' => $this->site, 'uid' => $uid, 'money' => $auth, 'key' => md5($this->site . $auth . $uid . $this->key));
		$ret = $this->_call('getUserMoney', $param);
		return $ret ? $ret->getUserMoneyResult : FALSE;
	}
	
	/**
	 * 获取互联支付用户信息
	 *
	 * @param int $uid
	 * @return boolean/object {用户uid，余额，是否实名认证，真实姓名}
	 */
	public function get_user_info($uid)
	{
		$auth = rand(0, 10000);
		$param = array('site' => $this->site, 'uid' => $uid, 'money' => $auth, 'key' => md5($this->site . $auth . $uid . $this->key));
		$ret = $this->_call('getUser', $param);
		return $ret ? $ret->getUserResult : FALSE;
	}
	
	/**
	 * 商品退款 <br />
	 * 商品审核不通过全额退款给卖家(其它情况？)
	 * @author minch
	 * @version 2013-07-17
	 * @param int $gid 商品ID
	 * @param int $uid 用户ID
	 * @param string $title 标题
	 * @param float $money 金额(key中的验证金额，不参与交易)
	 * @return 返回说明
	 * -1	活动已经没有担保的金额
	 * -2	密匙验证不通过
	 * -4	不存在的活动担保
	 * 大于0		操作成功
	 */
	public function goods_refund($gid, $uid, $title, $money,$pno='',$oldpno=''){
		$param = array();
		$param['site'] = $this->site;
		$param['uid'] = $uid;
		$param['gid'] = $gid;
		$param['title'] = $title;
		$param['oldpNo'] = $oldpno;
		$param['pNo'] = $pno;
		$param['money'] = $money;
		$param['key'] = md5($this->site . $money . $uid . $pno . $oldpno . $this->key);
		$rs = $this->_call('payGoodsBackmentMoneyRecord', $param);
		return $rs ? $rs->payGoodsBackmentMoneyRecordResult : FALSE;
	}
	
	/**
	 * 结算商品(商家点结算商品)
	 * @author 宁天友
	 * @param array $param 参数数组,如：
	 * 			<p>$data = array(</p>
	 *			<p>	'gid' => 商品gid,</p>
	 *			<p>	'uid' => 商家uid,</p>
	 *			<p>	'title' => 商品标题,</p>
	 *			<p>	'paymoney' => 结算的担保金,</p>
	 *			<p>	'servicemoney' => 结算的服务费,</p>
	 *			<p>	'lastmoney' => 商品剩余的金额,</p>
	 *			<p>	'guaranteeorder' => 担保金订单号,</p>
	 *			<p>	'serviceorder' => 服务费订单号,</p>
	 *			<p>	'paymessage' => 担保金备注,</p>
	 *			<p>	'servicemessage' => 服务费备注,</p>
	 *			<p>);</p>
	 * @param decimal(9,2) $keymoney 生成KEY的money数值(decimal型)，由paymoney+servicemoney得来
	 * @return int $return 
	 * 			<p>返回大于0，则成功</p>
	 * 			<p>返回-2，则密匙验证不通过</p>
	 * 			<p>返回-3，则总金额不对应</p>
	 * 			<p>返回-10，则没有剩余的金额</p>
	 * 			<p>返回其他小于0，则结算失败</p>
	 */
	public function goods_balance($params, $keymoney){
		$param = array();
		if(is_array($params)){
			foreach ($params as $k=>$value) {
				$param[$k] = $value;
			}
		}
		$param['site'] = $this->site;
		$param['key'] = md5($this->site . $keymoney . $param['uid'] . $this->key);
		$rs = $this->_call('payGoodsBackDoneMoneyRecord', $param);
		return $rs->payGoodsBackDoneMoneyRecordResult;
	}
	
	/**
	 * 向互联支付请求单笔结算
	 * @param number $gid
	 * @param number $uid
	 * @param string $payTitle
	 * @param string $serviceTitle
	 * @param string $payPno
	 * @param string $servicePno
	 * @param number $payMoney
	 * @param number $serviceMoney
	 * @param number $freezeGoodsMoney
	 * @param number $freezeServiceMoney
	 * 返回值说明 	类型及范围	说明
	 * 大于0	操作成功
	 * -2	密匙验证不通过
	 * -3	已经支付的订单号
	 * -4	服务费金额不正确
	 * -5	众划算当前冻结的总担保金与互联支付不对
	 * -6	众划算当前冻结的总服务费与互联支付不对
	 * -10	没有没有可用的支付金额
	 */
	public function single_checkout($gid, $uid, $price, $payTitle, $serviceTitle, $payPno, $servicePno, $payMoney, $serviceMoney, $freezeGoodsMoney, $freezeServiceMoney)
	{
		$param = array();
		$param['site'] = $this->site;
		$param['gid'] = $gid;
		$param['uid'] = $uid;
		$param['paytitle'] = $payTitle;
		$param['servicetitle'] = $serviceTitle;
		$param['payPno'] = $payPno;
		$param['servicePno'] = $servicePno;
		$param['paymoney'] = $payMoney;
		$param['servicemoney'] = $serviceMoney;
		$param['freezeGoodsMoney'] = $freezeGoodsMoney;
		$param['freezeServiceMoney'] = $freezeServiceMoney;
		$param['key'] = md5($this->site . $price+$serviceMoney . $uid . $this->key);
		$rs = $this->_call('payedGoodsBackMoneyRecord', $param);
		return $rs ? $rs->payedGoodsBackMoneyRecordResult : FALSE;
	}

	/**
	 * 退还一战成名保证金
	 * @param unknown $uid
	 * @param unknown $pno
	 * @param unknown $title
	 * @param unknown $money
	 * 
	 * 错误返回参数说明
	 * 大于0:正常
	 * -2	密匙验证不通过
	 * -3	交易金额不能为小于0
	 */
	public function yzcm_refund($uid,$pno,$title,$money,$deposit_type){
		$param = array();
		$param['site'] = $this->site;
		$param['uid'] = $uid;
		$param['pNo'] = $pno;
		$param['title'] = $title;
		$param['money'] = $money;
		$param['deposit_type']=$deposit_type;
		$param['key'] = md5($this->site . $money . $uid . $pno . $this->key);
		$rs = $this->_call('pay_BackFreezeRecord', $param);
		return $rs ? $rs->pay_BackFreezeRecordResult : FALSE ;
	}

	/**
	 * 互联支付扣款
	 */
	public function hlpay_deduct($deduct_money,$pno,$paypNo,$deposit_type){
		$uid = 2; // 不能是商家本身一般为公司账号(uid=2)
		$title= $deposit_type==2?'扣除“【名品馆】”保证金。':'扣除“一站成名”保证金。';
		$params = array(
			'site'=>$this->site,
			'uid'=>$uid, 
			'pNo'=>$pno,
			'paypNo'=>$paypNo,
			'title'=>$title,
			'money'=>$deduct_money,
			'key'=>md5($this->site.$deduct_money.$uid.$pno.$paypNo. $this->key),
			'deposit_type'=> $deposit_type
		);
		$rs = $this->_call('pay_PayFreezeRecord', $params);
		return $rs ? $rs->pay_PayFreezeRecordResult : FALSE ;
	}
	
	/**
	 * 现金券打款给买家请求参数
	 * @author 关小龙
	 * @version 2014-07-16
	 * @param arrray $params 现金券打款给买家请求的参数
	 * @return boolean
	 */
	public function hlpay_deduct_cash($params){
		$param = array();
		if(is_array($params)){
			foreach ($params as $k=>$value) {
				$param[$k] = $value;
			}
		}
		$param['site'] = $this->site;
		$param['key'] = md5($this->site.$param['money'].$param['uid'].$param['pNo'].$param['paypNo']. $this->key);
		$rs = $this->_call('pay_PayFreezeRecord', $param);
		return $rs ? $rs->pay_PayFreezeRecordResult : FALSE ;
	}
	
	/**
	 * 众划算查询互联支付订单号是否已经存在
	 *
	 * 返回参数说明
	 * -2：密匙验证不通过
	 * 大于0:订单号已存在
	 * 等于0:订单号不存在
	 */
	public function hlpay_check_pay_pno($gid,$uid,$money,$pno){
		$params = array(
			'site'=>$this->site,
			'gid'=>$gid, 
			'uid'=>$uid, 
			'type'=>1, //订单号类型 1 担保金，2服务费
			'pNo'=>$pno,
			'money'=>$money,
			'key'=>md5($this->site.$money.$uid.$pno.$this->key),
		);
		$rs = $this->_call('getGoodsPrepaymentRecord', $params);
		return $rs->getGoodsPrepaymentRecordResult;
	}

	/**
	 * 众划算查询互联支付返现订单号是否已经存在
	 *
	 * 返回参数说明
	 * -2：密匙验证不通过
	 * 大于0:订单号已存在
	 * 等于0:订单号不存在
	 */
	public function hlpay_check_return_pno($gid,$uid,$money,$pno){
		$params = array(
			'site'=>$this->site,
			'gid'=>$gid, 
			'uid'=>$uid, 
			'pNo'=>$pno,
			'money'=>$money,
			'key'=>md5($this->site.$money.$uid.$pno.$this->key),
		);
		$rs = $this->_call('getGoodspayRecord', $params);
		return $rs->getGoodspayRecordResult;
	}
	
	/**
	 * 互联支付即时交易接口
	 * 
	 * @param array $params = 
	 * array(
	 *   'uid'   =>$uid,   //支付的用户id
	 *   'touid' =>$touid, //接收的用户id
	 *   'pNo'   =>$pno,   //交易号
	 *   'title' =>$title, //标题
	 *   'money' =>$money  //支付交易的金额
	 * );
	 * @return boolean / int
	 *  -1	金额不足
	 *	-2	密匙验证不通过
	 *	-3	交易金额不能为小于0
	 *	-4	其中有一个号为测试账号
	 *	-5	存在的订单
	 *	FALSE 请求失败
	 *	大于0    转账成功
	 */
	public function hlpay_immediate_pay($params)
	{
		$param = array();
		if(is_array($params))
		{
			foreach ($params as $k=>$value)
			{
				$param[$k] = $value;
			}
		}
		$param['site'] = $this->site;
		$param['key'] = md5($param['money'].$param['uid'].$param['touid'].$param['pNo'].$this->key);
		$rs = $this->_call('In_immediatelyRecord', $param);
		return $rs ? $rs->In_immediatelyRecordResult : FALSE ;
	}
	
}
?>