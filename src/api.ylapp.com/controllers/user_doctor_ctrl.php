<?php
/**
 * 登陆后用户医生控制器
 * User: momo1a@qq.com
 * Date: 2016/8/4
 * Time: 15:31
 */

class User_doctor_ctrl extends MY_Controller
{

    /**
     * 当前用户类型
     * @var string
     */
    protected $currentUserType = '';

    /**
     * 当前uid
     * @var string
     */
    protected $currentUserId = '';


    public function __construct(){
        parent::__construct();
        $this->load->model('user_model');
        $privateToken = trim($this->input->post('privateToken'));
        if(!$privateToken){
            $this->response($this->responseDataFormat(10,'未登录',array()));
        }
        $decodeToken = $this->crypt->decode($privateToken);

        $tokenArr = explode('-',$decodeToken);
        $this->currentUserId = intval($tokenArr[0]);
        $this->currentUserType = intval($tokenArr[3]);
        $user = $this->user_model->getUserByUid($this->currentUserId);
        if(!$user){
            $this->response($this->responseDataFormat(11,'用户不存在',array()));
        }
        if($this->currentUserType != 1 && $this->currentUserType != 2){
            $this->response($this->responseDataFormat(12,'用户类型异常',array()));
        }

    }

    public function userIndex(){
        echo 'test';
    }

    public function doctorIndex(){

    }
}