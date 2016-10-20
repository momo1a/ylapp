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


}