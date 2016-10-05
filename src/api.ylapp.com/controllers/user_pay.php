<?php
/**
 * 用户支付接口.
 * User: momo1a@qq.com
 * Date: 2016/10/5 0005
 * Time: 下午 2:46
 */

class User_pay extends MY_Controller
{

    /**
     * 初始化
     */
    public function __construct(){
        parent::__construct();
        $this->checkUserLogin();
        $this->load->library('WxPay',null,'wxpay');   // 微信支付调用类
        $this->load->library('alipay/AliPay',null,'alipay');   // 微信支付调用类
        $this->load->helper('url');
    }


    /**
     * 充值接口
     */
    public function recharge(){
        $uid = self::$currentUid;  // 充值用户uid
        $payType = intval($this->input->get_post('payType'));
        $orderBody = "移动医疗平台充值";
        switch($payType){
            case 1 :  //  微信支付
                $amount = intval($this->input->get_post('amount'));  // 金额  单位 ： 分
                $tradeNo = 'WXCZ'.time().rand(10000,99999).$uid;
                $noticeUrl = site_url().'notice/wx_recharge';
                $response = $this->wxpay->getPrePayOrder($orderBody, $tradeNo, $amount,$uid,$noticeUrl);
                $wxPayUrl = site_url().'wxpay_return/toWxPay?prepay_id='.$response['prepay_id'];
                $this->response($this->responseDataFormat(0,'请求成功',array('wxPayUrl'=>$wxPayUrl)));
                break;
            case 2 :  //  支付宝支付
                $tradeNo = 'ALCZ'.time().rand(10000,99999).$uid;
                $amount = intval($this->input->get_post('amount'));  // 金额  单位 ： 分
                $amount = $amount / 100;
                $this->alipay->submitPay($tradeNo,$orderBody,$amount,"");
                break;
            case 3 :  // 银联支付
                break;
            default :
                $this->response($this->responseDataFormat(0,'请求成功','请选择正确的支付方式充值'));
        }
    }


    /**
     *
     * @param $prepOrder
     */
    protected function wxPaySignReply($prepOrder){

    }


}