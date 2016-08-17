<?php
/**
 * 关于我们
 * User: Administrator
 * Date: 2016/8/17 0017
 * Time: 下午 8:14
 */

class Us extends MY_Controller
{
    public function __construct(){
        parent::__construct();
    }

    /**
     * 关于我们
     */

    public function aboutUs(){
        $this->load->model('About_us_model','about');
        $this->load->model('Hospital_model','hospital');
        $us = $this->about->getAboutUs();
        $hospital = $this->hospital->getHospitalList(0,'','name as hospitalNames');
        $this->response($this->responseDataFormat(0,'请求成功',array('us'=>$us,'hospital'=>$hospital)));
    }
}