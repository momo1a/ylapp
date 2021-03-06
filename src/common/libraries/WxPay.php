<?php

/**
 * 微信app支付调用接口类
 * Class WxPay
 */

class WxPay{

    /**
     * 配置
     * @var array|null
     */
    protected $config = null;


    /**
     * 统一下单接口地址
     * @var null|string
     */
    protected $apiUrl = null;


    /**
     * 构造函数 初始化
     */

    public function  __construct() {
        $this->config = array(
            //'appid' => "wx1a13456d65204e33",    /*微信开放平台上的应用id*/
            //'api_key' => "68f96eefa7b34346670149f370c7af5a",    /*在微信商户平台上自己设定的api密钥 32位*/
            //'mch_id' => "1266284201",   /*微信申请成功之后邮件中的商户id*/
            'appid' => "wxda3086c3063a6d9b",    /*微信开放平台上的应用id*/
            'mch_id' => "1410453702",   /*微信申请成功之后邮件中的商户id*/
            'api_key' => "1234567890qwertyuiop1234567890QW",    /*在微信商户平台上自己设定的api密钥 32位*/
        );

        $this->apiUrl = "https://api.mch.weixin.qq.com/pay/unifiedorder";


    }

    /**
     * 获取预处理订单
     * @param $body   商品描述
     * @param $out_trade_no  交易号（本系统自动生成唯一码）
     * @param $total_fee  (交易额 单位：分)
     * @return array|string
     */
    public function getPrePayOrder($body, $out_trade_no, $total_fee,$attach,$notify_url){
        $url = $this->apiUrl;
        $onoce_str = $this->getRandChar(32);
        $data["appid"] = $this->config["appid"];
        $data["body"] = $body;
        $data["mch_id"] = $this->config['mch_id'];
        $data["nonce_str"] = $onoce_str;
        $data["notify_url"] = $notify_url;
        $data["out_trade_no"] = $out_trade_no;
        $data["spbill_create_ip"] = $this->get_client_ip();
        $data["total_fee"] = $total_fee;
        $data["trade_type"] = "APP";
        $data['attach'] = $attach;  //  支付成功 通知原样返回
        $s = $this->getSign($data, false);
        $data["sign"] = $s;
        $xml = $this->arrayToXml($data);
        $response = $this->postXmlCurl($xml, $url);
        //将微信返回的结果xml转成数组
        return $this->xmlstr_to_array($response);
    }

    /**
     * 签名返回给客户端发起支付请求
     * @param $prepayId (预处理订单中返回中获得)
     * @return mixed
     */
    public function getOrder($prepayId){
        $data["appid"] = $this->config["appid"];
        $data["noncestr"] = $this->getRandChar(32);;
        $data["package"] = "Sign=WXPay";
        $data["partnerid"] = $this->config['mch_id'];
        $data["prepayid"] = $prepayId;
        $data["timestamp"] = time();
        $s = $this->getSign($data, false);
        $data["sign"] = $s;
        return $data;
    }

    /**
     * 生成签名
     * @param $Obj
     * @return string
     */
    protected function getSign($Obj){
        foreach ($Obj as $k => $v) {
            $Parameters[strtolower($k)] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //echo "【string】 =".$String."</br>";
        //签名步骤二：在string后加入KEY
        $String = $String."&key=".$this->config['api_key'];
        //echo "<textarea style='width: 50%; height: 150px;'>$String</textarea> <br />";
        //签名步骤三：MD5加密
        $result_ = strtoupper(md5($String));
        return $result_;
    }

    /**
     * 获取指定长度的随机字符串
     * @param $length
     * @return null|string
     */
    protected function getRandChar($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }

    /**
     * 数组转xml
     * @param $arr
     * @return string
     */
    protected function arrayToXml($arr){
        $xml = "<xml>";
        foreach ($arr as $key=>$val) {
            if (is_numeric($val)) {
                $xml.="<".$key.">".$val."</".$key.">";

            }
            else
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * post https请求，CURLOPT_POSTFIELDS xml格式
     * @param $xml
     * @param $url
     * @param int $second
     * @return bool|mixed
     */
    protected function postXmlCurl($xml,$url,$second=30)
    {
        //初始化curl
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data)
        {
            curl_close($ch);
            return $data;
        }
        else
        {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error"."<br>";
            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
            curl_close($ch);
            return false;
        }
    }

    /**
     * 获取当前服务器的IP
     * @return string
     */
    protected function get_client_ip()
    {
        if ($_SERVER['REMOTE_ADDR']) {
            $cip = $_SERVER['REMOTE_ADDR'];
        } elseif (getenv("REMOTE_ADDR")) {
            $cip = getenv("REMOTE_ADDR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $cip = getenv("HTTP_CLIENT_IP");
        } else {
            $cip = "unknown";
        }
        return $cip;
    }

    /**
     * 将数组转成uri字符串
     * @param $paraMap
     * @param $urlencode
     * @return string
     */
    protected function formatBizQueryParaMap($paraMap, $urlencode){
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
            if($urlencode)
            {
                $v = urlencode($v);
            }
            $buff .= strtolower($k) . "=" . $v . "&";
        }
        $reqPar = '';
        if (strlen($buff) > 0)
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }

    /**
     * xml转成数组
     * @param $xmlstr
     * @return array|string
     */
    function xmlstr_to_array($xmlstr) {
        $doc = new DOMDocument();
        $doc->loadXML($xmlstr);
        return $this->domnode_to_array($doc->documentElement);
    }
    function domnode_to_array($node) {
        $output = array();
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = $this->domnode_to_array($child);
                    if(isset($child->tagName)) {
                        $t = $child->tagName;
                        if(!isset($output[$t])) {
                            $output[$t] = array();
                        }
                        $output[$t][] = $v;
                    }
                    elseif($v) {
                        $output = (string) $v;
                    }
                }
                if(is_array($output)) {
                    if($node->attributes->length) {
                        $a = array();
                        foreach($node->attributes as $attrName => $attrNode) {
                            $a[$attrName] = (string) $attrNode->value;
                        }
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if(is_array($v) && count($v)==1 && $t!='@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }
                break;
        }
        return $output;
    }
}