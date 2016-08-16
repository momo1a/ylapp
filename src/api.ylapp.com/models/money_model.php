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

}