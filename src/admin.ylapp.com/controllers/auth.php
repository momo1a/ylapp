<?php
/**
 * 账户管理控制器
 * User: momo1a@qq.com
 * Date: 2016/9/7 0007
 * Time: 下午 8:08
 */

class Auth extends MY_Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function index(){

        $this->load->view('auth/index');
    }
}