<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 在线问诊控制器
 * User: Administrator
 * Date: 2016/8/9 0009
 * Time: 下午 11:28
 */
class Diagnosis_online extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('User_illness_history_model','illness_history');
        $this->load->model('Doctor_fee_setting_model','fee_setting');
    }

    /**
     * 在线问诊预约
     */
    public function diaSelectTimeLenView(){
        $docId = intval($this->input->get_post('docId'));
        $illness = $this->illness_history->illnessList(self::$currentUid);
        $timeLen = $this->fee_setting->getFeeSettingByUid($docId,'phoneTimeLenFirst,phoneFeeFirst,phoneTimeLenSecond,phoneFeeSecond,phoneTimeLenThird,phoneFeeThird');
        $this->response($this->responseDataFormat(0,'请求成功',array('illness'=>$illness,'timeLen'=>$timeLen)));
    }


    /**
     * 在线问诊支付页面
     */
    public function diaDoPostTempOne(){
        $phoneTimeLen = intval($this->input->get_post('phoneTimeLen')); // 通话时长
        $price = floatval($this->input->get_post('price'));  //价钱
        $ask_sex = intval($this->input->get_post('sex'));  // 性别
        $askNickname = trim(addslashes($this->input->get_post('person'))); // 联系人
        $askTelephone = trim(addslashes($this->input->get_post('telephone'))); // 联系电话
        $hopeCalldate = strtotime($this->input->get_post('hopeCallDate')); // 客户希望沟通的日期
        $askContent = addslashes($this->input->get_post('content'));  // 病情简述、
        $illnessId =  intval($this->input->get_post('illnessId')); // 病历
        $otherIllness = addslashes($this->input->get_post('otherIllness'));  // 其他病史内容

    }
}