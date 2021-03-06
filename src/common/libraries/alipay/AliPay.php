<?php
/**
 * 支付宝支付
 * User: Administrator
 * Date: 2016/10/5 0005
 * Time: 下午 9:35
 */

/*require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."lib/alipay_submit.class.php");  //  构造请求类
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."lib/alipay_notify.class.php");  //  通知处理类*/


class AliPayWeb
{
    /**
     * 配置
     * @var null

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

//商户的私钥,此处填写原始私钥去头去尾，RSA公私钥生成：https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.nBDxfy&treeId=58&articleId=103242&docType=1
        $this->_config['private_key']	= 'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAMtKIMiktTFClCknLwvXoS7RPymMzxf1Qq0QeySREXHfLSYfLzlNBie8JONJcbtddNN1z7s275tGqrDS4squs9Nv1SvuKtKnxUFtuDgCJw4qM7MG5GTSUJ1Vay7qBa1mVTHQPiYHR0ibd05iVogcfmdi2lOWRpBZblDRwFoi8W7XAgMBAAECgYB+W/VEwhxeIiQ18EUt9zoY/7di/EM9gRWQvU3NzN4rCa3mpDuWQmoxCKFlJaGr9MtJJVGZ7OvCeIpgnrSZhz3+ctKaeS+F4IlOkV9lsgJFlFnZvC8LYXQSYW+KqgFcTE7ZsfJvZbd43Z7f5ZmQz0V+Y3iOVI8j/l8eiVmgaqhmAQJBAPGrrxQx1DRn0ANa1y/6XmM/2EasT7qEFfQ/go2aQ6YgTlre/XXMhUANCaVs8iKoA/tmJeHkz5wCvkFvVmEuSakCQQDXV9uySwxtPJGf+Ht1N2XU1P9p5GCaUO3OJ5NDm9apNg6wnrKScR6GGaSiaIpBsOXcjQDG5h+S9/WnFwlcWkR/AkBX1WklctLIVS6x+XMaOenSMqMdVIUJqfX8tpRxeK67kyRHPKJsDPAlDlgCKq16UQxZc4+zISEfd5PEXn3LhjI5AkBw14MybI04eLK+pxDanYro+ixVKu1ML/hNPQO4O+NCjCcqeh6NCmW6U5mn2SwJvE7XQbQUheYpt3Gsey/Wix61AkEAlcjwdbAfo4HMqtKxmkdsW7P/r1r5uLs9wO4zItRk38dynC47LjeilSZEp5apN+ZjndYGxGDFiLxyAvTrn4mEmQ==';

//支付宝的公钥，查看地址：https://b.alipay.com/order/pidAndKey.htm
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
        $this->_config['cacert']    = getcwd().'\\cacert.pem';

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
     * 发起请求
     * @param $parameter
     */
    public function submitRequest($parameter){
        $alipaySubmit = new AlipaySubmit($this->_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        echo $html_text;
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

class  AliPay
{

    protected $_config = array();
    /**
     * 初始化
     */
    public function __construct($config){
        $this->_config['service'] = 'mobile.securitypay.pay';
        $this->_config['partner'] = '2088521192348391';
        $this->_config['seller_id'] = 'mingyihui8@163.com';
        $this->_config['_input_charset'] = 'UTF-8';
        $this->_config['payment_type'] = '1';
        $this->_config['it_b_pay'] = '1d';
        $this->_config['notify_url'] = $config['notifyUrl'];
        $this->_config['show_url'] =  $config['returnUrl'];
    }





    /**
     * 对签名字符串转义
     * @param $para
     * @return string
     */
    protected function createLinkstring($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key.'="'.$val.'"&';
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        return $arg;
    }

    /**
     * 签名生成订单信息
     * @param $data
     * @return string
     */
    protected function rsaSign($data) {
        $priKey = "-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQCxp/YT2wJJkhE6KaCofGVXdUmPKNqctjf1K5p8cj0tvCvjk5jM
tl4Ok+EFWJIbtGNVPRhCkEDTiyttnjpZ2efHnlEKEIFOz4MBaYnFQ2av+LUev3We
NvllqPeOIbcwNPIu0JoxWOqcslDJQskZyuEUDD7xgoXogVNVck6QD2axcwIDAQAB
AoGBAKwRBMq4XV/KZHsT5HIrgbASfjt1Ez+jUVFZeRg9QTnxvgR+aQklPLYbzl7y
KRlCPs9JDB00QEVjzp0uGk/6OHzGGGotjaKHbKHnodscr7PVimcGvxwOXotDT6kp
/oWtSDxKFO5+Havik41A31r3Rp316yEJzHMgMJnWYUnkPf55AkEA4HiNlMJVtSBC
sr5LTJRJvts3MoxCeu2K2N4s4cKSnB0I8zeYhl34nGQRToUQ7WtA7SRUGT0F9ddW
SlSxkdkyXwJBAMqcDrYGWam1cjF6aZd36jxkHbGpFHCWw4GbiaWGpp+ziKyPSxav
Asod+KWrrGZp0j+JpE7h1f7PvltPAqxSIW0CQQCbOcJuhJTQVCbLhFx98G2u1dkt
02CFsY66uak/1VWdL1bpGiEXihRJ0clGCy7Rf8G+O8kMSu68OtUpQbgpq5DxAkAn
UKL2JUNO0BupiDRnJOi58T9l0wZZVpf3VWQfT4KTqLXTrjaG30zuRWSTWT2p3czj
cWUFvYLV4B/y/eDp6UH9AkAK4LqtYI2ygI6RtJfaE0QB4bRO5AfLffWSlCmdaJmf
Mgt2xDN/sLgiap+eLHBE8zkdFRKOp5xJbhhyoTyocWn/
-----END RSA PRIVATE KEY-----";

        $res = openssl_get_privatekey($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        $sign = base64_encode($sign);
        $sign = urlencode($sign);
        return $sign;
    }

    /**
     * 生成订单
     * @param $tradeNo  交易号
     * @param $orderTitle
     * @param $amount
     * @param $orderBody
     * @return string
     */

     public function createOrder($tradeNo,$orderTitle,$amount,$orderBody){
         $this->_config['body'] = $orderBody;   //必填商品详情
         $this->_config['subject'] = $orderTitle;   //必填商品名称
         $this->_config['out_trade_no'] = $tradeNo;   //必填交易号
         $this->_config['total_fee'] = $amount;   // 交易额
         $orderInfo = $this->createLinkstring($this->_config);
         $sign = $this->rsaSign($orderInfo);
         $returnStr = $orderInfo.'&sign="'.$sign.'"&sign_type="RSA"';
         return $returnStr;
     }

}