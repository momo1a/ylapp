<?php
class Home extends MY_Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $data['user'] = get_user();
        $data['menu'] = self::$_top_menu;
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