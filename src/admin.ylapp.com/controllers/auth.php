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
        $this->load->model('User_model','user');
    }

    public function index(){
        $limit = 10;
        $offset = intval($this->uri->segment(3));
        $total = $this->user->AuthListCount();
        $list = $this->user->getAuthList($limit,$offset);
        //$page_conf = array('uri_segment'=>3,'anchor_class'=>'type="load" rel="div#main-wrap"');
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $this->load->view('auth/index',$data);
    }
}