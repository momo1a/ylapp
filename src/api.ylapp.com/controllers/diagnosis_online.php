<?php
/**
 * 在线问诊控制器
 * User: Administrator
 * Date: 2016/8/9 0009
 * Time: 下午 11:28
 */
class Diagnosis_online extends MY_Controller
{
    public function __construct(){

        parent::__construct();
    }

    /**
     * 在线问诊预约
     */
    public function commitRecord(){
        $phoneTimeLen = intval($this->input->get_post('phoneTimeLen'));  // 通话时长

    }
}