<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pay extends CI_Controller {
	public function __construct()
	{
	    parent::__construct();
	}
	
	public function index(){
		//TODO 动态支付		
	}
	public function weiXin(){
		/*$openId = isset($_SESSION['lao337']['MALL']['uid'])?$_SESSION['lao337']['MALL']['uid']:'';
		if(!$openId){
			return FALSE;
		}*/
        $openId = '123456';
		require_once('Weixinpay/lib/WxPay.Data.php');
		require_once('Weixinpay/lib/WxPay.Api.php');
		require_once('Weixinpay/example/WxPay.JsApiPay.php');
		
		$paymentOid =  5;//$this->input->get('paymentOid',TRUE);
		//$token = $this->input->get('token',TRUE);
		$orderName = '我的测试支付';//$this->input->get('orderName',TRUE);
		$orderNumber = 15;//$this->input->get('single_orderid',TRUE);
		$totalFee = 1;//$this->input->get('price',TRUE)*100;// 单位为分

		$tools = new JsApiPay();	
		$input = new WxPayUnifiedOrder();   // 构造订单参数
		
		$input->SetBody($orderName);
		$input->SetOut_trade_no($orderNumber);
		$input->SetTotal_fee($totalFee);		
		$input->SetNotify_url($this->config->base_url() . 'pay/notify');
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$order = WxPayApi::unifiedOrder($input);
		//var_dump($order);exit;
		$jsApiParameters = $tools->GetJsApiParameters($order);
		
		$returnUrl = $this->config->base_url().'mall/orderdetail/';
		header('Location:' . $this->config->base_url() . 'pay/unifiedorder?jsApiParameters=' . $jsApiParameters . '&returnurl=' . $returnUrl . '&totalfee=' . $_GET['price'] . '&paymentOid=' . $paymentOid);
	}




	public function notify(){
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		if(!$xml){
			echo $this->_BackXml('获取XML为空');
			return;
		}
		libxml_disable_entity_loader(true);
		$values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		if(!$values){
			echo $this->_BackXml('XML解析失败');
			return;
		}
        var_dump($values);exit;
		require_once('Weixinpay/example/notify.php');
		$QueryNotify = new PayNotifyCallBack();
		$msg = '';
		$notify = $QueryNotify->NotifyProcess($values,$msg);
		if(!$notify){
			echo $this->_BackXml($msg);
			return;
		}
		$this->load->model('PayModel');
		$check = $this->PayModel->getRow('shop_order',array('order_number'=>$values['out_trade_no']),'pay_status');
		if($check['pay_status']){
			echo $this->_BackXml('订单状态错误');
			return;
		}
		$result = $this->PayModel->change_order_status($values['out_trade_no']);
		if(!$result){			
			echo $this->_BackXml('事务回滚');
			return;
		}
		echo $this->_BackXml('OK',TRUE);
	}
	public function unifiedorder(){
		$data['totalfee']=$_GET['totalfee'];
		$data['returnUrl']=$_GET['returnurl'];
		$data['jsApiParameters']=$_GET['jsApiParameters'];
		$data['paymentOid']=$_GET['paymentOid'];
		if(!$data['totalfee'] || !$data['returnUrl'] || !$data['jsApiParameters'] || !$data['paymentOid']){
			return FALSE;
		}
		$this->load->view('mall/unifiedorder',$data);
	}
	
	private function _BackXml($msg='',$state = FALSE){
		$error = $state?'SUCCESS':'FAIL';
		return printf('<xml><return_code><![CDATA[%s]]></return_code><return_msg><![CDATA[%s]]></return_msg></xml>',$error,$msg);
	}
}
