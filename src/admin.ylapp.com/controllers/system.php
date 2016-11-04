<?php
/**
 * 系统设置控制器
 * User: momo1a@qq.com
 * Date: 2016/9/29
 * Time: 11:40
 */

class System extends MY_Controller
{

    public function __construct(){
        parent::__construct();
        $this->load->model('System_setting_model','system');
    }

    public function index(){
        //var_dump($_REQUEST);return;
        $telephone = $this->system->getValue('customer_tel');
        $telephone = $telephone ? $telephone['settingValue'] : '';

        $msgPattern = $this->system->getValue('rollmsg_pattern');
        $msgPattern = $msgPattern ? $msgPattern['settingValue'] : '';

        $userManual = $this->system->getValue('user_manual');
        $userManual = $userManual ? $userManual['settingValue'] : '';

        $agree = $this->system->getValue('agree_book');
        $agree = $agree ? $agree['settingValue'] : '';


        // 客服电话设置
        if($this->input->get_post('dosave') == 'telephone'){
            //$postTel = addslashes(trim($this->input->get_post('c-phone')));
            $postTel = addslashes(trim($this->input->get_post('value')));
            $res = $this->system->settingValue('customer_tel',$postTel);
            if($res){
                $this->ajax_json(0,'保存成功');
            }else{
                $this->ajax_json(-1,'设置失败');
            }
        }

        // 滚动消息模式设置
        if($this->input->get_post('dosave') == 'rollmsg'){
            $pattern = addslashes(trim($this->input->get_post('value')));
            $res = $this->system->settingValue('rollmsg_pattern',$pattern);
            if($res){
                $this->ajax_json(0,'保存成功');
            }else{
                $this->ajax_json(-1,'设置失败');
            }
        }


        // 用户手册
        if($this->input->get_post('dosave') == 'user-manual-save'){
            //var_dump($_REQUEST);
            $manual = addslashes(trim($this->input->get_post('value')));
            $res = $this->system->settingValue('user_manual',$manual);
            if($res){
                $this->ajax_json(0,'保存成功');
            }else{
                $this->ajax_json(-1,'设置失败');
            }
        }


        // 用户手册
        if($this->input->get_post('dosave') == 'agree-book-save'){
            //var_dump($_REQUEST);
            $agreeBook = addslashes(trim($this->input->get_post('agree')));
            $res = $this->system->settingValue('agree_book',$agreeBook);
            if($res){
                $this->ajax_json(0,'保存成功');
            }else{
                $this->ajax_json(-1,'设置失败');
            }
        }






        $this->load->view('system/index',get_defined_vars());
    }




}