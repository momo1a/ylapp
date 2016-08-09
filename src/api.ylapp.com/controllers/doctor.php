<?php
/**
 * 医生控制器
 * User: momo1a@qq.com
 * Date: 2016/8/9 0009
 * Time: 下午 10:06
 */

class Doctor extends MY_Controller
{
    public function __construct(){

        parent::__construct();
    }

    /**
     * 用户首页获取医生列表
     */
    public function getDoctorList(){
        $officeId = intval($this->input->get_post('officeId'));
        $hid = intval($this->input->get_post('hid'));
        $keyword = addslashes(trim($this->input->get_post('keyword')));
        $this->load->model('user_model','user');
        $res = $this->user->getDoctorList(300,'YL_user.uid,YL_user.avatar,YL_user.nickname,YL_hospital.name AS hospitalName,YL_doctor_offices.officeName,YL_hospital.address,YL_doctor_info.goodAt',$officeId,$hid,$keyword);
        $this->response($this->responseDataFormat(0,'请求成功',$res));
    }

}