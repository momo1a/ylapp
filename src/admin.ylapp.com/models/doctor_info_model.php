<?php
/**
 * 医生信息model
 * User: momo1a@qq.com
 * Date: 2016/9/1
 * Time: 10:35
 */

class Doctor_info_model extends MY_Model
{
    public static $table_name = 'doctor_info';

    public function __construct(){
        parent::__construct();
    }


    /**
     * 修改医生状态
     * @param $uid
     */
    public function setDoctorStat($uid,$state){
        $where = array('uid'=>$uid);
        $data = array('state'=>$state);
        $res = $this->update($where,$data);
        return $res;
    }


    /**
     * 获取医生简介
     * @param $uid
     */
    public function getDoctorDetail($uid,$select='*'){
        $where = array('YL_doctor_info.uid'=>$uid);
        $this->select($select);
        $this->join('YL_user','YL_user.uid=YL_doctor_info.uid','left');
        $this->join('YL_hospital','YL_hospital.hid=YL_doctor_info.hid','left');
        $this->join('YL_doctor_offices','YL_doctor_offices.id=YL_doctor_info.officeId','left');
        $this->order_by('YL_user.dateline','desc');
        $res = $this->find_by($where);
        return $res;
    }

    /**
     * 根据uid获取一列信息
     * @param $docId
     * @param $column
     */
    public function getInfoByUid($docId,$column){
        $this->select($column);
        $res = $this->find_by('uid',$docId);
        return $res[$column];
    }

    /**
     * 修改医生信息
     * @param $docId
     */
    public function updateDocInfo($docId,$data){
        $where = array('uid'=>$docId);
        $res = $this->update($where,$data);
        return $res;
    }


}