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


	public function __construct()
	{
		parent::__construct();
	}

	public function index(){
		$this->response();
	}

    public function getAllUsers(){
        $this->load->model('user_model','users');
        $data = $this->users->getAllUsers();
        if($data){
            $this->ok['data'] = $data;
            $this->response($this->ok);
        }else{
            $this->response($this->sysErr);
        }
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
        $request = $_REQUEST;
        $token = $this->input->post('token');
        unset($request['token']);
        var_dump($request);
        ksort($request,SORT_FLAG_CASE | SORT_STRING );
        var_dump($request);
        $tokenStr = '';
        if(!empty($request)){
            foreach($request as $value){
                $tokenStr .= $value;
            }
        }
        var_dump($tokenStr);
        $authToken = strtoupper(md5($tokenStr.KEY_APP_SERVER));
        header('Content-Type: '.$content_type);

        if($token != $authToken){
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
        var_dump(strtoupper(md5('ab111111')));
        $form = <<<HTML
<form action="http://api.ylapp.com/api/getAllUsers" method="post">
    <input type="text" name="token"/><br/>
    <input type="text" name="amaa" /><br/>
    <input type="text" name="Cmaa" /><br/>
    <input type="submit" value="submit"/>

</form>
HTML;
        echo $form;

    }
}