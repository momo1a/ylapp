<?php
class Home extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        if(!$this->currentUser()){
            redirect(site_url().'login/index?request_url='.site_url().$this->input->server('REQUEST_URI'));
        }
    }

    public function index(){
        $this->load->view('index/index');
    }


    /**
     * 退出登录
     */
    public function logout(){
        unset($_SESSION['userInfo']);
        redirect('login/index');
    }



}