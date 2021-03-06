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
            redirect(site_url().'login/index?request_url='.trim(site_url(),'/').$this->input->server('REQUEST_URI'));
        }
        /*  是否超级管理员 */
        $supers = config_item('super_admin');
        $currentUser = get_user();
        self::$_is_super = in_array($currentUser[0]['uid'],$supers);
        if(!self::$_is_super){  //不是超级管理员
            $menus = $this->user_menu->get_menu_by_uid($currentUser[0]['uid']);
            $mids = array();
            if(!empty($menus)){
                foreach($menus as $key=>$value){
                    array_push($mids,$value['mid']);
                }
            }else{
                $mids = array(0);
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
        $this->load->vars('vars',array(self::$_top_menu ,get_user(),$this->msgList()));
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



    /**
     * 数据导出下载
     * @param array $data 数据
     * @param string $title 标题
     * @param string $filename 下载文件名
     */
    protected function data_export($data, $filename)
    {
        $this->load->library('ExportCSV');
        $this->exportcsv->export($data,$filename);
        exit();

        //把数据导出从xls格式修改为csv格式,update by 关小龙  2015-11-30 16:54:20 (备注：可通过注释代码块来切换不同格式的导出)
        /*
        $this->load->library('Excel_Xml', '', 'excel');
        $this->excel->addWorksheet($title, $data);
        // $this->excel->sendWorkbook($filename);
        $this->excel->download($filename);
        exit();
        */
    }


    protected function msgList(){
        $this->load->model('Post_model','post');
        $this->load->model('Post_comment_model','comment');
        $this->load->model('User_leaving_msg_model','leaving');
        $this->load->model('Doctor_info_model','doc_info');
        $this->load->model('User_phone_diagnosis_model','diagnosis');
        $this->load->model('User_reg_num_model','reg');
        $this->load->model('Order_model','order');
        $this->load->model('Take_cash_model','cash');
        $postMsg = $this->post->getNotDeal('*,postTime as dateline,"帖子" as msgType');  //  帖子
        $postCommentMsg = $this->comment->getNotDeal('*,recmdTime as dateline,"评论" as msgType');   //  帖子评论
        $leavingMsg = $this->leaving->getNotDeal('*,askTime as dateline,"留言" as msgType');  //  留言问答
        $diaMsg = $this->diagnosis->getNotDeal('*,askTime as dateline,"电话" as msgType');   //   电话问诊
        $regMsg = $this->reg->getNotDeal('*,"挂号" as msgType');   // 预约挂号
        $docMsg = $this->doc_info->getNotDealDoctor('*,"医生" as msgType');
        $orderMsg = $this->order->getNotDeal('*,"订单" as msgType');   // 订单
        $cashMsg = $this->cash->getNotDeal('*,"提现" as msgType');   // 提现
        $msg = array();
        $i = 0;
        $this->msgContainer($postMsg,$i,$msg);
        $this->msgContainer($postCommentMsg,$i,$msg);
        $this->msgContainer($leavingMsg,$i,$msg);
        $this->msgContainer($diaMsg,$i,$msg);
        $this->msgContainer($regMsg,$i,$msg);
        $this->msgContainer($orderMsg,$i,$msg);
        $this->msgContainer($cashMsg,$i,$msg);
        $this->msgContainer($docMsg,$i,$msg);

        $this->sortArrByField($msg,'dateline',true);
        return array('msg'=>$msg,'count'=>$i);
    }



    /**
     * @param $order
     * @param $i
     * @param $container
     */
    protected function msgContainer($msg,&$i,&$container){
        if(is_array($msg)){
            if(!empty($msg)){
                foreach($msg as $val){
                    array_push($container,$val);
                    $i++;
                }
            }
        }
    }

    /**
     * 多维数组排序
     * @param $array
     * @param $field
     * @param bool $desc
     */
    protected function sortArrByField(&$array, $field, $desc = false){
        $fieldArr = array();
        if(!empty($array)){
            foreach ($array as $k => $v) {
                $fieldArr[$k] = $v[$field];
            }
        }
        $sort = $desc == false ? SORT_ASC : SORT_DESC;
        array_multisort($fieldArr, $sort, $array);
    }


}
// End of MY_Controller class

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */