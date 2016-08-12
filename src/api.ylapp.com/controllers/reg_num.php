<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * 预约挂号控制器
 * User: momo1a@qq.com
 * Date: 2016/8/12
 * Time: 10:39
 */

class Reg_num extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->checkUserLogin();
        $this->load->model('User_reg_num_model','user_reg_num');
        $this->load->model('User_illness_history_model','illness');
        $this->load->model('Doctor_fee_setting_model','fee_setting');
        $this->load->model('User_model','user');
        $this->load->model('Money_model','money');

    }

    /**
     * 用户预约挂号页面
     */
    public function regNumView(){
        $docId = intval($this->input->get_post('docId'));
        $illness = $this->illness->illnessList(self::$currentUid,'illId,illName');
        $fee = $this->fee_setting->getFeeSettingByUid($docId,'regNumFee');
        $hospitalAddress = $this->user->getDoctorDetail($docId,'YL_hospital.address');
        $this->response($this->responseDataFormat(0,'请求成功',array('illness'=>$illness,'fee'=>$fee,'address'=>$hospitalAddress)));
    }

    /**
     * 支付页面
     */
    public function payView(){
        $docId = intval($this->input->get_post('docId'));
        $person = addslashes(trim($this->input->get_post('person')));
        $appointTime = strtotime($this->input->get_post('appointTime'));
        $sex = intval($this->input->get_post('sex'));
        $birthday = strtotime($this->input->get_post('birthday'));
        $telephone = trim($this->input->get_post('telephone'));
        $illId = intval($this->input->get_post('illId'));
        $remark = addslashes($this->input->get_post('remark'));
        /****/
        $priceArr = $this->fee_setting->getFeeSettingByUid($docId,'regNumFee');
        $hospitalArr = $this->user->getDoctorDetail($docId,'YL_hospital.address');
        $data = array(
            'userId'=>self::$currentUid,
            'userName'=>$this->user->getNickname(self::$currentUid,'nickname'),
            'docId'=>$docId,
            'docName'=>$this->user->getNickname($docId,'nickname'),
            'docTel'=>$this->user->getNickname($docId,'phone'),
            'price'=>$priceArr[0]['regNumFee'],
            'hosAddr'=>$hospitalArr[0]['address'],
            'contacts'=>$person,
            'appointTime'=>$appointTime,
            'sex'=>$sex,
            'appointBrithday'=>$birthday,
            'appointTel'=>$telephone,
            'illnessId'=>$illId,
            'userRemark'=>$remark,
            'dateline'=>time()
        );
        $res = $this->user_reg_num->firstStep($data);
        $remainAmount = $this->money->getUserMoney(self::$currentUid);
        $this->response($this->responseDataFormat(0,'请求成功',array('orderId'=>$res,'remainAmount'=>$remainAmount)));
    }


}