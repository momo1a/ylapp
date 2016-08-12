<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * 预约挂号控制器
 * User: momo1a@qq.com
 * Date: 2016/8/12
 * Time: 10:39
 */

class Reg_num extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->checkUserLogin();
        $this->load->model('User_reg_num_model','user_reg_num');
        $this->load->model('User_illness_history_model','illness');
        $this->load->model('Doctor_fee_setting_model','fee_setting');

    }

    /**
     * 用户预约挂号页面
     */
    public function regNumView(){
        $docId = intval($this->input->get_post('docId'));
        $illness = $this->illness->illnessList(self::$currentUid,'illId,illName');
        $fee = $this->fee_setting->getFeeSettingByUid($docId,'regNumFee');
        $this->response($this->responseDataFormat(0,'请求成功',array('illness'=>$illness,'fee'=>$fee)));
    }


}