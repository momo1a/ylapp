<?php
/**
 * 用户金额model.
 * User: Administrator
 * Date: 2016/8/12
 * Time: 9:11
 */

class System_setting_model extends MY_Model
{
    public static $table_name = 'system_setting';

    public function __construct(){
        parent::__construct();
    }

    /**
     * 根据key获取value
     * @param $key
     */
    public function getValue($key){
        $where = array('settingKey'=>$key);
        return $this->find_by($where);
    }

    /**
     * 设置value
     * @param $key
     * @param $value
     */
    public function settingValue($key,$value){
        $where = array('settingKey'=>$key);
        $this->update($where,array('settingValue'=>$value));
        if($this->db->affected_rows()){
            return true;
        }else{
            $this->insert(array('settingKey'=>$key,'settingValue'=>$value));
            return $this->db->insert_id();
        }
    }


}