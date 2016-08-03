<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'top/TopClient.php';
require_once 'top/request/AlibabaAliqinFcSmsNumSendRequest.php';
class ShortMsg
{
    /**
     * CI Object
     * @var null
     */
    protected $CI = null;

    /**
     * @var null
     */
    protected $TopClient = null;

    /**
     * @var null
     */
    protected $requestObj = null;

    /**
     * @var string
     */
    protected $msgTemplate = '';

    /**
     * 错误消息
     * @var array
     */
    protected $msgCode = array(
        '0' => '发送成功',
        '12'=>'空号',
        '002'=>'已欠费停机',
	    '006'=>'黑名单',
        '-103'=>'黑名单',
        '024'=>'用户关机或无法接通',
        '059'=>'关机',
        '-131'=>'验证码发送频率过高',
        '640'=>'空号',
        '-1005'=>'敏感词拦截',
        'DB:0141'=>'用户处于运营商黑名单',
        'DB:0142'=>'超过日最大发送下发短信数量',
        'mk:0010'=>'空号'
    );


    public function __construct(){
        $this->CI = & get_instance();
        $this->TopClient = new TopClient();
        $this->requestObj = new AlibabaAliqinFcSmsNumSendRequest;
        $this->CI->load->config('short_msg');
        $this->TopClient->appkey = $this->CI->config->item('aldy_appKey') or ajax_error('缺少配置项：aldy_appKey');
        $this->TopClient->secretKey = $this->CI->config->item('aldy_secretKey') or ajax_error('缺少配置项：aldy_secretKey');
        $this->requestObj->setSmsFreeSignName($this->CI->config->item('aldy_signName'));
        $this->msgTemplate = $this->CI->config->item('aldy_msgTemplate');


    }

    /*尊敬的用户，您的验证码为${code}，验证码有效期为 ${length}分钟。*/
    /**
     * 短信发送方法
     * @param $data
     * @param $mobile
     */
    public function send($data,$mobile){
        $this->requestObj->setExtend('123456');
        $this->requestObj->setSmsType("normal");
        $this->requestObj->setSmsParam("{\"code\":\"{$data['code']}\",\"length\":\"{$data['length']}\"}");
        $this->requestObj->setRecNum($mobile);
        $this->requestObj->setSmsTemplateCode($this->msgTemplate);
        $resp = $this->TopClient->execute($this->requestObj);
        return (array)$resp;
    }

}