<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 病历表模型
 */
class User_illness_history_model extends MY_Model
{
    public static $table_name = 'user_illness_history';


    /**
     * 获取用户病历
     * @param $uid
     */
    public function getUserIll($uid){
        $this->where(array('uid'=>$uid));
        $res = $this->find_all();
        return $res;
    }
}
