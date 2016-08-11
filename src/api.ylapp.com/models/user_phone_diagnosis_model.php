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

    public function __construct(){
        parent::__construct();
    }

    /**
     * 在线问诊预约
     */
    public function commitRecord($data){
        $this->insert($data);
        $res = $this->db->insert_id();
        return $res;
    }
}