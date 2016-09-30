<?php
/**
 * 医院控制器
 * User: momo1a@qq.com
 * Date: 2016/8/9
 * Time: 9:18
 */

class Hospital extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('Hospital_model','hospital');
    }

    /**
     * 获取医院列表
     */
    public function getHospitalList(){
        $hid = intval($this->input->get_post('hid'));
        $limit = intval($this->input->get_post('limit'));
        $offset = intval($this->input->get_post('offset'));
        $limit = $limit == 0 ? 10 : $limit;
        $keyword = trim(addslashes($this->input->get_post('keyword')));
        $res = $this->hospital->getHospitalList($hid,$keyword,'hid,img,name AS hospitalName,address',$limit,$offset);
        $this->response($this->responseDataFormat(0,'请求成功',array('hospitals'=>$res,'imgServer'=>$this->getImgServer())));

    }


    public function getAllHospital(){
        $res = $this->hospital->getHospitalList('','','hid,name AS hospitalName');

        $this->response($this->responseDataFormat(0,'请求成功',array($res)));

    }

    public function getAllOffices(){
        $this->load->model('Doctor_offices_model','docoffice');
        $res = $this->docoffice->getAllOffices();
        if($res){
            $this->response($this->responseDataFormat(0,'请求成功',array($res)));
        }else{
            $this->response($this->responseDataFormat(-1,'暂无数据',array()));
        }
    }
}
