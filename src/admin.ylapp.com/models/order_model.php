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
     * 订单列表
     * @param int $limit
     * @param int $offset
     * @param string $keyword
     * @param int $state
     * @param int $type
     */
    public function orderList($limit=10,$offset=0,$keyword='',$state=0,$type=0,$select='*'){
        if($keyword != ''){
            $this->like(array('buyerName'=>$keyword));
        }
        if($state != 0){
            $this->where(array('YL_order.status'=>$state));
        }
        if($type != 0){
            $this->where(array('type'=>$type));
        }
        $this->select($select);
        $this->join('YL_user as u','u.uid=YL_order.buyerId','left');
        $this->limit($limit);
        $this->offset($offset);
        $res = $this->find_all();
        $this->order_by(array('dateline'=>'desc'));
        return $res;
    }


    public function orderCount($keyword='',$state=0,$type=0){
        if($keyword != ''){
            $this->like(array('buyerName'=>$keyword));
        }
        if($state != 0){
            $this->where(array('status'=>$state));
        }
        if($type != 0){
            $this->where(array('type'=>$type));
        }

        return $this->count_all();
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
     * 更改订单状态
     * @param $oid
     * @param $status
     */
    public function settingStatus($oid,$status){
        $where = array('oid'=>$oid);
        $updateData = array('status'=>$status);
        $currentTime = time();
        /*开始事务*/
        $this->db->trans_begin();

        $this->update($where,$updateData);  // 更新状态
        $orderInfo = $this->select('*')->join('YL_user as u','u.uid=YL_order.buyerId','left')->find_by($where);
        switch(intval($orderInfo['type'])){
            case 1:
                $tradeDesc = '疫苗接种成功购买';
                $tradeType = 3;
                break;
            case 2:
                $tradeDesc = '基因检测成功购买';
                $tradeType = 4;
                break;
            default:
                $tradeDesc = '未知';
        }
        /*交易记录数据*/
        $insertData = array(
            'uid'=>$orderInfo['buyerId'],
            'userType'=>$orderInfo['userType'],
            'tradeVolume'=>$orderInfo['price'],
            'tradeDesc'=>$tradeDesc,
            'tradeChannel'=>0,
            'dateline'=>$currentTime,
            'status'=>1,
            'tradeType'=>$tradeType
        );
        if($status == 4) {
            $this->db->insert('trade_log', $insertData); // 修改交易记录
        }

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
    }
}