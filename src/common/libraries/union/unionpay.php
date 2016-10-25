<?php
//header ( 'Content-type:text/html;charset=utf-8' );
include_once dirname(__FILE__).DIRECTORY_SEPARATOR .'sdk/acp_service.php';

/**
 * 银联支付接口类
 * Class Unionpay
 */
class Unionpay
{
    protected $_request;

    public function __construct(){
        //以下信息非特殊情况不需要改动
        $this->_request = array(
            'version' => '5.0.0',                 //版本号
            'encoding' => 'utf-8',				  //编码方式
            'txnType' => '01',				      //交易类型
            'txnSubType' => '01',				  //交易子类
            'bizType' => '000201',				  //业务类型
            'frontUrl' =>  com\unionpay\acp\sdk\SDK_FRONT_NOTIFY_URL,  //前台通知地址
            'backUrl' => com\unionpay\acp\sdk\SDK_BACK_NOTIFY_URL,	  //后台通知地址
            'signMethod' => '01',	              //签名方法
            'channelType' => '08',	              //渠道类型，07-PC，08-手机
            'accessType' => '0',		          //接入类型
            'currencyCode' => '156',            //交易币种，境内商户固定156
            'merId' => com\unionpay\acp\sdk\SDK_MER_ID,
            'txnTime'=>date('YmdHis'),
        );
    }

    /**
     * 支付订单
     * @param $tradeNo
     * @param $amount
     */
    public function orderPay($tradeNo,$amount){
        $this->_request['orderId'] = $tradeNo;
        $this->_request['txnAmt'] = $amount;
        com\unionpay\acp\sdk\AcpService::sign ($this->_request); // 签名
        $url = com\unionpay\acp\sdk\SDK_App_Request_Url;
        $result_arr = com\unionpay\acp\sdk\AcpService::post ($this->_request,$url);
        var_dump($result_arr);
    }
}






/*$result_arr = com\unionpay\acp\sdk\AcpService::post ($params,$url);
if(count($result_arr)<=0) { //没收到200应答的情况
    printResult ($url, $params, "" );
    return;
}*/

/*printResult ($url, $params, $result_arr ); //页面打印请求应答数据

if (!com\unionpay\acp\sdk\AcpService::validate ($result_arr) ){
    echo "应答报文验签失败<br>\n";
    return;
}


echo "应答报文验签成功<br>\n";
if ($result_arr["respCode"] == "00"){
    //成功
    //TODO
    echo "成功接收tn：" . $result_arr["tn"] . "<br>\n";
    echo "后续请将此tn传给手机开发，由他们用此tn调起控件后完成支付。<br>\n";
    echo "手机端demo默认从仿真获取tn，仿真只返回一个tn，如不想修改手机和后台间的通讯方式，【此页面请修改代码为只输出tn】。<br>\n";
} else {
    //其他应答码做以失败处理
    //TODO
    echo "失败：" . $result_arr["respMsg"] . "。<br>\n";
}


/**
 * 打印请求应答
 *
 * @param
 *        	$url
 * @param
 *        	$req
 * @param
 *        	$resp
 */


