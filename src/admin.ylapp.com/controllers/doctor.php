<?php
/**
 * 医生管理控制器
 * User: momo1a@qq.com
 * Date: 2016/9/18 0018
 * Time: 下午 8:58
 */

class Doctor extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('User_model','user');
        $this->load->model('Doctor_info_model','doctor');
        $this->load->model('User_illness_history_model','illness');
        $this->load->model('User_illness_history_remarks_model','illness_remark');
        $this->load->model('User_phone_diagnosis_model','diagnosis');
        $this->load->model('User_reg_num_model','reg');
        $this->load->model('User_leaving_msg_model','leaving');
        $this->load->model('Order_model','order');
        $this->load->model('Common_trade_log_model','trade');
        $this->load->model('Doctor_fee_setting_model','doctor_fee');
        $this->load->model('Hospital_model','hospital');
        $this->load->model('Hospital_model','hospital');
        $this->load->model('Doctor_offices_model','office');
    }


    public function index(){
        $limit = 10;
        $stateArr = array(
            0  => '待审核',
            1  => '通过',
            2  => '未通过'
            );
        $nickname = trim(addslashes($this->input->get_post('nickname')));
        $phone = trim(addslashes($this->input->get_post('telephone')));
        $state = intval($this->input->get_post('state'));
        if(!isset($_GET['state'])){
            $_GET['state'] = -1;
            $state = -1;
        }
        $total = $this->user->getUserCount($nickname,$phone,2,$state);
        $offset = intval($this->uri->segment(3));
        $list = $this->user->getUserList($limit,$offset,$nickname,$phone,2,'YL_user.*,YL_money.amount,YL_doctor_info.state as doctorState,YL_doctor_info.isDude',$state);
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['hospital'] = $this->hospital->getHospitalList();
        $data['office'] = $this->office->getAllOffices();
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['get'] = $_GET;
        $data['stateArr'] = $stateArr;
        $this->load->view('doctor/index',$data);
    }

    /**
     * 修改医生信息状态
     */
    public function setDoctorStat(){
        $uid = intval($this->input->get_post('uid'));
        $state = intval($this->input->get_post('state'));
        $res = $this->doctor->setDoctorStat($uid,$state);
        if($res){
            $this->ajax_json(0,'操作成功');
        }else{
            $this->ajax_json(0,'操作失败');
        }
    }

    /**
     * 获取医生详情
     */
    public function getDoctorDetail(){
        $uid = intval($this->input->get_post('uid'));
        $res = $this->doctor->getDoctorDetail($uid);
        if(!empty($res['certificateImg'])){
            $res['certificateImg'] = json_decode($res['certificateImg'],true);
            $res['birthday'] = date('Y-m-d',$res['birthday']);
        }
        $this->ajax_json(0,'请求成功',$res);
    }


    /**
     *
     * 获取医生订单记录
     */
    public function getUserOrder(){
        $limit = 500;
        $type = intval($this->input->get_post('type'));
        $uid = intval($this->input->get_post('uid'));
        $result = array();
        switch($type){
            case 1:   //  在线问诊
                $result['order'] = $this->diagnosis->getListByUid($uid,'*,YL_user_phone_diagnosis.state as orderState,FROM_UNIXTIME(YL_user_phone_diagnosis.hopeCalldate) as hopeCalldate',$limit,0,2);
                $result['type'] = 1;
                break;
            case 2:  //  挂号记录
                $result['order'] = $this->reg->appointList($uid,'*,YL_user_reg_num.status as orderState,FROM_UNIXTIME(YL_user_reg_num.appointTime) AS appointTime',$limit,0,2);
                $result['type']  = 2;
                break;
            case 3:  //  问答记录
                $result['order'] = $this->leaving->getMsgList($uid,'*,YL_user_leaving_msg.state as orderState,doc.phone as docPhone',$limit,0,2);
                $result['type']  = 3;
                break;
            default:
                break;

        }
        $this->ajax_json(0,'请求成功',$result);
    }


    /**
     * 交易记录
     */
    public function getTradeList(){
        $uid = intval($this->input->get_post('uid'));
        $res = $this->trade->getListByUid($uid,'*,FROM_UNIXTIME(dateline) as dateline');
        $this->ajax_json(0,'请求成功',$res);
    }


    /**
     * 获取医生费用
     */
    public function getDoctorFee(){
        $uid = intval($this->input->get_post('uid'));
        $res = $this->doctor_fee->getFeeSettingByUid($uid);
        $this->ajax_json(0,'请求成功',$res);
    }


    public function saveDoctorFee(){
        $request = $_REQUEST;
        foreach($request as $key=>$value){
            if(preg_match('/.?(time|docid).?/i',$key)){
                $request[$key] = intval($value);
            }else{
                $request[$key] = floatval($value);
            }
        }

        $res = $this->doctor_fee->saveDoctorFee($request['docId'],$request);
        if($res === false){
            $this->ajax_json(-1,'操作失败');
        }else{
            $this->ajax_json(0,'操作成功');
        }

    }

    /**
     * 添加医生账户
     */
    public function addDoctor(){
        $phone = trim($this->input->get_post('phone'));
        $password = $this->encryption(trim($this->input->get_post('password')));
        $nickname = $this->input->get_post('nickname');
        $sex = intval($this->input->get_post('sex'));
        $hid = intval($this->input->get_post('hid'));
        $officeId = intval($this->input->get_post('officeId'));
        $docLevel = intval($this->input->get_post('docLevel'));
        $isDude = intval($this->input->get_post('isDude'));
        $this->user->getRecord('phone',$phone) ? $this->ajax_json(-1,'手机号已经注册') : '';
        $this->user->getRecord('nickname',$nickname) ? $this->ajax_json(-1,'昵称已经存在') : '';
        $res = $this->user->addDoctor($phone,$password,$nickname,$sex,$hid,$officeId,$isDude,$docLevel);
        if($res){
            $this->ajax_json(0,'操作成功');
        }else{
            $this->ajax_json(0,'操作失败');
        }

    }


}