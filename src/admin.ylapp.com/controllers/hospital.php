<?php
/**
 * 医院管理控制器
 * User: momo1a@qq.com
 * Date: 2016/9/23
 * Time: 10:11
 */
class Hospital extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('Hospital_model','hospital');
    }

    public function index(){
        $limit = 10;
        $keyword = addslashes(trim($this->input->get_post('keyword')));
        $total = $this->hospital->getHospitalCount($keyword);
        $offset = intval($this->uri->segment(3));
        $list = $this->hospital->getHospitalList(0,$keyword,'*',$limit,$offset);
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['get'] = $_GET;
        $this->load->view('hospital/index',$data);
    }


    /**
     * 医院详情
     */
    public function getHospitalDetail(){
        $hid = intval($this->input->get_post('hid'));
        $res = $this->hospital->getHospitalDetail($hid);
        if($res) {
            $this->ajax_json(0,'请求成功',$res);
        }else{
            $this->ajax_json(-1,'请求失败');
        }
    }
}