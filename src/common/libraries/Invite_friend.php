<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 邀请好友类库
 *
 * @author  唐赫
 * @version 2015.01.20
 * @link    http://www.zhonghuasuan.com/
 */
class Invite_friend {

    /**
     * @var string CI变量
     */
    private $CI;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->CI = get_instance();
    }
    /**
     * 记录邀请人的好友信息
     *
     * @param   int     $be_iv_uid      被邀请人用户ID
     * @param   string  $be_iv_uname    被邀请人用户名
     * @return  bool
     */
    public function add($be_iv_uid, $be_iv_uname)
    {
        function_exists('get_cookie') || $this->CI->load->helper('cookie');

        $iv_data = get_cookie('iv_flag'); //获取cookie中的邀请跟踪信息

        if(!$iv_data) return FALSE;

        $this->CI->load->model('invite_link_model');
        //解密并获取邀请连接ID
        $data_arr = invite_link_model::cookie_get_data($iv_data);
        //获取真实邀请连接ID
        $iv_id = (int)Invite_link_model::encrypt($data_arr['id']);
        //对比验证数据库信息
        $rs = $this->CI->invite_link_model->find($iv_id);
        if(!$rs) return FALSE;
        //生成验证key
        $auth_key = Invite_link_model::create_key($rs['ivuid'], $data_arr['id'], $data_arr['cd']);
        if($auth_key !== $rs['key']) return FALSE;

        $this->CI->load->model('invite_user_model');
        //检测是否过被邀请过
        $_tmp_rs = $this->CI->invite_user_model->find_by('beivuid', $be_iv_uid);
        if($_tmp_rs) {
            delete_cookie('iv_flag'); //清除邀请跟踪的cookie信息
            return TRUE;
        }
        //保存邀请好友信息
        $data = array(
            'ivuid'     =>  $rs['ivuid'],
            'ivuname'   =>  $rs['ivuname'],
            'beivuid'   =>  $be_iv_uid,
            'beivuname' =>  $be_iv_uname,
            'reg_time'  =>  time(),
            'expiry_date'  =>  $this->CI->config->item('iv_expiry'),
            'order_sum'  =>  $this->CI->config->item('iv_order_sum'),
            'commission'=>  $this->CI->config->item('iv_commission')
        );
        $insert_id = $this->CI->invite_user_model->insert($data);

        if(! $insert_id) return FALSE;

        //邀请好友LOG记录
        $this->CI->load->model('invite_log_model');
        $log_data = array(
            'ivid'           =>  $insert_id,
            'operate_uid'   =>  $rs['ivuid'],
            'operate_uname' =>  $rs['ivuname'],
            'operation'      =>  '邀请到新会员',
            'dateline'       =>  time(),
            'ip'              =>  bindec(decbin(ip2long($this->CI->input->ip_address())))
        );
        $this->CI->invite_log_model->insert($log_data);

        delete_cookie('iv_flag'); //清除邀请跟踪的cookie信息

        return TRUE;
    }

} //end class