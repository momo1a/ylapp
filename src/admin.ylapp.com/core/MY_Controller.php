<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
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
        $this->load->helper('get_user');
        if(!get_user()){
            redirect(site_url().'login/index?request_url='.site_url().$this->input->server('REQUEST_URI'));
        }

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


}
// End of MY_Controller class

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */