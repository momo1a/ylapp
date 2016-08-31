<?php
/**
 * 资讯控制器
 * User: momo1a@qq.com
 * Date: 2016/8/8 0008
 * Time: 下午 10:34
 */

class News extends MY_Controller
{

    protected static $_imgServer = null;

    public function __construct(){
        parent::__construct();
        $this->load->model('News_model','news');
        $this->load->model('News_collections_model','news_collection');
        self::$_imgServer = $this->getImgServer();
    }

    /**
     * 用户端
     * 资讯首页
     */
    public function getNewsList(){
        $limit = intval($this->input->get_post('limit'));
        $limit = $limit == 0 ? 10 : $limit;
        $offset = intval($this->input->get_post('offset'));
        $res = $this->news->getNewsList($limit,1,'YL_news.nid,YL_news.thumbnail,YL_news.title,YL_news.author,FROM_UNIXTIME(YL_news.createTime) AS createTime,YL_news_category.name AS newsCate',$offset);
        $this->response($this->responseDataFormat(0,'请求成功',array('news'=>$res,'imgServer'=>self::$_imgServer)));

    }
    /**
     * 医生端
     * 资讯首页
     */
    public function getNewsListDoc(){
        $limit = intval($this->input->get_post('limit'));
        $limit = $limit == 0 ? 10 : $limit;
        $offset = intval($this->input->get_post('offset'));
        $res = $this->news->getNewsList($limit,2,'YL_news.nid,YL_news.thumbnail,YL_news.title,YL_news.author,FROM_UNIXTIME(YL_news.createTime) AS createTime,YL_news_category.name AS newsCate',$offset);
        $this->response($this->responseDataFormat(0,'请求成功',array('news'=>$res,'imgServer'=>self::$_imgServer)));
    }

    /**
     * 资讯详情
     */
    public function getNewsDetail(){
        $nid = intval($this->input->get_post('nid'));
        $res = $this->news->getNewsDetail($nid,'title,author,FROM_UNIXTIME(createTime) AS createTime,content');
        if($res){
            $this->response($this->responseDataFormat(0,'请求成功',array($res)));
        }else{
            $this->response($this->responseDataFormat(-1,'请求失败',array()));
        }
    }


    /**
     * 资讯收藏
     */

    public function newsCollection(){
        $this->checkUserLogin();
        $nid = intval($this->input->get_post('nid'));
        $isCollection =  $this->news_collection->getCollectionByUidAndNid(self::$currentUid,$nid);
        $isExistsNews = $this->news->getNewsDetail($nid);
        if($isCollection){
            $this->response($this->responseDataFormat(1,'你已经收藏过该资讯了',array()));
        }
        if(!$isExistsNews){
            $this->response($this->responseDataFormat(2,'资讯不存在',array()));
        }
        $data = array(
            'nid'=>$nid,
            'uid'=>self::$currentUid,
            'dateline'=>time()
        );
        $res = $this->news_collection->addCollection($data);
        if($res){
            $this->response($this->responseDataFormat(0,'请求成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'系统错误',array()));
        }
    }
}
