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
        $menus = explode('&menu=',trim($_REQUEST['menu'],'menu='));
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
            $this->ajax_json(0,'操作成功');
        }else{
            $this->ajax_json(-1,'系统错误');
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
     * 获取用户信息
     */
    public function getUserInfoByUid(){
        $uid = intval($this->input->get_post('uid'));
        $userInfo = $this->user->getUserInfoByUid($uid);
        if($userInfo){
            $this->ajax_json(0,'请求成功',$userInfo);
        }else{
            $this->ajax_json(-1,'用户异常');
        }
    }

    /**
     * 修改账户信息
     */
    public function updateUserInfo(){
        $uid = intval($this->input->get_post('uid'));
        $data = array(
            'nickname'=>addslashes(trim($this->input->get_post('username'))),
            'phone'=>trim(addslashes($this->input->get_post('telephone')))
        );
        $password = trim($this->input->get_post('password'));
        if($password != ''){
            $data['password'] = $this->encryption($password);
        }
        $res = $this->user->updateUserInfoByUid($uid,$data);
        if($res){
            $this->ajax_json(0,'操作成功');
        }else{
            $this->ajax_json(-1,'系统错误');
        }
    }

    /**
     * 删除账户
     */
    public function delUser(){
        $uid = intval($this->input->get_post('uid'));
        $res = $this->user->delUserByUid($uid);
        $this->user_menu->deleteUserMenu($uid);
        if($res){
            $this->ajax_json(0,'操作成功');
        }else{
            $this->ajax_json(-1,'系统错误');
        }
    }


    /**
     * 添加账户
     */
    public function addAuth(){
        $auth = $_REQUEST['auth'];
        $this->pass($auth);  // 检测参数
        $menus = explode('&menu=',trim($_REQUEST['menu'],'menu='));
        if($auth['role'] == 1 ){ $role = '管理员';}
        if($auth['role'] == 2 ){ $role = '客服';}
        $data = array(
            'nickname'=>$auth['username'],
            'password'=>$this->encryption($auth['pwd']),
            'phone'=>$auth['phone'],
            'role'=> $role,
            'isBackgroundUser'=>1,
            'dateline'=>time()
            );
        $this->db->trans_begin();
        $uid = $this->user->addAuth($data);
        $str = '';
        if(!empty($menus[0])){
            foreach($menus as $menu){
                $str .= '('.$uid.','.$menu.'),';
            }
            $str = rtrim($str,',');
        }
        if('' != $str){$this->user_menu->setUserMenu($uid,$str);}
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->ajax_json(-1,'系统错误');
        }else{
            $this->db->trans_commit();
            $this->ajax_json(0,'操作成功');

        }


    }

    /**
     * 检测添加账户输入
     * @param $data
     */
    protected function pass($data){
        if(mb_strlen($data['username']) > 15 || mb_strlen($data['username']) < 2){
            $this->ajax_json(1,'姓名为2-15位字符');
        }
        $nameIsExist = $this->user->getRecord('nickname',$data['username']);

        if($nameIsExist){
            $this->ajax_json(1,'姓名已经存在');
        }

        if(!preg_match('/^1(3|4|5|7|8)\d{9}$/',$data['phone'])){
            $this->ajax_json(4,'请输入正确的手机号');
        }

        if(mb_strlen($data['pwd']) < 6){
            $this->ajax_json(2,'密码不能小于6位字符');
        }


        if(is_numeric($data['pwd'])){
            $this->ajax_json(3,'密码不能为纯数字');
        }

        if(intval($data['role']) != 1 && intval($data['role']) != 2){
            $this->ajax_json(5,'身份异常');
        }

    }

}