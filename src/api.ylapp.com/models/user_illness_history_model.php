<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 病历表模型
 */
class User_illness_history_model extends MY_Model
{
    public static $table_name = 'user_illness_history';

    /**
     * 添加病历
     * @param $data
     * @return bool|int|mixed
     */
    public function addIllness($data){
        $res = $this->insert($data);
        return $res;
    }

    /**
     * 获取病历列表
     * @param $uid
     */
    public function illnessList($uid,$select='*',$limit=20,$offset=0){
        $this->select($select);
        $where = array('uid'=>$uid);
        $this->limit($limit);
        $this->offset($offset);
        $res = $this->find_all_by($where);
        return $res;
    }

    /**
     * 获取病历详情
     * @param $uid
     * @param $illId
     */
    public function getIllnessDetail($uid,$illId){
        $where = array('uid'=>$uid,'illId'=>$illId);
        $res = $this->find_by($where);
        return $res;
    }

    /**
     * @param $uid
     * @param $illId
     */
    public function editIllness($uid,$illId,$data){
        $where = array('uid'=>$uid,'illId'=>$illId);
        $res = $this->update($where,$data);
        return $res;
    }
}
