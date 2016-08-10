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
    public function getFeeSettingByUid($docId,$select){
        $this->where(array('docId'=>$docId));
        $this->select($select);
        $res = $this->find_all();
        return $res;
    }


}