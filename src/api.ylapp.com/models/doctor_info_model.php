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