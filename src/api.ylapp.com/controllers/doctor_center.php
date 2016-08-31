<?php
/**
 * 医生中心控制器
 * User: momo1a@qq.com
 * Date: 2016/8/19
 * Time: 13:49
 */

class Doctor_center extends MY_Controller
{

    protected $imgServer = null;
    public function __construct(){
        parent::__construct();
        $this->checkUserLogin();
        $this->load->model('User_reg_num_model','reg_num');
        $this->load->model('User_phone_diagnosis_model','diagnosis');
        $this->load->model('User_leaving_msg_model','levemsg');
        $this->load->model('Doctor_reply_model','reply');
        $this->load->model('User_model','user');
        $this->load->model('User_illness_history_remarks_model','ill_remark');
        $this->imgServer = $this->getImgServer();
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
        $this->response($this->responseDataFormat(0,'请求成功',array('order'=>$result,'count'=>$i,'imgServer'=>$this->imgServer)));
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
        $this->response($this->responseDataFormat(0,'请求成功',array('order'=>$order,'imgServer'=>$this->imgServer)));
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
        $this->response($this->responseDataFormat(0,'请求成功',array('result'=>$res,'imgServer'=>$this->imgServer)));
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
    /**
     * 问诊列表
     */
    public function getOnlineDiaList(){
        $flag = intval($this->input->get_post('state'));
        $limit = intval($this->input->get_post('limit'));
        $limit = $limit == 0 ? 10 : $limit;
        $offset = intval($this->input->get_post('offset'));
        $select = 'id,nickname as username,(case  when ask_sex=1 then "男" when ask_sex=2 then "女" end) as sex,YL_user_illness_history.age,askNickname,askTelephone,phoneTimeLen,FROM_UNIXTIME(hopeCalldate) hopeCalldate,askContent,docRemark';
        $res = $this->diagnosis->getDoctorDiaList(self::$currentUid,$select,$flag,$limit,$offset);
        $this->response($this->responseDataFormat(0,'请求成功',array($res)));
    }


    /**
     * 问诊详情
     */

    public function getOnlineDiaDetail(){
        $id = intval($this->input->get_post('id'));  // 问诊id
        $select = 'nickname as username,(case  when ask_sex=1 then "男" when ask_sex=2 then "女" end) as sex,YL_user_illness_history.age,askNickname,allergyHistory,stages,askTelephone,askContent,phoneTimeLen,hopeCalldate,illnessId';
        $order = $this->diagnosis->getDoctorDiaDetail($id,$select);
        if($order) {
            $remarkSelect = 'FROM_UNIXTIME(visitDate) as visitDate,stage,content,img';
            $remark = $this->ill_remark->getRemarksByIllId($order['illnessId'],$remarkSelect);
            if(!empty($remark)){
                foreach($remark as $key=>$value){
                    $remark[$key]['img'] = json_decode($value['img'],true);
                }
            }
            $this->response($this->responseDataFormat(0,'请求成功',array('detail'=>$order,'remark'=>$remark,'imgServer'=>$this->imgServer)));
        }else{
            $this->response($this->responseDataFormat(-1,'记录不存在',array()));
        }
    }


    /**
     * 提交备注
     */


    public function commitRemark(){
        $id = intval($this->input->get_post('id'));  // 问诊id
        $content = trim(addslashes($this->input->get_post('content')));  // 内容
        $res = $this->diagnosis->editDoctorRemark($id,$content);
        if($res){
            $this->response($this->responseDataFormat(0,'请求成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'操作失败',array()));
        }
    }



    /*********************问诊之在线问诊end**********************************/



    /*********************问诊之预约挂号start**********************************/

    /**
     * 预约挂号列表
     */
    public function getRegList(){
        $flag = intval($this->input->get_post('state'));   // 1 未完成  2 已经完成
        $limit = intval($this->input->get_post('limit'));
        $offset = intval($this->input->get_post('offset'));
        $limit = $limit == 0 ? 10 : $limit;
        $select = 'id,nickname as username,(case  when YL_user_reg_num.sex=1 then "男" when YL_user_reg_num.sex=2 then "女" end) as sex,age,contacts,appointTel,FROM_UNIXTIME(appointTime) AS appointTime';
        $res = $this->reg_num->getDoctorRegList(self::$currentUid,$select,$flag,$limit,$offset);
        if($res){
            $this->response($this->responseDataFormat(0,'请求成功',array($res)));
        }else{
            $this->response($this->responseDataFormat(1,'暂无数据',array()));
        }
    }


    /**
     * 问诊详情
     */

    public function getRegNumDetail(){
        $id = intval($this->input->get_post('id'));  // 预约id
        $select = 'nickname as username,(case  when YL_user_reg_num.sex=1 then "男" when YL_user_reg_num.sex=2 then "女" end) as sex,YL_user_illness_history.age,contacts,allergyHistory,stages,appointTel,userRemark,FROM_UNIXTIME(appointTime) AS appointTime,illnessId';
        $order = $this->reg_num->getDoctorRegDetail($id,$select);
        if($order) {
            $remarkSelect = 'FROM_UNIXTIME(visitDate) as visitDate,stage,content,img';
            $remark = $this->ill_remark->getRemarksByIllId($order['illnessId'],$remarkSelect);
            if(!empty($remark)){
                foreach($remark as $key=>$value){
                    $remark[$key]['img'] = json_decode($value['img'],true);
                }
            }
            $this->response($this->responseDataFormat(0,'请求成功',array('detail'=>$order,'remark'=>$remark,'imgServer'=>$this->imgServer)));
        }else{
            $this->response($this->responseDataFormat(-1,'记录不存在',array()));
        }
    }



    /*********************问诊之预约挂号end**********************************/

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