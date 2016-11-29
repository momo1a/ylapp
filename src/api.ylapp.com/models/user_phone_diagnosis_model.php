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
    public function getListByUid($uid, $select = "*",$limit=10,$offset=0)
    {
        $this->where(array('askUid' => $uid));
        $this->where('(state IN(0,1,2,3,4))');
        $this->select($select);
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
     * 获取订单信息
     * @param $oid
     */
    public function getOrderById($oid){
        return $this->find_by(array('id'=>$oid));
    }

    /**
     * 重新预约问诊
     * @param $oid
     * @param $data
     */
    public function reDia($oid,$data){
        $where = array('id'=>$oid);
        $res = $this->update($where,$data);
        return $res;
    }

    /**
     * 取消在线问诊
     * @param $uid
     * @param $id
     */
    public function askOnlineCancel($uid,$id){
        /*开始事务*/
        $this->db->trans_begin();
        $currentTime = time();
        //$this->select('hopeCalldate,price');
        $orderInfo = $this->find($id);
        if($orderInfo['state'] != 1){  // 当前订单不是已付款的状态
            exit(json_encode(array('code'=>-1,'msg'=>'订单状态异常','data'=>null)));
        }
        /*修改订单状态*/
        $where = array('askUid'=>$uid,'id'=>$id);
        $data = array('cencelTime'=>$currentTime,'state'=>5);
        $this->update($where,$data);
        $sevenDay = 7*86400;
        $threeDay = 3*86400;
        $appointTime = intval($orderInfo['hopeCalldate']);
        $diffTime = $appointTime - $currentTime;
        /*退款处理 -- 提前1个星期退全额,提前3天退一半,提前1天不退,退到余额*/
        $tradeData = array(
            'uid'=>$orderInfo['askUid'],
            'userType'=>1,
            'tradeDesc'=>'电话问诊退款',
            'tradeChannel'=>0,
            'dateline'=>$currentTime,
            'status'=>1,
            'tradeType'=>7
        );
        if($diffTime >= $sevenDay){ // 退全款
            $this->db->query('UPDATE YL_money set `amount`=`amount`+'.$orderInfo['price'].',`updateTime`='.$currentTime.' WHERE `uid`='.$orderInfo['askUid']);
            if($this->db->affected_rows() == 0){
                $this->db->insert('money',array('uid'=>$orderInfo['askUid'],'amount'=>$orderInfo['price'],'updateTime'=>$currentTime));
            }
            $tradeData['tradeVolume'] = $orderInfo['price'];
            $this->db->insert('trade_log', $tradeData);
        }elseif($diffTime >= $threeDay && $diffTime < $sevenDay){  // 退一半
            $backMoney = bcmul(floatval($orderInfo['price']),0.5,2);
            $this->db->query('UPDATE YL_money set `amount`=`amount`+'.$backMoney.',`updateTime`='.$currentTime.' WHERE `uid`='.$orderInfo['askUid']);
            if($this->db->affected_rows() == 0){
                $this->db->insert('money',array('uid'=>$orderInfo['askUid'],'amount'=>$backMoney,'updateTime'=>$currentTime));
            }
            $tradeData['tradeVolume'] = $backMoney;
            $this->db->insert('trade_log', $tradeData);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            /*执行成功后查询数据返回*/
            $this->select('YL_hospital.name as hosName,YL_doctor_offices.officeName,YL_user_phone_diagnosis.docName,FROM_UNIXTIME(YL_user_phone_diagnosis.cencelTime) AS cancelTime');
            $this->where(array('YL_user_phone_diagnosis.id'=>$id));
            $this->join('YL_doctor_info','YL_doctor_info.uid=YL_user_phone_diagnosis.docId','left');
            $this->join('YL_doctor_offices','YL_doctor_offices.id=YL_doctor_info.officeId','left');
            $this->join('YL_hospital','YL_hospital.hid=YL_doctor_info.hid','left');
            $result = $this->find_all();
            return $result;
        }


    }

    /**
     * 医生首页在线问诊内容
     * @param $docId
     */
    public function doctorIndex($docId,$state=' =2 ')
    {
        $docId = intval($docId);
        $sql = <<<SQL
SELECT
	d.askTime AS dateline,
	d.id,
	u.nickname,
	d.askNickname,
	i.age,
	case WHEN i.sex=1 THEN '男' WHEN i.sex=2 THEN '女' END AS sex,
	d.askTelephone,
	d.docRemark,
	d.phoneTimeLen,
	FROM_UNIXTIME(d.hopeCalldate) AS callDate,
	d.askContent,
	i.situation,
	'在线问诊' AS type

FROM
	YL_user_phone_diagnosis AS d
LEFT JOIN YL_user AS u ON d.askUid = u.uid
LEFT JOIN YL_user_illness_history AS i ON d.illnessId=i.illId
WHERE
	d.state {$state}
AND d.docId = {$docId}
SQL;
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res;
    }


    /**
     * 获取医生端问诊列表
     * @param $docId
     * @param string $select
     * @param int $flag
     * @return array
     */
    public function getDoctorDiaList($docId,$select="*",$flag=1,$limit=10,$offset=0){
        $this->where(array('docId'=>$docId));
        switch($flag){
            case 1:   //未完成问诊列表
                $this->where('(YL_user_phone_diagnosis.state IN(2))');
                break;
            case 2:   //已完成问诊列表
                $this->where(array('YL_user_phone_diagnosis.state'=>3));
                break;
            default:
                exit(json_encode(array('code'=>305,'msg'=>"flag参数非法",array())));
        }
        $this->join('YL_user','YL_user.uid=YL_user_phone_diagnosis.askUid','left');
        $this->join('YL_user_illness_history','YL_user_illness_history.illId=YL_user_phone_diagnosis.illnessId','left');
        $this->select($select);
        $this->limit($limit);
        $this->offset($offset);
        $this->order_by('YL_user_phone_diagnosis.id','DESC');
        $res = $this->find_all();
        return $res;

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


    /**
     * 医生提交备注
     * @param $id
     */
    public function editDoctorRemark($id,$content){
        $where = array('id'=>intval($id));
        $data =  array('docRemark'=>$content);
        $res = $this->update($where,$data);
        return $res;
    }


    /**
     * @param $oid
     */
    public function orderCancel($oid){
        $where = array('id'=>$oid);
        return $this->delete_where($where);
    }

}