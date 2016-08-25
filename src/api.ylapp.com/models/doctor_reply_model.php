<?php
/**
 * 医生回复留言问诊model
 * User: Administrator
 * Date: 2016/8/10 0010
 * Time: 下午 11:07
 */

class Doctor_reply_model extends MY_Model
{
    public static $table_name = 'doctor_reply';

    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取医生成功回答的总数
     * @param $docId
     */
    public function getDocCompleteAnswerTotal($docId){
        $this->where(array('replyId'=>$docId,'state'=>1));
        $res = $this->count_all();
        return $res;
    }

    /**
     * 获取医生回答通过的对应用户的对应问题
     * @param $uid
     * @param $id
     * @param int $type
     */
    public function getContentByThemeId($uid,$id,$select,$type=1){
        $this->where(array('themeId'=>$id,'userId'=>$uid,'type'=>$type,'state'=>1));
        $this->select($select);
        $res = $this->find_all();
        return $res;
    }

    /**
     * 医生回答问题
     * @param $data
     */
    public function recordAdd($data){
        $this->insert($data);
        return $this->db->insert_id();
    }
}