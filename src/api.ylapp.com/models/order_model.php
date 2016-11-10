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

    /**
     * 获取用户订单
     * @param $uid
     * @param $select
     * @param $type 订单类型默认
     */
    public function getOrdersByUid($uid,$select,$type=1){
        $this->where(array('YL_order.buyerId'=>$uid,'YL_order.type'=>$type));
        switch($type){
            case 1:
                $this->join('YL_vaccinum','YL_order.packageId=YL_vaccinum.id','left');
                break;
            case 2:
                $this->join('YL_gene_check','YL_order.packageId=YL_gene_check.id','left');
                break;
            default:
                break;
        }

        $this->order_by(array('YL_order.oid'=>'desc'));
        $this->select($select);
        $res = $this->find_all();
        return $res;
    }


    /**
     * @param $uid
     * @param $select
     * @return array
     */
    public function getOrdersMsg($uid,$select){
        $this->where(array('YL_order.buyerId'=>$uid));
        $this->select($select);
        $res = $this->find_all();
        return $res;
    }

    /**
     * 获取订单
     * @param $oid
     */
    public function getOrderById($oid){
        return $this->find_by(array('oid'=>$oid));
    }

    /**
     * 取消订单
     * @param $oid
     * @return bool
     */
    public function orderCancel($oid){
        $where = array('id'=>$oid);
        return $this->delete_where($where);
    }
}