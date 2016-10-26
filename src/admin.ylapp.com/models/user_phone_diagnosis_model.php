<?php

/**
 * 在线问诊model
 * User: momo1a@qq.com
 * Date: 2016/8/9 0009
 * Time: 下午 11:18
 */
class User_phone_diagnosis_model extends MY_Model
{
    public static $table_name = 'user_phone_diagnosis';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 在线问诊预约
     */
    public function commitRecord($data)
    {
        $this->insert($data);
        $res = $this->db->insert_id();
        return $res;
    }

    /**
     * 根据用户id获取问诊记录
     * @param $uid
     * @param string $select
     */
    public function getListByUid($uid, $select = "*",$limit=10,$offset=0,$userType=1)
    {
        switch($userType){
            case 2 :
                $this->where(array('docId' => $uid));
                break;
            default :
                $this->where(array('askUid' => $uid));
        }
        $this->select($select);
        $this->join('YL_doctor_info','YL_doctor_info.uid=YL_user_phone_diagnosis.docId','left');
        $this->join('YL_user','YL_doctor_info.uid=YL_user.uid','left');
        $this->join('YL_doctor_offices','YL_doctor_offices.id=YL_doctor_info.officeId','left');
        $this->join('YL_hospital','YL_hospital.hid=YL_doctor_info.hid','left');
        $this->order_by('askTime', 'DESC');
        $this->limit($limit);
        $this->offset($offset);
        $res = $this->find_all();
        return $res;
    }

    /**
     *
     * @param $id
     * @param $select
     */
    public function getDetailById($uid, $id, $select)
    {
        $this->where(array('askUid' => $uid));
        $this->select($select);
        $res = $this->find($id);
        return $res;
    }



    /**
     * 取消在线问诊
     * @param $uid
     * @param $id
     */
    public function askOnlineCancel($uid,$id){
        $where = array('askUid'=>$uid,'id'=>$id);
        $data = array('cencelTime'=>time(),'state'=>5);
        $res = $this->update($where,$data);
        /*执行成功后查询数据返回*/
        if($res){
            $this->select('YL_hospital.name as hosName,YL_doctor_offices.officeName,YL_user_phone_diagnosis.docName,FROM_UNIXTIME(YL_user_phone_diagnosis.cencelTime) AS cancelTime');
            $this->where(array('YL_user_phone_diagnosis.id'=>$id));
            $this->join('YL_doctor_info','YL_doctor_info.uid=YL_user_phone_diagnosis.docId','left');
            $this->join('YL_doctor_offices','YL_doctor_offices.id=YL_doctor_info.officeId','left');
            $this->join('YL_hospital','YL_hospital.hid=YL_doctor_info.hid','left');
            $result = $this->find_all();
            return $result;
        }else{
            return false;
        }
    }



    /**
     * 获取问诊详情医生端
     * @param $id
     *
     */
    public function getDoctorDiaDetail($id,$select='*'){
        $this->select($select);
        $this->join('YL_user','YL_user.uid=YL_user_phone_diagnosis.askUid','left');
        $this->join('YL_user_illness_history','YL_user_illness_history.illId=YL_user_phone_diagnosis.illnessId','left');
        $res = $this->find($id);
        return $res;
    }



    /**************管理员后台******************/

    /**
     * 统计
     * @param $keyword
     */
    public function countAppoint($keyword='',$state= -1){
        if($state != -1){
            $this->where(array('state'=>$state));
        }
        if($keyword != ''){
            $this->where(array('askNickname'=>$keyword));
        }
        return $this->count_all();
    }




    /**
     * @param string $keyword
     * @param int $limit
     * @param int $offset
     */
    public function getAppointList($keyword='',$limit=10,$offset=0,$state=-1,$select='*'){
        if($keyword != ''){
            $this->where(array('askNickname'=>$keyword));
        }
        if($state != -1){
            $this->where(array('YL_user_phone_diagnosis.state'=>$state));
        }
        $this->select($select);
        $this->join('YL_doctor_info','YL_doctor_info.uid=YL_user_phone_diagnosis.docId','left');
        $this->join('YL_user','YL_doctor_info.uid=YL_user.uid','left');
        $this->join('YL_doctor_offices','YL_doctor_offices.id=YL_doctor_info.officeId','left');
        $this->join('YL_hospital','YL_hospital.hid=YL_doctor_info.hid','left');
        $this->join('YL_user_illness_history','YL_user_illness_history.illId=YL_user_phone_diagnosis.illnessId','left');
        $this->limit($limit);
        $this->offset($offset);
        $this->order_by(array('askTime'=>'desc'));
        return $this->find_all();
    }


    /**
     * 修改状态
     * @param $oid
     * @param $status
     * @return bool
     */
    public function settingStatus($oid,$status){
        $where = array('id'=>$oid);
        $updateData = array('state'=>$status);
        $currentTime = time();
        /*开始事务*/
        $this->db->trans_begin();

        $this->update($where,$updateData);  // 更新状态
        $orderInfo = $this->select('*,d.nickname as docName,u.nickname as userName,YL_user_phone_diagnosis.price as diaFee')
            ->join('YL_user as d','d.uid=YL_user_phone_diagnosis.docId','left')
            ->join('YL_user as u','u.uid=YL_user_phone_diagnosis.askUid','left')
            ->join('YL_doctor_fee_seting as s','s.docId=YL_user_phone_diagnosis.docId','left')
            ->find_by($where);
        switch(intval($status)){
            case 2:
                $tradeDesc = '电话问诊确定沟通时间';
                $stat = 0;
                break;
            case 3:
                if(!$orderInfo['timeLenKey']){
                    $per = 0;
                }else{
                    $perKey = str_replace('TimeLen','Per',$orderInfo['timeLenKey']);
                }
                if(!$orderInfo[$perKey]){
                    $per = 0;
                }else{
                    $per = $orderInfo[$perKey];
                }
                $docGetFee = bcmul($orderInfo['diaFee'],$per/100,2);  //  医生获得费用
                $updateRes =$this->db->query('UPDATE YL_money set `amount`=`amount`+'.$docGetFee.',`updateTime`='.$currentTime.' WHERE `uid`='.$orderInfo['docId']);
                if(!$updateRes){
                    $this->db->insert('money',array('uid'=>$orderInfo['docId'],'amount'=>$docGetFee,'updateTime'=>$currentTime));
                }
                $tradeDesc = '电话问诊预约完成';
                $stat = 1;
                break;
            case 4:
                $tradeDesc = '电话问诊预约失败';
                $stat = 2;
                break;
            default:
                $tradeDesc = '未知';
        }
        /*交易记录数据*/
        $insertData = array(
            'uid'=>$orderInfo['askUid'],
            'userType'=>1,
            'tradeVolume'=>$orderInfo['price'],
            'tradeDesc'=>$tradeDesc,
            'tradeChannel'=>0,
            'dateline'=>$currentTime,
            'status'=>$stat,
            'tradeType'=>5
        );
        if($status == 2 || $status == 3) {
            $this->db->insert('trade_log', $insertData); //  交易记录
        }

        /*需求不需要退款*/
        /*if($status == 4){  // 预约失败 返回金额到用户钱包
            $updateRes = $this->db->query('UPDATE YL_money set `amount`=`amount`+'.$orderInfo['price'].',`updateTime`='.$currentTime.' WHERE `uid`='.$orderInfo['askUid']);
            if(!$updateRes){
                $this->db->insert('money',array('uid'=>$orderInfo['askUid'],'amount'=>$orderInfo['price'],'updateTime'=>$currentTime));
            }
        }*/

        $docUserLog = array(
            'userId' => $orderInfo['askUid'],
            'doctorId' => $orderInfo['docId'],
            'comType'=>2,
            'comState'=>$status,
            'description'=>$tradeDesc,
            'dateline'=>$currentTime,
        );

        // 用户医生日志表

        $this->db->insert('user_doctor_log',$docUserLog);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
    }


    /**
     * 获取详情
     * @param $oid
     * @return array
     */
    public function getDetail($oid){
        $where = array('id'=>$oid);
        return $this->find_by($where);
    }


    /**
     * 修改预约时间
     * @param $oid
     * @param $time
     */
    public function updateAppointTime($oid,$time){
        $where = array('id'=>$oid);
        $res = $this->update($where,array('hopeCalldate'=>$time));
        return $res;
    }


}