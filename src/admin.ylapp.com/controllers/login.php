<?php
class Login extends CI_Controller
{

    public function __construct(){
        parent::__construct();
        $this->load->helper(array('get_user','pwd_crypt'));
        $this->load->model('User_model','user');
    }

    public function index(){
        // 判断当前是否登录
        if(get_user()){redirect('home/index');}

        if($this->input->get_post('doAction') == 'yes'){
            $username = addslashes(trim($this->input->get_post('username')));
            $password = trim($this->input->get_post('password'));
            $rememberPwd = intval($this->input->get_post('remember_pwd'));
            $enPwd = pwd_crypt($password);
            $check = $this->checkLogin($username,$enPwd);
            if(!$check){
                $msg =  '密码或用户名错误';
            }else{
                if($rememberPwd){  // 记住密码
                    setcookie('username',$username,time()+86400*10,'/');
                    setcookie('password',$password,time()+86400*10,'/');
                    setcookie('remember',$rememberPwd,time()+86400*10,'/');
                }else {
                    setcookie('username', $username, time() - 1, '/');
                    setcookie('password', $password, time() - 1, '/');
                    setcookie('remember', $rememberPwd, time() - 1, '/');
                }
                $_SESSION['userInfo'] = $check;
                $this->input->get('request_url') ? redirect($this->input->get('request_url')) : redirect(site_url().'home/index');
            }
        }
        $data['username'] = $_COOKIE['username'];
        $data['password'] = $_COOKIE['password'];
        $data['remember'] = $_COOKIE['remember'];
        $data['msg'] = $msg;
        $this->load->view('login',$data);
    }



    protected function checkLogin($user,$pwd){
        $return = $this->user->check_user($user,$pwd);
        return $return;
    }
}