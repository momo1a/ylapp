<?php
/**
 * 用户管理控制器
 * User: momo1a@qq.com
 * Date: 2016/9/16 0016
 * Time: 下午 12:10
 */

class User extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model','user');
        $this->load->model('User_illness_history_model','illness');
        $this->load->model('User_illness_history_remarks_model','illness_remark');
        $this->load->model('User_phone_diagnosis_model','diagnosis');
        $this->load->model('User_reg_num_model','reg');
        $this->load->model('User_leaving_msg_model','leaving');
        $this->load->model('Order_model','order');
        $this->load->model('Common_trade_log_model','trade');
    }

    public function index(){
        $limit = 10;
        $nickname = trim(addslashes($this->input->get_post('nickname')));
        $phone = trim(addslashes($this->input->get_post('telephone')));
        $total = $this->user->getUserCount($nickname,$phone);
        $offset = intval($this->uri->segment(3));
        $list = $this->user->getUserList($limit,$offset,$nickname,$phone,1,'YL_user.*,YL_money.amount');
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['get'] = $_GET;
        $this->load->view('user/index',$data);
    }

    /**
     * 用户病历
     */
    public function getUserIllness(){
        $uid = intval($this->input->get_post('uid'));
        $ills = $this->illness->getUserIll($uid);
        if(!empty($ills)){
            foreach($ills as $key=>$ill){
                $ills[$key]['remarks'] = $this->illness_remark->getRemarksByIllIdAndUid($ill['illId'],$uid);
                if($ills[$key]['remarks']  != false){
                    foreach($ills[$key]['remarks'] as $k=>$remark){
                        $ills[$key]['remarks'][$k]['img'] = json_decode($remark['img'],true);
                        $ills[$key]['remarks'][$k]['visitDate'] = date('Y-m-d',$remark['visitDate']);
                    }
                }
            }
        }else{
            $this->ajax_json(1,'该用户暂无病历');
        }

        $this->ajax_json(0,'请求成功',$ills);

    }


    /**
     *
     * 获取用户订单记录
     */
    public function getUserOrder(){
        $limit = 500;
        $type = intval($this->input->get_post('type'));
        $uid = intval($this->input->get_post('uid'));
        $result = array();
        switch($type){
            case 1:   //  在线问诊
                $result['order'] = $this->diagnosis->getListByUid($uid,'*,YL_user_phone_diagnosis.state as orderState,FROM_UNIXTIME(YL_user_phone_diagnosis.hopeCalldate) as hopeCalldate',$limit,0);
                $result['type'] = 1;
                break;
            case 2:  //  挂号记录
                $result['order'] = $this->reg->appointList($uid,'*,YL_user_reg_num.status as orderState,FROM_UNIXTIME(YL_user_reg_num.appointTime) AS appointTime',$limit,0);
                $result['type']  = 2;
                break;
            case 3:  //  问答记录
                $result['order'] = $this->leaving->getMsgList($uid,'*,YL_user_leaving_msg.state as orderState,doc.phone as docPhone',$limit,0);
                $result['type']  = 3;
                break;
            case 4:  // 购买记录
                $result['order'] = $this->order->getAllOrder($uid,'*,YL_order.type as orderType,YL_vaccinum.type as vaccinumType,YL_order.price as orderPrice,',$limit,0);
                $result['type']  = 4;
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


    public function setUserBlank(){
        $uid = intval($this->input->get_post('uid'));
        $flag = intval($this->input->get_post('flag'));
        $res = $this->user->setUserBlank($uid,$flag);
        if($res){
            $this->ajax_json(0,'操作成功');
        }else{
            $this->ajax_json(0,'操作失败');
        }
    }
}