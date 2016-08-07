<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 病历备注表模型
 */
class User_illness_history_remarks_model extends MY_Model
{
    public static $table_name = 'user_illness_history_remarks';

    /**
     * 获取对应用户对应病历id的remark记录
     * @param $IllId
     * @param $uid
     */
    public function getRemarksByIllIdAndUid($IllId,$uid){
        $where = array('illId'=>$IllId,'uid'=>$uid);
        $res = $this->find_all_by($where);
        return $res;
    }
}
