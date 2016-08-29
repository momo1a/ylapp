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
        $this->load->model('Doctor_reply_model','reply');
        $this->load->model('User_model','user');
    }

    /**
     * 首页
     */
    public function index(){
        $limit = intval($this->input->get_post('limit'));
        $limit = $limit == 0 ? 10 : $limit;
        $offset = intval($this->input->get_post('offset'));
        $regOrder = $this->reg_num->doctorIndex(self::$currentUid);  //预约挂号
        $diagOrder = $this->diagnosis->doctorIndex(self::$currentUid); //在线问诊
        $msgOrder = $this->levemsg->doctorIndex(self::$currentUid); //留言问答
        if(!empty($msgOrder)){
            foreach($msgOrder as $key=>$value){
                $msgOrder[$key]['img'] = json_decode($value['img'],true);
            }
        }
        $i = 0;
        $order = array();
        $this->orderContainer($regOrder,$i,$order);
        $this->orderContainer($diagOrder,$i,$order);
        $this->orderContainer($msgOrder,$i,$order);
        $this->sortArrByField($order,'dateline',true);
        $result = array_slice($order,$offset,$limit,true);
        $this->response($this->responseDataFormat(0,'请求成功',array('order'=>$result,'count'=>$i)));
    }


    /**
     * 消息列表
     */
    public function msgList(){
        $limit = intval($this->input->get_post('limit'));
        $limit = $limit == 0 ? 10 : $limit;
        $offset = intval($this->input->get_post('offset'));
        $regOrder = $this->reg_num->doctorIndex(self::$currentUid,'in(2,3,4,5,6)');  //预约挂号
        $diagOrder = $this->diagnosis->doctorIndex(self::$currentUid,' in(2,3,4) '); //在线问诊
        $msgOrder = $this->levemsg->doctorIndex(self::$currentUid,'in(2,3,4) '); //留言问答
        $i = 0;
        $order = array();
        $this->orderContainer($regOrder,$i,$order);
        $this->orderContainer($diagOrder,$i,$order);
        $this->orderContainer($msgOrder,$i,$order);
        $this->sortArrByField($order,'dateline',true);
        $result = array_slice($order,$offset,$limit,true);
        $this->response($this->responseDataFormat(0,'请求成功',array('order'=>$result,'count'=>$i)));
    }


    /*********************问诊之留言文答start**********************************/
    /**
     *  留言问答列表
     */

    public function leavingMsgList(){
        $state = intval($this->input->get_post('state'));
        $limit = intval($this->input->get_post('limit'));
        $limit = $limit == 0 ? 10 : $limit;
        $offset = intval($this->input->get_post('offset'));
        switch($state){
            case 1:            // 未完成
                $order = $this->levemsg->doctorIndex(self::$currentUid,'=2 ',$limit,$offset);
                break;
            case 2:          // 已完成
                $order = $this->levemsg->doctorIndex(self::$currentUid,' =4 ',$limit,$offset);
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
        $id = intval($this->input->get_post('id')); /*留言id*/
        $state = intval($this->input->get_post('state'));
        switch($state){
            case 1 :
                $res = $this->levemsg->detail(self::$currentUid,$id);  // 未回答的
                break;
            case 2 :
                $res = $this->levemsg->detail(self::$currentUid,$id,true);  // 已完成的
                break;
            default :
                $this->response($this->responseDataFormat(1,'状态值不正常',array()));
                break;
        }
        if(!empty($res)){
            foreach($res as $key=>$value){
                $res[$key]['img'] = json_decode($value['img'],true);

            }
        }
        $imgServer =$this->getImgServer();
        $this->response($this->responseDataFormat(0,'请求成功',array('result'=>$res,'imgServer'=>$imgServer)));
    }


    /**
     * 医生提交回答
     */

    public function commitReply(){
        $id = intval($this->input->get_post('id'));  // 留言id
        $replyContent = addslashes($this->input->get_post('content'));
        if(!$id){
            $this->response($this->responseDataFormat(1,'请传入留言id',array()));
        }
        $info = $this->levemsg->getLeavMsgInfo($id,'askerUid');

        $data = array(
            'themeId'=>$id,
            'userId'=>intval($info['askerUid']),
            'type'=>1,
            'replyContent'=>$replyContent,
            'replyId'=>self::$currentUid,
            'replyNicname'=>$this->user->getUserInfoByUid(self::$currentUid,'nickname'),
            'replyTime'=>time()
        );
        $logData = array(
            'userId'=>intval($info['askerUid']),
            'doctorId'=>self::$currentUid,
            'comType'=>1,
            'comState'=>5,
            'description'=>'医生回答问题',
            'dateline'=>time()
        );
        $this->load->model('Common_user_doctor_log_model','udlog');
        $this->db->trans_begin();
        $insertId = $this->reply->recordAdd($data);
        $update = $this->levemsg->updateStatusById($id,5);  // 更改状态为已回答待审核
        $log = $this->udlog->saveLog($logData);
        if ($insertId && $update && $log) {
            $this->db->trans_commit();
            $this->response($this->responseDataFormat(0,'请求成功',array()));
        } else {
            $this->db->trans_rollback();
            $this->response($this->responseDataFormat(-1,'请求失败',array()));
        }
    }

    /*********************问诊之留言文答end**********************************/


    /*********************问诊之在线问诊start**********************************/

    public function getOnlineDiaList(){
        $flag = intval($this->input->get_post('state'));
        $limit = intval($this->input->get_post('limit'));
        $limit = $limit == 0 ? 10 : $limit;
        $offset = intval($this->input->get_post('offset'));
        $select = 'id,nickname as username,askNickname,askTelephone,phoneTimeLen,FROM_UNIXTIME(hopeCalldate) hopeCalldate,askContent';
        $res = $this->diagnosis->getDoctorDiaList(self::$currentUid,$select,$flag,$limit,$offset);
        $this->response($this->responseDataFormat(0,'请求成功',array($res)));
    }



    /*********************问诊之在线问诊end**********************************/

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