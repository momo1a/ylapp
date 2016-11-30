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
    public function getHelp($type,$select = 'id,title,description'){
        $type = intval($type);
        $this->where(array('(type'=>$type));
        $this->or_where('type','0)',false);
        $this->where(array('isShow'=>1));
        $this->select($select);
        $this->order_by(array('id'=>'desc'));
        $res = $this->find_all();
        return $res;
    }

    /**
     * 获取所有帮助
     * @return array
     */
    public function getAllHelp(){
        $this->order_by(array('id'=>'desc'));
        $res = $this->find_all();
        return $res;
    }


    /**
     * 帮助详情
     * @param $id
     */
    public function helpDetail($id,$select='id,title,description'){
        $id = intval($id);
        $this->where(array('id'=>$id));
        $this->select($select);
        $res = $this->find_all();
        return $res;
    }

    /**
     * 保存帮助
     * @param $id
     * @param $data
     */
    public function save($id,$data){
        if($id == 0){
            $this->insert($data);
        }else{
            $where = array('id'=>$id);
            $this->update($where,$data);
        }

        return $this->db->affected_rows();
    }

    public function del($id){
        return $this->delete($id);
    }

}