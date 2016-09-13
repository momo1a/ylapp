<?php
/**
 * 账户管理控制器
 * User: momo1a@qq.com
 * Date: 2016/9/7 0007
 * Time: 下午 8:08
 */

class Auth extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model','user');
        $this->load->model('User_menu_model','user_menu');
        $this->load->helper('application');
    }

    public function index(){
        $limit = 10;
        $offset = intval($this->uri->segment(3));
        $total = $this->user->AuthListCount();
        $list = $this->user->getAuthList($limit,$offset);
        //$page_conf = array('uri_segment'=>3,'anchor_class'=>'type="load" rel="div#main-wrap"');
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['menu'] = self::$_top_menu;
        $this->load->view('auth/index',$data);
    }

    /**
     * 给用户设置菜单权限
     */
    public function settingUserPrivileges(){
        $menus = $_REQUEST['menu'];
        $uid = intval($this->input->get_post('uid'));
        if(!empty($menus)){
            $str = '';
            foreach($menus as $menu){
                $str .= '('.$uid.','.$menu.'),';
            }
            $str = rtrim($str,',');
        }
        $res = $this->user_menu->setUserMenu($uid,$str);
        if($res){
            $this->showMsg();
            //redirect('Auth/index');
        }else{
            echo '授权失败';
        }
    }

    /**
     * ajax获取用户菜单
     */
    public function getUserMenuAjax(){
        $uid = intval($this->input->get_post('uid'));
        $userMenu = $this->user_menu->get_menu_by_uid($uid);
        if($userMenu) {
            exit(json_encode($userMenu));
        }else{
            exit(json_encode(array()));
        }
    }


    /**
     * 给用户授权
     */

}