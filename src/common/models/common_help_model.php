<?php
/**
 *
 * 交易日志
 * User: momo1a@qq.com
 * Date: 2016/8/13 0013
 * Time: 下午 10:55
 */

class Common_help_model extends YL_Model
{
    public static $table_name = 'help';

    /**
     * 构造方法
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取帮助列表
     * @param $type 1 用户端 2 医生端
     */
    public function getHelp($type){
        $type = intval($type);
        $this->where(array('type'=>$type,'isShow'=>1));
        $this->select('id,title,description');
        $this->order_by(array('id'=>'desc'));
        $res = $this->find_all();
        return $res;
    }


    /**
     * 帮助详情
     * @param $id
     */
    public function helpDetail($id){
        $id = intval($id);
        $this->where(array('id'=>$id));
        $this->select('id,title,description');
        $res = $this->find_all();
        return $res;
    }


}