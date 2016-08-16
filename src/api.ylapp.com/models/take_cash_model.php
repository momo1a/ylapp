<?php
/**
 * 提现model
 * User: momo1a@qq.com
 * Date: 2016/8/16
 * Time: 9:06
 */

class  Take_cash_model extends MY_Model
{
    public static $table_name = 'take_cash';


    /**
     * 申请提现
     * @param $data
     */
    public function addCash($data){
        $this->insert($data);
        return $this->db->insert_id();
    }
}