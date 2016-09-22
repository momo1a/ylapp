<?php
/**
 * 用户留言问答model
 * User: momo1a@qq.com
 * Date: 2016/8/11
 * Time: 11:02
 */

class User_leaving_msg_model extends MY_Model
{
    public static $table_name = 'user_leaving_msg';

    public function __construct(){
        parent::__construct();
    }

    /**
     * 用户留言提交
     * @param $data
     */
    public function commitFirst($data){
        $this->insert($data);
        return $this->db->insert_id();
    }


    /**
     *
     * @param $uid
     * @param $select
     *
     */
    public function getMsgList($uid,$select='*',$limit=10,$offset=0,$userType = 1){
        if($userType == 1){
            $this->where(array('askerUid'=>$uid));
        }else{
            $this->where(array('docId'=>$uid));
        }
        $this->join('YL_user as doc','YL_user_leaving_msg.docId=doc.uid','left');
        $this->join('YL_user','YL_user_leaving_msg.askerUid=YL_user.uid','left');
        $this->join('YL_doctor_info','YL_user_leaving_msg.docId=YL_doctor_info.uid','left');
        $this->join('YL_hospital','YL_hospital.hid=YL_doctor_info.hid','left');
        $this->join('YL_doctor_reply','YL_doctor_reply.themeId=YL_user_leaving_msg.id and YL_doctor_reply.type=1','left');
        $this->select($select);
        $this->order_by(array('askTime'=>'DESC'));
        $this->limit($limit);
        $this->offset($offset);
        $res = $this->find_all();
        return $res;
    }


    /**
     * 问答详情显示给用户
     */

    public function getMsgDetail($uid,$id,$select){
        $this->where(array('askerUid'=>$uid));
        $this->select($select);
        $res = $this->find($id);
        return $res;

    }


    /**
     * 医生首页
     * @param $docId
     */
    public function doctorIndex($docId,$state=' =2 ',$limit=10,$offset=0){
        $docId = intval($docId);
        $sql = <<<SQL
SELECT
	m.askTime as dateline,
	m.id,
	u.nickname,
	u.sex,
	FLOOR((UNIX_TIMESTAMP()-u.birthday)/31536000) AS age,
	m.price,
	m.askerContent,
	m.img,
	'留言问诊' as type
FROM
	YL_user_leaving_msg AS m
LEFT JOIN YL_user AS u ON m.askerUid = u.uid

WHERE
	m.state {$state}
AND m.docId = {$docId}
LIMIT {$offset},{$limit}
SQL;
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res;
    }

    /**
     * 详情显示给医生
     * @param $docId
     * @param $id  问诊id
     * @param $isComplete 是否完成 默认获取的是未完成的
     *
     */
    public function detail($docId,$id,$isComplete=false){
        $docId = intval($docId);
        $id = intval($id);
        if($isComplete){   //  如果是完成状态下的详情
            $this->where(array('YL_doctor_reply.type'=>1,'YL_doctor_reply.state'=>1)); /*回答留言类型，审核已经通过*/
            $this->select(
                'YL_user_leaving_msg.id,
                YL_user.nickname,
                (CASE WHEN YL_user_illness_history.sex=1 THEN "男" WHEN YL_user_illness_history.sex=2 THEN "女" END) as sex,
                YL_user_illness_history.age,
                YL_user_illness_history.realname,
                YL_user_illness_history.allergyHistory,
                YL_user_illness_history.stages,
                YL_user_illness_history.result,
                YL_user_leaving_msg.askerContent,
                YL_user_leaving_msg.img,
                YL_doctor_reply.replyContent
                '
            );

        }else{          //   如果是未完成状态下的详情
            $this->select(
                'YL_user_leaving_msg.id,
            YL_user.nickname,
            (CASE WHEN YL_user_illness_history.sex=1 THEN "男" WHEN YL_user_illness_history.sex=2 THEN "女" END) as sex,
            YL_user_leaving_msg.askerNickname,
            YL_user_leaving_msg.askerContent,
            YL_user_illness_history.age,
            YL_user_leaving_msg.img'
            );
        }
        $this->where(array('YL_user_leaving_msg.docId'=>$docId,'YL_user_leaving_msg.id'=>$id));
        $this->join('YL_user','YL_user.uid=YL_user_leaving_msg.askerUid','left');
        $this->join('YL_user_illness_history','YL_user_illness_history.illId=YL_user_leaving_msg.illid','left');
        $this->join('YL_doctor_reply','YL_doctor_reply.themeId=YL_user_leaving_msg.id','left');
        $res = $this->find_all();
        return $res;
    }


    /**
     * 根据留言id获取留言信息
     * @param $id
     */
    public function getLeavMsgInfo($id,$select){
        $this->select($select);
        $res = $this->find($id);
        return $res;
    }

    /**
     * 更改状态
     * @param $id
     */
    public function updateStatusById($id,$state){
        $where = array('id'=>$id);
        $data = array('state'=>$state);
        $res = $this->update($where,$data);
        return $res;
    }
}