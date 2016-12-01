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
     * 留言问答管理列表
     * @param string $keyword
     * @param int $isReply  1 已经回答 0 未回答
     * @param int $state
     * @param int $limit
     * @param int $offset
     */
    public function msgList($keyword = '',$isReply = -1 ,$state = -1,$limit = 10 ,$offset = 0,$select='*'){
        if($keyword != ''){
            $this->like(array('YL_user_leaving_msg.askerNickname'=>$keyword));  // 医生已经回答
        }
        switch(intval($isReply)){
            case 1:
                $this->where(array('YL_user_leaving_msg.state'=>5));  // 医生已经回答
                break;
            case 0:
                $this->where(array('YL_user_leaving_msg.state'=>2));  // 未回答显示到医生端的
                break;
            default:
                break;
        }

        if($state != -1){
            $this->where(array('YL_user_leaving_msg.state'=>$state));
        }

        $this->join('YL_user as doc','YL_user_leaving_msg.docId=doc.uid','left');
        $this->join('YL_user','YL_user_leaving_msg.askerUid=YL_user.uid','left');
        $this->join('YL_user_illness_history','YL_user_illness_history.illId=YL_user_leaving_msg.illId','left');
        $this->join('YL_doctor_info','YL_user_leaving_msg.docId=YL_doctor_info.uid','left');
        $this->join('YL_hospital','YL_hospital.hid=YL_doctor_info.hid','left');
        $this->join('YL_doctor_offices','YL_doctor_info.officeId=YL_doctor_offices.id','left');
        $this->join('YL_doctor_reply','YL_doctor_reply.themeId=YL_user_leaving_msg.id and YL_doctor_reply.type=1','left');
        $this->select($select);
        $this->order_by(array('askTime'=>'DESC'));
        $this->limit($limit);
        $this->offset($offset);
        $res = $this->find_all();
        return $res;
    }


    /**
     * 留言问答统计
     * @param string $keyword
     * @param int $isReply
     * @param int $state
     */
    public function msgCount($keyword = '',$isReply = -1 ,$state = -1){
        if($keyword != ''){
            $this->like(array('askerNickname'=>$keyword));  // 医生已经回答
        }
        switch(intval($isReply)){
            case 1:
                $this->where(array('state'=>5));  // 医生已经回答
                break;
            case 0:
                $this->where(array('state'=>2));  // 未回答显示到医生端的
                break;
            default:
                break;
        }

        if($state != -1){
            $this->where(array('state'=>$state));
        }


        return $this->count_all();
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

        $this->update($where,$updateData);  // 更新订单状态

        $orderInfo = $this->select('*,d.nickname as docName,u.nickname as userName,YL_user_leaving_msg.price as leavingFee,YL_user_leaving_msg.docId as doctorId')
            ->join('YL_user as d','d.uid=YL_user_leaving_msg.docId','left')
            ->join('YL_user as u','u.uid=YL_user_leaving_msg.askerUid','left')
            ->join('YL_doctor_fee_seting as s','s.docId=YL_user_leaving_msg.docId','left')
            ->find_by($where);
        switch(intval($status)){
            case 3:
                $tradeDesc = '留言问答失败';
                $stat = 2;
                break;
            case 4:
                $tradeDesc = '留言问答成功';
                $stat = 1;
                break;
            default:
                $tradeDesc = '未知';
        }
        /*交易记录数据*/
        $insertData = array(
            'uid'=>$orderInfo['askerUid'],
            'userType'=>1,
            'tradeVolume'=>$orderInfo['price'],
            'tradeDesc'=>$tradeDesc,
            'tradeChannel'=>0,
            'dateline'=>$currentTime,
            'status'=>$stat,
            'tradeType'=>5
        );
        // 成功在付款后记录
        if($status == 3) {
            $this->db->insert('trade_log', $insertData); //  交易记录
        }

        if($status == 3){
             // 退款到用户钱包
           /* $this->db->query('UPDATE YL_money set `amount`=`amount`+'.$orderInfo['price'].',`updateTime`='.$currentTime.' WHERE `uid`='.$orderInfo['askerUid']);
            if($this->db->affected_rows() == 0){
                $this->db->insert('money',array('uid'=>$orderInfo['askerUid'],'amount'=>$orderInfo['price'],'updateTime'=>$currentTime));
            }*/

            $this->db->query('UPDATE YL_doctor_reply SET `state`=2 WHERE `type`=1 AND `themeId`='.$oid); // 修改医生回复表状态
        }

        if($status == 4){  // 完成需要付钱给医生

            // todo
            $this->db->query('UPDATE YL_doctor_reply SET `state`=1 WHERE `type`=1 AND `themeId`='.$oid); // 修改医生回复表状态

            // 预约费用款项分配到医生钱包
            if(!$orderInfo['leavMsgPer']){
                $orderInfo['leavMsgPer'] = 0;
            }
            $docGetFee = bcmul($orderInfo['leavingFee'],$orderInfo['leavMsgPer']/100,2);  //  医生获得费用
            $this->db->query('UPDATE YL_money set `amount`=`amount`+'.$docGetFee.',`updateTime`='.$currentTime.' WHERE `uid`='.$orderInfo['doctorId']);
            if($this->db->affected_rows() == 0){
                $this->db->insert('money',array('uid'=>$orderInfo['doctorId'],'amount'=>$docGetFee,'updateTime'=>$currentTime));
            }

            //  trade log 表
            $tradeLog = array(
                'uid' => $orderInfo['doctorId'] ,
                'userType' => 2,
                'tradeVolume' => $docGetFee,
                'tradeDesc'=> '留言问答收入',
                'tradeChannel'=> 0,
                'dateline'=>time(),
                'status'=>1,
                'tradeType'=>9,
            );

            $this->db->insert('trade_log', $tradeLog);

        }


        $docUserLog = array(
            'userId' => $orderInfo['askerUid'],
            'doctorId' => $orderInfo['doctorId'],
            'comType'=>1,
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

    //  获取管理员未处理的
    public function getNotDeal($select='*'){
        $this->select($select,false);
        $this->where(array('state'=>5));
        return $this->find_all();
    }
}