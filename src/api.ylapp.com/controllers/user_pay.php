<?php
/**
 * 用户支付接口.
 * User: momo1a@qq.com
 * Date: 2016/10/5 0005
 * Time: 下午 2:46
 */

class User_pay extends CI_Controller
{

    /**
     * 初始化
     */
    public function __construct(){
        parent::__construct();
        $this->load->library('WxPay',null,'wxpay');   // 微信支付调用类
        $this->load->helper('url');
    }


    /**
     * 充值接口
     */
    public function recharge(){
        //$uid = self::$currentUid;  // 充值用户uid
        $uid = 5;  // 充值用户uid
        //$payType = intval($this->input->get_post('payType'));
        $payType = 1;
        $orderBody = "移动医疗平台充值";
        switch($payType){
            case 1 :  //  微信支付
                //$amount = intval($this->input->get_post('amount'));  // 金额  单位 ： 分
                $amount = 2;
                $tradeNo = 'WXCZ'.time().rand(10000,99999).$uid;
                $noticeUrl = site_url().'notice/wx_recharge';
                $response = $this->wxpay->getPrePayOrder($orderBody, $tradeNo, $amount,$uid,$noticeUrl);
                $backClient = $this->wxpay->getOrder($response['prepay_id']);
                exit(json_encode($backClient));  //  返回数据给客户端发起支付请求
                break;
            case 2 :  //  支付宝支付
                break;
            case 3 :  // 银联支付
                break;
            default :
                //$this->response($this->responseDataFormat(0,'请求成功','请选择正确的支付方式充值'));
        }
    }


}