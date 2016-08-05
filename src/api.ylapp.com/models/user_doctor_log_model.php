<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class
 */
class User_doctor_log_model extends MY_Model
{
    public static $table_name = 'user_doctor_log';

    public function __construct(){
        parent::__construct();
    }

    public function getIndexScrollLog($uid=0){
        $where = '(comType=1 AND comState=1) OR (comType=2 AND comState=4) OR (comType=3 AND comState=6)';
        $this->where($where);
        if($uid != 0){
            $this->where('userId !=', $uid);
        }
        $this->limit(500);
        $this->order_by('dateline','desc');
        $res = $this->find_all();
        return $res;
    }
}
