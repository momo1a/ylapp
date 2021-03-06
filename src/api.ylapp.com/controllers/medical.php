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
        $this->checkUserLogin();
        $this->load->model('User_illness_history_model','illness');
        $this->load->model('User_illness_history_remarks_model','illness_remarks');
        $this->load->library('upload_image');
    }

    /**
     * 添加病历
     */
    public function addIllnessHistory(){
        $this->load->model('user_model');
        $uid = self::$currentUid;
        $nickname = $this->user_model->getUserInfoByUid($uid,'nickname');
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
        $limit = intval($this->input->get_post('limit'));
        $offset = intval($this->input->get_post('offset'));
        $limit = $limit == 0 ? 20 : $limit;
        $uid = self::$currentUid;
        $res = $this->illness->illnessList($uid,'illId,illName,sex,age,realname,allergyHistory,result,stages,situation',$limit,$offset);
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
        if(!empty($resTwo)){
            foreach($resTwo as $k=>$v){
                $resTwo[$k]['img'] = json_decode($v['img'],true);
                $resTwo[$k]['visitDate'] = date('Y-m-d',$v['visitDate']);
            }
        }
        if($res){
            $res['imgServer'] = $this->getImgServer();
            $this->response($this->responseDataFormat(0,'请求成功',array('ill'=>$res,'remarks'=>$resTwo)));
        }else{
            $this->response($this->responseDataFormat(-1,'暂无数据',array()));
        }
    }


    /**
     * 添加病历记录
     */
    public function addIllRemark(){
        $illId = intval($this->input->get_post('illId'));
        $visitDate = strtotime($this->input->get_post('visitDate'));
        $content = addslashes($this->input->get_post('content'));
        $stages = trim(addslashes($this->input->get_post('stages')));
        $imgArr = array();
        if(!empty($_FILES)){
            foreach($_FILES as $k=>$val){
                if($val['name'] != '') {
                    $imgFile = $this->upload_image->save('illRemark', $val['tmp_name']);
                    $imgArr[$k]=$imgFile;
                }
            }
        }
        $imgArr = !empty($imgArr) ? json_encode($imgArr) : "";
        $data = array(
            'illId'=>$illId,
            'uid'=>self::$currentUid,
            'visitDate'=>$visitDate,
            'content'=>$content,
            'stage'=>$stages,
            'img'=> $imgArr
        );
        $res = $this->illness_remarks->addRemark($data);
        if($res){
            $this->response($this->responseDataFormat(0,'请求成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'暂无数据',array()));
        }
    }

    /**
     * 显示编辑病历页面
     */

    public function editIllnessView(){
        $illId = intval($this->input->get_post('illId'));
        $illness = $this->illness->getIllnessDetail(self::$currentUid,$illId);
        $remakes = $this->illness_remarks->getRemarksByIllIdAndUid($illId,self::$currentUid);
        $imgServer = $this->getImgServer();
        if(!empty($remakes)){
            foreach($remakes as $key=>$value){
                $remakes[$key]['img'] = json_decode($value['img'],true);
                $remakes[$key]['visitDate'] = date('Y-m-d',$value['visitDate']);
            }
        }
        if($illness && $imgServer){
            $this->response($this->responseDataFormat(0,'请求成功',array('illnessHistory'=>$illness,'remarks'=>$remakes,'imgServer'=>$imgServer)));
        }else{
            $this->response($this->responseDataFormat(-1,'暂无数据',array()));
        }

    }

    /**
     * 编辑病历
     */

    public function editIllness(){
        $illId = intval($this->input->get_post('illId'));
        $illName = trim(addslashes($this->input->get_post('illName')));
        $realname = trim(addslashes($this->input->get_post('realName')));
        $age = intval($this->input->get_post('age'));
        $sex = intval($this->input->get_post('sex'));
        $allergyHistory = addslashes(trim($this->input->get_post('allergyHistory')));
        $result = trim($this->input->get_post('result'));
        $stages = intval($this->input->get_post('stages'));
        $situation = addslashes($this->input->get_post('situation'));
        $data = array(
            'illName'=>$illName,
            'realname'=>$realname,
            'age'=>$age,
            'sex'=>$sex,
            'allergyHistory'=>$allergyHistory,
            'result'=>$result,
            'stages'=>$stages,
            'situation'=>$situation
        );
        $remarkIds = trim($this->input->get_post('remarkIds'));
        $idsArr = explode('-',$remarkIds);
        if(!empty($idsArr)){
            foreach($idsArr as $id){
                $visitDate = strtotime($this->input->get_post('visitDate_'.$id));
                $stages = trim($this->input->get_post('stages_'.$id));
                $content = addslashes($this->input->get_post('content_'.$id));
                if(!empty($_FILES)){
                    $imgArr = $this->illness_remarks->getImgById($id);
                    $imgArr = $imgArr ? $imgArr : array();
                    foreach($_FILES as $key=>$value){
                        if($value['name']==''){ continue;}
                        if('img1_'.$id == $key){
                            $imgFile = $this->upload_image->save('illRemark',$value['tmp_name']);
                            $imgArr['img1'] = $imgFile;
                        }
                        if('img2_'.$id == $key){
                            $imgFile = $this->upload_image->save('illRemark',$value['tmp_name']);
                            $imgArr['img2'] = $imgFile;
                        }
                        if('img3_'.$id == $key){
                            $imgFile = $this->upload_image->save('illRemark',$value['tmp_name']);
                            $imgArr['img3'] = $imgFile;
                        }
                    }
                }
                $remarkData = array(
                    'visitDate'=>$visitDate,
                    'stage'=>$stages,
                    'content'=>$content,
                );

                $remarkData['img'] = !empty($imgArr) ? json_encode($imgArr) : "";
                $remarkRes = $this->illness_remarks->editRemarks($id,self::$currentUid,$illId,$remarkData);
            }
        }
        $res = $this->illness->editIllness(self::$currentUid,$illId,$data);
        if($res && $remarkRes){
            $this->response($this->responseDataFormat(0,'请求成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'请求失败',array()));
        }
    }

    /**
     * 删除病历记录
     */
    public function delRemark(){
        $remarkId = intval($this->input->get_post('remarkId'));
        $rs = $this->illness_remarks->delRemarkById($remarkId,self::$currentUid);
        if($rs){
            $this->response($this->responseDataFormat(0,'删除成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'删除成功',array()));
        }
    }

}