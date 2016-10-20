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
        $telephone = $this->system->getValue('customer_tel');
        $telephone = $telephone ? $telephone['settingValue'] : '';
        $postTel = addslashes(trim($this->input->get_post('c-phone')));
        if($this->input->get_post('dosave') == 'telephone'){
            $postTel = addslashes(trim($this->input->get_post('value')));
            $res = $this->system->settingValue('customer_tel',$postTel);
            if($res){
                $this->ajax_json(0,'保存成功');
            }else{
                $this->ajax_json(-1,'设置失败');
            }
        }
        $this->load->view('system/index',get_defined_vars());
    }




}