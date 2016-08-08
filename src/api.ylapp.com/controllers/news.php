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
    }

    /**
     * 资讯首页
     */
    public function getNewsList(){
        $res = $this->news->getNewsList(500,1,'YL_news.nid,YL_news.thumbnail,YL_news.title,YL_news.author,FROM_UNIXTIME(YL_news.createTime) AS createTime,YL_news_category.name AS newsCate');
        if($res){
            $this->response($this->responseDataFormat(0,'请求成功',array($res)));
        }else{
            $this->response($this->responseDataFormat(-1,'请求失败',array()));
        }
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
}
