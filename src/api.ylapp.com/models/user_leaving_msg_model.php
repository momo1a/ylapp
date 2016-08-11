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

}