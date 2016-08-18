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

    public function getIndexScrollLog($uid=0,$select='*'){
        if($uid != 0){
            $where = ' AND l.userId !='.$uid;
        }
        $sql = <<<SQL
        SELECT  {$select} FROM YL_user_doctor_log as l  LEFT JOIN  YL_user as u ON l.userId=u.uid LEFT JOIN YL_user as d ON l.doctorId=d.uid  WHERE ((l.comType=1 AND l.comState=4) OR (l.comType=2 AND l.comState=3) OR (l.comType=3 AND l.comState=5)) {$where} ORDER BY l.id DESC LIMIT 500
SQL;
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res;
    }
}
