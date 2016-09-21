<?php
/**
 * 医生费用设置model
 * User: momo1a@qq.com
 * Date: 2016/8/10
 * Time: 10:35
 */

class Doctor_fee_setting_model extends MY_Model
{
    public static $table_name = 'doctor_fee_seting';

    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取医生的费用设置
     * @param $docId
     */
    public function getFeeSettingByUid($docId){
        $where = array('docId'=>$docId);
        $res = $this->find_by($where);
        return $res;
    }

    /**
     * 保存医生费用设置
     * @param $docId
     * @param $data
     */
    public function saveDoctorFee($docId,$data){
        $where = array('docId'=>$docId);
        if($this->update($where,$data)){
            return $this->db->affected_rows();
        }else{
            $this->insert($data);
            return $this->db->insert_id();
        }
    }
}