<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 用户个人中心
 * User: momo1a@qq.com
 * Date: 2016/8/15
 * Time: 16:48
 */

class User_center extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->checkUserLogin();   // 检查用户登录
        $this->load->model('User_model','user');
        $this->load->model('Money_model','money');
        $this->load->model('Take_cash_model','cash');
        $this->load->model('Common_trade_log_model','trade_log');
    }


    /**
     * 用户个人中心首页
     */
    public function userCenterIndex(){
        $res = $this->user->getUserByUid(self::$currentUid,'avatar,nickname');
        $this->response($this->responseDataFormat(0,'请求成功',array('userInfo'=>$res,'imgServer'=>$this->getImgServer())));
    }

    /**
     * 详细信息
     */
    public function userCenterDetail(){
        $res = $this->user->getUserByUid(self::$currentUid,'nickname,sex,FROM_UNIXTIME(birthday) AS birthday');
        $this->response($this->responseDataFormat(0,'请求成功',array('userInfo'=>$res)));
    }

    /**
     * 用户详情页提交保存
     */
    public function userDetailSave(){
        $sex = intval($this->input->get_post('sex'));
        if($sex != 1 && $sex != 2){
            $sex = 1;
        }
        $data = array('sex'=>$sex);
        $res = $this->user->saveUserDetail(self::$currentUid,$data);
        if($res){
            $this->response($this->responseDataFormat(0,'保存成功',array()));
        }
    }

    /**
     * 用户上传头像
     */
    public function avatarUpload(){
        $this->load->library('Upload_image',null,'upload');
        if(!empty($_FILES['avatar'])){
            $filePath = $this->upload->save('avatar',$_FILES['avatar']['tmp_name']);
        }
        if($filePath){
            $data = array('avatar'=>$filePath);
            $this->user->saveUserDetail(self::$currentUid,$data);
            $this->response($this->responseDataFormat(0,'上传成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'系统错误',array()));
        }
    }

    /**
     * 修改昵称
     */
    public function updateNickname(){
        $nickname = addslashes(trim($this->input->get_post('nickname')));
        $isExists = $this->user->getRecord('nickname',$nickname);
        if($isExists){
            $this->response($this->responseDataFormat(1,'昵称已经存在',array()));
        }
        $data = array('nickname'=>$nickname);
        $res = $this->user->saveUserDetail(self::$currentUid,$data);
        if($res){
            $this->response($this->responseDataFormat(0,'修改成功',array()));
        }
    }


    /**
     * 修改密码
     */

    public function updatePwd(){
        $oldPwd = $this->encryption(trim($this->input->get_post('oldPwd')));
        $newPwd = trim($this->input->get_post('newPwd'));
        $reNewPwd = trim($this->input->get_post('reNewPwd'));
        $password =$this->user->getUserInfoByUid(self::$currentUid,'password');
        if($oldPwd != $password){
            $this->response($this->responseDataFormat(1,'旧密码不正确',array()));
        }
        if(strlen($newPwd) < 6){
            $this->response($this->responseDataFormat(2,'密码不得小于6位',array()));
        }
        if(is_numeric($newPwd)){
            $this->response($this->responseDataFormat(3,'密码不得是纯数字',array()));
        }
        if($newPwd != $reNewPwd){
            $this->response($this->responseDataFormat(4,'第一次密码跟第二次密码不一致',array()));
        }
        $newPwd = $this->encryption($newPwd);
        if($newPwd == $password){
            $this->response($this->responseDataFormat(5,'新密码和旧密码一样未作修改',array()));
        }
        $data = array('password'=>$newPwd);
        $res = $this->user->saveUserDetail(self::$currentUid,$data);
        if($res){
            $this->response($this->responseDataFormat(0,'修改成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'系统错误',array()));
        }

    }

    /**
     * 我的钱包首页
     */

    public function myMoneyIndex(){
        $money = $this->money->getUserMoney(self::$currentUid);
        $money = $money ? $money : 0;
        $this->response($this->responseDataFormat(0,'请求成功',array($money)));
    }

    /**
     * 充值待开发
     */
    public function recharge(){

    }

    /**
     * 提现页面
     */

    public function takeCashView(){
        $money = $this->money->getUserMoney(self::$currentUid);
        $money = $money ? $money : 0;
        $this->response($this->responseDataFormat(0,'请求成功',array($money)));
    }


    /**
     * 提现提交
     */
    public function takeCash(){
        $bank = addslashes(trim($this->input->get_post('bank')));
        $cardNum = addslashes(trim($this->input->get_post('cardNum')));
        $address = addslashes(trim($this->input->get_post('address')));
        $realName = addslashes(trim($this->input->get_post('realName')));
        $identity = addslashes(trim($this->input->get_post('identity')));
        $amount = floatval($this->input->get_post('amount'));
        $userType  = intval($this->input->get_post('userType'));
        $money = $this->money->getUserMoney(self::$currentUid);
        $money = $money ? $money[0]['amount'] : 0;
        if(!is_numeric($cardNum)){
            $this->response($this->responseDataFormat(1,'请填写正确银行卡号',array()));
        }
        if(!is_numeric($identity) || strlen($identity) != 18){
            $this->response($this->responseDataFormat(2,'请填写正确身份证号',array()));
        }
        if($amount > $money){
            $this->response($this->responseDataFormat(3,'提现金额大于用户余额',array()));
        }
        if($userType != 1 && $userType != 2){
            $this->response($this->responseDataFormat(4,'用户类型异常',array()));
        }
        $data = array(
            'uid'=>self::$currentUid,
            'bank'=>$bank,
            'cardNum'=>$cardNum,
            'address'=>$address,
            'realName'=>$realName,
            'identity'=>$identity,
            'amount'=>$amount,
            'userType'=>$userType,
            'dateline'=>time()
        );
        $tradeData = array(
            'uid'=>self::$currentUid,
            'userType'=>$userType,
            'tradeVolume'=>$amount,
            'tradeDesc'=>'提现',
            'dateline'=>time()
        );
        $this->db->trans_begin();
        $this->cash->addCash($data);
        $this->trade_log->saveLog($tradeData);
        $this->money->updateUserMoney(self::$currentUid,$amount);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->response($this->responseDataFormat(-1,'系统错误',array()));
        } else {
            $this->db->trans_commit();
            $this->response($this->responseDataFormat(0,'提交申请成功',array()));
        }

    }


    /**
     * 交易记录
     */
    public function tradeLog(){
        $res = $this->trade_log->getListByUid(self::$currentUid,'tradeDesc,FROM_UNIXTIME(dateline) AS tradeTime,tradeVolume,tradeType');
        $this->response($this->responseDataFormat(0,'请求成功',array($res)));
    }

}