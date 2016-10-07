<?php
/**
 * 用户金额model.
 * User: Administrator
 * Date: 2016/8/12
 * Time: 9:11
 */

class Money_model extends MY_Model
{
    public static $table_name = 'money';

    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取用户金额
     * @param $uid
     */
    public function getUserMoney($uid){
        $uid = intval($uid);
        $this->where(array('uid'=>$uid));
        $this->select('amount');
        $res = $this->find_all();
        return $res;
    }

    /**
     * 用户余额变更
     * @param $uid
     * @param $amount
     */
    public function updateUserMoney($uid,$amount){
        $res = $this->db->query('UPDATE YL_money SET `amount`=`amount`-'.$amount.',`updateTime`='.time().' WHERE uid='.$uid);
        return $res;
    }

    /**
     * 订单支付
     * @param $uid
     * @param $amount
     * @param $oid
     * @param $orderType
     */
    public function orderPay($uid,$amount,$oid,$orderType){
        // ２疫苗接种支付，３基因检测支付，４电话问诊支付，５在线问答支付，６预约挂号支付'
        $this->db->trans_begin();

        $this->updateUserMoney($uid,$amount);   //  扣除用户费用

        $tradeData = array(
            'uid'=> $uid,
            'userType' => 1,
            'tradeVolume' => $amount,
            'tradeChannel' => 0,
            'dateline' => time(),
        );

        switch(intval($orderType)){
            case 2 :
            case 3 :
                $this->db->query('UPDATE YL_order SET `status`=2 WHERE oid='.intval($oid));  // 修改订单状态
                $tradeData['tradeDesc'] =  $orderType == 2 ? '疫苗付款' : '基因付款';
                $tradeData['tradeType'] =  $orderType == 2 ?  3  : 4;
                $this->db->insert('trade_log',$tradeData);
                $tradeData['oid'] = $oid;
                $tradeData['status'] = 1;
                $this->db->insert('pay',$tradeData);  // 支付表
                break;
            case 4 :  // 电话问诊
                $this->db->query('UPDATE YL_user_phone_diagnosis SET `state`=1 WHERE id='.intval($oid));
                $tradeData['tradeDesc'] = '电话问诊付款';
                $tradeData['tradeType'] = 5;
                $this->db->insert('trade_log',$tradeData);
                $tradeData['oid'] = $oid;
                $tradeData['status'] = 1;
                $this->db->insert('pay',$tradeData);  // 支付表
                break;
            case 5 :  // 留言问诊
                $this->db->query('UPDATE YL_user_leaving_msg SET `state`=2 WHERE id='.intval($oid));
                $tradeData['tradeDesc'] = '留言问诊付款';
                $tradeData['tradeType'] = 6;
                $this->db->insert('trade_log',$tradeData);
                $tradeData['oid'] = $oid;
                $tradeData['status'] = 1;
                $this->db->insert('pay',$tradeData);  // 支付表
                break;

            case 6 : // 预约挂号

                $this->db->query('UPDATE YL_user_reg_num SET `status`=2 WHERE id='.intval($oid));
                $tradeData['tradeDesc'] = '预约挂号付款';
                $tradeData['tradeType'] = 7;
                $this->db->insert('trade_log',$tradeData);
                $tradeData['oid'] = $oid;
                $tradeData['status'] = 1;
                $this->db->insert('pay',$tradeData);  // 支付表
                break;
            default :
                return false;
        }

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        } else{
            $this->db->trans_commit();
            return true;
        }
    }

}