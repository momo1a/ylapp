<?php
/**
 * 用户首页控制器
 * User: momo1a@qq.com
 * Date: 2016/8/4 0004
 * Time: 下午 10:47
 */

class User_index extends MY_Controller
{
    public function __construct(){

        parent::__construct();
    }


    public function index(){
        $banner = $this->getBannerImg();
        $scrollLog = $this->getIndexScrollLog();
        $doctor = $this->getIndexDoctorList();
        $news = $this->getIndexNewsList();
        $adWord = $this->getAdWords();
        $imgServer = $this->getImgServer();
        $this->response($this->responseDataFormat(0,'请求成功',array('banner'=>$banner,'adWords'=>$adWord,'scroll'=>$scrollLog,'doctor'=>$doctor,'news'=>$news,'imgServer'=>$imgServer)));
    }
    /**
     * 获取用户端首页banner
     */
    public function getBannerImg(){
        $this->load->model('banner_model','banner');
        $res = $this->banner->getBannerByUserType(1,'title,img',4);
        return $res;
        //$this->response($this->responseDataFormat(0,'请求成功',$res));
    }

    /**
     * @return mixed
     */
    protected function getAdWords(){
        $this->load->model('Index_ad_word_model','ad_words');
        $res = $this->ad_words->getWords();
        return $res;
    }

    /**
     * 用户首页获取医生列表
     */
    public function getIndexDoctorList(){
        $this->load->model('user_model','user');
        $res = $this->user->getDoctorList(6,'YL_user.uid,YL_user.avatar,YL_user.nickname,YL_doctor_offices.officeName');
        //$this->response($this->responseDataFormat(0,'请求成功',$res));
        return $res;
    }

    /**
     * 用户首页获取资讯列表
     */

    public function getIndexNewsList(){
        $this->load->model('news_model','news');
        $res = $this->news->getNewsList(6,1,'YL_news.nid,YL_news.title,YL_news.thumbnail,YL_news.author,FROM_UNIXTIME(YL_news.createTime) as createTime,YL_news_category.name as cateName',true);  // 默认获取6条发布在用户端的资讯
        return $res;
        //$this->response($this->responseDataFormat(0,'请求成功',$res));
    }


    /**
     * 获取首页滚动日志
     */
    public function getIndexScrollLog(){
        $this->load->model('user_doctor_log_model','udlog');
        $uid = self::$currentUid;
        $res = $this->udlog->getIndexScrollLog($uid,'l.description,u.nickname as user,d.nickname doctor');
        return $res;
        //$this->response($this->responseDataFormat(0,'请求成功',$res));
    }



}