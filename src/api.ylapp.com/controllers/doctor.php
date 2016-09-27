<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
        $this->load->model('User_model','user');
        $this->load->model('Doctor_evaluate_model','evaluate');
        $this->load->model('Doctor_reply_model','reply');
    }

    /**
     * 获取医生列表
     */
    public function getDoctorList(){
        $officeId = intval($this->input->get_post('officeId'));
        $limit = intval($this->input->get_post('limit'));
        $offset = intval($this->input->get_post('offset'));
        $limit = $limit == 0 ? 10 : $limit;
        $hid = intval($this->input->get_post('hid'));
        $keyword = addslashes(trim($this->input->get_post('keyword')));
        $this->load->model('user_model','user');
        $res = $this->user->getDoctorList($limit,'YL_user.uid,YL_user.avatar,YL_user.nickname,YL_hospital.name AS hospitalName,YL_doctor_offices.officeName,,YL_doctor_info.docLevel,YL_hospital.address,YL_doctor_info.goodAt',$officeId,$hid,$keyword,$offset);
        $this->response($this->responseDataFormat(0,'请求成功',array('doctors'=>$res,'imgServer'=>$this->getImgServer())));
    }

    /**
     * 获取医生详情
     */

    public function getDoctorDetail(){
        $docId = intval($this->input->get_post('docId'));
        $limit = intval($this->input->get_post('limit'));
        $offset = intval($this->input->get_post('offset'));
        $limit = $limit == 0 ? 10 : $limit;
        $select = 'YL_user.avatar,YL_user.nickname,YL_hospital.name AS hospitalName,YL_doctor_offices.officeName,YL_doctor_info.docLevel,YL_doctor_info.summary,YL_doctor_info.goodAt';
        $doctor = $this->user->getDoctorDetail($docId,$select);
        $evaluate = $this->evaluate->getDoctorEvaluate($docId,'YL_doctor_evaluate.content,FROM_UNIXTIME(YL_doctor_evaluate.dateline) as evaluateTime,YL_doctor_evaluate.username,YL_user.avatar',$limit,$offset);
        $answerNum = $this->reply->getDocCompleteAnswerTotal($docId);
        $this->response($this->responseDataFormat(0,'请求成功',array('doctor'=>$doctor,'evaluate'=>$evaluate,'answerNum'=>$answerNum,'imgServer'=>$this->getImgServer())));
    }

}