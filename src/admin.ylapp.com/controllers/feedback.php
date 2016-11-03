<?php
/**
 * 用户反馈控制器
 * User: momo1a@qq.com
 * Date: 2016/10/10 0010
 * Time: 下午 7:39
 */

class Feedback extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('Feedback_model','feedback');
    }

    public function index(){
        $limit = 10;
        $keyword = addslashes(trim($this->input->get_post('keyword')));
        $total = $this->feedback->feedbackCount($keyword);
        $offset = intval($this->uri->segment(3));
        $list = $this->feedback->getList($keyword,$limit,$offset,'*,YL_feedback.dateline as feedDate');
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['get'] = $_GET;
        $this->load->view('feedback/index',$data);
    }
}