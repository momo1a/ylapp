<?php
/**
 * 药品预约模型
 * User: momo1a@qq.com
 * Date: 2016/10/17
 * Time: 10:16
 */

class   Medi_appoint_model extends MY_Model
{

    public static $table_name = 'medi_appoint';


    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取消息
     * @param int $userType 默认伙计（医生端）
     * @param $select
     */
    public function getMsg($userType=2,$uid){

        switch($userType){
            case 1:
                $sql = <<<SQL
SELECT appoint.name as appointPersonName,appoint.id AS appointId,FROM_UNIXTIME(appoint.allotTime) AS dateline,g.nickname AS guysName,FROM_UNIXTIME(appoint.appointTime) AS appointTime,medi.id as medicineId,medi.name as medicineName,medi.thumbnail,appoint.telephone AS appoPersonTel,'药品分配' AS msgType
FROM YL_medi_appoint as appoint
LEFT JOIN YL_medicine AS  medi ON appoint.mediId=medi.id
LEFT JOIN YL_user AS  g ON appoint.guysId=g.uid
LEFT JOIN YL_user AS  u ON appoint.userId=u.uid
LEFT JOIN YL_doctor_info AS i ON i.uid=appoint.guysId
WHERE appoint.userId={$uid} AND appoint.state=1
SQL;
                break;
            case 2:
                $sql = <<<SQL
SELECT appoint.name as appointPersonName,appoint.id AS appointId,appoint.allotTime AS dateline,u.nickname AS appointName,FROM_UNIXTIME(appoint.appointTime) AS appointTime,medi.name as medicineName,medi.thumbnail,appoint.telephone AS appoPersonTel,'药品分配' AS type
FROM YL_medi_appoint as appoint
LEFT JOIN YL_medicine AS  medi ON appoint.mediId=medi.id
LEFT JOIN YL_user AS  g ON appoint.guysId=g.uid
LEFT JOIN YL_user AS  u ON appoint.userId=u.uid
WHERE appoint.guysId={$uid} AND appoint.state=1
SQL;
                break;
            default:
                break;
        }
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res;
    }


    public function getAppointDetail($appointId,$guysId){
        /*预约人、药品名称、电话、时间、图文详情 */
        $this->select('medi_appoint.name as appointPersonName,medicine.name as medicineName,medi_appoint.telephone AS appoPersonTel,FROM_UNIXTIME(YL_medi_appoint.appointTime) AS appointTime,medi_appoint.content as appointContent');
        $this->join('medicine','medicine.id=medi_appoint.mediId','left');
        $where = array('medi_appoint.id'=>$appointId,'medi_appoint.guysId'=>$guysId);
        return $this->find_by($where);

    }
}