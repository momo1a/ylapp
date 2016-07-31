<?php
/**
 * 试客联盟接口类
 *
 * @author 温守力
 * @version 13.8.29
 */
class Shikee {
    protected $error;

    /**
     * 返回最近操作的错误提示
     */
    public function error() {
        return $this->error;
    }

    /**
     * 初始化试客社区空间
     *
     * @param int uid 用户编号
     * @return 成功与否。错误编码：
     * SHIKEE_API_CONNECT   无法连接试客联盟社区空间
     * SHIKEE_API_FAILURE   初始化试客联盟社区空间失败
     */
    public function init_space($uid) {
        $key = md5('as' . $uid . KEY_SHIKEE);
        $CI = &get_instance();
        $sk_api_url = $CI->config->item('domain_shikee_bbs') . 'api.php?mod=shs&action=as&uid=' . $uid . '&key=' . $key;
        $sk_api_ret = file_get_contents($sk_api_url);
        if (!$sk_api_ret || !json_decode($sk_api_ret, TRUE)) {
           $this->error = array('errcode' => 'SHIKEE_API_CONNECT', 'errtxt' => '初始化试客联盟社区空间失败：'.$sk_api_ret);
           return FALSE;
        }

        $sk_api_ret = json_decode($sk_api_ret, TRUE);
        if ($sk_api_ret['error'] == 0 || $sk_api_ret['error'] == -5) {
            // error=-5表示重复激活
            return TRUE;
        }
        
        $this->error = array('errcode' => 'SHIKEE_API_FAILURE', 'errtxt' => '初始化试客联盟社区空间失败：' . $sk_api_ret['message']);
        return FALSE;
    }

    /**
     * 发放试用通过券
     * @param int uid 用户编号
     * @param int gid 商品编号
     * @param string title 商品标题
     *
     * @return 是否成功，失败编码：
     *           SHIKEE_API_FAILURE 无法获取试用通过券，错误原因：xxx
     *           SHIKEE_API_CONNECT 无法获取试用通过券：通讯错误。
     */
    public function release_pass($uid, $gid, $title) {
        $sk_key = KEY_SHIKEE;
        $orderNumber = str_pad($gid, 15, '0') . str_pad($uid, 15, '0');
        $key = md5($uid . $gid . $title . $orderNumber . $sk_key);
        $param = array('uid' => $uid, 'gid' => $gid, 'title' => $title, 'orderNumber' => $orderNumber, 'key' => $key);

        $CI = &get_instance();
        $domain_sk_pass = $CI->config->item('domain_shikee_pass');
        $api_url = $domain_sk_pass . 'pass/expose_order_return_pass';
        try {
            $ret_code = $this->_post($api_url, $param);
            if (intval($ret_code) === 1){
                return TRUE;
            }
            $error_config = array('-1'=>'众划算活动编号不能小于等于0', '-2' => '该用户不存在', '-3'=>'有效天数必须是1-1000之间', '-4' => '有效发放金只能在0-10000之间', '-5' => '来源长度不可以超过80个字符(一个汉字三个字符)', '-6' => '密钥不相等', '-7' => '用户已经获得通过卷', '-8' => '不存在这个类型的通过卷');
            $error_text = isset($error_config[$ret_code]) ? $error_config[$ret_code] : ('未知编码' . $ret_code);
            $this->error = array('errcode' => 'SHIKEE_API_FAILURE', 'errtxt' => '无法获取试用通过券，错误原因：' . $error_text);
        } catch (Exception $e) {
            $this->error = array('errcode' => 'SHIKEE_API_CONNECT', 'errtxt' => '无法获取试用通过券：通讯错误。','exception'=>$e);
//test begin
			$tmp_file = APPPATH.'./logs/release_pass_'.$gid.'.log';
			$tmp_content = '无法获取试用通过券：通讯错误。'.var_export($e,TRUE);
			file_put_contents($tmp_file, $tmp_content, FILE_APPEND);
//test end

        }
        $ret = FALSE;
    }
    
    /**
     * post 数据
     * @param string $url
     * @param string $post post数组
     * @return string
     */
    private function _post($url, $post = NULL){
		return file_get_contents($url."?".http_build_query($post, '', '&'));
	}
}
?>
