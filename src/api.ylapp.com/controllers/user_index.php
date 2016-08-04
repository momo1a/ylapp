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

}