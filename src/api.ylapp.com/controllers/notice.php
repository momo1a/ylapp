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

        $check = $this->pay->getRow($values['out_trade_no']);
        //file_put_contents(dirname(__FILE__).'/wx.xml',PHP_EOL.$check['status'],FILE_APPEND);
        if($check['status'] == 1){
            echo $this->_BackXml('已经支付',true);
            return;
        }

        $result = $this->pay->changeRechargeStatus($check['uid'],$values['out_trade_no'],$values['total_fee']/100);
        //file_put_contents(dirname(__FILE__).'/wx.xml',PHP_EOL.'44444',FILE_APPEND);
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
        
        $out_trade_no = $_POST['out_trade_no'];

        //支付宝交易号
        $trade_no = $_POST['trade_no'];

        //交易状态
        $trade_status = $_POST['trade_status'];

        // 交易额
        $total_fee = $_POST['total_fee'];

        $check = $this->pay->getRow($out_trade_no);

        if($check['status'] == 1){
            echo 'success';
            return;
        }
        $this->pay->changeRechargeStatus($check['uid'],$out_trade_no,$total_fee);

        echo "success";		//请不要修改或删除

    }

    //  银联充值通知地址
    public function union_recharge(){
        file_put_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'union.txt',var_export($_REQUEST,true),FILE_APPEND);
    }

    public function ali_return()
    {
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

        echo "验证成功<br />";

    }

    /**
     * 微信下单通知地址
     */
    public function order_notify(){
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


        $check = $this->pay->getRow($values['out_trade_no']);
        if($check['status'] == 1) {
            echo $this->_BackXml('已经支付',true);
            return;
        }
        $result = $this->pay->changeOrderStatus($values['out_trade_no'],$check['oid'],$check['tradeType']);
        if(!$result){
            echo $this->_BackXml('事务回滚');
            return;
        }
        echo $this->_BackXml('OK',TRUE);
    }

    /**
     * 支付宝支付订单通知地址
     */
    public function ali_order_notify(){

        $out_trade_no = $_POST['out_trade_no'];

        //支付宝交易号
        $trade_no = $_POST['trade_no'];

        //交易状态
        $trade_status = $_POST['trade_status'];

        // 交易额
        $total_fee = $_POST['total_fee'];


        $check = $this->pay->getRow($out_trade_no);
        if($check['status'] == 1){
            echo "success";
            return;
        }

        $this->pay->changeOrderStatus($out_trade_no,$check['oid'],$check['tradeType']);

        //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

        echo "success";		//请不要修改或删除

    }

    // 银联支付订单通知
    public function union_order_notify(){
        file_put_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'union_order.txt',var_export($_REQUEST),FILE_APPEND);
    }
}