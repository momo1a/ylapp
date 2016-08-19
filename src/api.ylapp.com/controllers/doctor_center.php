<?php
/**
 * 医生中心控制器
 * User: momo1a@qq.com
 * Date: 2016/8/19
 * Time: 13:49
 */

class Doctor_center extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->checkUserLogin();
        $this->load->model('User_reg_num_model','reg_num');
        $this->load->model('User_phone_diagnosis_model','diagnosis');
        $this->load->model('User_leaving_msg_model','levemsg');
    }

    /**
     * 首页
     */
    public function index(){
        $regOrder = $this->reg_num->doctorIndex(self::$currentUid);  //预约挂号
        $diagOrder = $this->diagnosis->doctorIndex(self::$currentUid); //在线问诊
        $msgOrder = $this->levemsg->doctorIndex(self::$currentUid); //留言问答

    }
}