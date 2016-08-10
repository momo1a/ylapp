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
}