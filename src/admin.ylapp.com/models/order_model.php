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
     * 获取用户所有订单
     * @param $uid
     * @param string $select
     * @param int $limit
     * @param int $offset
     *
     */
    public function getAllOrder($uid,$select='*',$limit=10,$offset=0){
        $this->where(array('buyerId'=>$uid));
        $this->select($select);
        $this->join('YL_vaccinum','YL_order.packageId=YL_vaccinum.id and YL_order.type=1','left');
        $this->join('YL_gene_check','YL_order.packageId=YL_gene_check.id and YL_order.type=2','left');
        $this->limit($limit);
        $this->offset($offset);
        $this->order_by('YL_order.dateline','desc');
        $res = $this->find_all();
        return $res;
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
}