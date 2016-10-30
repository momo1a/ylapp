<?php
/**
 * 帖子model.
 * User: momo1a@qq.com
 * Date: 2016/8/14 0014
 * Time: 下午 1:55
 */

class Rolling_msg_model extends MY_Model
{

    public static $table_name = 'rolling_msg';


    public function __construct(){
        parent::__construct();
    }

    // 列表
    public function msgList($limit=10,$offset=0){
        $this->limit($limit);
        $this->offset($offset);
        $this->order_by(array('id'=>'desc'));
        return $this->find_all();
    }

    // 统计
    public function msgCount(){
        return $this->count_all();
    }

    // 添加消息
    public function add($data){
        $this->insert($data);
        return $this->db->insert_id();
    }
}