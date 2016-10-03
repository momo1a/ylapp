<?php
/**
 * 留言问诊管理控制器
 * User: Administrator
 * Date: 2016/10/3 0003
 * Time: 下午 3:51
 */

class LeavMsg extends MY_Controller
{
    public function __construct(){
        parent::__construct();

    }

    public function index(){
        $this->load->view('leaving/index');
    }
}