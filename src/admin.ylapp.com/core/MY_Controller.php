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
        $this->load->model('User_menu_model','user_menu');
        $this->load->library('Upload_image',null,'upload');
        /* 未登录 */
        if(!get_user()){
            redirect(site_url().'login/index?request_url='.site_url().$this->input->server('REQUEST_URI'));
        }
        /*  是否超级管理员 */
        $supers = config_item('super_admin');
        $currentUser = get_user();
        self::$_is_super = in_array($currentUser[0]['uid'],$supers);
        if(!self::$_is_super){  //不是超级管理员
            $menus = $this->user_menu->get_menu_by_uid($currentUser[0]['uid']);
            $mids = array(0);
            if(!empty($menus)){
                foreach($menus as $key=>$value){
                    array_push($mids,$value['mid']);
                }
            }
            self::$_top_menu = $this->menu->get_menu($mids);
        }else{  // 超级管理员 直接获取所有菜单管理权限
            self::$_top_menu = $this->menu->get_menu();
        }
        $currentController = strtolower($this->router->class);
        $myPrivileges = array();
        if(!empty(self::$_top_menu)){
            foreach(self::$_top_menu as $k=>$v){
                array_push($myPrivileges,strtolower($v['ctrl']));
            }
        }
        if($currentController != 'home'){
            if(!in_array($currentController,$myPrivileges)){
                header('content-type:text/html;charset=utf-8');
                echo '<script>alert("你没有权限操作！");window.location.href="'.site_url().'login"</script>';
                exit;
            }
        }
        $this->load->vars('vars',array(self::$_top_menu ,get_user()));
    }



    /**
     * 分页公共方法
     * @param number $total 总记录数
     * @param number $per_page 每页显示记录数
     */
    protected function pager($total = 0, $per_page = 10, $ext_conf = array()){
        $this->load->library('pagination');
        $uri = http_build_query(array_merge($_GET, $_POST));
        $config['total_rows'] = $total;
        $config['per_page'] = $per_page;
        $config['base_url'] = site_url($this->router->class . '/' . $this->router->method);
        $config['prev_link'] = '上一页';
        $config['next_link'] = '下一页';
        $config['first_link'] = '首页';
        $config['last_link'] = '末页';
        $config['uri_segment'] = 3;
        $config['num_links'] = 3;
        $ext_conf['first_url'] = isset($ext_conf['first_url'])?$ext_conf['first_url']:NULL;
        if($ext_conf['first_url']){
            $config['first_url'] = $ext_conf['first_url'];
            unset($ext_conf['first_url']);
        }else{
            $config['first_url'] = site_url($this->router->class . '/' . $this->router->method . '/0');
        }
        if($uri){
            $config['suffix'] = '?' . $uri;
            $config['first_url'] = $config['first_url'] . '?' . $uri;
        }
        foreach($ext_conf as $k=>$v){
            if(property_exists($this->pagination, $k)){
                $config[$k] = $v;
            }
        }
        if($config['per_page'] == 0 or $config['total_rows'] == 0){
            return '';
        }
        $this->pagination->initialize($config);
        $html = $this->pagination->create_links();
        $total_page = ceil($config['total_rows'] / $config['per_page']);
        $cur_page = $this->pagination->cur_page;
        $cur_page = $cur_page ? $cur_page : 1;
        $html = '<span style="padding-right:10px;">第'.$cur_page.'页/共'.$total_page.'页</span>'.$html;
        if($total_page>1){
            $html .= '<a '.$this->pagination->anchor_class.'href="'.$this->pagination->base_url.$this->pagination->prefix.'{offset}'.$this->pagination->suffix.'" style="display:none;">go</a>';
            $html .= '<form onsubmit="(function(f){';
            $html .= "var per_page = {$config['per_page']};";
            $html .= 'var page = $(f).find(\'input.gopage\').val();';
            $html .= 'if(page>=1 && page<='.$total_page.' && page!='.$cur_page.'){}else{return false;}';	//判断输入的页数是否合理
            $html .= "var a = $(f).prev('a'); var gohref = a.attr('href').replace('{offset}', page*per_page-per_page);";
            $html .= "if(a.attr('rel') && a.attr('type')=='load'){a.attr('href', gohref).trigger('click');}else{location.href=gohref}";
            $html .= '})(this);return false;" style="display:inline;">';
            $html .= ' 到第 <input type="text" size="3" class="gopage" style="padding:2px 1px;" /> 页';
            $html .= ' <input type="submit" value="确定" class="ui-form-btnSearch" />';
            $html .= '</form>';
        }
        return $html;
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
     * 加密函数
     * @param $string
     */
    protected function encryption($string){
        return md5(sha1($string));
    }


}
// End of MY_Controller class

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */