<?php
/**
 * 支付宝支付
 * User: Administrator
 * Date: 2016/10/5 0005
 * Time: 下午 9:35
 */

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."lib/alipay_submit.class.php");  //  构造请求类
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."lib/alipay_notify.class.php");  //  通知处理类


class AliPay
{
    /**
     * 配置
     * @var null
     */
    protected $_config = null;


    /**
     * 配置
     */
    public function __construct($config){
        //↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://openhome.this->.com/platform/keyManage.htm?keyType=partner
        $this->_config['partner']		= '2088311771079114';

//收款支付宝账号，以2088开头由16位纯数字组成的字符串，一般情况下收款账号就是签约账号
        $this->_config['seller_id']	= $this->_config['partner'];

//商户的私钥,此处填写原始私钥去头去尾，RSA公私钥生成：https://doc.open.this->.com/doc2/detail.htm?spm=a219a.7629140.0.0.nBDxfy&treeId=58&articleId=103242&docType=1
        $this->_config['private_key']	= 'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAMtKIMiktTFClCknLwvXoS7RPymMzxf1Qq0QeySREXHfLSYfLzlNBie8JONJcbtddNN1z7s275tGqrDS4squs9Nv1SvuKtKnxUFtuDgCJw4qM7MG5GTSUJ1Vay7qBa1mVTHQPiYHR0ibd05iVogcfmdi2lOWRpBZblDRwFoi8W7XAgMBAAECgYB+W/VEwhxeIiQ18EUt9zoY/7di/EM9gRWQvU3NzN4rCa3mpDuWQmoxCKFlJaGr9MtJJVGZ7OvCeIpgnrSZhz3+ctKaeS+F4IlOkV9lsgJFlFnZvC8LYXQSYW+KqgFcTE7ZsfJvZbd43Z7f5ZmQz0V+Y3iOVI8j/l8eiVmgaqhmAQJBAPGrrxQx1DRn0ANa1y/6XmM/2EasT7qEFfQ/go2aQ6YgTlre/XXMhUANCaVs8iKoA/tmJeHkz5wCvkFvVmEuSakCQQDXV9uySwxtPJGf+Ht1N2XU1P9p5GCaUO3OJ5NDm9apNg6wnrKScR6GGaSiaIpBsOXcjQDG5h+S9/WnFwlcWkR/AkBX1WklctLIVS6x+XMaOenSMqMdVIUJqfX8tpRxeK67kyRHPKJsDPAlDlgCKq16UQxZc4+zISEfd5PEXn3LhjI5AkBw14MybI04eLK+pxDanYro+ixVKu1ML/hNPQO4O+NCjCcqeh6NCmW6U5mn2SwJvE7XQbQUheYpt3Gsey/Wix61AkEAlcjwdbAfo4HMqtKxmkdsW7P/r1r5uLs9wO4zItRk38dynC47LjeilSZEp5apN+ZjndYGxGDFiLxyAvTrn4mEmQ==';

//支付宝的公钥，查看地址：https://b.this->.com/order/pidAndKey.htm
        $this->_config['alipay_public_key']= 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRAFljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQEB/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5KsiNG9zpgmLCUYuLkxpLQIDAQAB';

// 服务器异步通知页面路径  需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
        $this->_config['notify_url'] = $config['notifyUrl'];

// 页面跳转同步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
        $this->_config['return_url'] = $config['returnUrl'];

//签名方式
        $this->_config['sign_type']    = strtoupper('RSA');

//字符编码格式 目前支持 gbk 或 utf-8
        $this->_config['input_charset']= strtolower('utf-8');

//ca证书路径地址，用于curl中ssl校验
//请保证cacert.pem文件在当前文件夹目录中
        $this->_config['cacert']    = dirname(__FILE__).'\\cacert.pem';

//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        $this->_config['transport']    = 'http';

// 支付类型 ，无需修改
        $this->_config['payment_type'] = "1";

// 产品类型，无需修改
        $this->_config['service'] = "create_direct_pay_by_user";

//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


//↓↓↓↓↓↓↓↓↓↓ 请在这里配置防钓鱼信息，如果没开通防钓鱼功能，为空即可 ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓

// 防钓鱼时间戳  若要使用请调用类文件submit中的query_timestamp函数
        $this->_config['anti_phishing_key'] = "";

// 客户端的IP地址 非局域网的外网IP地址，如：221.0.0.1
        $this->_config['exter_invoke_ip'] = "";

//↑↑↑↑↑↑↑↑↑↑请在这里配置防钓鱼信息，如果没开通防钓鱼功能，为空即可 ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
        //var_dump($this->_config);exit;

    }

    /**
     * @param $out_trade_no  //商户订单号，商户网站订单系统中唯一订单号，必填
     * @param $subject  //订单名称，必填
     * @param $total_fee  //付款金额，必填
     * @param $body  //商品描述，可空
     */
    public function submitPay($out_trade_no,$subject,$total_fee,$body){
        //构造要请求的参数数组，无需改动
        header("content-type:text/html;charset=utf-8");
        $parameter = array(
            "service"       => $this->_config['service'],
            "partner"       => $this->_config['partner'],
            "seller_id"  => $this->_config['seller_id'],
            "payment_type"	=> $this->_config['payment_type'],
            "notify_url"	=> $this->_config['notify_url'],
            "return_url"	=> $this->_config['return_url'],
            "anti_phishing_key"=>$this->_config['anti_phishing_key'],
            "exter_invoke_ip"=>$this->_config['exter_invoke_ip'],
            "out_trade_no"	=> $out_trade_no,
            "subject"	=> $subject,
            "total_fee"	=> $total_fee,
            "body"	=> $body,
            "_input_charset"	=> trim(strtolower($this->_config['input_charset']))
            //其他业务参数根据在线开发文档，添加参数.文档地址:https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.kiX33I&treeId=62&articleId=103740&docType=1
            //如"参数名"=>"参数值"
        );


        $alipaySubmit = new AlipaySubmit($this->_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        echo $html_text;
        /*$return = $alipaySubmit->buildRequestPara($parameter);  // 返回参数给客户端请求支付
        return $return;*/
    }

    /**
     *
     * @param string $type 默认通知验签
     * @return 验证结果
     */
    public function notify($type='notice')
    {

        $alipayNotify = new AlipayNotify($this->_config);
        //计算得出通知验证结果
        switch($type){
            case 'notice':
                $verify_result = $alipayNotify->verifyNotify();
                break;
            case 'return':
                $verify_result = $alipayNotify->verifyReturn();
        }



        return $verify_result;
    }

}