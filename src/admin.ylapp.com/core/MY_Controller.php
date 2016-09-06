<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 管理员控制器类
 */
class MY_Controller extends CI_Controller
{

    /**
     * 一级菜单
     * @var null
     */

    protected static $_top_menu = null;

    /**
     * 是否超级管理员
     * @var bool
     */
    protected static $_is_super = false;


    /**
     * 构造函数
     */
    public function __construct(){
        parent::__construct();
        $this->load->helper('get_user');
        $this->load->model('Menu_model','menu');
        /* 未登录 */
        if(!get_user()){
            redirect(site_url().'login/index?request_url='.site_url().$this->input->server('REQUEST_URI'));
        }
        /*  是否超级管理员 */
        $supers = config_item('super_admin');
        $currentUser = get_user();
        self::$_is_super = in_array($currentUser[0]['uid'],$supers);
        if(!self::$_is_super){  //不是超级管理员
            // todo
        }else{  // 超级管理员 直接获取所有菜单管理权限
            self::$_top_menu = $this->menu->get_menu();
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