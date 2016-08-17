<?php
/**
 * 反馈模型
 * User: momo1a@qq.com
 * Date: 2016/8/17 0017
 * Time: 下午 8:28
 */

class Feedback_model extends MY_Model
{

    public static $table_name = 'feedback';


    public function __construct(){
        parent::__construct();
    }

    /**
     * 添加反馈
     * @param $data
     */
    public function addFeedback($data){
        $this->insert($data);
        return $this->db->insert_id();
    }
}