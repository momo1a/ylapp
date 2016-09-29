<?php
/**
 * 交流圈控制器
 * User: momo1a@qq.com
 * Date: 2016/9/29
 * Time: 11:40
 */

class Post extends MY_Controller
{
    /**
     * 状态
     * @var null
     */
    protected $_state = null;



    public function __construct(){
        parent::__construct();
        $this->load->model('Post_model','post');
        $this->_state = array(
            -1 => '全部',
            0 => '待审核',
            1 => '通过',
            2 => '未通过'
        );
    }

    public function index(){
        $limit = 10;
        if(!isset($_GET['state'])){
            $_GET['state'] = -1;
        }
        $keyword = addslashes(trim($this->input->get_post('keyword')));
        $state = intval($_GET['state']);
        $total = $this->post->postCount($keyword,$state);
        $offset = intval($this->uri->segment(3));
        $list = $this->post->postList($keyword,$state,$limit,$offset);
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['state'] = $this->_state;
        $data['get'] = $_GET;
        $this->load->view('post/index',$data);
    }

    /**
     * 帖子详情
     */
    public function getPostDetail(){
        $pid = intval($this->input->get_post('pid'));
        $res = $this->post->postDetail($pid);
        if($res){
            foreach($res as $key=>$value){
                if($key == 'img'){
                    $res[$key] = json_decode($value,true);
                }
            }
        }
        $this->ajax_json(0,'请求成功',$res);
    }

    /**
     * 设置点赞数量
     */
    public function postSettingClick(){
        $pid = intval($this->input->get_post('pid'));
        $clickCount = intval($this->input->get_post('clickCount'));
        $res = $this->post->postSetting($pid,'clickLikeCount',$clickCount);
        if($res){
            $this->ajax_json(0,'操作成功');
        }else{
            $this->ajax_json(-1,'系统错误');
        }
    }
}