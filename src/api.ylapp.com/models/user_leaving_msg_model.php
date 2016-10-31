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
    public function getMsgList($uid,$select,$limit=10,$offset=0,$state='(state IN(2,4))'){
        $this->where(array('askerUid'=>$uid));
        $this->where($state);
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
    public function doctorIndex($docId,$state=' in(2,3)',$limit=1000,$offset=0){
        $docId = intval($docId);
        $sql = <<<SQL
SELECT
	m.askTime as dateline,
	m.id,
	u.nickname,
	(CASE  WHEN ill.sex=1 THEN '男' WHEN ill.sex=2 THEN '女' END ) AS sex,
	ill.age,
	m.price,
	m.askerContent,
	m.img,
	/*(IF(m.state=4,'完成','未完成')) AS state,*/
	(CASE WHEN m.state=3 THEN '未通过' WHEN m.state=2 THEN '待回答' WHEN m.state=4 THEN '完成' END ) AS state,
	'留言问诊' as type
FROM
	YL_user_leaving_msg AS m
LEFT JOIN YL_user AS u ON m.askerUid = u.uid
LEFT JOIN YL_user_illness_history AS ill ON m.illId=ill.illId
LEFT JOIN YL_doctor_reply AS r ON r.themeId=m.id AND r.type=1
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
                YL_user_illness_history.situation,
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
            YL_user_illness_history.situation,
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