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
        $keyword = trim(addslashes($this->input->get_post('keyword')));
        $res = $this->hospital->getHospitalList($hid,$keyword);
        if($res){
            $this->response($this->responseDataFormat(0,'请求成功',array($res)));
        }else{
            $this->response($this->responseDataFormat(-1,'暂无数据',array()));
        }
    }
}
