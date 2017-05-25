<?php
//namespace Qcloud\Sms\Call;

require_once "SmsSender.php";
require_once  "SmsVoiceSender.php";

use Qcloud\Sms\SmsSingleSender;

class SmsSenderCall
{
    protected $CI = null;

    protected $_appid = null;

    protected $_appkey = null;

    /**
     * 构造函数初始化
     */
    public function __construct(){
        $this->CI = & get_instance();
        $this->CI->load->config('msm_tencent');
        $this->_appid = $this->CI->config->item('tencent_appid') or ajax_error('缺少配置项：tencent_appid');
        $this->_appkey = $this->CI->config->item('tencent_appkey') or ajax_error('缺少配置项：tencent_appkey');
    }

    /**
     * 发送验证码
     * @param $mobile
     * @param $code
     * @param $timeLen 分
     * @param $tmpId 短信模板id
     * @param $nationCode 地区号
     */
    public function sendMsg($mobile,$code,$timeLen,$tmpId,$nationCode){
        $sender = new SmsSingleSender($this->_appid,$this->_appkey);
        $param = array($code,$timeLen);
        $result = $sender->sendWithParam($nationCode,$mobile,$tmpId,$param,"", "", "");
        return json_decode($result,true);
    }
}



