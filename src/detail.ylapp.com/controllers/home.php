<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * App_Server_API
 * @author momo1a@qq.com
 * @date 20160730
 *
 */
class Home extends MY_Controller
{



	public function __construct(){
		parent::__construct();
        $this->load->model('News_model','news');
	}

    /**
     * 资讯内容页
     * @param $nid
     */
	public function index($nid){
        $nid = intval($nid);
        $data['detail'] = $this->news->getNewsDetail($nid);
		$this->load->view('index',$data);
	}


    public function page404(){
        header('content-type:text/html;charset=utf-8');
        echo '页面被火星人劫持了！！';
    }

}