<?php
/**
 * 疫苗接种控制器
 * User: momo1a@qq.com
 * Date: 2016/9/30 0030
 * Time: 下午 10:17
 */

class Evaluate extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('Doctor_evaluate_model','evaluate');
    }

    public function index(){
        $limit = 10;
        $keyword = addslashes(trim($this->input->get_post('keyword')));
        $state = array('0'=>'待处理','1'=>'通过','2'=>'不通过');
        $total = $this->evaluate->getCount($keyword);
        $offset = intval($this->uri->segment(3));
        if(!empty($keyword)){
            $offset = 0;
        }
        $list = $this->evaluate->getList($keyword,$limit,$offset,'*,d.nickname as docName,u.nickname as userName,YL_doctor_evaluate.dateline as evaluateDate,YL_doctor_evaluate.state as estate');
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['get'] = $_GET;
        $data['state'] = $state;
        $this->load->view('evaluate/index',$data);
    }

    public function checkPass(){
        $state = intval($this->input->get_post('state'));
        $vid = intval($this->input->get_post('vid'));
        $res = $this->evaluate->checkPass($vid,$state);
        if($res){
            $this->ajax_json(0,'设置成功');
        }else{
            $this->ajax_json(-1,'设置失败');
        }
    }
}
