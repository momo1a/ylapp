<?php
/**
 * 订单model
 * User: momo1a@qq.com
 * Date: 2016/8/13 0013
 * Time: 下午 7:39
 */

class Order_model extends MY_Model
{


    public static $table_name = 'order';

    public function __construct(){
        parent::__construct();
    }




    /**
     * 添加订单
     * @param $data
     */
    public function addOrder($data){
        $this->insert($data);
        return $this->db->insert_id();
    }
}