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

    /**
     * 获取用户端首页banner
     */
    public function getBannerImg(){
        $this->load->model('banner_model','banner');
        $res = $this->banner->getBannerByUserType(1);
        $this->response($this->responseDataFormat(0,'请求成功',$res));
    }

    /**
     * 用户首页获取医生列表
     */
    public function getIndexDoctorList(){
        $this->load->model('user_model','user');
        $res = $this->user->getDoctorList(6);
        $this->response($this->responseDataFormat(0,'请求成功',$res));
    }

    /**
     * 用户首页获取资讯列表
     */

    public function getIndexNewsList(){
        $this->load->model('news_model','news');
        $res = $this->news->getNewsList(2,1);  // 获取两条发布在用户端的资讯
        $this->response($this->responseDataFormat(0,'请求成功',$res));
    }


    /**
     * 获取首页滚动日志
     */
    public function getIndexScrollLog(){
        $this->load->model('user_doctor_log_model','udlog');
        $uid = 0;
        $res = $this->udlog->getIndexScrollLog($uid);
        //$this->response($this->responseDataFormat(0,'请求成功',$res));
        var_dump($this->crypt->decode(self::$privateToken));
    }



}