<?php
/**
 *
 * 交易日志
 * User: momo1a@qq.com
 * Date: 2016/8/13 0013
 * Time: 下午 10:55
 */

class Common_trade_log_model extends YL_Model
{
    public static $table_name = 'trade_log';

    /**
     * 保存日志
     * @param $data
     */
    public function saveLog($data){
        $res = $this->insert($data);
        return $res;
    }

    /**
     * 获取对应用户的交易记录
     * @param $uid
     * @param $select
     * @param $status 默认获取交易成功的记录
     */
    public function getListByUid($uid,$select='*',$status=1){
        $this->where(array('uid'=>$uid,'status'=>$status));
        $this->select($select);
        $this->order_by('dateline','DESC');
        $res = $this->find_all();
        return $res;
    }
}