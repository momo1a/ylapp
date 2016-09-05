<?php
class Login extends MY_Controller
{

    public function __construct(){
        parent::__construct();
        $this->load->model('User_model','user');
    }

    public function index(){
        $this->load->view('login');
    }


    public function doAction(){
        $username = addslashes(trim($this->input->get_post('username')));
        $password = trim($this->input->get_post('password'));
        $rememberPwd = intval($this->input->get_post('remember_pwd'));
        $enPwd = $this->encryption($password);
        $check = $this->checkLogin($username,$enPwd);
        if(!$check){
            echo '密码或者用户名错误';
        }else{
            if($rememberPwd){  // 记住密码
                set_cookie('login', $_REQUEST);
            }
            redirect('home/index');
        }

    }


    protected function checkLogin($user,$pwd){
        $return = $this->user->check_user($user,$pwd);
        return $return;
    }
}