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
        $userInfo = $this->user->getUserByUid(self::$currentUid,'nickname,phone,(case when sex=1 then "男" when sex=2 then "女" end)as sex');
        $this->response($this->responseDataFormat(0,'请求成功',array('illness'=>$illness,'fee'=>$fee,'address'=>$hospitalAddress,'userInfo'=>$userInfo)));
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
        $userAddr = addslashes(trim($this->input->get_post('userAddr')));
        /****/
        $priceArr = $this->fee_setting->getFeeSettingByUid($docId,'regNumFee');
        $hospitalArr = $this->user->getDoctorDetail($docId,'YL_hospital.address');
        $data = array(
            'userId'=>self::$currentUid,
            'userName'=>$this->user->getUserInfoByUid(self::$currentUid,'nickname'),
            'docId'=>$docId,
            'docName'=>$this->user->getUserInfoByUid($docId,'nickname'),
            'docTel'=>$this->user->getUserInfoByUid($docId,'phone'),
            'price'=>$priceArr[0]['regNumFee'],
            'hosAddr'=>$hospitalArr[0]['address'],
            'contacts'=>$person,
            'appointTime'=>$appointTime,
            'sex'=>$sex,
            'appointBrithday'=>$birthday,
            'userAddr' => $userAddr,
            'appointTel'=>$telephone,
            'illnessId'=>$illId,
            'userRemark'=>$remark,
            'dateline'=>time()
        );
        $res = $this->user_reg_num->firstStep($data);
        $remainAmount = $this->money->getUserMoney(self::$currentUid);
        //log
        $this->userDoctorLogSave(self::$currentUid,$docId,3,0,'用户提交预约挂号');
        $this->response($this->responseDataFormat(0,'请求成功',array('orderId'=>$res,'remainAmount'=>$remainAmount)));
    }

    /**
     * 重新预约界面
     */
    public function reAppointView(){
        $oid = intval($this->input->get_post('appointId'));  // 预约id
        $res = $this->user_reg_num->getDetailById($oid);
        $sex = array('1'=>'男','2'=>'女');
        $illness = $this->illness->illnessList(self::$currentUid,'illId,illName');
        if($res){
            $res['sex'] = $sex[$res['sex']];
            $res['appointBrithday'] = date('Y-m-d H:i:s',$res['appointBrithday']);
        }
        $this->response($this->responseDataFormat(0,'请求成功',array('orderInfo'=>$res,'illnessHistory'=>$illness)));
    }

    // 重新预约提交
    public function reAppointSubmit(){
        $oid = intval($this->input->get_post('appointId'));  // 预约id
        $person = addslashes(trim($this->input->get_post('person')));
        $appointTime = strtotime($this->input->get_post('appointTime'));
        $sex = intval($this->input->get_post('sex'));
        $birthday = strtotime($this->input->get_post('birthday'));
        $telephone = trim($this->input->get_post('telephone'));
        $illId = intval($this->input->get_post('illId'));
        $remark = addslashes($this->input->get_post('remark'));
        $userAddr = addslashes(trim($this->input->get_post('userAddr')));
        $data = array(
            'userName'=>$this->user->getUserInfoByUid(self::$currentUid,'nickname'),
            'contacts'=>$person,
            'appointTime'=>$appointTime,
            'sex'=>$sex,
            'appointBrithday'=>$birthday,
            'userAddr' => $userAddr,
            'appointTel'=>$telephone,
            'illnessId'=>$illId,
            'userRemark'=>$remark,
            'dateline'=>time(),
            'status'=>0
        );

        $res = $this->user_reg_num->reAppoint($oid,$data);

        if($res){
            $this->response($this->responseDataFormat(0,'操作成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'操作失败',array()));
        }
    }


}