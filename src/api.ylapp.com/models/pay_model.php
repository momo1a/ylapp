<?php
/**
 * 支付模型类
 * User: momo1a@qq.com
 * Date: 2016/10/6 0006
 * Time: 上午 11:36
 */

class Pay_model extends MY_Model
{
    public static $table_name = 'pay';

    public function __construct(){
        parent::__construct();
    }

    /**
     * 提交支付请求
     * @param $data
     */
    public function submitPay($data){
        /*开始事务*/
        $this->db->trans_begin();

        $this->insert($data);  //  支付表

        $this->db->insert('trade_log',array_diff($data,array('tradeNo'=>$data['tradeNo'],'oid'=>$data['oid']))); // 交易记录表


        // 用户医生日志表

       // $this->db->insert('user_doctor_log',$docUserLog);

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


    /**
     * 依据交易号获取一行
     * @param $tradeNo
     * @return array
     */
    public function getRow($tradeNo){
        $where = array('tradeNo'=>$tradeNo);
        return $this->find_by($where);
    }


    /**
     * 充值修改状态
     * @param $tradeNo 交易号
     * @param $amount  金额
     */
    public function changeRechargeStatus($uid,$tradeNo,$amount){
        /*开始事务*/
        $this->db->trans_begin();
        $currentTime  = time();
        $this->update_where('tradeNo',$tradeNo,array('status'=>1));

        $updateRes =$this->db->query('UPDATE YL_money set `amount`=`amount`+'.$amount.',`updateTime`='.$currentTime.' WHERE `uid`='.$uid);
        if(!$updateRes){
            $this->db->insert('money',array('uid'=>$uid,'amount'=>$amount,'updateTime'=>$currentTime));
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