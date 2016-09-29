<?php
/**
 * 提醒管理控制器
 * User: momo1a@qq.com
 * Date: 2016/9/29 0029
 * Time: 下午 10:24
 *
 *
 */

class Cash extends MY_Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $limit = 10;
        if(!isset($_GET['state'])){
            $_GET['state'] = -1;
        }
        $keyword = addslashes(trim($this->input->get_post('keyword')));
        $state = intval($_GET['state']);
        $total = $this->comment->commentCount($keyword,$state);
        $offset = intval($this->uri->segment(3));
        $list = $this->comment->commentList($keyword,$state,$limit,$offset);
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['state'] = $this->_state;
        $data['get'] = $_GET;
        $this->load->view('cash/index',$data);
    }
}