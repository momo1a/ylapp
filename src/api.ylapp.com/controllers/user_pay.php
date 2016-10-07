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
        $this->load->model('Pay_model','pay');
        $this->load->helper('url');
    }


    /**
     * 充值接口
     */
    public function recharge(){
        $uid = self::$currentUid;  // 充值用户uid
        $payType = intval($this->input->get_post('payType'));
        $amount = intval($this->input->get_post('amount'));  // 金额  单位 ： 分
        $orderBody = "移动医疗平台充值";
        $data = array(
            'uid' => $uid,
            'userType' => 1,
            'tradeVolume'=>$amount/100,
            'tradeDesc'=>'充值',
            'dateline'=>time(),
            'tradeType'=>1
        );
        switch($payType){
            case 1 :  //  微信支付
                $tradeNo = 'WXCZ'.time().rand(10000,99999).$uid;
                $data['tradeNo'] = $tradeNo;
                $data['tradeChannel'] = 2;
                $this->pay->submitPay($data) ? '' : $this->response($this->responseDataFormat(-1,'系统数据库错误',array()));
                $noticeUrl = site_url().'notice/wx_recharge';
                $response = $this->wxpay->getPrePayOrder($orderBody, $tradeNo, $amount,$uid,$noticeUrl);
                $wxPayUrl = site_url().'wxpay_return/toWxPay?prepay_id='.$response['prepay_id'];
                $this->response($this->responseDataFormat(0,'请求成功',array('wxPayUrl'=>$wxPayUrl)));
                break;
            case 2 :  //  支付宝支付
                $config = array(
                    'notifyUrl' => site_url().'notice/ali_recharge',
                    'returnUrl' => site_url().'notice/ali_return'
                );
                /*$config = array(
                    'notifyUrl' =>  'http://123.207.87.83:8080/alipay/notify_url.php',
                    'returnUrl' => 'http://123.207.87.83:8080/alipay/return_url.php'
                );*/
                $this->load->library('alipay/AliPay',$config,'alipay');   // 支付宝支付调用类
                $tradeNo = 'ALCZ'.time().rand(10000,99999).$uid;
                $data['tradeNo'] = $tradeNo;
                $data['tradeChannel'] = 1;
                $this->pay->submitPay($data) ? '' : $this->response($this->responseDataFormat(-1,'系统数据库错误',array()));
                $amount = $amount / 100;
                $submit = $this->alipay->submitPay($tradeNo,$orderBody,$amount,"");
                $this->response($this->responseDataFormat(0,'请求成功',array('aliSubmitParam'=>$submit)));
                break;
            case 3 :  // 银联支付
                break;
            default :
                $this->response($this->responseDataFormat(-1,'请选择正确的支付方式',array()));
        }
    }


    /**
     *
     * @param $prepOrder
     */
    protected function wxPaySignReply($prepOrder){

    }


}