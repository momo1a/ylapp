<?php
/**
 * 支付成功回调控制器
 * User: Administrator
 * Date: 2016/10/5 0005
 * Time: 下午 2:51
 */


class Notice extends CI_Controller
{


    public function __construct(){
        parent::__construct();
        $this->load->model('Pay_model','pay');
        $this->load->helper('url');
    }

    /**
     * 微信充值通知
     */
    public function wx_recharge(){
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
        require_once('Weixinpay/example/notify.php');
        $QueryNotify = new PayNotifyCallBack();
        $msg = '';
        $notify = $QueryNotify->NotifyProcess($values,$msg);
        if(!$notify){
            echo $this->_BackXml($msg);
            return;
        }

        $check = $this->pay->getRow($values['out_trade_no']);
        if($check['status']){
            echo $this->_BackXml('订单状态错误(或已经支付)');
            return;
        }
        $result = $this->pay->changeRechargeStatus($check['uid'],$values['out_trade_no'],$values['total_fee']/100);
        if(!$result){
            echo $this->_BackXml('事务回滚');
            return;
        }
        echo $this->_BackXml('OK',TRUE);
    }



    private function _BackXml($msg='',$state = FALSE){
        $error = $state?'SUCCESS':'FAIL';
        return printf('<xml><return_code><![CDATA[%s]]></return_code><return_msg><![CDATA[%s]]></return_msg></xml>',$error,$msg);
    }

    /**
     * 支付充值通知地址
     */
    public function ali_recharge(){
        $config = array(
            'notifyUrl' => site_url().'notice/ali_recharge',
            'returnUrl' => site_url().'notice/ali_return'
        );
        $this->load->library('alipay/AliPay',$config,'alipay');   // 支付宝支付调用类
        $verifyResult = $this->alipay->notify();
        if($verifyResult){  //  验证通过

            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号
            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];

            // 交易额
            $total_fee = $_POST['total_fee'];


            if($_POST['trade_status'] == 'TRADE_FINISHED') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //付款完成后，支付宝系统发送该交易状态通知

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }else{

                $check = $this->pay->getRow($out_trade_no);
                if($check['status']){
                    echo $this->_BackXml('订单状态错误(或已经支付)');
                    return;
                }

                $this->pay->changeRechargeStatus($check['uid'],$out_trade_no,$total_fee);
            }

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            echo "success";		//请不要修改或删除
        }else{     // 验证失败
            //验证失败
            echo "fail";

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }

    public function ali_return()
    {
        $config = $config = array(
            'notifyUrl' => site_url() . 'notice/ali_recharge',
            'returnUrl' => site_url() . 'notice/ali_return'
        );
        $this->load->library('alipay/AliPay', $config, 'alipay');   // 支付宝支付调用类
        $verifyResult = $this->alipay->notify();
        var_dump($this->alipay);
        if ($verifyResult) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

            //商户订单号
            $out_trade_no = $_GET['out_trade_no'];

            //支付宝交易号
            $trade_no = $_GET['trade_no'];

            //交易状态
            $trade_status = $_GET['trade_status'];


            if ($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
            } else {
                echo "trade_status=" . $_GET['trade_status'];
            }

            echo "验证成功hello<br />";

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            //////////////
        }

    }
}