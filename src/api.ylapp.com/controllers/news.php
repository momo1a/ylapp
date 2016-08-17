<?php
/**
 * 资讯控制器
 * User: momo1a@qq.com
 * Date: 2016/8/8 0008
 * Time: 下午 10:34
 */

class News extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('News_model','news');
        $this->load->model('News_collections_model','news_collection');
    }

    /**
     * 资讯首页
     */
    public function getNewsList(){
        $res = $this->news->getNewsList(500,1,'YL_news.nid,YL_news.thumbnail,YL_news.title,YL_news.author,FROM_UNIXTIME(YL_news.createTime) AS createTime,YL_news_category.name AS newsCate');
        $this->response($this->responseDataFormat(0,'请求成功',array($res)));

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
