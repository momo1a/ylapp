<?php
/**
 * 医生管理控制器
 * User: momo1a@qq.com
 * Date: 2016/9/18 0018
 * Time: 下午 8:58
 */

class Doctor extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('User_model','user');
        $this->load->model('Doctor_info_model','doctor');
    }


    public function index(){
        $limit = 10;
        $nickname = trim(addslashes($this->input->get_post('nickname')));
        $phone = trim(addslashes($this->input->get_post('telephone')));
        $total = $this->user->getUserCount($nickname,$phone);
        $offset = intval($this->uri->segment(3));
        $list = $this->user->getUserList($limit,$offset,$nickname,$phone,2,'YL_user.*,YL_money.amount,YL_doctor_info.state as doctorState');
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['get'] = $_GET;
        $this->load->view('doctor/index',$data);
    }

    /**
     * 修改医生信息状态
     */
    public function setDoctorStat(){
        $uid = intval($this->input->get_post('uid'));
        $state = intval($this->input->get_post('state'));
        $res = $this->doctor->setDoctorStat($uid,$state);
        if($res){
            $this->ajax_json(0,'操作成功');
        }else{
            $this->ajax_json(0,'操作失败');
        }
    }

    /**
     * 获取医生详情
     */
    public function getDoctorDetail(){
        $uid = intval($this->input->get_post('uid'));
        $res = $this->doctor->getDoctorDetail($uid);
        if(!empty($res['certificateImg'])){
            $res['certificateImg'] = json_decode($res['certificateImg'],true);
            $res['birthday'] = date('Y-m-d',$res['birthday']);
        }
        $this->ajax_json(0,'请求成功',$res);
    }


}