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
    public function getListByUid($uid, $select = "*")
    {
        $this->where(array('askUid' => $uid));
        $this->select($select);
        $this->order_by('askTime', 'DESC');
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
	d.phoneTimeLen,
	FROM_UNIXTIME(d.hopeCalldate) AS callDate,
	d.askContent,
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

}