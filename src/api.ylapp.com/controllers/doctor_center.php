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
        $i = 0;
        $order = array();
        $this->orderContainer($regOrder,$i,$order);
        $this->orderContainer($diagOrder,$i,$order);
        $this->orderContainer($msgOrder,$i,$order);
        $this->sortArrByField($order,'dateline',true);
        $this->response($this->responseDataFormat(0,'请求成功',array('order'=>$order,'count'=>$i)));
    }


    /**
     * 消息列表
     */
    public function msgList(){
        $regOrder = $this->reg_num->doctorIndex(self::$currentUid,'in(2,3,4,5,6)');  //预约挂号
        $diagOrder = $this->diagnosis->doctorIndex(self::$currentUid,' in(2,3,4) '); //在线问诊
        $msgOrder = $this->levemsg->doctorIndex(self::$currentUid,'in(2,3,4) '); //留言问答
        $i = 0;
        $order = array();
        $this->orderContainer($regOrder,$i,$order);
        $this->orderContainer($diagOrder,$i,$order);
        $this->orderContainer($msgOrder,$i,$order);
        $this->sortArrByField($order,'dateline',true);
        $this->response($this->responseDataFormat(0,'请求成功',array('order'=>$order,'count'=>$i)));
    }


    /**
     *  留言问答列表
     */

    public function leavingMsgList(){
        $state = intval($this->input->get_post('state'));
        switch($state){
            case 1:            // 未完成
                $order = $this->levemsg->doctorIndex(self::$currentUid,'in(2) ');
                break;
            case 2:          // 已完成
                $order = $this->levemsg->doctorIndex(self::$currentUid,' =4 ');
                break;

            default:
                $this->response($this->responseDataFormat(1,'订单状态值不允许',array()));
        }
        $this->response($this->responseDataFormat(0,'请求成功',array('order'=>$order)));
    }

    /**
     * 留言问答详情页
     */

    public function leavingDetail(){
        $id = intval($this->input->get_post('id'));
        $res = $this->levemsg->detail(self::$currentUid,$id);  // 未回答的
        $this->response($this->responseDataFormat(0,'请求成功',array($res)));
    }



    /**
     * @param $order
     * @param $i
     * @param $container
     */
    protected function orderContainer($order,&$i,&$container){
        if(is_array($order)){
            if(!empty($order)){
                foreach($order as $val){
                    array_push($container,$val);
                    $i++;
                }
            }
        }
    }

    /**
     * 多维数组排序
     * @param $array
     * @param $field
     * @param bool $desc
     */
    protected function sortArrByField(&$array, $field, $desc = false){
        $fieldArr = array();
        if(!empty($array)){
            foreach ($array as $k => $v) {
                $fieldArr[$k] = $v[$field];
            }
        }
        $sort = $desc == false ? SORT_ASC : SORT_DESC;
        array_multisort($fieldArr, $sort, $array);
    }
}