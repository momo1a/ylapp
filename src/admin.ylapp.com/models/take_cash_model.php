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
     * 提现列表
     * @param string $keyword
     * @param int $userType
     * @param int $status
     * @param int $limit
     * @param int $offset
     */
    public function cashList($keyword='',$userType=0,$status = -1,$limit=10,$offset=0){
        if($keyword != ''){
            $this->like(array('realName'=>$keyword));
        }
        if($status != -1){
            $this->where(array('status'=>$status));
        }
        if($userType != 0){
            $this->where(array('userType'=>$userType));
        }
        $this->limit($limit);
        $this->offset($offset);
        $this->order_by(array('dateline'=>'desc'));
        $res = $this->find_all();
        return $res;
    }

    /**
     * 列表统计
     * @param string $keyword
     * @param int $userType
     * @param int $status
     */
    public function cashCount($keyword='',$userType=0,$status = -1){
        if($keyword != ''){
            $this->like(array('realName'=>$keyword));
        }
        if($status != -1){
            $this->where(array('status'=>$status));
        }
        if($userType != 0){
            $this->where(array('userType'=>$userType));
        }
        $count = $this->count_all();
        return $count;
    }

    /**
     * 设置状态
     * @param $tid
     * @param $status
     */
    public function settingStatus($tid,$status){
        $where = array('id'=>$tid);
        $updateData = array('status'=>$status);
        $currentTime = time();
        /*开始事务*/
        $this->db->trans_begin();

        $this->update($where,$updateData);  // 更新状态
        $takeInfo = $this->select('*')->find_by($where);

        /*交易记录数据*/
        $insertData = array(
            'uid'=>$takeInfo['uid'],
            'userType'=>$takeInfo['userType'],
            'tradeVolume'=>$takeInfo['amount'],
            'tradeDesc'=>'提现',
            'tradeChannel'=>0,
            'dateline'=>$currentTime,
            'status'=>$status,
            'tradeType'=>0
        );
        $this->db->insert('trade_log',$insertData); // 修改交易记录

        //如果交易失败金额返回用户余额

        if($status == 2){
            $this->db->query('UPDATE YL_money set `amount`=`amount`+'.$takeInfo['amount'].',`updateTime`='.$currentTime.' WHERE `uid`='.$takeInfo['uid']);
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

        //return $return;
    }

    //  获取管理员未处理的
    public function getNotDeal($select='*'){
        $this->select($select,false);
        $this->where(array('status'=>0));
        return $this->find_all();
    }
}