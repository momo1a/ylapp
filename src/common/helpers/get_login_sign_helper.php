<?php 
/**
 * 获取同步登录所需的加密字符串
 * 
 * @author 温守力
 * @version 13.8.5
 * 
 * @return 成功返回认证字符串，失败返回false
 * 
 */
function get_login_sign(){
    $CI = &get_instance();
    $CI->load->helper('get_user');
    $user = get_user();
    if(!$user){
        return FALSE;
    }
    $uid = $user['id'];
    $db = $CI->load->database('uc', TRUE);
    $online_user = $db->select('code')->get_where('onlineusers', array('uid' => $uid), 1)->row_array();
    if(!$online_user){
        return FALSE;
    }
    
    $sign = md5($_SERVER['HTTP_USER_AGENT'] . $CI->input->ip_address() . $online_user['code']);
    return $sign;
}
?>