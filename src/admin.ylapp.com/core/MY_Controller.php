<?php
if(!defined('BASEPATH'))
	exit('No direct script access allowed');
/**
 * 管理员控制器类
 */
class MY_Controller extends CI_Controller
{

    /**
     * 构造函数
     */
    public function __construct(){
        parent::__construct();
        $this->load->helper('cookie');
    }
    /**
     * 加密函数
     * @param $string
     */
    protected function encryption($string){
        return md5(sha1($string));
    }

    /**
     * 异步请求数据返回
     * @param int $code
     * @param string $msg
     * @param null $data
     */
    protected function ajax_json($code=0,$msg="",$data=null){
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 获取当前用户
     * @return mixed
     */
    protected function currentUser(){
        return $_SESSION['userInfo'];
    }


}
// End of MY_Controller class

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */