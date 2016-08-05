<?php

require COMPATH."core/YL_Controller.php";

class MY_Controller extends CI_Controller
{

    /**
     * 用户类型
     * @var int
     */
    protected static $_TYPE_USER = 1;

    /**
     * 医生类型
     * @var int
     */
    protected static $_TYPE_DOCTOR = 2;

    /**
     * 接收客户端发送的私有token
     * @var null
     */
    protected static $privateToken = null;

	function __construct(){
		parent::__construct();
        if(strtolower($this->router->class) != 'test'){
            $this->checkToken();  //检测通讯token
        }
        $this->load->library('Crypt',array('key'=>KEY_APP_SERVER,'iv'=>'0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF'),'crypt');    // 加密类库
        $this->load->library("ShortMsg/ShortMsg",null,'sms');  // 加载短信类库
        $this->load->library('Cache_memcached',null,'cache');  // 加载缓存类库
        self::$privateToken = trim($this->input->post('privateToken'));

	}
	/**
	 * 处理成功返回json数据
	 * @param int $code:z状态编码
	 * @param string $msg:描述
	 * @param string $data:数据
	 * @author djj
	 * @version 2014-6-19
	 */
	protected function go_back($code , $msg = '', $data = NULL, $output = TRUE) {
		$code = intval($code);
		$ret = array (
				'code' => $code,
				'msg' => $msg
		);
		$data !== NULL && ($ret ['data'] = $data);
		$json_str = json_encode ( $ret);
	
		if ($output) {
			// 输出后停止程序
			die ( $json_str );
		}else{
			// 返回json字符串
			return $json_str;
		}
	}

    /**
     * @param array $content
     *         code   响应码
     *         msg    消息描述
     *         data   数据
     * @param string $content_type 响应头
     */
    protected function response($content = array('code'=>1002,'msg'=>'ERR_PARAMETER','data'=>array()), $content_type = 'text/html;charset=utf-8'){
        $content_type = trim($content_type) != '' ? trim($content_type) : 'text/html;charset=utf-8';
        header('Content-Type: '.$content_type);
        exit(json_encode($content));
    }

    /**
     * 检查通讯token
     */
    protected function checkToken(){
        $token = trim($this->input->get_post('token'));
        if($token != strtoupper(md5(KEY_APP_SERVER))){
            exit(json_encode(array('code'=>1001,'msg'=>'ERR_TOKEN_DIFFER')));  //通信TOKEN不一致
        }
    }

    /**
     * 加密函数
     * @param $string
     */
    protected function encryption($string){
        return md5(sha1($string));
    }

    /**
     * @param $code
     * @param $msg
     * @param $data
     */
    protected function responseDataFormat($code,$msg,$data){
        $responseData['code'] = $code;
        $responseData['msg'] = $msg;
        $responseData['data'] = $data;
        return $responseData;
    }


    protected function getRemoteAddr(){
        return ip2long($this->input->server('REMOTE_ADDR'));
    }
}