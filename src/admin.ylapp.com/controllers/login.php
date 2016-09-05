<?php
class Login extends MY_Controller
{

    public function __construct(){
        parent::__construct();
        $this->load->model('User_model','user');
    }

    public function index(){
        // 判断当前是否登录
        if($this->currentUser()){redirect('home/index');}

        if($this->input->get_post('doAction') == 'yes'){
            $username = addslashes(trim($this->input->get_post('username')));
            $password = trim($this->input->get_post('password'));
            $rememberPwd = intval($this->input->get_post('remember_pwd'));
            $enPwd = $this->encryption($password);
            $check = $this->checkLogin($username,$enPwd);
            if(!$check){
                echo '密码或者用户名错误';
            }else{
                if($rememberPwd){  // 记住密码
                    setcookie('username',$username,time()+86400*10,'/');
                    setcookie('password',$password,time()+86400*10,'/');
                    setcookie('remember',$rememberPwd,time()+86400*10,'/');
                }
                $_SESSION['userInfo'] = $check;
                redirect('home/index');
            }
        }
        $data['username'] = $_COOKIE['username'];
        $data['password'] = $_COOKIE['password'];
        $data['remember'] = $_COOKIE['remember'];
        $this->load->view('login',$data);
    }



    protected function checkLogin($user,$pwd){
        $return = $this->user->check_user($user,$pwd);
        return $return;
    }
}