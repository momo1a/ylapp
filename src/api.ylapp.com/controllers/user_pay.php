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
        $this->load->model('Money_model','money');
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
                //var_dump($response);
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
                $submit = $this->alipay->createOrder($tradeNo,$orderBody,$amount,$orderBody);
                $this->response($this->responseDataFormat(0,'请求成功',array('alipayReqStr'=>$submit)));
                break;
            case 3 :  // 银联支付
                $way = trim($this->input->get_post('way'));
                if(!$way){
                    $way = 'wap';
                }
                $this->load->library('union/Unionpay',array(site_url().'notice/union_recharge',site_url().'union_notice/recharge_success.html'),'unipay');
                $tradeNo = 'UNIONCZ'.time().rand(10000,99999).$uid;
                $data['tradeNo'] = $tradeNo;
                $data['tradeChannel'] = 3;
                $this->pay->submitPay($data) ? '' : $this->response($this->responseDataFormat(-1,'系统数据库错误',array()));
                $response = $this->unipay->orderPay($tradeNo,$amount,$way);
                if(!$response){$this->response($this->responseDataFormat(-1,'银联接口异常',array()));}
                $this->response($this->responseDataFormat(0,'请求成功',$response));
                break;
            default :
                $this->response($this->responseDataFormat(-1,'请选择正确的支付方式',array()));
        }
    }




    /**
     * 订单支付
     */
    public function orderPay(){
        $type = array(
            '2' => '疫苗接种',
            '3' => '基因检测',
            '4' => '电话问诊',
            '5' => '在线问答',
            '6' => '预约挂号'
        );
        $tradeNoPre = array(
            '2' => 'YMXD',
            '3' => 'JYXD',
            '4' => 'DHWZ',
            '5' => 'ZXWD',
            '6' => 'YYGH',
        );

        /*$notifyUri = array(
            '2' => 'wx_ym_notify',
            '3' => 'wx_jy_notify',
            '4' => 'wx_dh_notify',
            '5' => 'wx_wd_notify',
            '6' => 'wx_yy_notify',
        );*/
        $uid = self::$currentUid;   //  当前用户uid
        $payType = intval($this->input->get_post('payType'));  // 支付类型 0 余额支付 1 微信支付 2 支付宝支付 3 银联支付
        $orderType = intval($this->input->get_post('orderType'));  // ２疫苗接种支付，３基因检测支付，４电话问诊支付，５在线问答支付，６预约挂号支付'
        !in_array($orderType,array_keys($type))  ? $this->response($this->responseDataFormat(-1,'未知订单类型',array())) : '';
        $amount = intval($this->input->get_post('amount'));  // 金额  单位 ： 分
        $oid = intval($this->input->get_post('orderId'));  // 订单号
        $orderBody = $type[$orderType].'下单';

        $data = array(
            'uid' => $uid,
            'userType' => 1,
            'tradeVolume'=>$amount/100,
            'tradeDesc'=> $type[$orderType].'付款',
            'dateline'=>time(),
            'tradeType'=>$orderType,
            'oid'=>$oid
        );

        switch($payType){
            case 0:  // 余额支付
                $inputPwd = trim($this->input->get_post('payPwd'));  // 支付密码
                if(empty($inputPwd)){
                    $this->response($this->responseDataFormat(2,'支付密码不能为空',array()));
                }
                $userInfo = $this->user->getUserCondition(array('uid'=>self::$currentUid));
                if($this->encryption($inputPwd) != $userInfo['payPassword']){
                    $this->response($this->responseDataFormat(1,'支付密码不正确',array()));
                }
                $remainAmount = $this->money->getUserMoney($uid); // 当前用户余额
                $amount = $amount / 100;
                $amount > $remainAmount[0]['amount'] ? $this->response($this->responseDataFormat(-1,'余额不足',array())) : '';
                $res = $this->money->orderPay($uid,$amount,$oid,$orderType);
                if($res){
                    $this->response($this->responseDataFormat(0,'支付成功',array()));
                }else{
                    $this->response($this->responseDataFormat(-1,'支付失败',array()));
                }
                break;
            case 1:  // 微信支付
                $tradeNo = 'W'.$tradeNoPre[$orderType].time().rand(10000,99999).$uid;
                $data['tradeNo'] = $tradeNo;
                $data['tradeChannel'] = 2;
                $this->pay->submitPay($data) ? '' : $this->response($this->responseDataFormat(-1,'系统数据库错误',array()));
                $noticeUrl = site_url().'notice/order_notify';
                $response = $this->wxpay->getPrePayOrder($orderBody, $tradeNo, $amount,$orderType,$noticeUrl);
                $wxPayUrl = site_url().'wxpay_return/toWxPay?prepay_id='.$response['prepay_id'];
                $this->response($this->responseDataFormat(0,'请求成功',array('wxPayUrl'=>$wxPayUrl)));
                break;
            case 2:  //  支付宝支付
                /*header('Access-Control-Allow-Origin: *');
                header('Content-type: text/plain');*/
                $config = array(
                    'notifyUrl' => site_url().'notice/ali_order_notify',
                    'returnUrl' => site_url().'notice/ali_return'
                );
                /*$config = array(
                    'notifyUrl' =>  'http://123.207.87.83:8080/alipay/notify_url.php',
                    'returnUrl' => 'http://123.207.87.83:8080/alipay/return_url.php'
                );*/
                $this->load->library('alipay/AliPay',$config,'alipay');   // 支付宝支付调用类
                $tradeNo = 'A'.$tradeNoPre[$orderType].time().rand(10000,99999).$uid;
                $data['tradeNo'] = $tradeNo;
                $data['tradeChannel'] = 1;
                $this->pay->submitPay($data) ? '' : $this->response($this->responseDataFormat(-1,'系统数据库错误',array()));
                $amount = $amount / 100;
                $submit = $this->alipay->createOrder($tradeNo,$orderBody,$amount,$orderBody);
                $this->response($this->responseDataFormat(0,'请求成功',array('aliSubmitParam'=>$submit)));
                break;

            case 3:
                $way = trim($this->input->get_post('way'));
                if(!$way){
                    $way = 'wap';
                }
                $this->load->library('union/Unionpay',array(site_url().'notice/union_order_notify',site_url().'union_notice/pay_success.html'),'unipay');
                $tradeNo = 'UNI'.$tradeNoPre[$orderType].time().rand(10000,99999).$uid;
                $data['tradeNo'] = $tradeNo;
                $data['tradeChannel'] = 3;
                $this->pay->submitPay($data) ? '' : $this->response($this->responseDataFormat(-1,'系统数据库错误',array()));
                $response = $this->unipay->orderPay($tradeNo,$amount,$way);
                if(!$response){$this->response($this->responseDataFormat(-1,'银联接口异常',array()));}
                $this->response($this->responseDataFormat(0,'请求成功',$response));
                break;

            default:
                $this->response($this->responseDataFormat(-1,'请选择正确的支付方式',array()));
        }

    }

}