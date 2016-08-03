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
        $this->load->library("ShortMsg/ShortMsg",null,'sms');  // 加载短信类库
        $this->load->library('Cache_memcached',null,'cache');  // 加载缓存类库
	}

	public function index(){
		$this->response();
	}


    public function register(){
        /**
         * 手机号
         * 验证码
         * 密码
         * 第二次密码
         */
        $mobile = $this->input->post('mobile');
        $code = $this->input->post('code');
        $pwd = $this->input->post('pwd');
        $rePwd = $this->input->post('rePwd');

    }


    /**
     * 发送手机验证码
     */
    public function sendIdentifyCode(){
        $mobile = trim($this->input->post('mobile'));   //手机号
        if(!is_numeric($mobile)){
            $this->response(array('code'=>2,'msg'=>'ERR_MOBILE_NUM_NOT_ALLOW','data'=>array()));
        }
        $code = rand(100000,999999);
        $res = $this->sms->send(array('code'=>$code,'length'=>5),$mobile);
        $returnCode = $res['result']->err_code;
        $status  = $res['result']->success;
        if($returnCode == 0 && $status == 'true') {
            $this->cache->save($mobile, $code, 300);
        }
        /*{"code":{"0":"0"},"msg":{"0":"true"},"0":[]}*/
        $this->response(array('code'=>$returnCode,'msg'=>$status,'data'=>array()));
    }

    /**
     * @param array $content
     *         code   响应码
     *         msg    消息描述
     *         data   数据
     * @param string $content_type 响应头
     */
	private function response($content = array('code'=>1002,'msg'=>'ERR_PARAMETER','data'=>array()), $content_type = 'text/html;charset=utf-8')
	{
		$content_type = trim($content_type) != '' ? trim($content_type) : 'text/html;charset=utf-8';
        $token = $this->input->post('token');
        header('Content-Type: '.$content_type);

        if($token != md5(KEY_APP_SERVER)){
            exit(json_encode(array('code'=>1001,'msg'=>'ERR_TOKEN_DIFFER')));  //通信TOKEN不一致
        }
        exit(json_encode($content));

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
        var_dump(md5(111111));
        $form = <<<HTML
<form action="http://api.ylapp.com/api/sendIdentifyCode" method="post">
    <input type="text" name="token"/><br/>
   <!-- <input type="text" name="amaa" /><br/>
    <input type="text" name="bmaa" /><br/>-->
    <input name="mobile" type="text"/>
    <input type="submit" value="submit"/>

</form>
HTML;
        echo $form;

    }
}