<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 用户预约挂号模型
 * Author: momo1a@qq.com
 * Date: 2016/8/12
 * Time: 10:11
 */

class User_reg_num_model extends MY_Model
{
    public static $table_name = 'user_reg_num';

    public function __construct(){
        parent::__construct();
    }


    /**
     * 挂号第一步生成记录
     */
    public function firstStep($data){
        $this->insert($data);
        return $this->db->insert_id();
    }

    /**
     * 获取用户预约列表
     * @param $uid
     */
    public function appointList($uid,$select="*",$limit=10,$offset=0){
        $this->where(array('userId'=>$uid));
        $this->where('(YL_user_reg_num.status IN(0,2,3,4,5))');
        $this->select($select);
        $this->join('YL_doctor_info','YL_doctor_info.uid=YL_user_reg_num.docId','left');
        $this->join('YL_hospital','YL_doctor_info.hid=YL_hospital.hid','left');
        $this->join('YL_doctor_offices','YL_doctor_info.officeId=YL_doctor_offices.id','left');
        $this->order_by(array('dateline'=>'DESC'));
        $this->limit($limit);
        $this->offset($offset);
        $res = $this->find_all();
        return $res;
    }


    /**
     * @param $uid
     * @param $id
     * @param string $select
     */
    public function appointDetail($uid,$id,$select="*"){
        $this->where(array('userId'=>$uid));
        $this->select($select);
        $res = $this->find($id);
        return $res;
    }

    /**
     * 取消预约
     * @param $uid
     * @param $id
     */
    public function appointCancel($uid,$id){
        /*开始事务*/
        $this->db->trans_begin();
        $currentTime = time();
        $orderInfo = $this->find($id);
        if($orderInfo['status'] != 2){  // 当前订单不是已付款的状态
            exit(json_encode(array('code'=>-1,'msg'=>'订单状态异常','data'=>null)));
        }

        /*修改订单状态*/
        $where = array('userId'=>$uid,'id'=>$id);
        $data = array('cancelTime'=>$currentTime,'status'=>6);
        $this->update($where,$data);

        $sevenDay = 7*86400;
        $threeDay = 3*86400;
        $appointTime = intval($orderInfo['appointTime']);
        $diffTime = $appointTime - $currentTime;
        /*退款处理 -- 提前1个星期退全额,提前3天退一半,提前1天不退,退到余额*/
        if($diffTime >= $sevenDay){ // 退全款
            $this->db->query('UPDATE YL_money set `amount`=`amount`+'.$orderInfo['price'].',`updateTime`='.$currentTime.' WHERE `uid`='.$orderInfo['userId']);
            if($this->db->affected_rows() == 0){
                $this->db->insert('money',array('uid'=>$orderInfo['userId'],'amount'=>$orderInfo['price'],'updateTime'=>$currentTime));
            }
        }elseif($diffTime >= $threeDay && $diffTime < $sevenDay){  // 退一半
            $backMoney = bcmul(floatval($orderInfo['price']),0.5,2);
            $this->db->query('UPDATE YL_money set `amount`=`amount`+'.$backMoney.',`updateTime`='.$currentTime.' WHERE `uid`='.$orderInfo['userId']);
            if($this->db->affected_rows() == 0){
                $this->db->insert('money',array('uid'=>$orderInfo['userId'],'amount'=>$backMoney,'updateTime'=>$currentTime));
            }
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            /*执行成功后查询数据返回*/
            $this->select('YL_hospital.name as hosName,YL_doctor_offices.officeName,YL_user_reg_num.docName,FROM_UNIXTIME(YL_user_reg_num.cancelTime) AS cancelTime');
            $this->where(array('YL_user_reg_num.id'=>$id));
            $this->join('YL_doctor_info','YL_doctor_info.uid=YL_user_reg_num.docId','left');
            $this->join('YL_doctor_offices','YL_doctor_offices.id=YL_doctor_info.officeId','left');
            $this->join('YL_hospital','YL_hospital.hid=YL_doctor_info.hid','left');
            $result = $this->find_all();
            return $result;
        }
    }

    /**
     * 根据id获取一条数据
     * @param $oid
     */
    public function getDetailById($oid){
        return $this->find_by(array('id'=>$oid));
    }

    /**
     *
     * @param $oid
     * @param $data
     */
    public function reAppoint($oid,$data){
        $where = array('id'=>$oid);
        $res = $this->update($where,$data);
        return $res;
    }


    /**
     * 医生首页待处理的预约
     * @param $docId
     */
    public function doctorIndex($docId,$state=' =2 '){
        $docId = intval($docId);
        $sql = <<<SQL
SELECT
	r.dateline,
	r.id,
	u.nickname,
	r.contacts,
	CASE
WHEN r.sex = 1 THEN
	'男'
WHEN r.sex = 2 THEN
	'女'
END AS sex,
 FLOOR(
	(
		UNIX_TIMESTAMP() - r.appointBrithday
	) / 31536000
) AS age,
 r.appointTel,
 FROM_UNIXTIME(r.appointTime) AS appointDate,
 h.situation,
'预约' as type
FROM
	YL_user_reg_num AS r
LEFT JOIN YL_user AS u ON r.userId = u.uid
LEFT JOIN YL_user_illness_history AS h ON r.illnessId = h.illId
WHERE
	r.docId = {$docId}
AND r.`status` {$state}
SQL;
        $query = $this->db->query($sql);
        $res = $query->result_array();

        return $res;
    }



    /**
     * 获取医生端预约列表
     * @param $docId
     * @param string $select
     * @param int $flag
     * @return array
     */
    public function getDoctorRegList($docId,$select="*",$flag=1,$limit=10,$offset=0){
        $this->where(array('docId'=>$docId));
        switch($flag){
            case 1:   //未完成预约列表
                $this->where('(YL_user_reg_num.status IN(3))');
                break;
            case 2:   //已完成预约列表
                $this->where(array('YL_user_reg_num.status'=>5));
                break;
            default:
                exit(json_encode(array('code'=>305,'msg'=>"flag参数非法",array())));
        }
        $this->join('YL_user','YL_user.uid=YL_user_reg_num.userId','left');
        $this->join('YL_user_illness_history','YL_user_illness_history.illId=YL_user_reg_num.illnessId','left');
        $this->select($select);
        $this->limit($limit);
        $this->offset($offset);
        $this->order_by('YL_user_reg_num.id','DESC');
        $res = $this->find_all();
        return $res;

    }

    /**
     * 获取预约详情医生端
     * @param $id
     *
     */
    public function getDoctorRegDetail($id,$select='*'){
        $this->select($select);
        $this->join('YL_user','YL_user.uid=YL_user_reg_num.userId','left');
        $this->join('YL_user_illness_history','YL_user_illness_history.illId=YL_user_reg_num.illnessId','left');
        $res = $this->find($id);
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