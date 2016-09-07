<?php
class Home extends MY_Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $data['is_super'] = self::$_is_super;
        $this->load->view('index/index',$data);
    }


    /**
     * 退出登录
     */
    public function logout(){
        unset($_SESSION['userInfo']);
        redirect('login/index');
    }



}