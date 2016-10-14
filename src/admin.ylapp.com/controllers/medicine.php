<?php
/**
 * 药品管理控制器
 * User: momo1a@qq.com
 * Date: 2016/10/12 0012
 * Time: 下午 8:32
 */

class Medicine extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('Medicine_model','medi');
    }


    public function index(){
        $limit = 10;
        $cate = intval($this->input->get_post('cate'));
        $total = $this->medi->mediCount($cate);
        $offset = intval($this->uri->segment(3));
        $list = $this->medi->mediList($limit,$offset,'*',$cate);
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['get'] = $_GET;
        $this->load->view('gene/index',$data);
    }


}