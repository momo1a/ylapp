<?php
/**
 * 支付成功回调控制器
 * User: Administrator
 * Date: 2016/10/5 0005
 * Time: 下午 2:51
 */


class Notice extends CI_Controller
{


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
        /*$this->load->model('PayModel');
        $check = $this->PayModel->getRow('shop_order',array('order_number'=>$values['out_trade_no']),'pay_status');
        if($check['pay_status']){
            echo $this->_BackXml('订单状态错误');
            return;
        }*/
        /*$result = $this->PayModel->change_order_status($values['out_trade_no']);
        if(!$result){
            echo $this->_BackXml('事务回滚');
            return;
        }*/
        echo $this->_BackXml('OK',TRUE);
    }



    private function _BackXml($msg='',$state = FALSE){
        $error = $state?'SUCCESS':'FAIL';
        return printf('<xml><return_code><![CDATA[%s]]></return_code><return_msg><![CDATA[%s]]></return_msg></xml>',$error,$msg);
    }
}