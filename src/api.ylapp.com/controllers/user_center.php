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

}