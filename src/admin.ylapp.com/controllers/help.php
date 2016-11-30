<?php
/**
 * 帮助管理控制器
 * User: momo1a@qq.com
 * Date: 2016/11/30
 * Time: 14:19
 */
class Help extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('Common_help_model','help');
    }

    public function index(){
        //$limit = 10;
        //$offset = intval($this->uri->segment(3));
        $pos = array(0=>'全部',1=>'用户端',2=>'医生端');
        $isShow = array('0'=>'隐藏','1'=>'显示');
        $list = $this->help->getAllHelp();
        //$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        //$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        //$pager = $this->pager($total, $limit,$page_conf);
        //$data['pager'] = $pager;
        $data['list'] = $list;
        $data['pos'] = $pos;
        $data['isShow'] = $isShow;
        //$data['get'] = $_GET;
        $this->load->view('help/index',$data);
    }

    public function saveHelp(){
        $id = intval($this->input->get_post('hid'));
        $title = $this->input->get_post('title');
        $description = $this->input->get_post('description');
        $type = intval($this->input->get_post('pos'));
        $isShow = intval($this->input->get_post('is_show'));
        $data = array('title'=>$title,'description'=>$description,'type'=>$type,'isShow'=>$isShow,'dateline'=>time());
        $res = $this->help->save($id,$data);
        if($res) {
            $this->ajax_json(0,'操作成功');
        }else{
            $this->ajax_json(-1,'系统错误');
        }
    }
}