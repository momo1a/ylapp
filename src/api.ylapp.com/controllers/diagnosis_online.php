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
        $this->checkUserLogin();
        $this->load->model('User_model','user');
        $this->load->model('User_illness_history_model','illness_history');
        $this->load->model('Doctor_fee_setting_model','fee_setting');
        $this->load->model('User_phone_diagnosis_model','online_diagnosis');
        $this->load->model('Money_model','money');
    }

    /**
     * 在线问诊预约
     */
    public function diaSelectTimeLenView(){
        $docId = intval($this->input->get_post('docId'));
        $illness = $this->illness_history->illnessList(self::$currentUid);
        $timeLen = $this->fee_setting->getFeeSettingByUid($docId,'phoneTimeLenFirst,phoneFeeFirst,phoneTimeLenSecond,phoneFeeSecond,phoneTimeLenThird,phoneFeeThird');
        $userInfo = $this->user->getUserByUid(self::$currentUid,'nickname,phone,(case when sex=1 then "男" when sex=2 then "女" end)as sex');
        $this->response($this->responseDataFormat(0,'请求成功',array('illness'=>$illness,'timeLen'=>$timeLen,'userInfo'=>$userInfo)));
    }


    /**
     * 在线问诊支付页面
     */
    public function diaDoPostTempOne(){
        $docId = intval($this->input->get_post('docId'));
        $phoneTimeLen = intval($this->input->get_post('phoneTimeLen')); // 通话时长
        $timeLenKey = trim($this->input->get_post('timeLenKey'));  // 时长key
        $price = floatval($this->input->get_post('price'));  //价钱
        $ask_sex = intval($this->input->get_post('sex'));  // 性别
        $askNickname = trim(addslashes($this->input->get_post('person'))); // 联系人
        $askTelephone = trim(addslashes($this->input->get_post('telephone'))); // 联系电话
        $hopeCalldate = strtotime($this->input->get_post('hopeCallDate')); // 客户希望沟通的日期
        $askContent = addslashes($this->input->get_post('content'));  // 病情简述、
        $illnessId =  intval($this->input->get_post('illnessId')); // 病历
        //$otherIllness = addslashes($this->input->get_post('otherIllness'));  // 其他病史内容
        $data = array(
            'askUid'=>self::$currentUid,
            'illnessId' => $illnessId,
            'askNickname' =>$askNickname,
            'askTelephone' => $askTelephone,
            'ask_sex' => $ask_sex,
            'askContent'=>$askContent,
            //'otherIllness'=>$otherIllness,
            'phoneTimeLen'=>$phoneTimeLen,
            'hopeCalldate'=>$hopeCalldate,
            'price'=>$price,
            'docId'=>$docId,
            'docName'=>$this->user->getUserInfoByUid($docId,'nickname'),
            'docTelephone'=>$this->user->getUserInfoByUid($docId,'phone'),
            'timeLenKey'=>$timeLenKey,
            'askTime'=>time()
        );

        $res = $this->online_diagnosis->commitRecord($data);
        $money = $this->money->getUserMoney(self::$currentUid);
        $money = $money ? $money : 0;
        //log
        $this->userDoctorLogSave(self::$currentUid,$docId,2,0,'用户提交了在线问诊申请');
        $this->response($this->responseDataFormat(0,'请求成功',array('orderId'=>$res,'remainAmount'=>$money)));

    }

    // 重新问诊页面
    public function reDiaView(){
        $oid = intval($this->input->get_post('oid'));
        $res = $this->online_diagnosis->getOrderById($oid);
        if(!$res){
            $this->response($this->responseDataFormat(-1,'订单获取异常',array()));
        }
        $res['hopeCalldate'] = date('Y-m-d H:i:s',$res['hopeCalldate']);
        $illness = $this->illness_history->illnessList(self::$currentUid);
        $timeLen = $this->fee_setting->getFeeSettingByUid($res['docId'],'phoneTimeLenFirst,phoneFeeFirst,phoneTimeLenSecond,phoneFeeSecond,phoneTimeLenThird,phoneFeeThird');
        $this->response($this->responseDataFormat(0,'请求成功',array('illness'=>$illness,'timeLen'=>$timeLen,'orderInfo'=>$res)));

    }

    // 重新问诊提交
    public function reDiaSubmit(){
        $oid = intval($this->input->get_post('oid'));
        $phoneTimeLen = intval($this->input->get_post('phoneTimeLen')); // 通话时长
        $timeLenKey = trim($this->input->get_post('timeLenKey'));  // 时长key
        $price = floatval($this->input->get_post('price'));  //价钱
        $ask_sex = intval($this->input->get_post('sex'));  // 性别
        $askNickname = trim(addslashes($this->input->get_post('person'))); // 联系人
        $askTelephone = trim(addslashes($this->input->get_post('telephone'))); // 联系电话
        $hopeCalldate = strtotime($this->input->get_post('hopeCallDate')); // 客户希望沟通的日期
        $askContent = addslashes($this->input->get_post('content'));  // 病情简述、
        $illnessId =  intval($this->input->get_post('illnessId')); // 病历
        //$otherIllness = addslashes($this->input->get_post('otherIllness'));  // 其他病史内容
        $data = array(
            'illnessId' => $illnessId,
            'askNickname' =>$askNickname,
            'askTelephone' => $askTelephone,
            'ask_sex' => $ask_sex,
            'askContent'=>$askContent,
            //'otherIllness'=>$otherIllness,
            'phoneTimeLen'=>$phoneTimeLen,
            'hopeCalldate'=>$hopeCalldate,
            'price'=>$price,
            'timeLenKey'=>$timeLenKey,
            'askTime'=>time(),
            'state'=>0
        );

        $res = $this->online_diagnosis->reDia($oid,$data);

        if($res){
            $this->response($this->responseDataFormat(0,'操作成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'操作失败',array()));
        }
    }
}