<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * App_Server_API
 * @author momo1a@qq.com
 * @date 20160730
 *
 */
class Api extends MY_Controller
{

    /**
     * @var array  未定义错误
     */
	protected $undefined = array('code'=>1003, 'msg'=>'ERR_UNDEFINED','data'=>array());

    /**
     * 系统错误
     * @var array
     */
    protected $sysErr = array('code'=>1005,'msg'=>'ERR_SYSTEM','data'=>array());

    /**
     * 成功请求
     * @var array
     */
    protected $ok = array('code'=>0,'msg'=>'SUCCESS','data'=>array());




	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->response();
	}

    /**
     * 登陆接口
     */
    public function login(){
        $this->load->model('user_model','user');
        $user = addslashes(trim($this->input->post('user')));
        $pwd = $this->input->post('pwd');
        if(empty($user)){
            $this->response($this->responseDataFormat(4,'账号不能为空',array())); //用户状态异常
        }
        $res = $this->user->getRecord('phone',$user);
        if(!$res){
            $this->response($this->responseDataFormat(1,'用户不存在',array())); //用户用户不存在
        }
        if($this->encryption($pwd) != $res['password']){
            $this->response($this->responseDataFormat(2,'密码不正确',array())); //用户类型不存在
        }

        if($res['isBlack'] != 0 || $res['status'] != 0){
            $this->response($this->responseDataFormat(3,'用户状态异常',array())); //用户状态异常
        }
        /*  检测通过 */
        $privateToken = $this->crypt->encode($res['uid'].'-'.$user.'-'.time().'-'.$res['userType']);  //私有token
        if($this->user->updateLoginInfo($res['uid'])) {
            $this->response($this->responseDataFormat(0, '登陆成功', array('privateToken'=>$privateToken))); //登陆成功
        }else{
            $this->response($this->responseDataFormat(-1, '登陆失败', array())); //登陆失败
        }
        

    }

    /**
     * 注册接口
     */
    public function register(){
        $this->load->model('user_model','user');
        $mobile = trim($this->input->post('mobile'));      // 手机号
        $userType = trim($this->input->post('userType'));  //用户类型 1 用户 2 医生
        $pwd = trim($this->input->post('pwd'));            //密码
        $rePwd = $this->input->post('rePwd');              //确认密码
        $code = trim($this->input->post('code'));          //手机验证码
        $serverMsgCode = $this->cache->get($mobile);       //获取存在服务器的验证码
        if($code != $serverMsgCode){
            $this->response($this->responseDataFormat(1,'验证码不正确或者已经过期',array()));
        }
        $isExist = $this->user->getUserMobile($mobile);    //手机号是否已经注册
        if($userType != 1 && $userType != 2){
            $this->response($this->responseDataFormat(2,'用户类型异常',array())); //用户类型不允许
        }
        if(strlen($pwd) < 6){
            $this->response($this->responseDataFormat(3,'密码不得小于6位',array()));
        }
        if(is_numeric($pwd)){
            $this->response($this->responseDataFormat(4,'密码不得是纯数字',array()));
        }
        if($pwd != $rePwd){
            $this->response($this->responseDataFormat(5,'第一次密码跟第二次密码不一致',array()));
        }
        if($isExist){
            $this->response($this->responseDataFormat(6,'手机号码已经注册',array()));
        }
        /*  检查完毕入库 */

        $return = $this->user->reg($mobile,$userType,$this->encryption($pwd),$this->getRemoteAddr());

        if($return){
            $this->response($this->responseDataFormat(0,'注册成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'系统错误',array()));
        }


    }

    /**
     * 检验验证码接口
     */
    public function checkVerificationCode(){
        $mobile = trim($this->input->post('mobile'));      // 手机号
        $code = trim($this->input->post('code'));          //手机验证码
        $serverMsgCode = $this->cache->get($mobile);       //获取存在服务器的验证码
        if($code != $serverMsgCode){
            $this->response($this->responseDataFormat(1,'验证码不正确或者已经过期',array()));
        }

        $this->response($this->responseDataFormat(0,'验证成功',array()));

    }

    /**
     * 用户密码重置
     */
    public function reSettingPwd(){
        $this->load->model('user_model','user');
        $uid = self::$currentUid;  // 用户id
        $pwd = trim($this->input->post('pwd'));    // 密码
        $rePwd = trim($this->input->post('rePwd')); // 确认密码
        if(strlen($pwd) < 6){
            $this->response($this->responseDataFormat(1,'密码不的小于6位',array()));
        }
        if(is_numeric($pwd)){
            $this->response($this->responseDataFormat(2,'密码不得是纯数字',array()));
        }
        if($pwd != $rePwd){
            $this->response($this->responseDataFormat(3,'第一次密码跟第二次密码不一致',array()));
        }

        $return = $this->user->reSettingPwd($uid,$this->encryption($pwd));

        if($return){
            $this->response($this->responseDataFormat(0,'修改成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'系统错误',array()));
        }
    }

    /**
     * 发送手机验证码接口
     */
    public function sendIdentifyCode(){
        $timeLen = 60;
        $mobile = trim($this->input->post('mobile'));   //手机号
        if(!is_numeric($mobile)){
            $this->response($this->responseDataFormat(2,'手机号码非法',array()));
        }
        $code = rand(100000,999999);
        $res = $this->sms->sendMsg($mobile,$code,$timeLen);
        /*$returnCode = $res['result']->err_code;
        $status  = $res['result']->success;
        $returnCode = (array)$returnCode;
        $status = (array)$status;*/
        if($res == 0) {
            $this->cache->save($mobile, $code, $timeLen);
        }
        $this->response($this->responseDataFormat($res,'请求成功',array()));
    }


    /*
     * 404处理
     */
    public function errorPage(){
        exit(json_encode(array('code'=>404,'msg'=>'ERR_INTERFACE_NOT_FOUND')));
    }

}