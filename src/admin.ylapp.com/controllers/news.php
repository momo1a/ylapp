<?php
/**
 * 资讯管理
 * User: momo1a@qq.com
 * Date: 2016/9/24 0024
 * Time: 下午 9:38
 */

class News extends MY_Controller
{
    /**
     * 发布位置
     * @var null
     */
    protected $_post_pos = null;


    /**
     * 状态
     * @var null
     */
    protected $_state = null;


    public function __construct(){
        parent::__construct();
        $this->_post_pos = array(
            '0'=>'全部',
            '1'=>'用户端',
            '2'=>'医生端',
        );
        $this->_state = array(
            -1 => '全部',
            0 => '未发布',
            1 => '已发布',
        );
        $this->load->model('News_model','news');
    }

    public function index(){
        $limit = 10;
        if(!isset($_GET['state'])){
            $_GET['state'] = -1;
        }
        $keyword = addslashes(trim($this->input->get_post('keyword')));
        $state = intval($_GET['state']);
        $post_pos = intval($this->input->get('postPos'));
        $total = $this->news->newsCount($keyword,$state,$post_pos);
        $offset = intval($this->uri->segment(3));
        $list = $this->news->getNewsList($limit,$offset,$keyword,$state,$post_pos);
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['state'] = $this->_state;
        $data['post_pos'] = $this->_post_pos;
        $data['get'] = $_GET;
        $this->load->view('news/index',$data);
    }


    public function newsAdd(){
        $nid = intval($this->input->get_post('nid'));
        if($nid !== 0) {  //  编辑
            echo 'test';
        }else{    //  添加
                $title = trim($this->input->get_post('title'));
                $author = trim($this->input->get_post('author'));
                $content = $this->input->get_post('content');
                $tag = trim($this->input->get_post('tag'));
                $postPos = intval($this->input->get_post('postPos'));
                $isRecmd = intval($this->input->get_post('isRecmd'));
                $isRecmdIndex = intval($this->input->get_post('isRecmdIndex'));
                $state = intval($this->input->get_post('state'));
                $thumbnail_relative_path = $this->upload->save('news', $_FILES['thumbnail']['tmp_name']);
                $banner_relative_path = $this->upload->save('news', $_FILES['banner']['tmp_name']);
                $data = array(
                    'cid' => rand(1, 2),
                    'title' => $title,
                    'content' => $content,
                    'author' => $author,
                    'thumbnail' => $thumbnail_relative_path,
                    'banner' => $banner_relative_path,
                    'tag' => $tag,
                    'postPos' => $postPos,
                    'isRecmd' => $isRecmd,
                    'isRecmdIndex' => $isRecmdIndex,
                    'createTime' => time(),
                    'state' => $state
                );

                $res = $this->news->addNews($data);

                if ($res) {
                    $this->ajax_json(0, '添加成功');
                } else {
                    $this->ajax_json(-1, '系统错误');
                }
            }
        }

    /**
     * 获取资讯详情
     */
    public function getNewsDetail(){
        $nid = intval($this->input->get_post('nid'));
        $res = $this->news->getNewsDetail($nid);
        $this->ajax_json(0,'请求成功',$res);
    }
}