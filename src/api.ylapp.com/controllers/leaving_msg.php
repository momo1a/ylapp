<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 留言问答控制器
 * User: momo1a@qq.com
 * Date: 2016/8/11
 * Time: 11:09
 */
class Leaving_msg extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->checkUserLogin();
        $this->load->library('Upload_image',null,'upload_image');
        $this->load->model('User_leaving_msg_model','leaving');
        $this->load->model('User_model','user');
        $this->load->model('Doctor_fee_setting_model','fee_setting');
        $this->load->model('User_illness_history_model','illness');
        $this->load->model('Money_model','money');
    }

    /**
     * 留言问答页面
     */
    public function leavingMsgView(){
        $docId = intval($this->input->get_post('docId'));
        $select = 'YL_user.nickname AS docName,YL_doctor_offices.officeName';
        $doctor = $this->user->getDoctorDetail($docId,$select);
        $leavingFee = $this->fee_setting->getFeeSettingByUid($docId,'leavMsgFee');
        $illness = $this->illness->illnessList(self::$currentUid,'illId,illName');
        $this->response($this->responseDataFormat(0,'请求成功',array('doctor'=>$doctor,'leavFee'=>$leavingFee,'illnessHistory'=>$illness)));
    }

    /**
     * 用户提交留言到支付页面
     */
    public function commitStepFrt(){
        $content = addslashes($this->input->get_post('content'));
        $price = floatval($this->input->get_post('price'));
        $docId = intval($this->input->get_post('docId'));
        $illId = intval($this->input->get_post('illId'));
        if(!$docId){$this->response($this->responseDataFormat(1,'请选择医生',array()));}
        if(!$illId){$this->response($this->responseDataFormat(1,'请选择病历',array()));}
        $imgArr = array();
        if(!empty($_FILES)){
            foreach($_FILES as $k=>$val){
                if($val['name'] != '') {
                    $imgFile = $this->upload_image->save('leavingMsg', $val['tmp_name']);
                    $imgArr[$k]=$imgFile;
                }
            }
        }
        $imgArr = !empty($imgArr) ? json_encode($imgArr) : "";
        $data = array(
            'askerUid'=>self::$currentUid,
            'illid' => $illId,
            'askerNickname'=>$this->user->getUserInfoByUid(self::$currentUid,'nickname'),
            'askerPone' => $this->user->getUserInfoByUid(self::$currentUid,'phone'),
            'askerContent'=>$content,
            'price'=>$price,
            'docId'=>$docId,
            'docName'=>$this->user->getUserInfoByUid($docId,'nickname'),
            'img'=>$imgArr,
            'askTime'=>time()
        );

        $res = $this->leaving->commitFirst($data);
        $money = $this->money->getUserMoney(self::$currentUid);
        $money = $money ? $money : 0;
        // log
        $this->userDoctorLogSave(self::$currentUid,$docId,1,0,'用户提交了留言问答');
        if($res) {
            $this->response($this->responseDataFormat(0, '请求成功', array('orderId' => $res, 'remainAmount' => $money)));
        }else{
            $this->response($this->responseDataFormat(-1, '系统错误', array()));
        }
    }
}