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
     * 登录接口
     * @param bool $isThirdPart  默认非第三方平台登录
     */
    public function login($isThirdPart = false,$data = array()){
        if(!$isThirdPart) {
            $this->load->model('user_model', 'user');
            $user = addslashes(trim($this->input->post('user')));
            $pwd = $this->input->post('pwd');
            $userType = intval($this->input->get_post('userType'));  // 添加用户类型参数传递
            if (empty($user)) {
                $this->response($this->responseDataFormat(4, '账号不能为空', array())); //用户状态异常
            }
            $res = $this->user->getUserCondition(array('phone' => $user, 'userType' => $userType));
            if (!$res) {
                $this->response($this->responseDataFormat(1, '用户不存在', array())); //用户用户不存在
            }
            if ($this->encryption($pwd) != $res['password']) {
                $this->response($this->responseDataFormat(2, '密码不正确', array())); //用户密码不正确
            }

            if ($res['isBlack'] != 0 || $res['status'] != 0) {
                $this->response($this->responseDataFormat(3, '用户状态异常', array())); //用户状态异常
            }

        }else{
            $res = $data;
            if ($res['isBlack'] != 0 || $res['status'] != 0) {
                $this->response($this->responseDataFormat(3, '用户状态异常', array())); //用户状态异常
            }

        }
        /*  检测通过 */
        $privateToken = $this->crypt->encode($res['uid'].'-'.$user.'-'.time().'-'.$res['userType']);  //私有token
        $this->cache->save(md5($privateToken),$privateToken);
        if($this->user->updateLoginInfo($res['uid'])) {
            $this->response($this->responseDataFormat(0, '登录成功', array('privateToken'=>$privateToken))); //登陆成功
        }else{
            $this->response($this->responseDataFormat(-1, '登录失败', array())); //登陆失败
        }
        

    }

    /**
     * 检测绑定接口
     */
    public function checkBind(){
        $bindType = intval($this->input->get_post('bindType'));  // 绑定类型 1 微信 2 。。
        switch($bindType){
            case 1:  //  检查微信绑定
                $openId = trim($this->input->get_post('openId'));  // 微信唯一标识
                if(empty($openId)){
                    $this->response($this->responseDataFormat(-1, 'openId不能为空', array())); //未知绑定类型
                }
                $isBind = $this->user->getRecord('wechatOpenid',$openId);   // 判断是否绑定 绑定直接跳到登录接口
                if($isBind){  // 已经绑定 直接返回登录信息
                    $this->login(true,$isBind);
                }else{
                    $this->response($this->responseDataFormat(5, '微信账号没有绑定', array())); //登陆失败
                }
                break;
            case 2:  // 检查qq绑定
                $openId = trim($this->input->get_post('openId'));  // qq唯一标识
                if(empty($openId)){
                    $this->response($this->responseDataFormat(-1, 'openId不能为空', array())); //未知绑定类型
                }
                $isBind = $this->user->getRecord('QQOpenid',$openId);   // 判断是否qq 绑定直接跳到登录接口
                if($isBind){  // 已经绑定 直接返回登录信息
                    $this->login(true,$isBind);
                }else{
                    $this->response($this->responseDataFormat(5, '微信账号没有绑定', array())); //登陆失败
                }
                break;
            default:
                $this->response($this->responseDataFormat(-1, '未知绑定类型', array())); //未知绑定类型
        }
    }

    /**
     * 第三方绑定接口
     */
    public function thirdPartBind(){
        $bindType = intval($this->input->get_post('bindType'));  // 绑定类型 1 微信 2 。。
        switch($bindType){
            case 1:  //  微信绑定
                $openId = trim($this->input->get_post('openId'));  // 微信唯一标识
                $user = trim($this->input->get_post('telephone'));  // 用户手机号
                if(empty($openId)){
                    $this->response($this->responseDataFormat(-1, 'openId不能为空', array())); //未知绑定类型
                }
                /* 这里先走发送验证码接口 sendIdentifyCode(mobile=电话号码,flag=1)*/

                $code = $this->input->get_post('code');    //  验证码
                $serverMsgCode = $this->cache->get($user);  //获取存在服务器的验证码
                if($code != $serverMsgCode){
                    $this->response($this->responseDataFormat(1,'验证码不正确或者已经过期',array()));
                }

                /*开始绑定*/

                $res = $this->user->bindThirdPart($openId,$user);
                if(!$res){
                    $this->response($this->responseDataFormat(-1, '系统错误绑定失败', array())); //绑定失败
                }

                $this->login(true,$res);  // 转到登录接口

                break;
            case 2: //  qq登陆
                $openId = trim($this->input->get_post('openId'));  // 微信唯一标识
                $user = trim($this->input->get_post('telephone'));  // 用户手机号
                if(empty($openId)){
                    $this->response($this->responseDataFormat(-1, 'openId不能为空', array())); //未知绑定类型
                }
                /* 这里先走发送验证码接口 sendIdentifyCode(mobile=电话号码,flag=1)*/

                $code = $this->input->get_post('code');    //  验证码
                $serverMsgCode = $this->cache->get($user);  //获取存在服务器的验证码
                if($code != $serverMsgCode){
                    $this->response($this->responseDataFormat(1,'验证码不正确或者已经过期',array()));
                }

                /*开始绑定*/

                $res = $this->user->bindThirdPart($openId,$user,2);
                if(!$res){
                    $this->response($this->responseDataFormat(-1, '系统错误绑定失败', array())); //绑定失败
                }

                $this->login(true,$res);  // 转到登录接口
                break;
            default :
                $this->response($this->responseDataFormat(-1, '未知绑定类型', array())); //登陆失败
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
        if($code != $serverMsgCode || $code == 0){
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
        $this->checkUserLogin();
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
     * 忘记密码 -》 修改密码
     */

    public function reSetForgotPwd(){
        $this->load->model('user_model','user');
        $pwd = trim($this->input->post('pwd'));    // 密码
        $rePwd = trim($this->input->post('rePwd')); // 确认密码
        $phone = trim($this->input->get_post('telephone')); // 手机号码
        if(strlen($pwd) < 6){
            $this->response($this->responseDataFormat(1,'密码不的小于6位',array()));
        }
        if(is_numeric($pwd)){
            $this->response($this->responseDataFormat(2,'密码不得是纯数字',array()));
        }
        if($pwd != $rePwd){
            $this->response($this->responseDataFormat(3,'第一次密码跟第二次密码不一致',array()));
        }

        $return = $this->user->reSettingForgotPwd($phone,$this->encryption($pwd));

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
        $flag = intval($this->input->get_post('flag')); //忘记密码发送验证码判断标识
        if($flag === 1){
            $this->load->model('user_model','user');
            $checkPhone = $this->user->getRecord('phone',$mobile);
            if(!$checkPhone) {
                $this->response($this->responseDataFormat(3, '手机号没有注册', array()));
            }
        }
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


    /**
     * 获取帮助列表
     */
    public function getHelpList(){
        $type = intval($this->input->get_post('type'));
        $this->load->model('Common_help_model','help');
        $res = $this->help->getHelp($type);
        $this->response($this->responseDataFormat(0,'请求成功',$res));
    }

    /**
     * 帮助详情
     */
    public function helpDetail(){
        $id = intval($this->input->get_post('id'));
        $this->load->model('Common_help_model','help');
        $res = $this->help->helpDetail($id);
        $this->response($this->responseDataFormat(0,'请求成功',$res));
    }


    /**
     * 获取客服电话
     */
    public function customerTel(){
        $this->load->model('System_setting_model','system');
        $res = $this->system->getValue('customer_tel');
        $tel = $res ? $res['settingValue'] : '客服电话未设置';
        $this->response($this->responseDataFormat(0,'请求成功',array('tel'=>$tel)));
    }

    /*
     * 404处理
     */
    public function errorPage(){
        exit(json_encode(array('code'=>404,'msg'=>'ERR_INTERFACE_NOT_FOUND')));
    }

}