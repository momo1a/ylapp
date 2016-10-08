<?php
/**
 * 返回微信支付接口地址给客户端发起支付
 * User: Administrator
 * Date: 2016/10/5 0005
 * Time: 下午 6:24
 */


class Wxpay_return extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->library('WxPay',null,'wxpay');   // 微信支付调用类
        $this->load->helper('url');
        $this->load->model('Pay_model','pay');
    }


    /**
     * 发起微信支付
     */
    public function toWxPay(){
        $prepay_id = $this->input->get('prepay_id');
        $backClient = $this->wxpay->getOrder($prepay_id);
        exit(json_encode($backClient));  //  返回数据给客户端发起支付请求
    }


    /**
     * 微信支付测试
     */
    public function toWxpayTest(){
        $data = array(
            'uid' => 1,
            'userType' => 1,
            'tradeVolume'=>1/100,
            'tradeDesc'=>'充值',
            'dateline'=>time(),
            'tradeType'=>1
        );
        $orderBody = '我的微信测试支付';
        $tradeNo = 'CSWXCZ'.time().rand(10000,99999).'1';
        $data['tradeNo'] = $tradeNo;
        $data['tradeChannel'] = 2;
        $this->pay->submitPay($data) ? '' : exit(json_encode(array('code'=>-1,'msg'=>'数据库错误')));
        $noticeUrl = site_url().'notice/wx_recharge';
        $response = $this->wxpay->getPrePayOrder($orderBody, $tradeNo, 1,1,$noticeUrl);
        $backClient = $this->wxpay->getOrder($response['prepay_id']);
        exit(json_encode($backClient));  //  返回数据给客户端发起支付请求
    }

}