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
    public function appointList($uid,$select="*"){
        $this->where(array('userId'=>$uid));
        $this->select($select);
        $this->join('YL_doctor_info','YL_doctor_info.uid=YL_user_reg_num.docId','left');
        $this->join('YL_hospital','YL_doctor_info.hid=YL_hospital.hid','left');
        $this->order_by(array('dateline'=>'DESC'));
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
        $where = array('userId'=>$uid,'id'=>$id);
        $data = array('cancelTime'=>time(),'status'=>6);
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
'预约' as type
FROM
	YL_user_reg_num AS r
LEFT JOIN YL_user AS u ON r.userId = u.uid
WHERE
	r.docId = {$docId}
AND r.`status` {$state}
SQL;
        $query = $this->db->query($sql);
        $res = $query->result_array();

        return $res;
    }

}