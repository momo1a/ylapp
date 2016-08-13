<?php
/**
 * 医生-用户 交互日志公共模型
 * User: momo1a@qq.com
 * Date: 2016/8/13 0013
 * Time: 下午 10:55
 */

class Common_user_doctor_log_model extends YL_Model
{
    public static $table_name = 'user_doctor_log';

    /**
     * 保存日志
     * @param $data
     */
    public function saveLog($data){
        $this->insert($data);
    }
}