<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * App_Server_API
 * @author momo1a@qq.com
 * @date 20160730
 *
 */
class Api extends CI_Controller
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
    protected $ok = array('code'=>1,'msg'=>'SUCCESS','data'=>array());




	public function __construct(){
		parent::__construct();
        if($this->router->method !='test') {
            $this->checkToken();  //检测通讯token
        }
        $this->load->library("ShortMsg/ShortMsg",null,'sms');  // 加载短信类库
        $this->load->library('Cache_memcached',null,'cache');  // 加载缓存类库
	}

	public function index(){
		$this->response();
	}

    /**
     * 注册接口
     */
    public function register(){
        $this->load->model('user_model','user');
        $mobile = trim($this->input->post('mobile'));      // 手机号
        $userType = trim($this->input->post('userType'));  //用户类型 1 用户 2 医生
        $code = trim($this->input->post('code'));          //手机验证码
        $pwd = trim($this->input->post('pwd'));            //密码
        $rePwd = $this->input->post('rePwd');              //确认密码
        $serverMsgCode = $this->cache->get($mobile);       //获取存在服务器的验证码
        $isExist = $this->user->getUserMobile($mobile);    //手机号是否已经注册
        if($userType != 1 && $userType != 2){
            $this->response($this->responseDataFormat(1,'用户类型异常',array())); //用户类型不允许
        }
        if($code != $serverMsgCode){
            $this->response($this->responseDataFormat(2,'验证码不正确或者已经过期',array()));
        }
        if(strlen($pwd) < 6){
            $this->response(3,'密码不的小于6位',array());
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

        $return = $this->user->reg($mobile,$userType,$this->encryption($pwd));
        if($return){
            $this->response($this->responseDataFormat(0,'注册成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'系统错误',array()));
        }


    }


    /**
     * 发送手机验证码接口
     */
    public function sendIdentifyCode(){
        $mobile = trim($this->input->post('mobile'));   //手机号
        if(!is_numeric($mobile)){
            $this->response($this->responseDataFormat(2,'手机号码非法',array()));
        }
        $code = rand(100000,999999);
        $res = $this->sms->send(array('code'=>$code,'length'=>5),$mobile);
        $returnCode = $res['result']->err_code;
        $status  = $res['result']->success;
        $returnCode = (array)$returnCode;
        $status = (array)$status;
        if($returnCode[0] == 0 && $status[0] == 'true') {
            $this->cache->save($mobile, $code, 300);
        }
        $this->response($this->responseDataFormat($returnCode[0],$status[0],array()));
    }

    /**
     * @param array $content
     *         code   响应码
     *         msg    消息描述
     *         data   数据
     * @param string $content_type 响应头
     */
	private function response($content = array('code'=>1002,'msg'=>'ERR_PARAMETER','data'=>array()), $content_type = 'text/html;charset=utf-8'){
		$content_type = trim($content_type) != '' ? trim($content_type) : 'text/html;charset=utf-8';
        header('Content-Type: '.$content_type);
        exit(json_encode($content));
	}

    /**
     * 检查通讯token
     */
    private function checkToken(){
        $token = trim($this->input->post('token'));
        if($token != strtoupper(md5(KEY_APP_SERVER))){
            exit(json_encode(array('code'=>1001,'msg'=>'ERR_TOKEN_DIFFER')));  //通信TOKEN不一致
        }
    }

    /**
     * 加密函数
     * @param $string
     */
    private function encryption($string){
        return md5(sha1($string));
    }

    /**
     * @param $code
     * @param $msg
     * @param $data
     */
    private function responseDataFormat($code,$msg,$data){
        $responseData['code'] = $code;
        $responseData['msg'] = $msg;
        $responseData['data'] = $data;
        return $responseData;
    }

    /*
     * 404处理
     */
    public function errorPage(){
        exit(json_encode(array('code'=>404,'msg'=>'ERR_INTERFACE_NOT_FOUND')));
    }


    /*test*/
    public function test(){

        var_dump($this->cache->get('15977675495'));
        var_dump(strtoupper(md5(111111)));
        $form = <<<HTML
<form action="http://api.ylapp.com/api/register" method="post">
    <input type="text" name="token" value="96E79218965EB72C92A549DD5A330112"/><br/>
   <input type="text" name="mobile" /><br/>
   <input type="text" name="code" /><br/>
   <input type="hidden" name="userType" value="1"/><br/>
   <input type="text" name="pwd" /><br/>
    <input type="text" name="rePwd" /><br/>
    <!--<input name="mobile" type="text"/>-->
    <input type="submit" value="submit"/>

</form>
HTML;
        echo $form;

    }
}