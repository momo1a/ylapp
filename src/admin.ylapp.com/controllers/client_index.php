<?php
/**
 * 客户端首页banner控制器
 * User: momo1a@qq.com
 * Date: 2016/9/24 0024
 * Time: 下午 9:23
 */

class Client_index extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('News_model','news');
    }

    /**
     *首页banner管理
     */
    public function index(){
        $list = $this->news->getBanner();
        $userClient = $doctorClient = array();
        if(!empty($list)){
            foreach($list as $key=>$value){
                switch(intval($value['postPos'])){
                    case 1 :
                        array_push($userClient,$value);
                        break;
                    case 2 :
                        array_push($doctorClient,$value);
                        break;
                    default:
                        break;
                }
            }
        }
        $imageServers = config_item('image_servers');
        $this->load->view('clientindex/index',get_defined_vars());
    }

    /**
     * 删除banner
     */
    public function delBanner(){
        $nid = intval($this->input->get_post('nid'));
        $res = $this->news->delBanner($nid);
        if($res){
            $this->ajax_json(0,'操作成功');
        }else{
            $this->ajax_json(-1,'操作失败');
        }
    }
}