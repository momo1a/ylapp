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
    public function getMsgList($uid,$select){
        $this->where(array('askerUid'=>$uid));
        $this->select($select);
        $this->order_by(array('askTime'=>'DESC'));
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

}