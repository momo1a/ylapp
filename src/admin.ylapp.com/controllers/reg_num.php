<?php
/**
 * 预约挂号控制器
 * User: momo1a@qq.com
 * Date: 2016/10/1 0001
 * Time: 下午 8:35
 */

class Reg_num extends MY_Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $this->load->view('reg/index');
    }
}