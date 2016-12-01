<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User: momo1a@qq.com
 * Date: 2016/8/5
 * Time: 14:50
 */
class Doctor_offices_model extends MY_Model
{
    public static $table_name = 'doctor_offices';

    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取所有科室
     */

    public function getAllOffices($select='id as officeId,officeName'){
        $this->select($select);
        $res = $this->find_all();
        return $res;
    }


    public function save($id,$data){
        if($id == 0){
            $this->insert($data);
        }else{
            $where = array('id'=>$id);
            $this->update($where,$data);
        }

        return $this->db->affected_rows();
    }
}