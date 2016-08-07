<?php
/**
 * 病历控制器
 * User: momo1a@qq.com
 * Date: 2016/8/4 0004
 * Time: 下午 10:47
 */

class Medical extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('User_illness_history_model','illness');
        $this->load->model('User_illness_history_remarks_model','illness_remarks');
    }

    /**
     * 添加病历
     */
    public function addIllnessHistory(){
        $this->load->model('user_model');
        $uid = self::$currentUid;
        $nickname = $this->user_model->getNickname($uid,'nickname');
        $data = array(
            'uid'=>$uid,
            'username'=>$nickname,
            'illName'=>addslashes(trim($this->input->post('illName'))),   //病历名称
            'realname'=>addslashes(trim($this->input->post('realName'))), //姓名
            'age'=>intval($this->input->post('age')),  //年龄
            'sex'=>intval($this->input->post('sex')),  //性别
            'allergyHistory'=>addslashes(trim($this->input->post('allergyHistory'))),
            'result'=>addslashes(trim($this->input->post('result'))), //诊断结果
            'stages'=>intval($this->input->post('stages')), // 分期
            'situation'=>$this->input->post('situation') //基本病情
        );
        $res = $this->illness->addIllness($data);
        if($res){
            $this->response($this->responseDataFormat(0,'请求成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'暂无数据',array()));
        }

    }

    /**
     * 用户获取病历列表
     */

    public function getIllnessList(){
        $uid = self::$currentUid;
        $res = $this->illness->illnessList($uid);
        if($res){
            $this->response($this->responseDataFormat(0,'请求成功',array($res)));
        }else{
            $this->response($this->responseDataFormat(-1,'暂无数据',array()));
        }
    }

    /**
     * 病历详情
     */
    public function getIllnessDetail(){
        $illId = intval($this->input->get_post('illId'));
        $res = $this->illness->getIllnessDetail(self::$currentUid,$illId);
        $resTwo = $this->illness_remarks->getRemarksByIllIdAndUid($illId,self::$currentUid);
        if($res){
            $this->response($this->responseDataFormat(0,'请求成功',array('ill'=>$res,'remarks'=>$resTwo)));
        }else{
            $this->response($this->responseDataFormat(-1,'暂无数据',array()));
        }
    }



}